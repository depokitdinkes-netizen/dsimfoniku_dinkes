<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Preview Kop Surat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .kop-container {
            border: 2px solid #ddd;
            padding: 20px;
            margin-bottom: 30px;
            background-color: #f9f9f9;
        }
        .kop-header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .logo-section {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }
        .logo {
            width: 80px;
            height: 80px;
            margin-right: 20px;
        }
        .text-section {
            flex: 1;
            text-align: center;
        }
        .sample-content {
            padding: 20px;
            border: 1px dashed #ccc;
            background-color: #fff;
        }
        .preview-note {
            background-color: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin: 20px 0;
            font-style: italic;
        }
    </style>
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
                    @if($kopData['baris1'])
                    <div style="font-size: {{ $kopData['sizebaris1'] }}; font-family: Arial, sans-serif; margin-bottom: 5px;">
                        {{ $kopData['baris1'] }}
                    </div>
                    @endif
                    
                    @if($kopData['baris2'])
                    <div style="font-size: {{ $kopData['sizebaris2'] }}; font-family: Arial, sans-serif; font-weight: bold; margin-bottom: 5px;">
                        {{ $kopData['baris2'] }}
                    </div>
                    @endif
                    
                    @if($kopData['baris3'])
                    <div style="font-size: {{ $kopData['sizebaris3'] }}; font-family: Arial, sans-serif; font-weight: bold; margin-bottom: 5px;">
                        {{ $kopData['baris3'] }}
                    </div>
                    @endif
                    
                    @if($kopData['baris4'])
                    <div style="font-size: {{ $kopData['sizebaris4'] }}; font-family: Arial, sans-serif; margin-bottom: 5px;">
                        {{ $kopData['baris4'] }}
                    </div>
                    @endif
                    
                    @if($kopData['baris5'])
                    <div style="font-size: {{ $kopData['sizebaris5'] }}; font-family: Arial, sans-serif;">
                        {{ $kopData['baris5'] }}
                    </div>
                    @endif
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
