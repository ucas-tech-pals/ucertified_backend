<?php

namespace App\Models;

use App\PDFCryptoSigner\CryptoManager;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Institution extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = [];

    protected $hidden = [
        'password', 'private_key', 'public_key',
    ];
    protected $casts = [
        'password' => 'hashed',
    ];
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
    public function users(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, Document::class);
    }
    public static function boot()
    {
        parent::boot();
        static::creating(function ($institution) {
            $keys = CryptoManager::generateKeyPair();
            $institution->private_key = $keys['private_key'];
            $institution->public_key = $keys['public_key'];
        });
    }
}
