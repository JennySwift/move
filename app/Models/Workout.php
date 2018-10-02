<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\Relationships\OwnedByUser;
use Auth;

/**
 * Class Workout
 * @package App\Models\Exercises
 */
class Workout extends Model {

	use OwnedByUser;

    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
	{
		return $this->belongsTo('App\User');
	}


    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function exercises()
    {
        return $this->belongsToMany('App\Models\Exercise')->withPivot('level', 'quantity', 'unit_id', 'id', 'workout_group_id');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sessions()
    {
        return $this->hasMany('App\Models\Session');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groups()
    {
        return $this->hasMany('App\Models\WorkoutGroup');
//        return $this->belongsToMany('App\Models\WorkoutGroup', 'exercise_workout', 'workout_id', 'workout_group_id')->withPivot('order', 'quantity', 'unit_id', 'id', 'exercise_id');
//        return $this->belongsToMany('App\Models\WorkoutGroup', 'exercise_workout', 'workout_id', 'workout_group_id');
    }
}
