<?php

namespace App\Console\Commands;

use App\Models\Country;
use Illuminate\Console\Command;

class SampleMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sample:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sample command';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $result = Country::count();

        echo $result;
        // return $result;
    }
}
