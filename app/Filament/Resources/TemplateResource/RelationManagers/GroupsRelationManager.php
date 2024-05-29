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
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
            ])
            ->actions([
                Action::make('permissions')
                    ->form($this->getPermissionsForm())
                    ->fillForm(function ($record) {
                        return $record->toArray();
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
                    $templatePermissions = Permission::where('destination_id', $target->id)->whereIn('name', ['download', 'upload'])->get();
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
