<?php

namespace NextApps\VerificationCode\CodeTypes;

class Alphanumeric extends CodeType
{
    /**
     * Get the characters of the code type.
     *
     * @return array
     */
    public function getCharacters()
    {
        return (new Numeric())->getCharacters().(new Alphabetical())->getCharacters();
    }
}
