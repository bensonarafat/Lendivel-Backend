<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\AppointmentPayment;

class ExpireOldPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark payments older than 1 month as expired if not successful';


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
        $oneMonthAgo = Carbon::now()->subMonth();

        $expired = AppointmentPayment::where('payment_status', 'success')
            ->where('created_at', '<', $oneMonthAgo)
            ->update(['payment_status' => 'expired']);

        $this->info("Expired {$expired} payments.");
    }
}
