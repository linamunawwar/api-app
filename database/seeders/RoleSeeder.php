<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role();
        $role->name = 'ROLE_ADMIN';
        $role->description = 'admin';
        $role->save();

        $role2 = new Role();
        $role2->name = 'ROLE_USER';
        $role2->description = 'user';
        $role2->save();
    }
}
