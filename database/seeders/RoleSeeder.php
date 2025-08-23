<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'manajer']);
        Role::create(['name' => 'operator']);
        Role::create(['name' => 'kasir']);
        Role::create(['name' => 'designer']);
    }
}
