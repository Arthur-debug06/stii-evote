<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\VotingStatusService;

class ProcessExpiredVotings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voting:process-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process expired voting exclusives and update win/loss status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing expired voting exclusives...');
        
        $processedCount = VotingStatusService::processExpiredVotings();
        
        if ($processedCount > 0) {
            $this->info("Successfully processed {$processedCount} expired voting(s) and updated win/loss status.");
        } else {
            $this->info('No expired votings to process.');
        }
        
        return Command::SUCCESS;
    }
}
