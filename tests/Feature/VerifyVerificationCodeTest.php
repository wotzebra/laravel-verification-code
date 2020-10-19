<?php

namespace NextApps\VerificationCode\Tests\Feature;

use NextApps\VerificationCode\Models\VerificationCode;
use NextApps\VerificationCode\Tests\TestCase;
use NextApps\VerificationCode\VerificationCode as VerificationCodeFacade;

class VerifyVerificationCodeTest extends TestCase
{
    /** @test */
    public function it_returns_true_if_code_is_valid_for_verifiable()
    {
        VerificationCode::create([
            'code' => 'ABC123',
            'verifiable' => 'taylor@laravel.com',
        ]);

        $this->assertTrue(VerificationCodeFacade::verify('ABC123', 'taylor@laravel.com'));
    }

    /** @test */
    public function it_returns_false_if_code_is_invalid_for_verifiable()
    {
        VerificationCode::create([
            'code' => 'ABC123',
            'verifiable' => 'taylor@laravel.com',
        ]);

        $this->assertFalse(VerificationCodeFacade::verify('123ABC', 'taylor@laravel.com'));
    }

    /** @test */
    public function it_returns_false_if_code_is_valid_for_verifiable_but_has_expired()
    {
        VerificationCode::create([
            'code' => 'ABC123',
            'verifiable' => 'taylor@laravel.com',
            'expires_at' => now()->subHour(),
        ]);

        $this->assertFalse(VerificationCodeFacade::verify('123ABC', 'taylor@laravel.com'));
    }

    /** @test */
    public function it_deletes_code_if_used_for_verification()
    {
        $verificationCode = VerificationCode::create([
            'code' => 'ABC123',
            'verifiable' => 'taylor@laravel.com',
        ]);

        VerificationCodeFacade::verify('123ABC', 'taylor@laravel.com');
        $this->assertNotNull(VerificationCode::find($verificationCode->id));

        VerificationCodeFacade::verify('ABC123', 'taylor@laravel.com');
        $this->assertNull(VerificationCode::find($verificationCode->id));
    }

    /** @test */
    public function it_returns_true_if_test_code_used_by_test_verifiable()
    {
        config()->set('verification-code.test_verifiables', ['taylor@laravel.com']);
        config()->set('verification-code.test_code', 'TESTCODE');

        $this->assertTrue(VerificationCodeFacade::verify('TESTCODE', 'taylor@laravel.com'));
    }

    /** @test */
    public function it_returns_false_if_invalid_test_code_used_by_test_verifiable()
    {
        config()->set('verification-code.test_verifiables', ['taylor@laravel.com']);
        config()->set('verification-code.test_code', 'TESTCODE');

        $this->assertFalse(VerificationCodeFacade::verify('OTHERCODE', 'taylor@laravel.com'));
    }

    /** @test */
    public function it_returns_false_if_test_code_used_by_non_test_verifiable()
    {
        config()->set('verification-code.test_verifiables', ['taylor@laravel.com']);
        config()->set('verification-code.test_code', 'TESTCODE');

        $this->assertFalse(VerificationCodeFacade::verify('TESTCODE', 'dries@laravel.com'));
    }

    /** @test */
    public function it_returns_false_if_test_code_is_empty_in_config()
    {
        config()->set('verification-code.test_verifiables', ['taylor@laravel.com']);
        config()->set('verification-code.test_code', '');

        $this->assertFalse(VerificationCodeFacade::verify('', 'taylor@laravel.com'));
    }
}
