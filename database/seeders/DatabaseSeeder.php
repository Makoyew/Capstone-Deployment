<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Department;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'username' => 'admin',
            'surname' => 'HR',
            'first_name' => 'Administrator',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $departments = ['COE', 'CON', 'CCJ', 'CABM-M', 'CABM-H', 'CAST'];

        foreach ($departments as $departmentName) {
            $department = Department::create(['name' => $departmentName]);

            User::create([
                'username' => Str::slug($departmentName . ' HEAD'),
                'surname' => $departmentName . ' HEAD',
                'first_name' => 'Supervisor',
                'password' => bcrypt('password'),
                'role' => 'supervisor',
                'department_id' => $department->id,
            ]);
        }

        \App\Models\User::factory(50)->create();
    }
}
