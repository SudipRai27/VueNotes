<?php

namespace App\Http\Controllers\Course;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Filters\Course\CourseFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::with(['subjects', 'users'])
            ->filter($request, $this->getFilters())
            ->paginate(2);

        return  CourseResource::collection($courses)
            ->additional(['message' => 'Course Details'])->response()
            ->setStatusCode(200);
    }

    /// To add filters progammatically // not needed just merges with the filter defined in Course filter class
    public function getFilters()
    {
        return [];
    }

    public function getCourseFilters()
    {
        return response()->json(['data' => CourseFilter::mappings(), 'message' => 'Available filters'], 200);
    }
}