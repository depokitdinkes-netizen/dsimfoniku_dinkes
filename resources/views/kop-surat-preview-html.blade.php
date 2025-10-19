<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Kop Surat - HTML</title>
    <link rel="stylesheet" href="{{ asset('css/kop-surat-preview-html.css') }}">
</head>
<body>
    <div class="container">
        <div class="preview-header">
            <h1 style="margin: 0; font-size: 24px;">🖨️ Preview Kop Surat</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">Pratinjau tampilan kop surat pada dokumen PDF resmi</p>
        </div>

        <div class="info-box">
            <strong>ℹ️ Informasi:</strong> Ini adalah preview real-time kop surat Anda. Tampilan ini akan sama persis dengan yang muncul di dokumen PDF resmi.
        </div>

        <div class="kop-preview">
            <div class="kop-content">
                <div class="logo-container">
                    <img src="{{ asset('logo/kota-depok-logo.png') }}" alt="Logo Depok" onerror="this.style.display='none'">
                </div>
                <div class="text-container">
                    @for($i = 1; $i <= 10; $i++)
                        @if(!empty($kopData["baris{$i}"]))
                            @php
                                $fontWeight = in_array($i, [2, 3]) ? 'font-weight: bold;' : '';
                            @endphp
                            <div class="text-line" style="font-size: {{ $kopData["sizebaris{$i}"] ?? '13px' }}; {{ $fontWeight }}">
                                {{ $kopData["baris{$i}"] }}
                            </div>
                        @elseif($i <= 4)
                            <div class="text-line empty-field">[Baris {{ $i }} - belum diisi]</div>
                        @endif
                    @endfor
                </div>
            </div>
            <div class="divider"></div>
        </div>

        <div class="sample-doc">
            <h3 style="text-align: center; margin-bottom: 20px;">BERITA ACARA<br>INSPEKSI KESEHATAN LINGKUNGAN</h3>
            
            <p style="text-align: justify; line-height: 1.6; color: #666;">
                Pada hari ini tanggal <strong>{{ date('d') }}</strong> bulan <strong>{{ \Carbon\Carbon::now()->translatedFormat('F') }}</strong> tahun <strong>{{ date('Y') }}</strong> telah dilakukan Inspeksi Kesehatan Lingkungan (IKL) terhadap:
            </p>
            
            <div style="margin: 20px 0; color: #888; font-style: italic; text-align: center;">
                ... contoh isi dokumen akan muncul di sini ...
            </div>
        </div>

        <div class="btn-container">
            <a href="javascript:history.back()" class="btn">« Kembali ke Edit</a>
            <a href="{{ route('kop-surat.preview.pdf', request()->all()) }}" class="btn btn-success" target="_blank">
                📄 Lihat PDF Preview
            </a>
        </div>
    </div>

    <script src="{{ asset('js/kop-surat-preview-html.js') }}"></script>
</body>
</html>
