<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    protected $fillable = [
        'name',
        'domain',
        'subdomain',
        'settings',
        'status',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function associations(): HasMany
    {
        return $this->hasMany(Association::class);
    }

    public function safes(): HasMany
    {
        return $this->hasMany(Safe::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}

