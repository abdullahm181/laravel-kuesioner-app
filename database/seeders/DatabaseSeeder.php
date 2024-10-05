<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\KuesionerQuestionType;
use App\Models\Module;
use App\Models\ModuleWithRole;
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
                'password'=>bcrypt('amin.abdullah'),
                'isDeleted'=>false,
                'isDisable'=>false,
            ],[
                'name'=>'amin.user',
                'email'=>'amin.user@gmail.com',
                'role_id'=>1,
                'password'=>bcrypt('amin.user'),
                'isDeleted'=>false,
                'isDisable'=>false,
            ]
        ];
        
        foreach($UserData as $key=>$val){
            User::create($val);

        }
        ///MODULE 
        $ModuleData=[
            [
                'ModuleName'=>'Roles',
                'ModuleGroup'=>'Administrator',
                'ModuleSubGroup'=>null,
                'ModuleAction'=>'index',
                'ModuleController'=>'role',
                'ModuleOrder'=>2,
                'ModuleIcon'=>'fas fa-briefcase'
            ],[
                'ModuleName'=>'Users',
                'ModuleGroup'=>'Administrator',
                'ModuleSubGroup'=>null,
                'ModuleAction'=>'index',
                'ModuleController'=>'users',
                'ModuleOrder'=>3,
                'ModuleIcon'=>'fas fa-users'
            ]
            ,[
                'ModuleName'=>'Modules',
                'ModuleGroup'=>'Administrator',
                'ModuleSubGroup'=>null,
                'ModuleAction'=>null,
                'ModuleController'=>'Modules',
                'ModuleOrder'=>4,
                'ModuleIcon'=>'fas fa-cog'
            ],[
                'ModuleName'=>'Kuesioner',
                'ModuleGroup'=>'Master',
                'ModuleSubGroup'=>null,
                'ModuleAction'=>'index',
                'ModuleController'=>'kuesioner',
                'ModuleOrder'=>5,
                'ModuleIcon'=>'fas fa-task'
            ],[
                'ModuleName'=>'Kuesioner Response',
                'ModuleGroup'=>'History',
                'ModuleSubGroup'=>null,
                'ModuleAction'=>'index',
                'ModuleController'=>'kuesionerresponse',
                'ModuleOrder'=>6,
                'ModuleIcon'=>'fas fa-history'
            ]
        ];
        foreach($ModuleData as $key=>$val){
            Module::create($val);

        }
        //ModuleWithRole
        //----- ADMIN
        $ADMIN=[
            [
                'module_id'=>1,
                'role_id'=>2
            ], [
                'module_id'=>2,
                'role_id'=>2
            ], [
                'module_id'=>3,
                'role_id'=>2
            ], [
                'module_id'=>4,
                'role_id'=>2
            ], [
                'module_id'=>5,
                'role_id'=>2
            ]
        ];
        foreach($ADMIN as $key=>$val){
            ModuleWithRole::create($val);

        }
        ///-- User
        $USER=[
            [
                'module_id'=>1,
                'role_id'=>1
            ],[
                'module_id'=>5,
                'role_id'=>1
            ]
        ];
        foreach($USER as $key=>$val){
            ModuleWithRole::create($val);

        }
        //kuesioner_question_types
        $kuesioner_question_types=[
            [
                'inputType'=>'input',
                'type'=>'text',
                'allowMultipleOption'=>false,
                'isDeleted'=>false,
            ],[
                'inputType'=>'input',
                'type'=>'number',
                'allowMultipleOption'=>false,
                'isDeleted'=>false,
            ],[
                'inputType'=>'input',
                'type'=>'date',
                'allowMultipleOption'=>false,
                'isDeleted'=>false,
            ],[
                'inputType'=>'input',
                'type'=>'email',
                'allowMultipleOption'=>false,
                'isDeleted'=>false,
            ],[
                'inputType'=>'select',
                'type'=>'text',
                'allowMultipleOption'=>false,
                'isDeleted'=>false,
            ],[
                'inputType'=>'checkbox',
                'type'=>'text',
                'allowMultipleOption'=>true,
                'isDeleted'=>false,
            ],[
                'inputType'=>'textarea',
                'type'=>'text',
                'allowMultipleOption'=>false,
                'isDeleted'=>false,
            ],
        ];
        
        foreach($kuesioner_question_types as $key=>$val){
            KuesionerQuestionType::create($val);

        }
    }
}
