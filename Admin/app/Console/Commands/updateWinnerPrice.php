<?php

namespace App\Console\Commands;
use App\Http\Controllers\ApiCronController;
use Illuminate\Console\Command;
use App\Models\CryptoTransactions;
  
class updateWinnerPrice extends Command
{
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:updateWinnerPrice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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
       $updatematch->updateWinnerPrice();
       $this->info('Update Winner Price');
    }
}
