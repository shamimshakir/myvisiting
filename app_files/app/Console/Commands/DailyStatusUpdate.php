<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use DateTime;

class DailyStatusUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expired:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily Meeting Status Check and update to expired';

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
     * @return int
     */
    public function handle()
    {
        $dt = new DateTime();
        $today = $dt->format('Y-m-d');
    
        $res = DB::table('meetings')->where('meeting_date', $today)->where('meeting_status', 'new')->update(['meeting_status' => 'expired']);

        if($res){
            echo "Today's [".$today."] expired status updated";
        }
    }
}
