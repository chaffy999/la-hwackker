<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function hwacks()
    {
        return $this->hasMany(Hwack::class);
    }

    public function followed()
    {
        return $this->hasManyThrough(User::class, Follow::class, 'user_id', 'id', 'id', 'followed_id');
    }
}
