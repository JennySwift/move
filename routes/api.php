<?php

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
//    Route::resource('series', 'ExerciseSeriesController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);


    Route::get('entries/specificExerciseAndDateAndUnit', 'ExerciseEntriesController@getEntriesForSpecificExerciseAndDateAndUnit');
    Route::get('entries/{date}', ['as' => 'api.exerciseEntries.index', 'uses' => 'ExerciseEntriesController@index']);
    Route::resource('entries', 'ExerciseEntriesController', ['only' => ['store', 'update', 'destroy']]);

    Route::resource('units', 'UnitsController', ['only' => ['index', 'store', 'update']]);

    Route::resource('workouts', 'WorkoutsController');


    Route::resource('seriesEntries', 'SeriesEntriesController', ['only' => ['show']]);

});