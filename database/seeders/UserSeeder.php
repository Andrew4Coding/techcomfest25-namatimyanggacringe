<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::factory()->count(5)->for(
            Teacher::factory(), 'userable'
        )->create();
        User::factory()->count(5)->for(
            Student::factory(), 'userable'
        )->create();
    }
}
