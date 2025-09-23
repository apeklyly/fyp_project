<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearHealthData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-health-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears all health records and messages from the database.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->confirm('Are you sure you want to delete ALL health records and messages? This cannot be undone.')) {
            $this->info('Clearing health data...');

            // Disables foreign key checks to avoid errors
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            DB::table('health_records')->truncate();
            DB::table('messages')->truncate();

            // Re-enables foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $this->info('Health records and messages have been cleared successfully!');
        } else {
            $this->info('Operation cancelled.');
        }
    }
}