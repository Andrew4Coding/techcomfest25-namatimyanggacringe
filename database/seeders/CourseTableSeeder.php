<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CourseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('courses')->insert([
            'id' =>Str::uuid()->toString(),
            'name' => 'Laravel 8',
            'description' => 'Learn Laravel 8 from scratch',
            'code' => '120A',
            'updated_at' => now(),
        ]);
        DB::table('courses')->insert([
            'id' => Str::uuid()->toString(),
            'name' => 'ReactJS',
            'description' => 'Learn ReactJS from scratch',
            'code' => '120B',
            'updated_at' => now(),
        ]);

        DB::table('courses')->insert([
            'id' => Str::uuid()->toString(),
            'name' => 'VueJS',
            'description' => 'Learn VueJS from scratch',
            'code' => '120C',
            'updated_at' => now(),
        ]);

        DB::table('courses')->insert([
            'id' => Str::uuid()->toString(),
            'name' => 'Angular',
            'description' => 'Learn Angular from scratch',
            'code' => '120D',
            'updated_at' => now(),
        ]);
    }
}
