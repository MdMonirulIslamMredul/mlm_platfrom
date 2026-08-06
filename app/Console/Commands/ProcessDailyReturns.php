<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\DailyReturnService;
use Illuminate\Console\Command;

class ProcessDailyReturns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'investments:process-daily-returns';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process daily returns for active investment packages and credit user wallets';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting daily investment return processing...');

        $usersWithActiveInvestments = User::whereHas('investments', function ($query) {
            $query->where('status', 'active');
        })->get();

        $totalProcessed = 0;

        foreach ($usersWithActiveInvestments as $user) {
            $totalProcessed += DailyReturnService::processUserReturns($user);
        }

        $this->info("Successfully processed {$totalProcessed} daily return payouts across " . $usersWithActiveInvestments->count() . " active users.");
        return Command::SUCCESS;
    }
}
