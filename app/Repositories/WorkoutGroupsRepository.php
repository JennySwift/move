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

    /**
     * Todo: fix the ordering of the workout groups after deleting the unused ones?
     * @param Workout $workout
     * @return Workout
     */
    public function deleteUnused(Workout $workout)
    {
        $groupsToDelete = $workout->groups()->whereDoesntHave('exercises')->get();
        foreach ($groupsToDelete as $groupToDelete) {
            $groupToDelete->delete();
        }
        return $workout;
    }
}