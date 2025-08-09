<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\HistoricalTransactionSeeder;

class GenerateHistoricalData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:generate-historical 
                            {--truncate : Truncate existing transactions before generating new ones}
                            {--years=5 : Number of years to generate data for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate historical transaction data for testing charts with different time periods';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $years = $this->option('years');
        $truncate = $this->option('truncate');

        if ($truncate) {
            if ($this->confirm('This will delete ALL existing transactions. Are you sure?')) {
                $this->info('Truncating transactions and goods_transaction tables...');
                \DB::table('goods_transaction')->delete();
                \DB::table('transactions')->delete();
                $this->info('Existing data cleared.');
            } else {
                $this->info('Operation cancelled.');
                return;
            }
        }

        $this->info("Generating historical data for {$years} years...");
        
        $seeder = new HistoricalTransactionSeeder();
        $seeder->setCommand($this);
        $seeder->run();

        $this->info('Historical data generation completed!');
        $this->info('You can now test the chart with different time periods.');
        
        // Display some statistics
        $totalTransactions = \App\Models\Transaction::count();
        $totalGoodsTransactions = \App\Models\GoodsTransaction::count();
        $earliestDate = \App\Models\Transaction::min('created_at');
        $latestDate = \App\Models\Transaction::max('created_at');
        
        $this->info("\nStatistics:");
        $this->info("- Total Transactions: {$totalTransactions}");
        $this->info("- Total Goods Transactions: {$totalGoodsTransactions}");
        $this->info("- Date Range: {$earliestDate} to {$latestDate}");
    }
}
