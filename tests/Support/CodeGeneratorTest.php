<?php

namespace NextApps\VerificationCode\Tests\Support;

use RuntimeException;
use NextApps\VerificationCode\Tests\TestCase;
use NextApps\VerificationCode\CodeTypes\CodeType;
use NextApps\VerificationCode\Support\CodeGenerator;

class CodeGeneratorTest extends TestCase
{
    /** @test */
    public function it_generates_code_using_length_from_config()
    {
        config()->set('verification-code.length', 4);

        $code = app(CodeGenerator::class)->generate();

        $this->assertCount(4, str_split($code));
    }

    /** @test */
    public function it_generates_code_using_type_from_config()
    {
        config()->set('verification-code.type', RandomCodeType::class);

        $this->mock(RandomCodeType::class, function ($mock) {
            $mock->shouldReceive('getAllowedCharacters')->andReturn('abc123');
        });

        app(CodeGenerator::class)->generate();
    }

    /** @test */
    public function it_throws_exception_if_length_is_no_integer()
    {
        config()->set('verification-code.length', null);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The code length must be an integer');

        app(CodeGenerator::class)->generate();
    }

    /** @test */
    public function it_throws_exception_if_type_does_not_extend_abstract_class()
    {
        config()->set('verification-code.type', CodeTypeThatDoesNotExtendAbstractClass::class);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The code type must extend the `\NextApps\VerificationCode\CodeTypes\CodeType` class');

        app(CodeGenerator::class)->generate();
    }
}

class RandomCodeType extends CodeType
{
    public function getCharacters()
    {
        return 'abc123';
    }
}

class CodeTypeThatDoesNotExtendAbstractClass
{
}
