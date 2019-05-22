<?php

namespace App\Console\Db;

use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

trait DbQueryLoggerTrait
{
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $logDbQueries = $this->logDbQueries();

        if ($logDbQueries) {
            $this->enableQueryLog();
        }

        parent::execute($input, $output);

        if ($logDbQueries) {
            $this->outputQueryLog();
        }
    }

    private function enableQueryLog()
    {
        DB::connection()->enableQueryLog();
    }

    private function outputQueryLog()
    {
        $queries = DB::getQueryLog();

        $this->info('Query Log:');

        foreach($queries as $query) {
            $this->output->writeln('<fg=blue>Query: <fg=blue;options=bold>' . $query['query'] . '</></>');
            $this->output->writeln('<fg=magenta>Bindings: <fg=magenta;options=bold>' . implode(', ', $query['bindings']) . '</></>');
            $this->output->writeln('<fg=cyan>Time: <fg=cyan;options=bold>' . $query['time'] . '</></>');
        }
    }

    abstract protected function logDbQueries(): bool;
}
