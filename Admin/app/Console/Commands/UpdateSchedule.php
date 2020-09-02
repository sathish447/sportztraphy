<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ApiCronController;

class UpdateSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:updateSchedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Match schedules will be updated daily.';

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
        $updatematch = new ApiCronController;
        $updatematch->matchShedule();
        $this->info('Schedule updated.');
    }
}
