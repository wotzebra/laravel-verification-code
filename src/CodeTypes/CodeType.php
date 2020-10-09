<?php

namespace NextApps\VerificationCode\CodeTypes;

abstract class CodeType
{
    /**
     * Get the allowed characters of the code type.
     *
     * @return string
     */
    public function getAllowedCharacters()
    {
        $excludedCharacters = config('verification-code.excluded_characters', []);

        return str_replace($excludedCharacters, '', $this->getCharacters());
    }

    /**
     * Get the characters of the code type.
     *
     * @return string
     */
    abstract public function getCharacters();
}
