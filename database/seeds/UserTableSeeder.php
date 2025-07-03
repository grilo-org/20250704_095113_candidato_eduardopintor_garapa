<?php

use Illuminate\Database\Seeder;
use GarAppa\User;
use GarAppa\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$role_curator = Role::where('name', 'curator')->first();
    	$role_manager  = Role::where('name', 'admin')->first();

    	$admin = new User();
    	$admin->name = 'Burn It';
    	$admin->email = 'garappa@burnit.tech';
        $admin->password = bcrypt('bW76qRVjUf');
        $admin->save();
        $admin->roles()->attach($role_manager);

        $curator = new User();
        $curator->name = 'Rusty Marcellini';
        $curator->email = 'rusty@burnit.tech';
        $curator->password = bcrypt('g@rApp@');
        $curator->curator_id = '-L21GTy3gW_C3_T3SSyG';
        $curator->save();
        $curator->roles()->attach($role_curator);

        $curator = new User();
        $curator->name = 'Fred Sabbag ';
        $curator->email = 'fred@burnit.tech';
        $curator->password = bcrypt('g@rApp@');
        $curator->curator_id = '-L21GTyExplnFI05_mD1';
        $curator->save();
        $curator->roles()->attach($role_curator);
        
        $curator = new User();
        $curator->name = 'Eduardo Tristão Girão';
        $curator->email = 'eduardo@burnit.tech';
        $curator->password = bcrypt('g@rApp@');
        $curator->curator_id = '-L21GTyExplnFI05_mD2';
        $curator->save();
        $curator->roles()->attach($role_curator);
        
        $curator = new User();
        $curator->name = 'Luiz Gustavo Medina';
        $curator->email = 'luiz@burnit.tech';
        $curator->password = bcrypt('g@rApp@');
        $curator->curator_id = '-L21GTyExplnFI05_mD3';
        $curator->save();
        $curator->roles()->attach($role_curator);
    }
}
