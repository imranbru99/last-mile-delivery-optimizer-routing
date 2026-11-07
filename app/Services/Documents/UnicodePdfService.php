<?php

declare(strict_types=1);

namespace App\Services\Documents;

use ImranDev\UnicodePdf\Facades\UnicodePdf;
use Throwable;

/**
 * Wrapper around imrandevbd/laravel-unicode-pdf for invoices, waybills, POD, payslips.
 */
final class UnicodePdfService
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function renderView(string $view, array $data = [], string $filename = 'document.pdf', string $locale = 'en'): string
    {
        $dir = storage_path('app/pdf');
        if (! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $path = $dir.'/'.$filename;

        try {
            $document = UnicodePdf::view($view, $data)->locale($locale);

            if (in_array($locale, ['ar', 'ur', 'he', 'fa'], true)) {
                $document = $document->direction('rtl')->bidi();
            }

            if (method_exists($document, 'save')) {
                $document->save($path);
            } elseif (method_exists($document, 'output')) {
                file_put_contents($path, $document->output());
            } else {
                file_put_contents($path, (string) $document);
            }

            return $path;
        } catch (Throwable $e) {
            $htmlPath = $path.'.html';
            file_put_contents($htmlPath, "<!-- PDF fallback: {$e->getMessage()} -->\n".view($view, $data)->render());

            return $htmlPath;
        }
    }
}
