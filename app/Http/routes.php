<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::auth();

Route::get('/', 'HomeController@index');

// API
Route::group(['middleware' => ['auth', 'owner'], 'namespace' => 'API', 'prefix' => 'api'], function () {

    Route::resource('exercises', 'ExercisesController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::resource('exerciseSeries', 'ExerciseSeriesController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);


    Route::get('exerciseEntries/specificExerciseAndDateAndUnit', 'ExerciseEntriesController@getEntriesForSpecificExerciseAndDateAndUnit');
    Route::get('exerciseEntries/{date}', ['as' => 'api.exerciseEntries.index', 'uses' => 'ExerciseEntriesController@index']);
    Route::resource('exerciseEntries', 'ExerciseEntriesController', ['only' => ['store', 'update', 'destroy']]);
    Route::resource('exerciseUnits', 'ExerciseUnitsController', ['only' => ['index', 'store', 'update', 'destroy']]);


    Route::resource('exercisePrograms', 'ExerciseProgramsController', ['except' => ['create', 'edit']]);
    Route::resource('seriesEntries', 'SeriesEntriesController', ['only' => ['show']]);
});
