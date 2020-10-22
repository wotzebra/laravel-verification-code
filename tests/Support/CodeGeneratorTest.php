<?php

namespace NextApps\VerificationCode\Tests\Support;

use Illuminate\Support\Str;
use NextApps\VerificationCode\Support\CodeGenerator;
use NextApps\VerificationCode\Tests\TestCase;
use RuntimeException;

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
    public function it_generates_code_using_characters_from_config()
    {
        config()->set('verification-code.characters', 'abc123');

        $code = app(CodeGenerator::class)->generate();

        foreach (str_split($code) as $character) {
            $this->assertTrue(Str::contains('abc123', $character));
        }
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
    public function it_throws_exception_if_characters_is_empty_string()
    {
        config()->set('verification-code.characters', '');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The character list must contain at least 1 character');

        app(CodeGenerator::class)->generate();
    }
}
