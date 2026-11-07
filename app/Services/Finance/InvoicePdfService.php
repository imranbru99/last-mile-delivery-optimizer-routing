<?php

declare(strict_types=1);

namespace App\Services\Finance;

use App\Models\Invoice;
use App\Services\Documents\UnicodePdfService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class InvoicePdfService
{
    public function __construct(private readonly UnicodePdfService $pdf) {}

    public function generate(Invoice $invoice, string $locale = 'en'): string
    {
        $invoice->loadMissing(['business', 'shipment', 'merchantUser']);

        $filename = 'invoice-'.$invoice->invoice_number.'-'.$locale.'.pdf';

        $path = $this->pdf->renderView('pdf.invoice', [
            'invoice' => $invoice,
            'locale' => $locale,
        ], $filename, $locale);

        $invoice->forceFill(['pdf_path' => $path])->save();

        return $path;
    }

    public function download(Invoice $invoice, string $locale = 'en'): BinaryFileResponse
    {
        $path = $this->generate($invoice, $locale);

        return response()->download($path, basename($path));
    }
}
