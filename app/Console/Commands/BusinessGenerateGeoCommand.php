<?php

namespace App\Console\Commands;

use App\Console\Db\DbQueryLoggerTrait;
use App\Models\Business;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\URL;

class BusinessGenerateGeoCommand extends Command
{
    use DbQueryLoggerTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'business:generate-geo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Business geo json file.';

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
        return true;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $count = Business::count();

        if (!$count) {
            return;
        }

        $bar = $this->output->createProgressBar($count);

        $path = config('filesystems.geojson_path');

        $geoJson = [
            'type'     => 'FeatureCollection',
            'features' => []
        ];

        $file = fopen(storage_path($path), "w");

        fwrite($file, substr(json_encode($geoJson), 0, -2));

        Business::chunk(200, function ($businesses) use ($file, $bar) {
            foreach ($businesses as $business) {
                $append = [
                    'type'       => 'Feature',
                    'geometry'   => [
                        'type'        => 'Point',
                        'coordinates' => [$business['lng'], $business['lat']]
                    ],
                    'properties' => [
                        'name' => "<a href='".URL::to(config('nova.path')."/resources/businesses/".$business['id'])."'>{$business['name']}</a>",
                    ]
                ];
                $append = json_encode($append) . ",";
                fwrite($file, $append);

                $bar->advance();
            }
        });

        ftruncate($file, fstat($file)['size'] - 1);
        fseek($file, fstat($file)['size']);
        fwrite($file, "]}");
        fclose($file);
        chmod(storage_path($path), 0644);

        $bar->finish();

        $this->info('Done');
    }
}
