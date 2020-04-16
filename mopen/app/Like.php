<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = [
        'model_id', "model_type", "type", "user_id"
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', "user_id");
    }
}
