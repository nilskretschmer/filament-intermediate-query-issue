<?php

namespace Database\Seeders;

use App\Models\Destination;
use App\Models\Template;
use App\Models\Permission;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(1)->create();
        $destinations = Destination::factory(3)->create();
        foreach ($destinations as $destination) {
            Permission::factory()->download()->create(['destination_id' => $destination->id]);
            Permission::factory()->upload()->create(['destination_id' => $destination->id]);
        }
        Template::factory(3)->hasAttached($destinations, relationship: 'allowedTargets')->create();
        Group::factory(3)->create();
    }
}
