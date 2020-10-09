<?php

namespace NextApps\VerificationCode\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use NextApps\VerificationCode\Support\CodeGenerator;

class VerificationCode extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'verifiable',
        'expires_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'code',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($verificationCode) {
            if ($verificationCode->expires_at === null) {
                $verificationCode->expires_at = now()->addHours(config('verification-code.expire_hours', 0));
            }

            if (Hash::needsRehash($verificationCode->code)) {
                $verificationCode->code = Hash::make($verificationCode->code);
            }
        });

        static::created(function ($verificationCode) {
            $maxCodes = config('verification-code.max_per_verifiable', 1);

            if ($maxCodes !== null) {
                VerificationCode::exceptRecentFor($verificationCode->verifiable, $maxCodes)->delete();
            }
        });
    }

    /**
     * Get the expired state of the verification code.
     *
     * @return bool
     */
    public function getExpiredAttribute()
    {
        return $this->expires_at < now();
    }

    /**
     * Create a verification code for the verifiable.
     *
     * @param string $verifiable
     *
     * @return string
     */
    public static function createFor(string $verifiable)
    {
        VerificationCode::create([
            'code' => $code = app(CodeGenerator::class)->generate(),
            'verifiable' => $verifiable,
        ]);

        return $code;
    }

    /**
     * Scope a query to only include verification codes for the provided verifiable.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param string $verifiable
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFor($query, string $verifiable)
    {
        return $query->where('verifiable', $verifiable);
    }

    /**
     * Scope a query to only include verification codes from the provided verifiable.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param string $verifiable
     * @param int $amount
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExceptRecentFor($query, string $verifiable, int $amount)
    {
        return $query
            ->for($verifiable)
            ->whereNotIn(
                'id',
                static::query()
                    ->select('id')
                    ->for($verifiable)
                    ->orderByDesc('expires_at')
                    ->orderByDesc('id')
                    ->limit($amount)
            );
    }
}
