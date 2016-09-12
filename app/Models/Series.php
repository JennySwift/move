<?php namespace App\Models;

use App\Traits\Models\Relationships\OwnedByUser;
use Auth;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Series
 * @package App\Models\Exercises
 */
class Series extends Model
{

    use OwnedByUser;

    /**
     * @var array
     */
    protected $fillable = ['name', 'priority'];

    /**
     * @var string
     */
    protected $table = "exercise_series";

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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function exercises()
    {
        return $this->hasMany('App\Models\Exercise')->orderBy('step_number');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function entries()
    {
        return $this->hasManyThrough('App\Models\Entry', 'App\Models\Exercise');
    }

    /**
     * Return the URL of the project
     * it needs to be called getFieldAttribute
     * @return string
     */
    public function getPathAttribute()
    {
        return route('api.exerciseSeries.show', $this->id);
    }

    /**
     * Get how many days ago a series was last done
     * @return mixed
     */
    public function getLastDoneAttribute()
    {
        if (count($this->entries) > 0) {
            return getHowManyDaysAgo($this->entries()->max('date'));
        }

        return null;
    }

}
