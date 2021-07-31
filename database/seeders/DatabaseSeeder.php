<?php

namespace Database\Seeders;

use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Container\Container;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */


    protected $faker;

    /**
     * Create a new seeder instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->faker = $this->withFaker();
    }

    protected function withFaker()
    {
        return Container::getInstance()->make(Generator::class);
    }

    public function run()
    {
        // \App\Models\User::factory(10)->create()->each(function ($user) {
        //     $notes = \App\Models\Note::factory(100)->make();
        //     $user->notes()->saveMany($notes);
        // });

        \App\Models\User::factory(1)->create()->each(function ($user) {
            $notes = \App\Models\Note::factory(100)->make();
            $user->notes()->saveMany($notes);
        });

        \App\Models\Course::factory(10)->create();

        $this->call([
            SubjectTableSeeder::class,
            CourseUserSeeder::class
        ]);
    }
}