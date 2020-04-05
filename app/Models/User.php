<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property string email
 * @property string password
 * @property string username
 * @property Collection currentPurchases
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * @var AuthToken
     */
    private $currentToken;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'created_at', 'updated_at', 'email'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function currentPurchases()
    {
        return $this->hasMany(Purchase::class)
            ->where('created_at', '>', Carbon::now()->startOfWeek());
    }

    /**
     * @param mixed $currentToken
     */
    public function setCurrentToken($currentToken): void
    {
        $this->currentToken = $currentToken;
    }

    /**
     * @return AuthToken
     */
    public function getCurrentToken(): AuthToken
    {
        return $this->currentToken;
    }
}
