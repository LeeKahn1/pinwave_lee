<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Following extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'followings_id'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function following()
    {
        return $this->belongsTo('App\Models\User', 'followings_id');
    }
}
