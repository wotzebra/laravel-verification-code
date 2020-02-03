<?php

namespace NextApps\VerificationCode\Tests\Models;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use NextApps\VerificationCode\Models\VerificationCode;
use NextApps\VerificationCode\Tests\TestCase;

class VerificationCodeTest extends TestCase
{
    /** @test */
    public function it_returns_a_valid_code_using_the_create_for_verifiable_method()
    {
        $verifiable = $this->faker->randomElement([$this->faker->safeEmail]);

        $code = VerificationCode::createFor($verifiable);

        $dbVerificationCode = VerificationCode::where('verifiable', $verifiable)->first();

        $this->assertNotNull($dbVerificationCode);
        $this->assertTrue(Hash::check($code, $dbVerificationCode->code));
        $this->assertEquals(
            0,
            now()->addHours(config('verification.expire_hours'))->diffInMinutes($dbVerificationCode->expires_at)
        );
    }

    /** @test */
    public function it_returns_a_code_using_the_from_verifiable_method()
    {
        $verifiable = $this->faker->randomElement([$this->faker->safeEmail]);

        $verificationCode = factory(VerificationCode::class)->create([
            'verifiable' => $verifiable,
        ]);

        factory(VerificationCode::class, 3)->create();

        $this->assertEquals($verificationCode->id, VerificationCode::from($verifiable)->id);
    }

    /** @test */
    public function it_returns_a_valid_code_when_excluded_characters_is_not_an_array()
    {
        config()->set('verification-code.exclude_characters', Str::random());

        $verifiable = $this->faker->randomElement([$this->faker->safeEmail]);

        $code = VerificationCode::createFor($verifiable);

        $dbVerificationCode = VerificationCode::where('verifiable', $verifiable)->first();

        $this->assertNotNull($dbVerificationCode);
        $this->assertTrue(Hash::check($code, $dbVerificationCode->code));
        $this->assertEquals(
            0,
            now()->addHours(config('verification.expire_hours'))->diffInMinutes($dbVerificationCode->expires_at)
        );
    }

    /** @test */
    public function it_deletes_old_codes_of_verifiable_when_creating()
    {
        $verifiable = $this->faker->randomElement([$this->faker->safeEmail]);

        $oldVerificationCode = factory(VerificationCode::class)->create([
            'verifiable' => $verifiable,
        ]);

        $verificationCode = factory(VerificationCode::class)->create([
            'verifiable' => $verifiable,
        ]);

        $this->assertNull(VerificationCode::find($oldVerificationCode->id));
    }

    /** @test */
    public function it_sets_expires_at_if_not_set_after_create()
    {
        $verificationCode = factory(VerificationCode::class)->create([
            'expires_at' => null,
        ]);

        $this->assertEquals(
            0,
            now()->addHours(config('verification.expire_hours'))->diffInMinutes($verificationCode->expires_at)
        );
    }

    /** @test */
    public function it_does_not_set_expires_at_if_set_after_create()
    {
        $verificationCode = factory(VerificationCode::class)->create([
            'expires_at' => ($expiresAt = now()->addDays(1000)),
        ]);

        $this->assertEquals(0, $expiresAt->diffInMinutes($verificationCode->expires_at));
    }

    /** @test */
    public function it_hashes_code_if_not_hashed_yet_after_create()
    {
        $verificationCode = factory(VerificationCode::class)->create([
            'code' => ($code = Str::random()),
        ]);

        $this->assertTrue(Hash::check($code, $verificationCode->code));
    }

    /** @test */
    public function it_does_not_hash_code_if_hashed_after_create()
    {
        $verificationCode = factory(VerificationCode::class)->create([
            'code' => ($hashedCode = Hash::make(Str::random())),
        ]);

        $this->assertEquals($hashedCode, $verificationCode->code);
    }
}
