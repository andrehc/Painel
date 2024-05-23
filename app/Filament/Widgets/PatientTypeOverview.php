<?php

namespace App\Filament\Widgets;

use App\Models\Patient;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PatientTypeOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Cat', Patient::query()->where('type', 'cat')->count()),
            Stat::make('Dog', Patient::query()->where('type', 'dog')->count()),
            Stat::make('Rabbit', Patient::query()->where('type', 'rabbit')->count()),
            Stat::make('Bird', Patient::query()->where('type', 'bird')->count()),
            Stat::make('Fish', Patient::query()->where('type', 'fish')->count()),
        ];
    }
}
