<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Attributes\Unguarded;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Unguarded]
class User extends Authenticatable
{   
    //Ungaurded for now;

    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function searchRequests(): HasMany
    {
        return $this->hasMany(SearchRequest::class);
    }

}
