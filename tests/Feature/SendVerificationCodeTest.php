<?php

namespace NextApps\VerificationCode\Tests\Feature;

use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Testing\Fakes\NotificationFake;
use NextApps\VerificationCode\Models\VerificationCode;
use NextApps\VerificationCode\Notifications\VerificationCodeCreated;
use NextApps\VerificationCode\Tests\TestCase;
use NextApps\VerificationCode\VerificationCode as VerificationCodeFacade;
use RuntimeException;

class SendVerificationCodeTest extends TestCase
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
        $this->mock(NotificationFake::class, function ($mock) {
            $mock->shouldReceive('route')
                ->with('a-random-channel', 'taylor@laravel.com')
                ->andReturnSelf()
                ->shouldReceive('notify');
        });

        VerificationCodeFacade::send('taylor@laravel.com', 'a-random-channel');
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
        $oldVerificationCode = VerificationCode::create(['code' => 'ABC123', 'verifiable' => 'taylor@laravel.com']);

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
}

class NotificationDoesNotImplementInterface extends Notification
{
}
