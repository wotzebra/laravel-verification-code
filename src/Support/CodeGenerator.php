<?php

namespace NextApps\VerificationCode\Support;

use NextApps\VerificationCode\CodeTypes\CodeType;
use RuntimeException;

class CodeGenerator
{
    /**
     * Generate a random code.
     *
     * @return string
     */
    public function generate()
    {
        $length = $this->getLength();
        $characters = $this->getCharacters();

        return collect(range(0, $length - 1))
            ->map(function () use ($characters) {
                return $characters[rand(0, strlen($characters) - 1)];
            })
            ->join('');
    }

    /**
     * Get the required length.
     *
     * @throws \RuntimeException
     *
     * @return int
     */
    protected function getLength()
    {
        $length = config('verification-code.length');

        if (! is_int($length)) {
            throw new RuntimeException('The length of verification code must be an integer');
        }

        return $length;
    }

    /**
     * Get the allowed characters.
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    protected function getCharacters()
    {
        $codeType = config('verification-code.type');

        if ($codeType instanceof CodeType) {
            throw new RuntimeException('The code type must extend the `\NextApps\VerificationCode\CodeTypes\CodeType` class');
        }

        return app($codeType)->getAllowedCharacters();
    }
}
