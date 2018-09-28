<?php

use App\Http\Controllers\API\WorkoutGroupsController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'API', 'middleware' => 'auth:api'], function () {
    Route::resource('exercises', 'ExercisesController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::resource('units', 'UnitsController', ['only' => ['index', 'store', 'update']]);
    Route::resource('workouts', 'WorkoutsController');
    Route::resource('sessions', 'SessionsController');
    Route::resource('workouts.exercises', 'WorkoutsExercisesController');
    Route::put('workoutGroups/reorder', 'WorkoutGroupsController@reorder');
//    Route::resource('workoutGroups', 'WorkoutGroupsController');
});
