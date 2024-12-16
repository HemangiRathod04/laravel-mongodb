<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::factory()->count(100000)->create();
        // echo "Total User now is - " . User::count() . "\n";
        $totalUsers = 700000;
        $batchSize = 10000; 
        for ($i = 0; $i < $totalUsers / $batchSize; $i++) {
            $users = User::factory()->count($batchSize)->make()->toArray();

            DB::table('users')->insert($users);
            echo "Inserted batch " . ($i + 1) . "\n";
        }

        echo "Total User now is - " . User::count() . "\n";
    }
}

