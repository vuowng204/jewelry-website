<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\User; 
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
         return [
            Stat::make('Sản phẩm', Product::count())
                ->description('Tổng số sản phẩm')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('success'),

            Stat::make('Danh mục', \App\Models\Category::count())
                ->color('info'),

            Stat::make('Người dùng', User::count())
                ->color('warning'),

            Stat::make('SP nổi bật', Product::where('is_featured', true)->count())
                ->color('danger'),
        ];
    }
}
