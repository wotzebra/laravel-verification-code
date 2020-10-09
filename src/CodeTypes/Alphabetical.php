<?php

namespace NextApps\VerificationCode\CodeTypes;

class Alphabetical extends CodeType
{
    /**
     * Get the characters of the code type.
     *
     * @return string
     */
    public function getCharacters()
    {
        return 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    }
}
