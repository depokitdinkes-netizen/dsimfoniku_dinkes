<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Preview Kop Surat</title>
    <link rel="stylesheet" href="{{ asset('css/kop-surat-preview.css') }}">
</head>
<body>
    <div class="preview-note">
        <strong>Preview Kop Surat</strong><br>
        Ini adalah preview tampilan kop surat yang akan muncul di dokumen PDF resmi.
    </div>

    <div class="kop-container">
        <table border="0" style="margin: 0 auto; width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 20%; vertical-align: middle; text-align: center;">
                    <img src="{{ asset('logo/kota-depok-logo.png') }}" alt="Logo Depok" style="height: 80px;">
                </td>
                <td style="width: 80%; vertical-align: middle; text-align: center; padding-left: 20px;">
                    @for($i = 1; $i <= 10; $i++)
                        @if(!empty($kopData["baris{$i}"]))
                            @php
                                $fontWeight = in_array($i, [2, 3]) ? 'font-weight: bold;' : '';
                                $marginBottom = $i < 10 && !empty($kopData["baris" . ($i + 1)]) ? 'margin-bottom: 5px;' : '';
                            @endphp
                            <div style="font-size: {{ $kopData["sizebaris{$i}"] ?? '13px' }}; font-family: Arial, sans-serif; {{ $fontWeight }} {{ $marginBottom }}">
                                {{ $kopData["baris{$i}"] }}
                            </div>
                        @endif
                    @endfor
                </td>
            </tr>
        </table>
        <hr style="margin-top: 15px; margin-bottom: 15px; border: 1px solid #000;">
    </div>

    <div class="sample-content">
        <h3 style="text-align: center; margin-bottom: 20px;">BERITA ACARA<br>INSPEKSI KESEHATAN LINGKUNGAN</h3>
        
        <p style="text-align: justify; line-height: 1.6;">
            Pada hari ini tanggal <strong>{{ date('d') }}</strong> bulan <strong>{{ \Carbon\Carbon::now()->translatedFormat('F') }}</strong> tahun <strong>{{ date('Y') }}</strong> telah dilakukan Inspeksi Kesehatan Lingkungan (IKL) terhadap:
        </p>
        
        <table border="0" style="width: 100%; margin: 20px 0; border-collapse: collapse;">
            <tr>
                <td style="width: 5%; vertical-align: top;">1.</td>
                <td style="width: 35%; vertical-align: top;">Nama Tempat</td>
                <td style="width: 5%; vertical-align: top;">:</td>
                <td style="width: 55%; vertical-align: top;">[Contoh: Restoran ABC]</td>
            </tr>
            <tr>
                <td style="vertical-align: top;">2.</td>
                <td style="vertical-align: top;">Nama Pengelola</td>
                <td style="vertical-align: top;">:</td>
                <td style="vertical-align: top;">[Contoh: Budi Santoso]</td>
            </tr>
            <tr>
                <td style="vertical-align: top;">3.</td>
                <td style="vertical-align: top;">Alamat</td>
                <td style="vertical-align: top;">:</td>
                <td style="vertical-align: top;">[Contoh: Jl. Margonda Raya No. 123]</td>
            </tr>
        </table>
        
        <div style="margin-top: 40px; text-align: center; color: #666; font-style: italic;">
            ... isi dokumen lainnya akan muncul di sini ...
        </div>
    </div>
</body>
</html>
