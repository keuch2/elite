<?php

namespace App\Services;

use App\Models\Athlete;
use App\Models\Report;
use Illuminate\Support\Facades\DB;

class RankingService
{
    /**
     * Calculate rankings for an athlete compared to their peers
     * Peers are defined as athletes from the same institution, same gender, and similar age group
     *
     * @param Report $report The report to calculate rankings for
     * @param int $ageRangeYears The age range to consider as peers (±years)
     * @return array Rankings for each metric
     */
    public function calculateRankings(Report $report, int $ageRangeYears = 2): array
    {
        // Get the athlete associated with this report
        $athlete = $report->athlete;
        if (!$athlete || !$athlete->athleteProfile) {
            return [];
        }

        // Get the metrics we want to rank
        $metricsToRank = $this->getMetricsToRank($report);
        if (empty($metricsToRank)) {
            return [];
        }

        // Get peers (same institution, gender, and age range)
        $peers = $this->getPeerAthletes($athlete, $ageRangeYears);
        if ($peers->isEmpty()) {
            return [];
        }

        // Get all reports for these peers
        $peerReports = $this->getPeerReports($peers);
        if ($peerReports->isEmpty()) {
            return [];
        }

        // Calculate rankings for each metric
        return $this->calculateRankingsForMetrics($report, $peerReports, $metricsToRank);
    }

    /**
     * Get metrics from the report that should be ranked
     * 
     * @param Report $report
     * @return array
     */
    private function getMetricsToRank(Report $report): array
    {
        $metricsMap = [
            'sit_and_reach' => ['field' => 'sit_and_reach', 'higher_is_better' => true],
            'sentadilla_arranque' => ['field' => 'sentadilla_arranque', 'higher_is_better' => true],
            'fuerza_agarre' => ['field' => 'fuerza_agarre', 'higher_is_better' => true],
            'potencia_piernas' => ['field' => 'potencia_piernas', 'higher_is_better' => true],
            'potencia_brazos' => ['field' => 'potencia_brazos', 'higher_is_better' => true],
            'velocidad_10m' => ['field' => 'velocidad_10m', 'higher_is_better' => false], // Lower is better for speed
            'velocidad_20m' => ['field' => 'velocidad_20m', 'higher_is_better' => false],
            'velocidad_30m' => ['field' => 'velocidad_30m', 'higher_is_better' => false],
            'course_navette' => ['field' => 'course_navette', 'higher_is_better' => true],
            'vo2_max' => ['field' => 'vo2_max', 'higher_is_better' => true],
            'abalakov_altura_cm' => ['field' => 'abalakov_altura_cm', 'higher_is_better' => true],
            'cmj_altura_cm' => ['field' => 'cmj_altura_cm', 'higher_is_better' => true],
            'reaccion_oculo_manual' => ['field' => 'reaccion_oculo_manual', 'higher_is_better' => false], // Lower is better for reaction time
            'reaccion_oculo_podal' => ['field' => 'reaccion_oculo_podal', 'higher_is_better' => false],
        ];

        $validMetrics = [];
        foreach ($metricsMap as $key => $config) {
            if (isset($report->report_data[$key]) && is_numeric($report->report_data[$key])) {
                $validMetrics[$key] = $config;
            }
        }

        return $validMetrics;
    }

    /**
     * Get peer athletes based on institution, gender, and age range
     * 
     * @param Athlete $athlete
     * @param int $ageRangeYears
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getPeerAthletes(Athlete $athlete, int $ageRangeYears): \Illuminate\Database\Eloquent\Collection
    {
        $profile = $athlete->athleteProfile;
        if (!$profile) {
            return collect();
        }

        // Calculate min and max age
        $minAge = max(0, $athlete->age - $ageRangeYears);
        $maxAge = $athlete->age + $ageRangeYears;

        return Athlete::with('athleteProfile')
            ->where('institution_id', $athlete->institution_id)
            ->where('id', '!=', $athlete->id) // Exclude the current athlete
            ->whereHas('athleteProfile', function($query) use ($profile) {
                $query->where('gender', $profile->gender);
            })
            ->whereBetween('age', [$minAge, $maxAge])
            ->get();
    }

    /**
     * Get all reports for a collection of peer athletes
     * 
     * @param \Illuminate\Database\Eloquent\Collection $peers
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getPeerReports(\Illuminate\Database\Eloquent\Collection $peers): \Illuminate\Database\Eloquent\Collection
    {
        $peerIds = $peers->pluck('id')->toArray();
        
        return Report::whereIn('athlete_id', $peerIds)
            ->get();
    }

    /**
     * Calculate rankings for each metric
     * 
     * @param Report $report
     * @param \Illuminate\Database\Eloquent\Collection $peerReports
     * @param array $metricsToRank
     * @return array
     */
    private function calculateRankingsForMetrics(Report $report, \Illuminate\Database\Eloquent\Collection $peerReports, array $metricsToRank): array
    {
        $rankings = [];
        $totalPeers = $peerReports->count() + 1; // Include current athlete

        foreach ($metricsToRank as $metricKey => $config) {
            $currentValue = $report->report_data[$metricKey] ?? null;
            
            if ($currentValue === null || !is_numeric($currentValue)) {
                continue;
            }

            // Count how many peer athletes have better performance
            $betterPerformances = 0;
            
            foreach ($peerReports as $peerReport) {
                $peerValue = $peerReport->report_data[$metricKey] ?? null;
                
                if ($peerValue === null || !is_numeric($peerValue)) {
                    continue;
                }
                
                // Determine if peer is better based on whether higher or lower values are better
                $peerIsBetter = $config['higher_is_better'] 
                    ? $peerValue > $currentValue 
                    : $peerValue < $currentValue;
                
                if ($peerIsBetter) {
                    $betterPerformances++;
                }
            }
            
            // Calculate rank (1-based)
            $rank = $betterPerformances + 1;
            
            // Store ranking data
            $rankings[$metricKey] = [
                'rank' => $rank,
                'total' => $totalPeers,
                'percentile' => round(100 * (($totalPeers - $rank + 1) / $totalPeers), 1)
            ];
        }

        return $rankings;
    }
}
