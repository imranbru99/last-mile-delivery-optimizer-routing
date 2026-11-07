<?php

declare(strict_types=1);

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\Finance\InvoicePdfService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class InvoicePdfDownloadController extends Controller
{
    public function __invoke(Invoice $invoice, string $locale, InvoicePdfService $pdfs): BinaryFileResponse
    {
        abort_unless(in_array($locale, ['en', 'bn', 'ar'], true), 404);

        $path = $pdfs->generate($invoice, $locale);

        return response()->download($path, basename($path));
    }
}
