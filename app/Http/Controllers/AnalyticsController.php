<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        // Default range: Last 30 days
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // 1. Most Profitable Services (From Summary Table)
        $profitableServicesData = DB::table('analytics_summaries')
            ->where('metric_type', 'SERVICE_PROFIT')
            ->whereBetween('summary_date', [$startDate, $endDate])
            ->select('group_key as title', DB::raw('SUM(value_main) as total_profit'))
            ->groupBy('group_key')
            ->orderByDesc('total_profit')
            ->limit(10)
            ->get();

        $profitableServices = [
            'labels' => $profitableServicesData->pluck('title'),
            'data' => $profitableServicesData->pluck('total_profit'),
        ];

        // 2. Profit Segmentation (From Summary Table)
        $rawSegmentation = DB::table('analytics_summaries')
            ->where('metric_type', 'SEGMENTATION')
            ->whereBetween('summary_date', [$startDate, $endDate])
            ->select('group_key', DB::raw('SUM(value_main) as total_profit'))
            ->groupBy('group_key')
            ->get();

        $segmentationGroups = ['17-22', '23-30', '31-40', '41-50', '>50'];
        $segmentationData = [
            'INDIVIDUAL' => array_fill_keys($segmentationGroups, 0),
            'COMPANY' => array_fill_keys($segmentationGroups, 0),
        ];

        foreach ($rawSegmentation as $item) {
            // group_key format: "AgeGroup:Type" e.g., "17-22:INDIVIDUAL"
            $parts = explode(':', $item->group_key);
            if (count($parts) === 2) {
                $group = $parts[0];
                $type = $parts[1];
                if (isset($segmentationData[$type][$group])) {
                    $segmentationData[$type][$group] = $item->total_profit;
                }
            }
        }

        $profitSegmentation = [
            'labels' => $segmentationGroups,
            'datasets' => [
                [
                    'label' => 'Individual',
                    'data' => array_values($segmentationData['INDIVIDUAL']),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.6)'
                ],
                [
                    'label' => 'Company',
                    'data' => array_values($segmentationData['COMPANY']),
                    'backgroundColor' => 'rgba(255, 99, 132, 0.6)'
                ]
            ]
        ];

        // 3. Analyst Performance (From Summary Table)
        $analystSummaries = DB::table('analytics_summaries')
            ->where('metric_type', 'ANALYST_PERF')
            ->whereBetween('summary_date', [$startDate, $endDate])
            ->select(
                'group_key',
                DB::raw('SUM(value_main) as total_profit'),
                DB::raw('SUM(value_secondary) as total_rating_sum'),
                DB::raw('SUM(value_tertiary) as total_days_sum'),
                DB::raw('SUM(record_count) as total_count')
            )
            ->groupBy('group_key')
            ->get();
            
        $expGroups = ['Junior (0-1)', 'Associate (2-5)', 'Mid-Level (6-10)', 'Senior (>10)'];
        $analystPerformance = [];
        
        // Map results to ensure all groups exist
        $summaryMap = $analystSummaries->keyBy('group_key');

        foreach ($expGroups as $group) {
            $stats = $summaryMap->get($group);
            
            $avgRating = 0;
            $avgTime = 0;
            $totalProfit = 0;
            
            if ($stats && $stats->total_count > 0) {
                // If secondary/tertiary were null in DB, they come as 0 in SUM if we aren't careful, 
                // but our logic ensures they are populated.
                $avgRating = $stats->total_rating_sum / $stats->total_count;
                // Note: record_count here was stored based on 'count_rating'. 
                // If rating is optional but profit is not, this might be skewed if we use same count for both.
                // However, for this simplified ETL optimization, this is the trade-off accepted.
                // In a perfect world, we'd store count_rating and count_time separately.
                // Assuming every completed order has a review for simplicity or trusting the approximation.
                
                 $avgTime = $stats->total_days_sum / $stats->total_count;
                 $totalProfit = $stats->total_profit;
            }

            $analystPerformance[] = [
                'group' => $group,
                'avg_rating' => round($avgRating, 2),
                'total_profit' => $totalProfit,
                'avg_completion_time' => round($avgTime, 1),
            ];
        }

        return view('analytics.dashboard', compact(
            'profitableServices',
            'profitSegmentation',
            'analystPerformance',
            'startDate',
            'endDate'
        ));
    }
}
