<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $course1 = Course::find(1);
        if ($course1) {
            $course1->subjects()->firstOrCreate([
                'name' => 'PHP',
                'slug' => 'php'
            ]);
        }

        $course2 = Course::find(2);
        if ($course2) {
            $course2->subjects()->firstOrCreate([
                'name' => 'Laravel',
                'slug' => 'laravel'
            ]);
        }
    }
}