<?php

namespace App\Console\Commands;

use App\Console\Db\DbQueryLoggerTrait;
use App\Models\BusinessReview;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BusinessFixHtmlEntities extends Command
{
    use DbQueryLoggerTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'business:fix-html-entities';

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
     *
     * @return mixed
     */
    public function handle()
    {
        $count = BusinessReview::count();

        if (!$count) {
            return;
        }

        $bar = $this->output->createProgressBar($count);

        BusinessReview::chunk(500, function ($reviews) use ($bar) {
            DB::transaction(function () use ($reviews, $bar) {
                foreach ($reviews as $review) {
                    $review->comment = html_entity_decode($review->comment);
                    $review->save();
                    $bar->advance();
                }
            });
        });

        $bar->finish();

        $this->info('Done');
    }
}
