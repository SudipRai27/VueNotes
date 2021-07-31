<?php

namespace App\Filters\Course;

use App\Models\Subject;
use Illuminate\Http\Request;
use App\Filters\FiltersAbstract;
use App\Filters\Course\TypeFilter;
use App\Filters\Course\AccessFilter;
use App\Filters\Course\StartedFilter;
use App\Filters\Course\SubjectFilter;
use App\Filters\Course\DifficultyFilter;
use App\Filters\Course\Ordering\ViewsOrder;

class CourseFilter extends FiltersAbstract
{
    protected $filters = [
        'access' => AccessFilter::class,
        'difficulty' => DifficultyFilter::class,
        'type' => TypeFilter::class,
        'subject' => SubjectFilter::class,
        'started' => StartedFilter::class,
        'views' => ViewsOrder::class
    ];

    public static function mappings()
    {
        $map = [
            'access' => [
                'free' => 'Free',
                'premium' => 'Premium'
            ],
            'difficulty' => [
                'beginner' => 'Beginner',
                'intermediate' => 'Intermediate',
                'advanced' => 'Advanced'
            ],
            'type' => [
                'snippet' => 'Snippet',
                'project' => 'Project',
                'theory' => 'Theory'
            ],
            'subjects' => Subject::get()->pluck('name', 'slug')->toArray(),

        ];

        if (auth()->check()) {
            $map = array_merge($map, [
                'started' => [
                    'true' => 'Started',
                    'false' => 'Not Started'
                ]
            ]);
        }

        return $map;
    }
}