<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Note\NoteController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Datatable\NoteDataTableController;
use App\Http\Controllers\Course\CourseController;

Route::prefix('v1')->group(function () {


    ////// Auth Routes /////
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
    });

    Route::group(['prefix' => 'auth', 'middleware' => 'jwt'], function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'user']);
    });
    ////// Auth Routes /////

    Route::middleware('jwt')->group(function () {
        Route::apiResource('datatable/note', NoteDataTableController::class);

        Route::apiResource('note', NoteController::class);

        Route::get('course/filters', [CourseController::class, 'getCourseFilters']);
        Route::get('course', [CourseController::class, 'index']);
    });
});