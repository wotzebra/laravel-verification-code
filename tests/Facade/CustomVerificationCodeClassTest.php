<?php

namespace Wotz\VerificationCode\Tests\Facade;

use RuntimeException;
use Wotz\VerificationCode\Models\VerificationCode;
use Wotz\VerificationCode\Tests\TestCase;
use Wotz\VerificationCode\VerificationCode as VerificationCodeFacade;

class CustomVerificationCodeClassTest extends TestCase
{
    /** @test */
    public function it_returns_the_model_verification_code_class_by_default()
    {
        $this->assertSame(VerificationCode::class, VerificationCodeFacade::getModelClass());
    }

    /** @test */
    public function it_returns_the_model_class_that_was_set_in_the_config()
    {
        config()->set('verification-code.model', ModelDoesExtendVerificationCode::class);

        $this->assertSame(ModelDoesExtendVerificationCode::class, VerificationCodeFacade::getModelClass());
    }

    /** @test */
    public function it_throws_exception_if_notification_does_not_extend_the_verification_notification_class()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The model class must extend the `Wotz\VerificationCode\Models\VerificationCode` class');

        config()->set('verification-code.model', ModelDoesNotExtendVerificationCode::class);

        VerificationCodeFacade::getModelClass();
    }
}

class ModelDoesExtendVerificationCode extends VerificationCode
{
}

class ModelDoesNotExtendVerificationCode
{
}
