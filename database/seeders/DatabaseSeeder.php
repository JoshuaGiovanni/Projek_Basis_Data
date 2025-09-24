<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\AnalystProfile;
use App\Models\ClientProfile;
use App\Models\Service;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create some clients
        $clients = User::factory()->count(3)->state(['role' => 'CLIENT'])->create();
        foreach ($clients as $client) {
            ClientProfile::create([
                'client_id' => $client->user_id,
                'type' => fake()->randomElement(['INDIVIDUAL','COMPANY']),
                'company_name' => fake()->optional()->company(),
                'industry' => fake()->optional()->randomElement(['Finance','Tech','Healthcare','Retail']),
            ]);
        }

        // Create many analysts with profiles and services
        $analysts = User::factory()->count(8)->state(['role' => 'ANALYST'])->create();
        foreach ($analysts as $analystUser) {
            $profile = AnalystProfile::create([
                'user_id' => $analystUser->user_id,
                'full_name' => $analystUser->name,
                'years_of_experience' => fake()->numberBetween(1, 12),
                'description' => fake()->sentence(12),
                'status' => 'available',
                'skills' => [fake()->randomElement(['SQL','Python','Excel','PowerBI','R','Looker']) , fake()->randomElement(['Forecasting','ETL','Data Cleaning','Dashboarding'])],
                'average_rating' => fake()->randomFloat(2, 3.5, 5.0),
            ]);

            // 2-3 services per analyst
            $serviceCount = fake()->numberBetween(2, 3);
            for ($i = 0; $i < $serviceCount; $i++) {
                $min = fake()->numberBetween(50, 200);
                $max = $min + fake()->numberBetween(50, 400);
                Service::create([
                    'analyst_id' => $profile->analyst_id,
                    'title' => fake()->randomElement(['Data Analysis','Dashboard Creation','Forecasting Model','SQL Data Cleanse','ETL Pipeline Setup']),
                    'description' => fake()->paragraph(),
                    'price_min' => $min,
                    'price_max' => $max,
                    'category' => fake()->randomElement(['Analytics','BI','ETL','ML','Reporting']),
                ]);
            }
        }
    }
}
