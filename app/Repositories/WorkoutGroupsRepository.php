<?php

namespace App\Repositories;

use App\Models\Workout;
use App\Models\WorkoutGroup;

class WorkoutGroupsRepository
{
    /**
     *
     * @param Workout $workout
     * @return WorkoutGroup
     */
    public function createWorkoutGroup(Workout $workout)
    {
        $workoutGroup = new WorkoutGroup([
            'order' => $workout->groups()->max('order') + 1,
        ]);

        $workoutGroup->workout()->associate($workout);
        $workoutGroup->save();

        return $workoutGroup;
    }
}