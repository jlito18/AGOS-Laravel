<?php

namespace App\Filament\Widgets;

use App\Models\Reading;
use Filament\Widgets\ChartWidget;

class WaterLevelChart extends ChartWidget
{
    protected static ?string $heading = 'A.G.O.S. Live Readings Trend';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $pollingInterval = '5s';


    protected static ?string $maxHeight = '250px';
    protected function getFilters(): ?array
    {
        return [
            'all' => 'Show All',
            'water_rain' => '🌊 Water Level + 🌧️ Rainfall',
            'water_flow' => '🌊 Water Level + 💧 Water Flow',
            'rain_flow' => '🌧️ Rainfall + 💧 Water Flow',
            'water' => '🌊 Water Level Only',
            'rain' => '🌧️ Rainfall Only',
            'flow' => '💧 Water Flow Only',
        ];
    }

    public ?string $filter = 'all';

    protected function getData(): array
    {
        $readings = \Illuminate\Support\Facades\DB::table('readings')
            ->orderBy('id', 'desc')
            ->take(10)
            ->get()
            ->reverse();

        $labels = [];
        $waterData = [];
        $rainData = [];
        $flowData = [];

        foreach ($readings as $reading) {
            $labels[] = \Carbon\Carbon::parse($reading->created_at)->format('h:i:s A');
            $waterData[] = $reading->water_level_cm;
            $rainData[] = $reading->rainfall_mm;
            $flowData[] = $reading->water_flow_rate;
        }

        $activeFilter = $this->filter ?? 'all';
        $datasets = [];

        // WATER LEVEL LOGIC
        if (in_array($activeFilter, ['all', 'water_rain', 'water_flow', 'water'])) {
            $datasets[] = [
                'label' => 'Water Level (cm)',
                'data' => $waterData,
                'borderColor' => '#3b82f6', // Blue
                'yAxisID' => 'y',
            ];
        }

        // RAINFALL LOGIC
        if (in_array($activeFilter, ['all', 'water_rain', 'rain_flow', 'rain'])) {
            $datasets[] = [
                'label' => 'Rainfall (mm)',
                'data' => $rainData,
                'borderColor' => '#10b981', // Green
                'yAxisID' => 'y1',
            ];
        }

        // WATER FLOW LOGIC
        if (in_array($activeFilter, ['all', 'water_flow', 'rain_flow', 'flow'])) {
            $datasets[] = [
                'label' => 'Water Flow (L/min)',
                'data' => $flowData,
                'borderColor' => '#f59e0b', // Orange
                'yAxisID' => 'y1',
            ];
        }

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    // ITO YUNG PINAGSAMANG FUNCTION PARA SA DUAL Y-AXIS AT HIDDEN LEGEND!
    protected function getOptions(): array
    {
        return [
            // 1. ITO ANG MAGTATAGO SA CLICKABLE LABELS SA ILALIM
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],

            // 2. ITO YUNG DATING CODE MO PARA SA SUKATAN SA GILID
            'scales' => [
                'y' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'left',
                    'title' => [
                        'display' => true,
                        'text' => 'Water Level (cm)'
                    ]
                ],
                'y1' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'right',
                    'title' => [
                        'display' => true,
                        'text' => 'Rainfall & Flow'
                    ],
                    'grid' => [
                        'drawOnChartArea' => false, // Para hindi magulo ang mga linya sa likod
                    ],
                ],
            ],
        ];
    }
}
