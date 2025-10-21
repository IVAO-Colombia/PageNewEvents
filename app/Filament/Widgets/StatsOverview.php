<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\News;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Support\Icons\Heroicon;
use App\Models\User;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Registered Users', $this->countUserRegister())
                ->description('Users registered')
                ->descriptionIcon(Heroicon::UserCircle)
                ->color('success'),
            Stat::make('Registered Events', $this->countEventRegister())
                ->description('News Events registered')
                ->descriptionIcon(Heroicon::Calendar)
                ->color('success'),
            Stat::make('Active News', $this->getNewsActive())
                ->description('News Events active')
                ->descriptionIcon(Heroicon::CheckCircle)
                ->color('success'),
        ];
    }

    public function countUserRegister()
    {
        return User::count();
    }

    public function countEventRegister()
    {
        return Event::count();
    }

    public function getNewsActive()
    {
        return News::where('is_active', true)->count();
    }
}
