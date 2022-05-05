<?php

namespace NextApps\VerificationCode\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PruneCommand extends Command
{
    protected $signature = 'verification-code:prune {--hours=24 : The number of hours to retain verification codes}';

    protected $description = 'Prune old verification codes';

    public function handle() : void
    {
        $query = DB::table('verification_codes')
            ->where('created_at', '<', $this->option('hours'));

        $totalDeleted = 0;

        do {
            $deleted = $query->take(1000)->delete();

            $totalDeleted += $deleted;
        } while ($deleted !== 0);

        $this->info($totalDeleted . ' entries pruned.');
    }
}
