<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * @property string token
 */
class AuthToken extends Model
{
    /**
     * @var string the plain text token. Only set during creation of token.
     */
    private $plainTextToken;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setPlainTextToken(string $plainTextToken): void
    {
        $this->plainTextToken = $plainTextToken;
    }

    public function getPlainTextToken(): string
    {
        return $this->plainTextToken;
    }
}
