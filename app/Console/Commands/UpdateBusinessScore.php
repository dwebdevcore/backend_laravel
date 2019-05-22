<?php

namespace App\Console\Commands;

use App\Console\Db\DbQueryLoggerTrait;
use App\Models\Business;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateBusinessScore extends Command
{
    use DbQueryLoggerTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:business-score';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate business score and business internal score';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected function logDbQueries(): bool
    {
        return false;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $businessQuery = Business::withRequiredForUpdate()
                ->withCount('attributes')
                ->with('reviewsExists', 'postsExists', 'categoriesExists', 'addyAttributesExists', 'reviewsAvgCode');

        $count = $businessQuery->count();

        if (!$count) {
            return;
        }

        $bar = $this->output->createProgressBar($count);

        $businessQuery->chunk(200, function ($businesses) use ($bar) {
            DB::transaction(function () use ($businesses, $bar) {
                foreach ($businesses as $business) {
                    $business->updateScores();
                    $bar->advance();
                }
            });
        });

        $bar->finish();

        $this->info('Done');
    }
}
