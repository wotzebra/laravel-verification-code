<?php

namespace NextApps\VerificationCode\Console;

use Illuminate\Console\Command;
use NextApps\VerificationCode\Models\VerificationCode;

class PruneCommand extends Command
{
    protected $signature = 'verification-code:prune {--hours=24 : The number of hours to retain verification codes}';

    protected $description = 'Prune old verification codes';

    public function handle() : void
    {
        VerificationCode::query()
            ->where('created_at', '<=', now()->subHours($this->option('hours')))
            ->where('expires_at', '<', now())
            ->delete();
    }
}
