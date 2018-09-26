<?php namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Transformers\WorkoutGroupTransformer;
use App\Models\Workout;
use App\Models\WorkoutGroup;
use Auth;
use Illuminate\Http\Request;


/**
 * Class WorkoutGroupsController
 * @package App\Http\Controllers\API
 */
class WorkoutGroupsController extends Controller
{

    public function reorder(Request $request)
    {
        $oldPosition = $request->get('old_position');
        $newPosition = $request->get('new_position');
        $workout = Workout::find($request->get('workout_id'));

        $group = $workout->groups()->where('order', $oldPosition)->firstOrFail();

        if ($newPosition < $oldPosition) {
            //Group was moved up
            //First get this group out of the way so that other groups can be moved up
            $group->update([
                'order' => 0
            ]);

            $workout->groups()
                ->where('order', '<', $oldPosition)
                ->where('order', '>=', $newPosition)
                ->increment('order');

            //Now the group can be moved to its new position
            $group->update([
                'order' => $newPosition
            ]);
        }
        else if ($newPosition > $oldPosition) {
            //Group was moved down
            //First get this group out of the way so that other groups can be moved down
            $group->update([
                'order' => 0
            ]);

            $workout->groups()
                ->where('order', '>', $oldPosition)
                ->where('order', '<=', $newPosition)
                ->decrement('order');

            //Now the group can be moved to its new position
            $group->update([
                'order' => $newPosition
            ]);
        }
//        return $this->respondUpdate($workoutGroup, new WorkoutGroupTransformer);
    }
}