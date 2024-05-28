<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function likes()
    {
        return $this->hasMany('App\Models\Like');
    }


    public function followings()
    {
        return $this->hasMany('App\Models\Following');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    public function following()
    {
        return $this->belongsToMany('App\Models\User', 'followings', 'user_id', 'followings_id');
    }

    public function followers()
    {
        return $this->belongsToMany('App\Models\User', 'followings', 'followings_id', 'user_id');
    }

    public function hasLikedPin($pinId)
    {
        return $this->likes()->where('pin_id', $pinId)->exists();
    }

    public function albums()
    {
        return $this->hasMany(Album::class);
    }
}
