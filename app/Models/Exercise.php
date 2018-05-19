<?php namespace App\Models;

use App\Traits\Models\Relationships\OwnedByUser;
use Auth;
use DB;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Exercise
 * @package App\Models\Exercises
 */
class Exercise extends Model
{
    use OwnedByUser;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'priority',
    ];

    /**
     * @var array
     */
    protected $appends = ['path'];

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
    public function workouts()
    {
        return $this->belongsToMany('App\Models\Workout');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sessions()
    {
        return $this->belongsToMany('App\Models\Session')->withTimestamps();
    }

    /**
     * Return the URL of the project
     * it needs to be called getFieldAttribute
     * @return string
     */
    public function getPathAttribute()
    {
        return route('exercises.show', $this->id);
    }
}
