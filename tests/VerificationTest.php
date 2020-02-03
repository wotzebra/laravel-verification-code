<?php

namespace NextApps\VerificationCode\Tests;

use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use NextApps\VerificationCode\Models\VerificationCode;
use NextApps\VerificationCode\Notifications\VerificationCodeCreated;
use NextApps\VerificationCode\Verification;

class VerificationTest extends TestCase
{
    /** @test */
    public function it_sends_mail_to_verifiable()
    {
        $email = $this->faker->safeEmail;

        app(Verification::class)->sendCode($email);

        $this->assertNotNull(VerificationCode::where('verifiable', $email)->first());

        Notification::assertSentTo(
            new AnonymousNotifiable,
            VerificationCodeCreated::class,
            function ($notification, $channels, $notifiable) use ($email) {
                return $notifiable->routes['mail'] === $email;
            });
    }

    /** @test */
    public function it_sends_no_mail_to_test_verifiable()
    {
        $email = $this->faker->safeEmail;

        config()->set('verification-code.test_verifiables', [$email]);

        app(Verification::class)->sendCode($email);

        $this->assertNull(VerificationCode::where('verifiable', $email)->first());

        Notification::assertNothingSent();
    }

    /** @test */
    public function it_deletes_other_codes_of_verifiable_after_send_mail()
    {
        $email = $this->faker->safeEmail;

        $oldVerificationCodes = factory(VerificationCode::class, 3)->create([
            'verifiable' => $email,
        ]);

        app(Verification::class)->sendCode($email);

        $dbOldVerificationCodes = VerificationCode::find($oldVerificationCodes->pluck('id')->toArray());

        $this->assertCount(0, $dbOldVerificationCodes);
    }

    /** @test */
    public function it_returns_true_if_valid_code()
    {
        $verifiable = $this->faker->randomElement([$this->faker->safeEmail]);

        $verificationCode = factory(VerificationCode::class)->create([
            'code' => ($code = Str::random()),
            'verifiable' => $verifiable,
        ]);

        $this->assertTrue(app(Verification::class)->verify($code, $verifiable));

        $this->assertNull(VerificationCode::find($verificationCode->id));
    }

    /** @test */
    public function it_returns_false_if_invalid_code()
    {
        $verifiable = $this->faker->randomElement([$this->faker->safeEmail]);

        $verificationCode = factory(VerificationCode::class)->create([
            'verifiable' => $verifiable,
        ]);

        $this->assertFalse(app(Verification::class)->verify(Str::random(), $verifiable));

        $this->assertNotNull(VerificationCode::find($verificationCode->id));
    }

    /** @test */
    public function it_returns_false_if_expired_code()
    {
        $verifiable = $this->faker->randomElement([$this->faker->safeEmail]);

        $verificationCode = factory(VerificationCode::class)->state('expired')->create([
            'code' => ($code = Str::random()),
            'verifiable' => $verifiable,
        ]);

        $this->assertFalse(app(Verification::class)->verify($code, $verifiable));

        $this->assertNotNull(VerificationCode::find($verificationCode->id));
    }

    /** @test */
    public function it_returns_true_if_test_verifiable_with_test_code()
    {
        $verifiable = $this->faker->randomElement([$this->faker->safeEmail]);

        config()->set('verification-code.test_verifiables', [$verifiable]);
        config()->set('verification-code.test_code', ($code = Str::random()));

        $this->assertTrue(app(Verification::class)->verify($code, $verifiable));
    }

    /** @test */
    public function it_returns_false_if_test_verifiable_with_invalid_test_code()
    {
        $verifiable = $this->faker->randomElement([$this->faker->safeEmail]);

        config()->set('verification-code.test_verifiables', [$verifiable]);
        config()->set('verification-code.test_code', Str::random());

        $this->assertFalse(app(Verification::class)->verify(Str::random(), $verifiable));
    }

    /** @test */
    public function it_returns_false_if_test_verifiable_with_empty_test_code()
    {
        $verifiable = $this->faker->randomElement([$this->faker->safeEmail]);

        config()->set('verification-code.test_verifiables', [$verifiable]);
        config()->set('verification-code.test_code', '');

        $this->assertFalse(app(Verification::class)->verify('', $verifiable));
    }
}
