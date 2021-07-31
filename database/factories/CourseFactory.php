<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Course::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $name = $this->faker->sentence,
            'slug' => Str::slug($name),
            'free' => rand(0, 1),
            'views' => rand(0, 200),
            'difficulty' => ['beginner', 'intermediate', 'advanced'][rand(0, 2)],
            'type' => ['theory', 'project', 'snippet'][rand(0, 2)],

        ];
    }
}