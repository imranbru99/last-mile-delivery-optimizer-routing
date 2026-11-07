<?php

declare(strict_types=1);

namespace App\Filament\Franchise\Pages;

use App\Filament\Concerns\ModuleAware;
use App\Models\Invoice;
use App\Models\Shipment;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class CommissionOverview extends Page
{
    use ModuleAware;

    public static string $module = 'franchise';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Commissions';

    protected static ?string $title = 'Franchise Commission Overview';

    protected static ?int $navigationSort = 10;

    protected string $view = 'filament.franchise.pages.commission-overview';

    public float $deliveredCount = 0;

    public float $codCollected = 0;

    public float $estimatedCommission = 0;

    public function mount(): void
    {
        $businessId = Auth::user()?->business_id;

        $this->deliveredCount = (float) Shipment::query()
            ->where('business_id', $businessId)
            ->where('status', 'delivered')
            ->count();

        $this->codCollected = (float) Shipment::query()
            ->where('business_id', $businessId)
            ->where('status', 'delivered')
            ->sum('cod_amount');

        $invoiceTotal = (float) Invoice::query()
            ->where('business_id', $businessId)
            ->where('status', 'paid')
            ->sum('total');

        $rate = (float) (Auth::user()?->business?->settings['franchise_commission_rate'] ?? 0.08);
        $this->estimatedCommission = round(($invoiceTotal + $this->codCollected) * $rate, 2);
    }
}
