<?php

namespace NextApps\VerificationCode\CodeTypes;

class Alphabetical extends CodeType
{
    /**
     * Get the characters of the code type.
     *
     * @return array
     */
    public function getCharacters()
    {
        return 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    }
}
