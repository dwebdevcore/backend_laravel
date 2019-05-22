<?php

namespace App\Console\Commands;

use App\Models\Business;
use App\Rules\Lat;
use App\Rules\Lng;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\Table;

class PlaceShowWrongCoords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'place:show-wrong-coords';

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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $table = new Table($this->output);
        $table->setHeaders(['#id', 'lat', 'lng']);

        Business::chunk(200, function ($places) use ($table) {
            foreach ($places as $place) {
                if (!(new Lat())->passes('lat', $place['lat']) || !(new Lng())->passes('lng', $place['lng'])) {
                    $table->addRow([$place['id'], $place['lat'], $place['lng']]);
                }
            }
        });

        $table->render();
        $this->info('Done');
    }
}
