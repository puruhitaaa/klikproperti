<?php

namespace App\Filament\Widgets;

use App\Models\Property;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Voucher;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Properti', Property::count())
                ->description('Jumlah properti yang terdaftar')
                ->descriptionIcon('heroicon-m-home')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5]),

            Stat::make('Total Transaksi', Transaction::count())
                ->description('Jumlah transaksi keseluruhan')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning')
                ->chart([4, 5, 3, 7, 4, 5, 3]),

            Stat::make('Total Pengguna', User::count())
                ->description('Jumlah pengguna terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('info')
                ->chart([3, 5, 4, 3, 6, 3, 5]),

            Stat::make('Voucher Aktif', Voucher::where('is_active', true)->count())
                ->description('Jumlah voucher yang aktif')
                ->descriptionIcon('heroicon-m-ticket')
                ->color('success'),
        ];
    }
}
