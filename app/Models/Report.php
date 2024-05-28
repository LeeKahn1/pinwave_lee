<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'pin_id', 'reason'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pin()
    {
        return $this->belongsTo(Pin::class);
    }
}
