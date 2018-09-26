<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\Relationships\OwnedByUser;

/**
 * Class WorkoutGroup
 * @package App\Models
 */
class WorkoutGroup extends Model {

	use OwnedByUser;

    /**
     * @var array
     */
    protected $fillable = ['order'];

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
//    public function exercise()
//    {
//        return $this->belongsTo('App\Models\Exercise');
//    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function workout()
    {
        return $this->belongsTo('App\Models\Workout');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function exercises()
    {
        return $this->belongsToMany('App\Models\Exercise')->withPivot('order');
    }
}
