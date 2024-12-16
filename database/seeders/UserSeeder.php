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
        // Make new user
        $data = [
            'name' => 'Andrew Devito Aryo',
            'email' => 'a@co',
            'verified' => 1,
            'phone_number' => '1234567890',
            'password' => Hash::make('dummy123'),
            'profile_picture' => null,
        ];

        $data2 = [
            'name' => 'Andrew Devito Arjo',
            'email' => 'b@co',
            'verified' => 1,
            'phone_number' => '1234567890',
            'password' => Hash::make('dummy123'),
            'profile_picture' => null,
        ];

        $newUser = new Student();
        $newUser->save();
        $newUser->user()->create($data);

        $newUser2 = new Teacher();
        $newUser2->save();
        $newUser2->user()->create($data2);

        $data = [
            'name' => 'Andrew Devito Aryo',
            'email' => 'b@gmail.com',
            'verified' => 1,
            'phone_number' => '1234567890',
            'password' => Hash::make('dummy123'),
            'profile_picture' => null,
        ];

        $newUser = new Student();
        $newUser->save();
        $newUser->user()->create($data);

        User::factory()->count(5)->for(
            Teacher::factory(), 'userable'
        )->create();
        User::factory()->count(5)->for(
            Student::factory(), 'userable'
        )->create();
    }
}
