<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Plan::create([
            'slug' => 'monthly',
            'price' => 1200,
            'duration_in_days' => 30,
        ]);

        Plan::create([
            'slug' => 'yearly',
            'price' => 9999,
            'duration_in_days' => 365,
        ]);
    }
}
