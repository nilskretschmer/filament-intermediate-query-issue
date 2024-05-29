<?php

namespace App\Filament\Resources\TemplateResource\RelationManagers;

use App\Models\Template;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\AttachAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use App\Models\Permission;
use App\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Builder;

class GroupsRelationManager extends RelationManager
{
    protected static string $relationship = 'groups';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('templates.groups');
    }

    public Template $template;

    public function mount(): void
    {
        $this->template = $this->ownerRecord;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('templates.name'))
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('templates.name')),
                SelectColumn::make('role_mapping')
                    ->label(__('globals.role_mapping'))
                    ->options([
                        'admins' => 'Admins',
                        'users' => 'Users',
                    ]),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Select::make('role_mapping')
                            ->options([
                                'admins' => 'Admins',
                                'users' => 'Users',
                            ])
                            ->required(),
                    ])
            ])
            ->actions([
                Action::make('permissions')
                    ->form($this->getPermissionsForm())
                    ->fillForm(function ($record) {
                        return $record->toArray();
                    })
                    ->modalHeading(function ($record) {
                        return __('templates.permissions_for', ['group' => $record->name, 'template' => $this->template->name]);
                    })
                    ->model(function ($record) {
                        return $record;
                    })
                    ->icon('heroicon-o-key'),
                Tables\Actions\DetachAction::make()
                    ->after(function ($record) {
                        $permissionIds = $record->getTemplatePermissions($this->template)->pluck('id');
                        $record->templatePermissions()->detach($permissionIds);
                    })
            ])
            ->bulkActions([]);
    }

    private function getPermissionsForm()
    {
        $checkboxLists = [];
        foreach ($this->template->allowedTargets as $target) {
            $checkboxLists[] = CheckboxList::make('templatePermissions')
                ->label($target->name)
                ->relationship(
                    name: 'templatePermissions',
                    titleAttribute: 'name',
                    modifyQueryUsing: fn (Builder $query) => $query->where('template_id', $this->template->id),
                )
                ->options(function () use ($target) {
                    $permissions = [];
                    $templatePermissions = Permission::whereNull('model_id')->where('model_type', Project::class)
                        ->where('destination_id', $target->id)->whereIn('name', ['download', 'upload'])->get();
                    foreach ($templatePermissions as $permission) {
                        $permissions[$permission->id] = $permission->name;
                    }
                    return $permissions;
                })
                ->pivotData([
                    'template_id' => $this->template->id,
                ]);
        }
        return $checkboxLists;
    }
}
