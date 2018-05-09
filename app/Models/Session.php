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
    protected $fillable = ['name'];

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}