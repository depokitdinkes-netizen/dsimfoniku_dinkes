@php
use Carbon\Carbon;

$today = Carbon::now();
$issuedDate = $slhsIssuedDate ? Carbon::parse($slhsIssuedDate) : null;
$expireDate = null;

// Hitung expire date berdasarkan issued date + 3 tahun
if ($issuedDate) {
    $expireDate = $issuedDate->copy()->addYears(3);
} elseif ($slhsExpireDate) {
    $expireDate = Carbon::parse($slhsExpireDate);
}

if (!$expireDate || !$issuedDate) {
    $status = 'no-data';
    $borderColor = 'border-gray-400';
    $textColor = 'text-gray-400';
    $text = 'Tidak ada data';
    $icon = 'ri-question-line';
} else {
    $diffInDays = $today->diffInDays($expireDate, false); // false = bisa negative
    
    if ($diffInDays < 0) {
        // Sudah expired - MERAH
        $status = 'expired';
        $borderColor = 'border-error';
        $textColor = 'text-error';
        $text = 'Expired ' . abs($diffInDays) . ' hari';
        $icon = 'ri-close-circle-line';
    } elseif ($diffInDays <= 180) {
        // 6 bulan ke bawah - MERAH
        $status = 'critical';
        $borderColor = 'border-error';
        $textColor = 'text-error';
        if ($diffInDays <= 30) {
            $text = (int)$diffInDays . ' hari lagi';
        } else {
            $text = (int)($diffInDays / 30) . ' bulan lagi';
        }
        $icon = 'ri-alarm-warning-line';
    } elseif ($diffInDays < 365) {
        // 6 bulan sampai kurang dari 1 tahun - KUNING
        $status = 'warning';
        $borderColor = 'border-warning';
        $textColor = 'text-warning';
        $text = (int)($diffInDays / 30) . ' bulan lagi';
        $icon = 'ri-error-warning-line';
    } else {
        // 1 tahun dan di atasnya - HIJAU
        $status = 'good';
        $borderColor = 'border-success';
        $textColor = 'text-success';
        $text = (int)($diffInDays / 365) . ' tahun lagi';
        $icon = 'ri-shield-check-line';
    }
}
@endphp

<div class="flex flex-col items-start">
    <div class="{{ $borderColor }} border {{ $textColor }} font-medium px-3 py-1.5 rounded-full text-xs text-center flex items-center gap-1">
        <i class="{{ $icon }} text-sm"></i>
        {{ $text }}
    </div>
    
    @if($expireDate)
    <div class="text-xs text-gray-500 mt-1 ml-1">
        Exp: {{ $expireDate->format('d/m/Y') }}
    </div>
    @endif
</div>

@if($showTooltip ?? false)
<div class="tooltip tooltip-top" data-tip="
@if($expireDate)
SLHS {{ $status === 'expired' ? 'sudah expired' : 'akan expired' }} pada {{ $expireDate->format('d F Y') }}
@if($issuedDate)
(Issued: {{ $issuedDate->format('d F Y') }})
@endif
@else
Belum ada dokumen SLHS yang diupload
@endif
">
    <i class="ri-information-line text-sm cursor-help"></i>
</div>
@endif