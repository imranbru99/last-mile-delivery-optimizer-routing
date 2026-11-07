@php
    $locale = $locale ?? 'en';
    $labels = [
        'en' => [
            'title' => 'Invoice',
            'number' => 'Invoice #',
            'issued' => 'Issued',
            'due' => 'Due',
            'subtotal' => 'Subtotal',
            'tax' => 'Tax',
            'total' => 'Total',
            'notes' => 'Notes',
        ],
        'bn' => [
            'title' => 'ইনভয়েস',
            'number' => 'ইনভয়েস নং',
            'issued' => 'ইস্যু',
            'due' => 'পরিশোধের তারিখ',
            'subtotal' => 'উপমোট',
            'tax' => 'কর',
            'total' => 'মোট',
            'notes' => 'নোট',
        ],
        'ar' => [
            'title' => 'فاتورة',
            'number' => 'رقم الفاتورة',
            'issued' => 'تاريخ الإصدار',
            'due' => 'تاريخ الاستحقاق',
            'subtotal' => 'المجموع الفرعي',
            'tax' => 'الضريبة',
            'total' => 'الإجمالي',
            'notes' => 'ملاحظات',
        ],
    ];
    $t = $labels[$locale] ?? $labels['en'];
    $rtl = in_array($locale, ['ar'], true);
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $rtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <title>{{ $t['title'] }} {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
        h1 { font-size: 20px; margin-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: {{ $rtl ? 'right' : 'left' }}; }
        .meta { margin-top: 12px; }
        .totals { margin-top: 16px; width: 50%; {{ $rtl ? 'margin-right: auto;' : 'margin-left: auto;' }} }
    </style>
</head>
<body>
    <h1>{{ $t['title'] }}</h1>
    <div class="meta">
        <div><strong>{{ $t['number'] }}:</strong> {{ $invoice->invoice_number }}</div>
        @if ($invoice->issued_at)
            <div><strong>{{ $t['issued'] }}:</strong> {{ $invoice->issued_at->format('Y-m-d') }}</div>
        @endif
        @if ($invoice->due_at)
            <div><strong>{{ $t['due'] }}:</strong> {{ $invoice->due_at->format('Y-m-d') }}</div>
        @endif
    </div>

    @if (is_array($invoice->line_items) && count($invoice->line_items))
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->line_items as $line)
                    <tr>
                        <td>{{ $line['description'] ?? 'Item' }}</td>
                        <td>{{ number_format((float) ($line['amount'] ?? 0), 2) }} {{ $invoice->currency }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <table class="totals">
        <tr>
            <td>{{ $t['subtotal'] }}</td>
            <td>{{ number_format((float) $invoice->subtotal, 2) }} {{ $invoice->currency }}</td>
        </tr>
        <tr>
            <td>{{ $t['tax'] }}</td>
            <td>{{ number_format((float) $invoice->tax, 2) }} {{ $invoice->currency }}</td>
        </tr>
        <tr>
            <td><strong>{{ $t['total'] }}</strong></td>
            <td><strong>{{ number_format((float) $invoice->total, 2) }} {{ $invoice->currency }}</strong></td>
        </tr>
    </table>

    @if ($invoice->notes)
        <p class="meta"><strong>{{ $t['notes'] }}:</strong> {{ $invoice->notes }}</p>
    @endif
</body>
</html>
