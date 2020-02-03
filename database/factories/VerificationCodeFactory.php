<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use NextApps\VerificationCode\Models\VerificationCode;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(VerificationCode::class, function (Faker $faker) {
    return [
        'code' => Str::random(6),
        'verifiable' => $faker->safeEmail,
        'expires_at' => now()->addHours(rand(1, 100)),
    ];
});

$factory->state(VerificationCode::class, 'expired', [
    'expires_at' => now()->subHours(1),
]);
