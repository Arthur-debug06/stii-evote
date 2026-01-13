<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\voting_exclusive;
use App\Models\applied_candidacy;
use App\Models\voting_vote_count;
use Illuminate\Support\Facades\DB;

class SyncVotingVoteCounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voting:sync-vote-counts {--exclusive=* : Limit to specific exclusive id(s)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'One-time: create missing voting_vote_count rows for approved applied_candidacy entries for active voting exclusives.';

    public function handle()
    {
        $this->info('Starting sync of voting_vote_count records...');

        $exclusiveIds = $this->option('exclusive');

        $query = voting_exclusive::where('status', 'active');
        if (!empty($exclusiveIds)) {
            $query->whereIn('id', $exclusiveIds);
        }

        $exclusives = $query->get();
        if ($exclusives->isEmpty()) {
            $this->info('No active voting_exclusives found (or none matching the provided --exclusive filter).');
            return Command::SUCCESS;
        }

        $totalCreated = 0;

        DB::beginTransaction();
        try {
            foreach ($exclusives as $exclusive) {
                $this->info("Processing exclusive id={$exclusive->id} (school_year_id={$exclusive->school_year_id})...");

                $candidatesQuery = applied_candidacy::where('status', 'approved');
                if ($exclusive->school_year_id) {
                    $candidatesQuery->where('school_year_and_semester_id', $exclusive->school_year_id);
                }

                $approved = $candidatesQuery->get();

                foreach ($approved as $c) {
                    $attributes = [
                        'voting_exclusive_id' => $exclusive->id,
                        'students_id' => $c->students_id,
                    ];

                    $values = [
                        'number_of_vote' => 0,
                        'status' => 'official'
                    ];

                    $record = voting_vote_count::firstOrCreate($attributes, $values);
                    if ($record && $record->wasRecentlyCreated) {
                        $totalCreated++;
                        $this->line("  - Created vote_count for student_id={$c->students_id}");
                    }
                }
            }

            DB::commit();
            $this->info("Sync complete. Created {$totalCreated} voting_vote_count record(s).");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Sync failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
