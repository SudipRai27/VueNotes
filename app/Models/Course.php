<?php

namespace App\Models;

use App\Models\User;
use App\Models\Subject;
use App\Filters\Course\CourseFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    protected $casts = [
        'free' => 'boolean'
    ];

    protected $appends = [  //for adding it automatically in json response of the model.
        'started', 'formattedAccess', 'formattedDifficulty', 'formattedType'
    ];

    public $hidden = [
        'users'
    ];

    public function scopeFilter(Builder $builder, $request, array $filters = [])
    {
        return (new CourseFilter($request))
            ->add($filters)
            ->filter($builder);
    }

    public function getStartedAttribute()
    {
        if (!auth()->user()) {
            return false;
        }
        return $this->users->contains(auth()->user());
    }

    public function getFormattedAccessAttribute()
    {
        return $this->free = true ? 'Free' : 'Premium';
    }

    public function getFormattedDifficultyAttribute()
    {
        return ucfirst($this->difficulty);
    }

    public function getFormattedTypeAttribute()
    {
        return ucfirst($this->type);
    }

    public function subjects()
    {
        return $this->morphToMany(Subject::class, 'subjectable');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}