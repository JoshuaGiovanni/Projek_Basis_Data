<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UpdateAnalytics extends Command
{
    protected $signature = 'analytics:update {date? : The date to calculate analytics for (YYYY-MM-DD)}';
    protected $description = 'Calculate and store daily analytics summaries';

    public function handle()
    {
        $date = $this->argument('date') ? Carbon::parse($this->argument('date')) : Carbon::yesterday();
        $dateStr = $date->toDateString();

        $this->info("Updating analytics for date: {$dateStr}");

        DB::beginTransaction();
        try {
            DB::table('analytics_summaries')->where('summary_date', $dateStr)->delete();

            // 1. Service Profit (Daily)
            $serviceProfits = DB::table('orders')
                ->join('services', 'orders.service_id', '=', 'services.service_id')
                ->whereDate('orders.order_date', $dateStr)
                ->whereIn('orders.status', ['COMPLETED', 'SUBMITTED'])
                ->select('services.title', DB::raw('SUM(orders.final_amount) as total_profit'))
                ->groupBy('services.title')
                ->get();

            foreach ($serviceProfits as $sp) {
                DB::table('analytics_summaries')->insert([
                    'summary_date' => $dateStr,
                    'metric_type' => 'SERVICE_PROFIT',
                    'group_key' => (string) $sp->title,
                    'value_main' => (float) $sp->total_profit,
                    'record_count' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // 2. Segmentation (Daily)
            $segmentation = DB::table('orders')
                ->join('client_profile', 'orders.client_id', '=', 'client_profile.client_id')
                ->join('users', 'client_profile.client_id', '=', 'users.user_id')
                ->whereDate('orders.order_date', $dateStr)
                ->whereIn('orders.status', ['COMPLETED', 'SUBMITTED'])
                ->select(
                    'client_profile.type',
                    'users.birthdate',
                    'orders.final_amount as total_profit'
                )
                ->get();

            $segGroups = [];
            foreach ($segmentation as $item) {
                // $item->birthdate is available, calculate age in PHP
                $age = Carbon::parse($item->birthdate)->age;
                if ($age >= 17 && $age <= 22) $group = '17-22';
                elseif ($age >= 23 && $age <= 30) $group = '23-30';
                elseif ($age >= 31 && $age <= 40) $group = '31-40';
                elseif ($age >= 41 && $age <= 50) $group = '41-50';
                elseif ($age > 50) $group = '>50';
                else $group = 'Unknown';

                $key = "{$group}:{$item->type}";
                if (!isset($segGroups[$key])) $segGroups[$key] = 0;
                $segGroups[$key] += $item->total_profit;
            }

            foreach ($segGroups as $key => $profit) {
                DB::table('analytics_summaries')->insert([
                    'summary_date' => $dateStr,
                    'metric_type' => 'SEGMENTATION',
                    'group_key' => (string) $key,
                    'value_main' => (float) $profit,
                    'record_count' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // 3. Analyst Performance
            $analystPerf = DB::table('orders')
                ->join('services', 'orders.service_id', '=', 'services.service_id')
                ->join('analyst_profile', 'services.analyst_id', '=', 'analyst_profile.analyst_id')
                ->leftJoin('reviews', 'orders.order_id', '=', 'reviews.order_id')
                ->whereDate('orders.order_date', $dateStr)
                ->whereIn('orders.status', ['COMPLETED', 'SUBMITTED'])
                ->select(
                    'analyst_profile.years_of_experience',
                    'reviews.rating',
                    'orders.final_amount as profit',
                    'orders.order_date',
                    'reviews.created_at as review_date'
                )
                ->get();

            $perfStats = [];
            foreach ($analystPerf as $item) {
                $exp = $item->years_of_experience;
                if ($exp <= 1) $group = 'Junior (0-1)';
                elseif ($exp <= 5) $group = 'Associate (2-5)';
                elseif ($exp <= 10) $group = 'Mid-Level (6-10)';
                else $group = 'Senior (>10)';

                if (!isset($perfStats[$group])) {
                    $perfStats[$group] = [
                        'profit' => 0,
                        'total_rating' => 0,
                        'count_rating' => 0,
                        'total_days' => 0,
                        'count_days' => 0
                    ];
                }

                $perfStats[$group]['profit'] += $item->profit;

                if ($item->rating) {
                    $perfStats[$group]['total_rating'] += $item->rating;
                    $perfStats[$group]['count_rating']++;
                }

                if ($item->order_date && $item->review_date) {
                    $days = Carbon::parse($item->order_date)->diffInDays(Carbon::parse($item->review_date));
                    $perfStats[$group]['total_days'] += $days;
                    $perfStats[$group]['count_days']++;
                }
            }

            foreach ($perfStats as $group => $stats) {
                // dd($group, $stats); // Uncomment to debug
                DB::table('analytics_summaries')->insert([
                    'summary_date' => $dateStr,
                    'metric_type' => 'ANALYST_PERF',
                    'group_key' => (string) $group,
                    'value_main' => (float) $stats['profit'],
                    'value_secondary' => (float) $stats['total_rating'],
                    'value_tertiary' => (float) $stats['total_days'],
                    'record_count' => (int) $stats['count_rating'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();
            $this->info("Analytics updated successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error updating analytics: " . $e->getMessage());
        }
    }
}
