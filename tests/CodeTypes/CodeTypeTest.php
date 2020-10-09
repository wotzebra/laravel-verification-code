<?php

namespace NextApps\VerificationCode\Tests\CodeTypes;

use NextApps\VerificationCode\CodeTypes\Alphabetical;
use NextApps\VerificationCode\CodeTypes\Alphanumeric;
use NextApps\VerificationCode\CodeTypes\CodeType;
use NextApps\VerificationCode\CodeTypes\Numeric;
use NextApps\VerificationCode\Tests\TestCase;

class CodeTypeTest extends TestCase
{
    /** @test */
    public function it_removes_excluded_characters()
    {
        config()->set('verification-code.type', CodeTypeThatContainsExcludedCharacters::class);
        config()->set('verification-code.excluded_characters', 'BAR');

        $characters = app(CodeTypeThatContainsExcludedCharacters::class)->getAllowedCharacters();

        $this->assertEquals('FOOa123', $characters);
    }

    /** @test */
    public function it_returns_only_numbers()
    {
        $characters = app(Numeric::class)->getAllowedCharacters();

        $this->assertEquals('0123456789', $characters);
    }

    /** @test */
    public function it_returns_only_letters()
    {
        $characters = app(Alphabetical::class)->getAllowedCharacters();

        $this->assertEquals('ABCDEFGHIJKLMNOPQRSTUVWXYZ', $characters);
    }

    /** @test */
    public function it_returns_numbers_and_letters()
    {
        $characters = app(Alphanumeric::class)->getAllowedCharacters();

        $this->assertEquals('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', $characters);
    }
}

class CodeTypeThatContainsExcludedCharacters extends CodeType
{
    public function getCharacters()
    {
        return 'FOOBAaR123';
    }
}
