<?php

namespace App\Console\Commands;

use App\Models\Business;
use App\Models\BusinessHour;
use Illuminate\Console\Command;
use File;

class InsertDataIntoBusinessHoursTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:business-hours-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is parsing a file and inserts contents into "business_hours table"';

    protected $business;

    protected $batch;

    protected $data;

    protected $i;

    protected $daysOfWeek;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->batch = 10000;
        $this->data = [];
        $this->i = 0;
        $this->daysOfWeek =
        [
            'sunday' =>    0,
            'monday' =>    1,
            'tuesday' =>   2,
            'wednesday' => 3,
            'thursday' =>  4,
            'friday' =>    5,
            'saturday' =>  6,

        ];
    }

    public function invalidJson()
    {
        if( json_last_error() !== JSON_ERROR_NONE ) return true;
    }

    public function invalidStructure()
    {
        if(! array_key_exists('business_id', $this->business) ) return true;
    }

    public function invalidData()
    {
        if( Business::find($this->business['business_id']) === null ) return true;
    }

    public function prepareForBatch($dayOfWeek, $business_id, $value)
    {
        $day = strtolower($dayOfWeek);
        
        array_push($this->data,
            [
            'business_id' => $business_id,
            'day_of_week' => $this->daysOfWeek[$day],
            'open_period_mins' => $value['open'],
            'close_period_mins' => $value['close']
            ]
        );

        $this->i++;

    }

    public function makeInsert()
    {
        BusinessHour::insert($this->data);
    }

    public function needsInsert()
    {
        if($this->i === $this->batch)
        {
            $this->makeInsert();
            $this->data = [];
            $this->i = 0;
        }
    }

    public function remainingInsert()
    {
        if( count($this->data) )
        {
           $this->makeInsert($this->data);
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $file = fopen( storage_path('hours.txt'), "r" );
        
        while (! feof($file) )
        {
            $row = fgets($file, 4096);
            $this->business = json_decode($row, true);

            if( $this->invalidJson() || $this->invalidStructure() || $this->invalidData() ) continue;
            $business_id = $this->business['business_id'];

            foreach ($this->business['business_hours'] as $dayOfWeek => $periods)
            {
                if ( count($periods) )
                {
                    foreach ($periods as $key => $value)
                    {
                        $this->prepareForBatch($dayOfWeek, $business_id, $value);
                        $this->needsInsert();
                    }
                }
            }

        }
        fclose($file);

        $this->remainingInsert();
    }
}


