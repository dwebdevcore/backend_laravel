<?php

namespace App\Console\Commands;

use App\Console\Db\DbQueryLoggerTrait;
use App\Models\Business;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BusinessFixUtf8 extends Command
{
    use DbQueryLoggerTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'business:fix-utf8';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the DB with the correct UTF8 character for business name';

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
        $businessQuery = Business::withRequiredForUpdate();

        $count = $businessQuery->count();

        if (!$count) {
            return;
        }

        $bar = $this->output->createProgressBar($count);

        $businessQuery->chunk(200, function ($businesses) use ($bar) {
            Db::transaction(function () use ($businesses, $bar) {
                foreach ($businesses as $business) {
                    $business->name = $this->unicodeDecode($business->name);
                    $business->save();
                    $bar->advance();
                }
            });
        });

        $bar->finish();

        $this->info('Done');
    }

    /**
     * @param $str
     * @return null|string|string[]
     */
    function unicodeDecode($str) {
        return preg_replace_callback('/\\\\u([0-9a-f]{4})/i', function ($match) {
            return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
        }, $str);
    }
}
