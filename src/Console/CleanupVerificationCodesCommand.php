<?php

namespace NextApps\VerificationCode\Console;

use Illuminate\Console\Command;
use NextApps\VerificationCode\Models\VerificationCode;

class CleanupVerificationCodesCommand extends Command
{
    protected $signature = 'verification-code:cleanup {days : Codes older than these days will be deleted.}';

    protected $description = 'Remove verification code older than the given days.';

    public function handle() : void
    {
        $days = $this->argument('days');

        VerificationCode::query()
            ->where('created_at', '<=', now()->subDays($days))
            ->delete();
    }
}
