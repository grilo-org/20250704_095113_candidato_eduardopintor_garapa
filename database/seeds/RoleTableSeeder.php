<?php

use Illuminate\Database\Seeder;
use GarAppa\User;
use GarAppa\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$role_manager = new Role();
    	$role_manager->name = 'admin';
    	$role_manager->description = 'A Adminstrator User';
    	$role_manager->save();

        $role_employee = new Role();
        $role_employee->name = 'curator';
        $role_employee->description = 'A Curator User';
        $role_employee->save();
    }
}
