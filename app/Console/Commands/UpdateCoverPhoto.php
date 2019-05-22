<?php

namespace App\Console\Commands;

use App\Console\Db\DbQueryLoggerTrait;
use App\Models\Business;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateCoverPhoto extends Command
{
    use DbQueryLoggerTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:place-avatar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     */
    public function handle()
    {
        $businessesQuery = Business::has('images')
            ->doesntHave('coverImages')
            ->with('images');

        $count = $businessesQuery->count();

        if (!$count) {
            return;
        }

        $bar = $this->output->createProgressBar($count);

        while ($businessesQuery->exists()) {
            $businesses = $businessesQuery->limit(200)->get();

            DB::transaction(function () use ($businesses, $bar) {
                foreach ($businesses as $business) {
                    if ($business->images->count()) {
                        $coverImage = $business->images->first();

                        $coverImage->cover = true;
                        $coverImage->save();
                    }

                    $bar->advance();
                }
            });
        }

        $this->info('Done');
    }
}
