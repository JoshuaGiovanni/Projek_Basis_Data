<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\AnalystProfile;
use App\Models\ClientProfile;
use App\Models\Service;
use App\Models\Order;
use App\Models\OrderBrief;
use App\Models\Deliverable;
use App\Models\Payment;
use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user for testing
        User::factory()->create([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'role' => 'ADMIN',
            'birthdate' => fake()->dateTimeBetween('-60 years', '-25 years')->format('Y-m-d')
        ]);

        // Create some clients
        $clients = User::factory()
            ->count(5)
            ->state(['role' => 'CLIENT'])
            ->create()
            ->each(function ($client) {
                $client->update([
                    'birthdate' => fake()->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d')
                ]);
            });

        $clientProfiles = [];
        foreach ($clients as $client) {
            $clientProfile = ClientProfile::create([
                'client_id' => $client->user_id,
                'type' => fake()->randomElement(['INDIVIDUAL','COMPANY']),
                'company_name' => fake()->optional()->company(),
                'industry' => fake()->optional()->randomElement(['Finance','Tech','Healthcare','Retail']),
            ]);
            $clientProfiles[] = $clientProfile;
        }

        // Create many analysts with profiles and services
        $analysts = User::factory()
            ->count(8)
            ->state(['role' => 'ANALYST'])
            ->create()
            ->each(function ($analyst) {
                $analyst->update([
                    'birthdate' => fake()->dateTimeBetween('-60 years', '-22 years')->format('Y-m-d')
                ]);
            });

        $analystProfiles = [];
        $services = [];
        
        foreach ($analysts as $analystUser) {
            // Create some analysts with different ongoing order counts and limits to test the system
            $ongoingOrdersCount = fake()->numberBetween(0, 6);
            $limit = fake()->numberBetween(3, 8);
            $status = $ongoingOrdersCount >= $limit ? 'unavailable' : 'available';

            $profile = AnalystProfile::create([
                'user_id' => $analystUser->user_id,
                'full_name' => $analystUser->username,
                'years_of_experience' => fake()->numberBetween(1, 12),
                'description' => fake()->sentence(12),
                'status' => $status,
                'max_ongoing_orders' => $limit,
                'skills' => [
                    fake()->randomElement(['SQL','Python','Excel','PowerBI','R','Looker']),
                    fake()->randomElement(['Forecasting','ETL','Data Cleaning','Dashboarding'])
                ],
                'average_rating' => fake()->randomFloat(2, 3.5, 5.0),
            ]);
            $analystProfiles[] = $profile;

            // 2-3 services per analyst
            $serviceCount = fake()->numberBetween(2, 3);
            for ($i = 0; $i < $serviceCount; $i++) {
                $min = fake()->numberBetween(50, 200);
                $max = $min + fake()->numberBetween(50, 400);
                $service = Service::create([
                    'analyst_id' => $profile->analyst_id,
                    'title' => fake()->randomElement([
                        'Data Analysis & Insights',
                        'Interactive Dashboard Creation',
                        'Predictive Forecasting Model',
                        'SQL Database Optimization',
                        'ETL Pipeline Development',
                        'Business Intelligence Setup',
                        'Data Visualization',
                        'Statistical Analysis',
                        'Machine Learning Model',
                        'Data Mining & Processing'
                    ]),
                    'description' => fake()->paragraph(),
                    'price_min' => $min,
                    'price_max' => $max,
                    'category' => fake()->randomElement([
                        'BI & Visualization',
                        'Statistical Analysis',
                        'ML/AI',
                        'Data Engineering',
                        'Consultation'
                    ]),
                ]);
                $services[] = $service;
            }
        }

        // Create sample orders with different statuses
        $orderStatuses = ['PENDING', 'IN_PROGRESS', 'SUBMITTED', 'COMPLETED', 'CANCELLED'];
        
        foreach ($services as $service) {
            // Create 1-3 orders per service
            $orderCount = fake()->numberBetween(0, 3);
            
            for ($i = 0; $i < $orderCount; $i++) {
                $clientProfile = fake()->randomElement($clientProfiles);
                $status = fake()->randomElement($orderStatuses);
                
                // Adjust probabilities to have more realistic distribution
                if (fake()->boolean(30)) $status = 'PENDING';
                elseif (fake()->boolean(40)) $status = 'IN_PROGRESS';
                elseif (fake()->boolean(20)) $status = 'SUBMITTED';
                elseif (fake()->boolean(70)) $status = 'COMPLETED';
                else $status = 'CANCELLED';
                
                $order = Order::create([
                    'service_id' => $service->service_id,
                    'client_id' => $clientProfile->client_id,
                    'order_date' => fake()->dateTimeBetween('-3 months', 'now'),
                    'due_date' => fake()->optional()->dateTimeBetween('now', '+2 months'),
                    'final_amount' => fake()->randomFloat(2, $service->price_min, $service->price_max),
                    'status' => $status,
                ]);

                // Create order brief for IN_PROGRESS, SUBMITTED, and COMPLETED orders
                if (in_array($status, ['IN_PROGRESS', 'SUBMITTED', 'COMPLETED'])) {
                    OrderBrief::create([
                        'order_id' => $order->order_id,
                        'project_description' => fake()->paragraphs(3, true),
                        'attachments_url' => fake()->optional()->url(),
                        'submitted_at' => fake()->dateTimeBetween($order->order_date, 'now'),
                    ]);
                }

                // Create deliverable for SUBMITTED and COMPLETED orders
                if (in_array($status, ['SUBMITTED', 'COMPLETED'])) {
                    Deliverable::create([
                        'order_id' => $order->order_id,
                        'submission_link' => fake()->randomElement([
                            'https://drive.google.com/file/d/1a2B3c4D5e6F7g8H9i0J/view',
                            'https://www.dropbox.com/s/abc123def456/project_' . $order->order_id . '.zip',
                            'https://onedrive.live.com/redir?resid=123&authkey=xyz&ithint=file%2czip',
                            'https://drive.google.com/drive/folders/1aBcDeFgHiJkLmNoPqRsTuVwXyZ'
                        ]),
                        'submission_note' => fake()->optional(0.7)->sentence(10),
                        'submitted_at' => fake()->dateTimeBetween($order->order_date, 'now'),
                        'approved_by_admin' => $status === 'COMPLETED',
                    ]);
                }

                // Create payment for COMPLETED orders and some SUBMITTED orders with pending payments
                if ($status === 'COMPLETED') {
                    Payment::create([
                        'order_id' => $order->order_id,
                        'amount' => $order->final_amount,
                        'payment_date' => fake()->dateTimeBetween($order->order_date, 'now'),
                        'payment_method' => 'QR_CODE',
                        'proof_image' => null, // Will be null in seeded data, real images only from actual uploads
                        'status' => 'COMPLETED',
                    ]);
                } elseif ($status === 'SUBMITTED' && fake()->boolean(70)) {
                    // Create pending payments for some submitted orders (without proof images)
                    Payment::create([
                        'order_id' => $order->order_id,
                        'amount' => $order->final_amount,
                        'payment_date' => fake()->dateTimeBetween($order->order_date, 'now'),
                        'payment_method' => 'QR_CODE',
                        'proof_image' => null, // Will be null in seeded data, real images only from actual uploads
                        'status' => fake()->randomElement(['PENDING', 'FAILED']),
                    ]);
                }

                // Create review for some completed orders
                if ($status === 'COMPLETED' && fake()->boolean(70)) {
                    Review::create([
                        'order_id' => $order->order_id,
                        'reviewer_id' => $clientProfile->client_id,
                        'analyst_id' => $service->analyst_id,
                        'rating' => fake()->numberBetween(3, 5),
                        'comment' => fake()->optional()->paragraph(),
                        'created_at' => fake()->dateTimeBetween($order->order_date, 'now'),
                    ]);
                }
            }
        }

        // Update analyst ratings based on their reviews
        foreach ($analystProfiles as $profile) {
            $reviews = Review::where('analyst_id', $profile->analyst_id)->get();
            if ($reviews->count() > 0) {
                $averageRating = $reviews->avg('rating');
                $profile->update(['average_rating' => round($averageRating, 2)]);
            }
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Created:');
        $this->command->info('- ' . count($clients) . ' clients');
        $this->command->info('- ' . count($analysts) . ' analysts');
        $this->command->info('- ' . count($services) . ' services');
        $this->command->info('- ' . Order::count() . ' orders');
        $this->command->info('- ' . OrderBrief::count() . ' order briefs');
        $this->command->info('- ' . Deliverable::count() . ' deliverables');
        $this->command->info('- ' . Payment::count() . ' payments');
        $this->command->info('- ' . Review::count() . ' reviews');
    }
}
