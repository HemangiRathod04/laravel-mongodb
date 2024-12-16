<?php

namespace App\Console\Commands;

use App\Models\Country;
use App\Models\User;
use Illuminate\Console\Command;

class MeasureDataLoadingTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'measure:query-time';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Measuring time to load users...');
        $userCount = User::count();
        $this->info('Measuring time to load users in chunks...');
       
        $startUser = microtime(true);
       try {
           User::chunk(10000, function ($users) {
               foreach ($users as $user) {
               }
           });
           } catch (\Exception $e) {
               $this->error('Error loading users: ' . $e->getMessage());
               return 1;
           }
        $userDiff = microtime(true) - $startUser;

        // Measure time to get countries
        $this->info('Measuring time to load countries...');
        $startCountry = microtime(true);
        $countries = Country::all();
        $countryDiff = microtime(true) - $startCountry;

        // Output the results
        $this->info('Number of users: ' . $userCount);
        $this->info('Time to load users: ' . $userDiff . ' seconds');
        $this->info('Time to load countries: ' . $countryDiff . ' seconds');
        
        return 0;
    }
}
