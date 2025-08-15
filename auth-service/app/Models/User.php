<?php

namespace App\Models;

use App\Enums\ActionType;
use App\Jobs\SyncUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    protected static function booted(): void
    {
        static::created(function (User $user) {
            SyncUser::dispatch($user, ActionType::Create);
        });

        static::updated(function (User $user) {
            SyncUser::dispatch($user, ActionType::Update);
        });
    }

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
