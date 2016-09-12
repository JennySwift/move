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
        'default_unit_id',
        'description',
        'default_quantity',
        'step_number',
        'series_id',
        'target',
        'priority',
        'stretch',
        'frequency'
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function program()
    {
        return $this->belongsTo('App\Models\ExerciseProgram');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function defaultUnit()
    {
        return $this->belongsTo('App\Models\Unit', 'default_unit_id', 'id');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function series()
    {
        return $this->belongsTo('App\Models\Series');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entries()
    {
        return $this->hasMany('App\Models\Entry');
    }

    /**
     * Return the URL of the project
     * it needs to be called getFieldAttribute
     * @return string
     */
    public function getPathAttribute()
    {
        return route('api.exercises.show', $this->id);
    }

    /**
     * Get how many days ago the exercise was done
     * @return mixed
     */
    public function getLastDoneAttribute()
    {
        if (count($this->entries) > 0) {
            return getHowManyDaysAgo($this->entries()->max('date'));
        }

        return null;
    }

    /**
     * Get how many days ago the exercise was done
     * @return mixed
     */
    public function getDueInAttribute()
    {
        if (count($this->entries) < 1) {
            return 0;
        }

        return $this->frequency - $this->lastDone;
    }

}
