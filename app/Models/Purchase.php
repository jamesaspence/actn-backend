<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = ['price', 'quantity', 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
