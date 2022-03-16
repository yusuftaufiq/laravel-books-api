<?php

namespace App\Models;

use App\Scopes\NotExpiredScope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

final class PersonalAccessToken extends SanctumPersonalAccessToken
{
    use HasFactory;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'abilities' => 'json',
        'last_used_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'token',
        'abilities',
        'expired_at',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    final protected static function booted()
    {
        static::addGlobalScope(new NotExpiredScope());
    }

    /**
     * Set expiry date format.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    final public function expiredAt(): Attribute
    {
        return new Attribute(
            set: fn (string $value) => \DateTime::createFromFormat('Y-m-d H:i:s', "$value 23:59:59"),
        );
    }
}
