<?php

namespace NextApps\VerificationCode\Tests\Models;

use Illuminate\Support\Facades\Hash;
use NextApps\VerificationCode\Models\VerificationCode;
use NextApps\VerificationCode\Support\CodeGenerator;
use NextApps\VerificationCode\Tests\TestCase;

class VerificationCodeTest extends TestCase
{
    /** @test */
    public function it_creates_and_returns_code()
    {
        $code = VerificationCode::createFor('taylor@laravel.com');

        $this->assertNotNull($dbVerificationCode = VerificationCode::first());
        $this->assertEquals('taylor@laravel.com', $dbVerificationCode->verifiable);
        $this->assertTrue(Hash::check($code, $dbVerificationCode->code));
        $this->assertNotNull($dbVerificationCode->expires_at);
    }

    /** @test */
    public function it_sets_expiration_date_based_on_config()
    {
        config()->set('verification-code.expire_hours', 6);

        VerificationCode::createFor('taylor@laravel.com');

        $dbVerificationCode = VerificationCode::first();

        $this->assertNotNull($dbVerificationCode->expires_at);
        $this->assertEquals(0, $dbVerificationCode->expires_at->diffInMinutes(now()->addHours(6)));
    }

    /** @test */
    public function it_creates_code_using_code_generator()
    {
        $this->mock(CodeGenerator::class, function ($mock) {
            $mock->shouldReceive('generate')->andReturn('ABC123');
        });

        VerificationCode::createFor('taylor@laravel.com');

        $this->assertTrue(Hash::check('ABC123', VerificationCode::first()->code));
    }

    /** @test */
    public function it_deletes_old_codes_of_verifiable_when_creating()
    {
        $otherVerificationCode = factory(VerificationCode::class)->create(['verifiable' => 'dries@laravel.com']);
        $oldVerificationCode = factory(VerificationCode::class)->create(['verifiable' => 'taylor@laravel.com']);

        factory(VerificationCode::class)->create(['verifiable' => 'taylor@laravel.com']);

        $this->assertNull(VerificationCode::find($oldVerificationCode->id));
        $this->assertNotNull(VerificationCode::find($otherVerificationCode->id));
    }

    /** @test */
    public function it_sets_expiration_date_if_not_set_on_create()
    {
        config()->set('verification-code.expire_hours', 4);

        $verificationCode = factory(VerificationCode::class)->create(['expires_at' => null]);

        $this->assertNotNull($verificationCode->expires_at);
        $this->assertEquals(0, $verificationCode->expires_at->diffInMinutes(now()->addHours(4)));
    }

    /** @test */
    public function it_does_not_set_expiration_date_if_already_set_on_create()
    {
        config()->set('verification.expire_hours', 4);

        $verificationCode = factory(VerificationCode::class)->create(['expires_at' => now()->addDays(1000)]);

        $this->assertNotNull($verificationCode->expires_at);
        $this->assertNotEquals(0, $verificationCode->expires_at->diffInMinutes(now()->addHours(4)));
    }

    /** @test */
    public function it_hashes_code_if_not_hashed_yet_on_create()
    {
        $verificationCode = factory(VerificationCode::class)->create(['code' => 'ABC123']);

        $this->assertTrue(Hash::check('ABC123', $verificationCode->code));
    }

    /** @test */
    public function it_does_not_hash_code_if_already_hashed_on_create()
    {
        $verificationCode = factory(VerificationCode::class)->create(['code' => $hashedCode = Hash::make('ABC123')]);

        $this->assertEquals($hashedCode, $verificationCode->code);
    }
}
