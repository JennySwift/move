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
     * @param Workout $workout
     * @return Workout
     */
    public function deleteUnused(Workout $workout)
    {
        $groupsToDelete = $workout->groups()->whereDoesntHave('exercises')->get();
        foreach ($groupsToDelete as $groupToDelete) {
            $order = $groupToDelete->order;

            $groupToDelete->delete();

            //Update the order of the workout groups
            $workout->groups()
                ->where('order', '>', $order)
                ->decrement('order');
        }
        return $workout;
    }
}