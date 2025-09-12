<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Kop Surat - HTML</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 40px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .preview-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
            margin: -40px -40px 30px -40px;
        }
        .kop-preview {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 30px;
            margin-bottom: 30px;
            background-color: #fafafa;
            min-height: 200px;
        }
        .kop-content {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        .logo-container {
            margin-right: 30px;
        }
        .logo-container img {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }
        .text-container {
            flex: 1;
            text-align: center;
        }
        .text-line {
            margin: 8px 0;
            word-wrap: break-word;
        }
        .divider {
            border-top: 2px solid #333;
            margin: 20px 0;
        }
        .sample-doc {
            padding: 20px;
            border: 1px dashed #ccc;
            background-color: #f9f9f9;
            border-radius: 4px;
        }
        .info-box {
            background-color: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .btn-container {
            text-align: center;
            margin-top: 30px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #2196f3;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 0 10px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #1976d2;
        }
        .btn-success {
            background-color: #4caf50;
        }
        .btn-success:hover {
            background-color: #45a049;
        }
        .empty-field {
            color: #999;
            font-style: italic;
        }
    </style>
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
                    @if($kopData['baris1'])
                        <div class="text-line" style="font-size: {{ $kopData['sizebaris1'] }};">
                            {{ $kopData['baris1'] }}
                        </div>
                    @else
                        <div class="text-line empty-field">[Baris 1 - belum diisi]</div>
                    @endif
                    
                    @if($kopData['baris2'])
                        <div class="text-line" style="font-size: {{ $kopData['sizebaris2'] }}; font-weight: bold;">
                            {{ $kopData['baris2'] }}
                        </div>
                    @else
                        <div class="text-line empty-field">[Baris 2 - belum diisi]</div>
                    @endif
                    
                    @if($kopData['baris3'])
                        <div class="text-line" style="font-size: {{ $kopData['sizebaris3'] }}; font-weight: bold;">
                            {{ $kopData['baris3'] }}
                        </div>
                    @else
                        <div class="text-line empty-field">[Baris 3 - belum diisi]</div>
                    @endif
                    
                    @if($kopData['baris4'])
                        <div class="text-line" style="font-size: {{ $kopData['sizebaris4'] }};">
                            {{ $kopData['baris4'] }}
                        </div>
                    @else
                        <div class="text-line empty-field">[Baris 4 - belum diisi]</div>
                    @endif
                    
                    @if(Auth::user()->role == 'ADMIN')
                        @if($kopData['baris5'])
                            <div class="text-line" style="font-size: {{ $kopData['sizebaris5'] }};">
                                {{ $kopData['baris5'] }}
                            </div>
                        @else
                            <div class="text-line empty-field">[Baris 5 - belum diisi]</div>
                        @endif
                    @endif
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

    <script>
        // Auto close after 30 seconds if opened in popup
        if (window.opener) {
            setTimeout(() => {
                if (confirm('Tutup jendela preview ini?')) {
                    window.close();
                }
            }, 30000);
        }
    </script>
</body>
</html>
