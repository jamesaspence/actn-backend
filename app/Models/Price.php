<?php


namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string time
 * @property Carbon date
 * @property integer price
 */
class Price extends Model
{
    public const TIME_MORNING = 'morning';
    public const TIME_EVENING = 'evening';

    protected $dates = ['date'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
