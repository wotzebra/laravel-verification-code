<?php

namespace NextApps\VerificationCode\Tests\Feature;

use NextApps\VerificationCode\Models\VerificationCode;
use NextApps\VerificationCode\Tests\TestCase;

class PruneCommandTest extends TestCase
{
    /** @test */
    public function it_deletes_old_expired_code()
    {
        $verificationCode = VerificationCode::create([
            'code' => 'ABC123',
            'verifiable' => 'taylor@laravel.com',
            'expires_at' => now()->subHour(),
        ]);
        VerificationCode::where('id', $verificationCode->id)->update(['created_at' => now()->subHours(5)]);

        $this->artisan('verification-code:prune', ['--hours' => 3]);

        $this->assertNull(VerificationCode::find($verificationCode->id));
    }

    /** @test */
    public function it_does_not_delete_old_but_not_expired_code()
    {
        $verificationCode = VerificationCode::create([
            'code' => 'ABC123',
            'verifiable' => 'taylor@laravel.com',
            'expires_at' => now()->addHour(),
        ]);
        VerificationCode::where('id', $verificationCode->id)->update(['created_at' => now()->subHours(5)]);

        $this->artisan('verification-code:prune', ['--hours' => 3]);

        $this->assertNotNull(VerificationCode::find($verificationCode->id));
    }

    /** @test */
    public function it_does_not_delete_code_that_is_not_old_enough()
    {
        $verificationCode = VerificationCode::create([
            'code' => 'ABC123',
            'verifiable' => 'taylor@laravel.com',
            'expires_at' => now()->subHour(),
        ]);
        VerificationCode::where('id', $verificationCode->id)->update(['created_at' => now()->subHours(2)]);

        $this->artisan('verification-code:prune', ['--hours' => 3]);

        $this->assertNotNull(VerificationCode::find($verificationCode->id));
    }
}
