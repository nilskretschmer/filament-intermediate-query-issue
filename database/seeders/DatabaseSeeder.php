<?php

namespace Database\Seeders;

use App\Models\Destination;
use App\Models\Template;
use App\Models\Permission;
use App\Models\Group;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Destination::factory(3)->create();
        Template::factory(3)->create();
        Permission::factory(6)->create();
        Group::factory(3)->create();
    }
}
