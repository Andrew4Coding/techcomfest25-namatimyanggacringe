<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CourseStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $students = Student::factory(10)->create();
        $students = [
            [
                'name' => 'John Doe',
                'email' => 'johndoe@gmail.com',
                'verified' => 1,
                'phone_number' => '08123456789',
                'password' => Hash::make('dummy123'),
                'profile_picture' => 'https://techcomfest.s3.ap-southeast-2.amazonaws.com/profile_pictures/mascot-nyari.png',
                'class' => 'XII-IPA-1'
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'janesmith@gmail.com',
                'verified' => 1,
                'phone_number' => '08123456788',
                'password' => Hash::make('dummy123'),
                'profile_picture' => 'https://techcomfest.s3.ap-southeast-2.amazonaws.com/profile_pictures/mascot-nyari.png',
                'class' => 'XII-IPA-2'
            ],
            [
                'name' => 'Alice Johnson',
                'email' => 'alicejohnson@gmail.com',
                'verified' => 1,
                'phone_number' => '08123456787',
                'password' => Hash::make('dummy123'),
                'profile_picture' => 'https://techcomfest.s3.ap-southeast-2.amazonaws.com/profile_pictures/mascot-nyari.png',
                'class' => 'XII-IPA-3'
            ],
            [
                'name' => 'Bob Brown',
                'email' => 'bobbrown@gmail.com',
                'verified' => 1,
                'phone_number' => '08123456786',
                'password' => Hash::make('dummy123'),
                'profile_picture' => 'https://techcomfest.s3.ap-southeast-2.amazonaws.com/profile_pictures/mascot-nyari.png',
                'class' => 'XII-IPA-4'
            ],
            [
                'name' => 'Charlie Davis',
                'email' => 'charliedavis@gmail.com',
                'verified' => 1,
                'phone_number' => '08123456785',
                'password' => Hash::make('dummy123'),
                'profile_picture' => 'https://techcomfest.s3.ap-southeast-2.amazonaws.com/profile_pictures/mascot-nyari.png',
                'class' => 'XII-IPA-5'
            ],
            [
                'name' => 'Eve White',
                'email' => 'evewhite@gmail.com',
                'verified' => 1,
                'phone_number' => '08123456784',
                'password' => Hash::make('dummy123'),
                'profile_picture' => 'https://techcomfest.s3.ap-southeast-2.amazonaws.com/profile_pictures/mascot-nyari.png',
                'class' => 'XII-IPA-6'
            ],
            [
                'name' => 'Frank Green',
                'email' => 'frankgreen@gmail.com',
                'verified' => 1,
                'phone_number' => '08123456783',
                'password' => Hash::make('dummy123'),
                'profile_picture' => 'https://techcomfest.s3.ap-southeast-2.amazonaws.com/profile_pictures/mascot-nyari.png',
                'class' => 'XII-IPA-7'
            ],
            [
                'name' => 'Grace Lee',
                'email' => 'gracelee@gmail.com',
                'verified' => 1,
                'phone_number' => '08123456782',
                'password' => Hash::make('dummy123'),
                'profile_picture' => 'https://techcomfest.s3.ap-southeast-2.amazonaws.com/profile_pictures/mascot-nyari.png',
                'class' => 'XII-IPA-8'
            ],
            [
                'name' => 'Hank Miller',
                'email' => 'hankmiller@gmail.com',
                'verified' => 1,
                'phone_number' => '08123456781',
                'password' => Hash::make('dummy123'),
                'profile_picture' => 'https://techcomfest.s3.ap-southeast-2.amazonaws.com/profile_pictures/mascot-nyari.png',
                'class' => 'XII-IPA-9'
            ],
            [
                'name' => 'Ivy Wilson',
                'email' => 'ivywilson@gmail.com',
                'verified' => 1,
                'phone_number' => '08123456780',
                'password' => Hash::make('dummy123'),
                'profile_picture' => 'https://techcomfest.s3.ap-southeast-2.amazonaws.com/profile_pictures/mascot-nyari.png',
                'class' => 'XII-IPA-10'
            ]
        ];

        // For Each Student
        foreach($students as $student) {
            $newStudent = new Student();
            $newStudent->class = $student['class'];
            $newStudent->save();

            $newStudent->user()->create([
                'name' => $student['name'],
                'email' => $student['email'],
                'verified' => $student['verified'],
                'phone_number' => $student['phone_number'],
                'password' => $student['password'],
                'profile_picture' => $student['profile_picture']
            ]);
        }

        $students = Student::all();
        $courses = Course::all();

        // Apply all student to all courses
        foreach($courses as $course) {
            $course->students()->attach($students);
        }
    }
}
