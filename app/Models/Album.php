<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pins()
    {
        return $this->belongsToMany(Pin::class);
    }

    public function getCoverImageUrlAttribute()
    {
        $firstPin = $this->pins()->first();
        return $firstPin ? $firstPin->image_path : 'thumbnail.jpg';
    }
}
