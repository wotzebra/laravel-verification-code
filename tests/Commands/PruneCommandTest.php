<?php

namespace NextApps\VerificationCode\Tests\Feature;

use NextApps\VerificationCode\Models\VerificationCode;
use NextApps\VerificationCode\Tests\TestCase;

class PruneCommandTest extends TestCase
{
    /** @test */
    public function it_cleans_the_verification_code_table()
    {
        VerificationCode::create([
            'code' => 'ABC123',
            'verifiable' => 'taylor@laravel.com',
            'created_at' => now()->subHours(5),
        ]);

        $verificationCode = VerificationCode::create([
            'code' => '123ABC',
            'verifiable' => 'taylor@laravel.com',
        ]);

        $this->artisan('verification-code:prune', ['--hours' => 3]);

        $dbVerificationCodes = VerificationCode::all();

        $this->assertCount(1, $dbVerificationCodes);
        $this->assertEquals($verificationCode->id, $dbVerificationCodes->first()->id);
    }
}
