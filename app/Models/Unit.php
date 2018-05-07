<?php namespace App\Models;

use App\Traits\Models\Relationships\OwnedByUser;
use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

/**
 * Class Unit
 * @package App\Models\Units
 */
class Unit extends Model {

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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entries()
    {
        return $this->hasMany('App\Models\Entry');
    }
}
