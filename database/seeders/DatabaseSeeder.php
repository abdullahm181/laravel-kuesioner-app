<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $RoleData=[
            [
                'name'=>'user'
            ],[
                'name'=>'admin'
            ],[
                'name'=>'karyawan'
            ]
        ];
        // DB::table('roles')->insert(
        //     array('id' => 1, 'name' => 'user'),
        //     array('id' => 2, 'name' => 'admin')
        // );
        foreach($RoleData as $key=>$val){
            Role::create($val);

        }

        $UserData=[
            [
                'name'=>'amin.abdullah',
                'email'=>'amin.abdullah@gmail.com',
                'role_id'=>2,
                'password'=>bcrypt('amin.abdullah')
            ],[
                'name'=>'amin.user',
                'email'=>'amin.user@gmail.com',
                'role_id'=>1,
                'password'=>bcrypt('amin.user')
            ]
        ];
        
        foreach($UserData as $key=>$val){
            User::create($val);

        }
    }
}
