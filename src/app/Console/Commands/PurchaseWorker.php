<?php

namespace App\Console\Commands;

use App\Jobs\SubscriptionCheckJob;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PurchaseWorker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:purchaseWorker';

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
     * @return int
     */
    public function handle()
    {

        $subscriptions = Subscription::where('status', 1)
            ->where('expireDate', '<', Carbon::now())
            ->get();
        foreach ($subscriptions as $subscription) {
           SubscriptionCheckJob::dispatch((new SubscriptionCheckJob($subscription)));
           $this->info('Cron:purchaseWorker Command Run!');
        }

    }
}
