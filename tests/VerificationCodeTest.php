<?php

namespace NextApps\VerificationCode\Tests;

use Illuminate\Support\Str;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\AnonymousNotifiable;
use NextApps\VerificationCode\Models\VerificationCode;
use NextApps\VerificationCode\Exceptions\InvalidClassException;
use NextApps\VerificationCode\Notifications\VerificationCodeCreated;
use NextApps\VerificationCode\VerificationCode as VerificationCodeFacade;
use NextApps\VerificationCode\Notifications\VerificationCodeCreatedInterface;
use RuntimeException;

class VerificationCodeTest extends TestCase
{
    /** @test */
    public function it_sends_mail_notification_to_verifiable()
    {
        VerificationCodeFacade::send('taylor@laravel.com');

        $this->assertNotNull(VerificationCode::where('verifiable', 'taylor@laravel.com')->first());

        Notification::assertSentTo(
            new AnonymousNotifiable,
            VerificationCodeCreated::class,
            function ($notification, $channels, $notifiable) {
                $this->assertEquals(['mail'], $channels);
                $this->assertEquals('taylor@laravel.com', $notifiable->routes['mail']);

                return true;
            }
        );
    }

    /** @test */
    public function it_sets_notification_queue_based_on_config()
    {
        config()->set('verification-code.queue', 'random-queue');

        VerificationCodeFacade::send('taylor@laravel.com');

        Notification::assertSentTo(
            new AnonymousNotifiable,
            VerificationCodeCreated::class,
            function ($notification, $channels, $notifiable) {
                return $notification->queue === 'random-queue';
            }
        );
    }

    /** @test */
    public function it_sends_notification_using_provided_channel()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function it_sends_no_notification_to_test_verifiable()
    {
        config()->set('verification-code.test_verifiables', ['taylor@laravel.com']);

        VerificationCodeFacade::send('taylor@laravel.com');

        $this->assertNull(VerificationCode::where('verifiable', 'taylor@laravel.com')->first());

        Notification::assertNothingSent();
    }

    /** @test */
    public function it_deletes_old_code_of_verifiable_on_send()
    {
        $oldVerificationCode = factory(VerificationCode::class)->create(['verifiable' => 'taylor@laravel.com']);

        VerificationCodeFacade::send('taylor@laravel.com');

        $this->assertNull(VerificationCode::find($oldVerificationCode->id));
        $this->assertCount(1, VerificationCode::where('verifiable', 'taylor@laravel.com')->get());
    }

    /** @test */
    public function it_throws_exception_if_notification_does_not_extend_the_verification_notification_class()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The notification class must implement the `\NextApps\VerificationCode\Notifications\VerificationCodeCreatedInterface` interface');

        config()->set('verification-code.notification', NotificationDoesNotImplementInterface::class);

        VerificationCodeFacade::send('taylor@laravel.com');
    }

    /** @test */
    public function it_returns_true_if_code_is_valid_for_verifiable()
    {
        factory(VerificationCode::class)->create([
            'code' => 'ABC123',
            'verifiable' => 'taylor@laravel.com',
        ]);

        $this->assertTrue(VerificationCodeFacade::verify('ABC123', 'taylor@laravel.com'));
    }

    /** @test */
    public function it_returns_false_if_code_is_invalid_for_verifiable()
    {
        factory(VerificationCode::class)->create([
            'code' => 'ABC123',
            'verifiable' => 'taylor@laravel.com',
        ]);

        $this->assertFalse(VerificationCodeFacade::verify('123ABC', 'taylor@laravel.com'));
    }

    /** @test */
    public function it_returns_false_if_code_is_valid_for_verifiable_but_has_expired()
    {
        factory(VerificationCode::class)->create([
            'code' => 'ABC123',
            'verifiable' => 'taylor@laravel.com',
            'expires_at' => now()->subHour(),
        ]);

        $this->assertFalse(VerificationCodeFacade::verify('123ABC', 'taylor@laravel.com'));
    }

    /** @test */
    public function it_deletes_code_if_used_for_verification()
    {
        $verificationCode = factory(VerificationCode::class)->create([
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

class NotificationDoesNotImplementInterface extends Notification
{
}
