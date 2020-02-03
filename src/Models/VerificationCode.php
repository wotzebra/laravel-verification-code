<?php

namespace NextApps\VerificationCode\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class VerificationCode extends Model
{
    const NUMERIC = '0123456789';
    const ALPHABETICAL = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const ALPHANUMERIC = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

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
            self::query()->where('verifiable', $verificationCode->verifiable)->delete();

            if ($verificationCode->expires_at === null) {
                $verificationCode->expires_at = now()->addHours(config('verification-code.expire_hours'));
            }

            if (Hash::needsRehash($verificationCode->code)) {
                $verificationCode->code = Hash::make($verificationCode->code);
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
        $code = static::generateCode();

        self::create([
            'code' => $code,
            'verifiable' => $verifiable,
        ]);

        return $code;
    }

    /**
     * Find verification code for the verifiable.
     *
     * @param string $verifiable
     *
     * @return self
     */
    public static function from(string $verifiable)
    {
        return self::query()->where('verifiable', $verifiable)->first();
    }

    /**
     * Generate random code.
     */
    protected static function generateCode() : string
    {
        $length = config('verification-code.length');
        $characterSet = static::getCharacterSet();

        if(!is_int($length)) {
            $length = 6;
        }

        $code = '';

        for ($i = 0; $i < $length; $i++) {
            $code .= $characterSet[rand(0, strlen($characterSet) - 1)];
        }

        return $code;
    }

    /**
     * Get the character set.
     */
    protected static function getCharacterSet() : string
    {
        $type = config('verification-code.type');
        $excludedCharacters = config('verification-code.exclude_characters');

        $characterSet = defined($type) ? static::$type : static::NUMERIC;

        if(is_array($excludedCharacters)) {
            return str_replace($excludedCharacters, '', $characterSet);
        }

        return $characterSet;
    }
}
