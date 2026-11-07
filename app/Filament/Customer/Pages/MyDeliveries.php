<?php

declare(strict_types=1);

namespace App\Filament\Customer\Pages;

use App\Filament\Concerns\ModuleAware;
use App\Models\Shipment;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class MyDeliveries extends Page implements HasTable
{
    use InteractsWithTable;
    use ModuleAware;

    public static string $module = 'customer_tracking';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationLabel = 'My Deliveries';

    protected static ?string $title = 'My Deliveries';

    protected static ?int $navigationSort = 10;

    protected string $view = 'filament.customer.pages.my-deliveries';

    public function table(Table $table): Table
    {
        $user = Auth::user();

        return $table
            ->query(
                Shipment::query()
                    ->with('deliveryAddress')
                    ->when(
                        $user?->phone,
                        fn ($q) => $q->whereHas(
                            'deliveryAddress',
                            fn ($aq) => $aq->where('phone', $user->phone)
                        )
                    )
                    ->when(
                        ! $user?->phone && $user?->email,
                        fn ($q) => $q->where('notes', 'like', '%'.$user->email.'%')
                    )
                    ->when(
                        ! $user?->phone && ! $user?->email,
                        fn ($q) => $q->whereRaw('1 = 0')
                    )
            )
            ->columns([
                TextColumn::make('tracking_code')->searchable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('deliveryAddress.line1')->label('Address')->limit(40),
                TextColumn::make('time_window_end')->dateTime()->label('Window end'),
                TextColumn::make('updated_at')->since(),
            ])
            ->paginated([10, 25]);
    }
}
