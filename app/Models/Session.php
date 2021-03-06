<?php namespace App\Models;

use App\Traits\Models\Relationships\OwnedByUser;
use Auth;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Session
 * @package App\Models
 */
class Session extends Model
{

    use OwnedByUser;

    /**
     * @var array
     */
    protected $fillable = ['name', 'created_at'];

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
        return $this->belongsToMany('App\Models\Exercise')->withTimestamps()->withPivot('level', 'quantity', 'complete', 'unit_id', 'id', 'workout_group_id');
    }
}
