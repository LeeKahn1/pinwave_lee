<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'image_path',
        'link',
        'tags',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany('App\Models\Like');
    }

    public function isLikedBy(User $user)
    {
        return $this->likes->contains('user_id', $user->id);
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    public function albums()
    {
        return $this->belongsToMany(Album::class);
    }
}
