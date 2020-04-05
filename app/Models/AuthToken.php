<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * @property string token
 */
class AuthToken extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
