<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::find(1);
        $course1 = Course::find(1);
        $course2 = Course::find(2);
        if ($user && $course1 && $course2) {
            $user->courses()->sync([$course1->id, $course2->id]);
        }
    }
}