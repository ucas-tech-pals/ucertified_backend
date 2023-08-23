<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Institution extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = [];

    protected $table = 'institutions';

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
