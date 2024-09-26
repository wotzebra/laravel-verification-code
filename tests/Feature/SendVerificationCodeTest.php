<?php

namespace Wotz\VerificationCode\Tests\Feature;

use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Testing\Fakes\NotificationFake;
use RuntimeException;
use Wotz\VerificationCode\Models\VerificationCode;
use Wotz\VerificationCode\Notifications\VerificationCodeCreated;
use Wotz\VerificationCode\Tests\TestCase;
use Wotz\VerificationCode\VerificationCode as VerificationCodeFacade;

class SendVerificationCodeTest extends TestCase
{
    /** @test */
    public function it_sends_mail_notification_to_verifiable()
    {
        VerificationCodeFacade::send('taylor@laravel.com');

        $this->assertNotNull(VerificationCode::where('verifiable', 'taylor@laravel.com')->first());

        Notification::assertSentTo(
            new AnonymousNotifiable(),
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
            new AnonymousNotifiable(),
            VerificationCodeCreated::class,
            function ($notification, $channels, $notifiable) {
                return $notification->queue === 'random-queue';
            }
        );
    }

    /** @test */
    public function it_sends_notification_using_provided_channel()
    {
        if (! method_exists(NotificationFake::class, 'assertSentOnDemand')) {
            $this->markTestSkipped('assertSentOnDemand method not available on NotificationFake');
        }

        VerificationCodeFacade::send('taylor@laravel.com', 'a-random-channel');

        Notification::assertSentOnDemand(function (VerificationCodeCreated $notification, array $channels, object $notifiable) {
            return $notifiable->routes === ['a-random-channel' => 'taylor@laravel.com'];
        });
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
    public function it_sends_no_notification_to_uppercase_test_verifiable()
    {
        config()->set('verification-code.test_verifiables', ['taylor@laravel.com']);

        VerificationCodeFacade::send('TAYLOR@LARAVEL.COM');

        $this->assertNull(VerificationCode::where('verifiable', 'TAYLOR@LARAVEL.COM')->first());

        Notification::assertNothingSent();
    }

    /** @test */
    public function it_deletes_old_code_of_verifiable_on_send()
    {
        $oldVerificationCode = VerificationCode::create(['code' => 'ABC123', 'verifiable' => 'taylor@laravel.com']);

        VerificationCodeFacade::send('taylor@laravel.com');

        $this->assertNull(VerificationCode::find($oldVerificationCode->id));
        $this->assertCount(1, VerificationCode::where('verifiable', 'taylor@laravel.com')->get());
    }

    /** @test */
    public function it_throws_exception_if_notification_does_not_extend_the_verification_notification_class()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The notification class must implement the `Wotz\VerificationCode\Notifications\VerificationCodeCreatedInterface` interface');

        config()->set('verification-code.notification', NotificationDoesNotImplementInterface::class);

        VerificationCodeFacade::send('taylor@laravel.com');
    }
}

class NotificationDoesNotImplementInterface extends Notification
{
}
