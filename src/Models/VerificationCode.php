<?php

namespace Wotz\VerificationCode\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Wotz\VerificationCode\Support\CodeGenerator;

class VerificationCode extends Model
{
    protected $fillable = [
        'code',
        'verifiable',
        'expires_at',
    ];

    protected $hidden = [
        'code',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($verificationCode) {
            if ($verificationCode->expires_at === null) {
                $verificationCode->expires_at = now()->addSeconds(config('verification-code.expire_seconds', 0));
            }

            if (Hash::needsRehash($verificationCode->code)) {
                $verificationCode->code = Hash::make($verificationCode->code);
            }
        });

        static::created(function ($verificationCode) {
            $maxCodes = config('verification-code.max_per_verifiable', 1);

            if ($maxCodes === null) {
                return;
            }

            $oldVerificationCodeIds = self::for($verificationCode->verifiable)
                ->orderByDesc('expires_at')
                ->orderByDesc('id')
                ->skip($maxCodes)
                ->take(PHP_INT_MAX)
                ->pluck('id');

            self::whereIn('id', $oldVerificationCodeIds)->delete();
        });
    }

    public static function createFor(string $verifiable) : string
    {
        self::create([
            'code' => $code = app(CodeGenerator::class)->generate(),
            'verifiable' => $verifiable,
        ]);

        return $code;
    }

    public function scopeFor(Builder $query, string $verifiable) : Builder
    {
        return $query->where('verifiable', $verifiable);
    }

    public function scopeNotExpired(Builder $query) : Builder
    {
        return $query->where('expires_at', '>=', now());
    }
}
