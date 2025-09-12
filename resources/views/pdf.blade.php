<!DOCTYPE html>
<html>

<body style="font-family: Arial, sans-serif;">
    @auth
    <table border="0" style="margin: 0 auto; width: auto;">
        <tr>
            <td style="width: 33.33%; vertical-align: middle; text-align: right;">
                <img src="logo/kota-depok-logo.png" alt="Logo" style="height: 0.91in; padding-right: 50px;">
            </td>
            <td style="width: 66.67%; vertical-align: middle; text-align: center;">
            <div style="font-size: {{ Auth::user()->sizebaris1 }}; font-family: Arial, sans-serif;">{{ Auth::user()->baris1 }}</div>
            <div style="font-size: {{ Auth::user()->sizebaris2 }}; font-family: Arial, sans-serif; font-weight: bold;">{{ Auth::user()->baris2 }}</div>
            <div style="font-size: {{ Auth::user()->sizebaris3 }}; font-family: Arial, sans-serif; font-weight: bold;">{{ Auth::user()->baris3 }}</div>
            <div style="font-size: {{ Auth::user()->sizebaris4 }}; font-family: Arial, sans-serif;">{{ Auth::user()->baris4 }}</div>
            @if(Auth::user()->baris5)
            <div style="font-size: {{ Auth::user()->sizebaris5 }}; font-family: Arial, sans-serif;">{{ Auth::user()->baris5 }}</div>
            @endif
            </td>
        </tr>
    </table>

    <hr>
    @elseguest
    <div style="height:129px"></div>
    @endauth

    <div style="text-align: center; font-size: 9pt; font-weight: bold;">
        <h2>BERITA ACARA<br>INSPEKSI KESEHATAN LINGKUNGAN {{ strtoupper($form) }}</h2>
    </div>

    <p style="text-align: justify;">Pada hari ini tanggal {{ $tanggal }} bulan {{ $bulan }} tahun
        {{ $tahun }} telah dilakukan Inspeksi Kesehatan Lingkungan (IKL) terhadap :</p>
    <table border="0" style="border-collapse: collapse;">
        @foreach ($informasi as $item)
            <tr>
                <td style="vertical-align: top;">{{ $loop->iteration }}. </td>
                <td style="vertical-align: top;">{{ $item[0] }}</td>
                <td style="vertical-align: top;">:</td>
                <td style="vertical-align: top;">{{ $item[1] }}</td>
            </tr>
        @endforeach
    </table>
    @if (isset($gerai) && count($gerai))
        <p>Yang terdiri dari:</p>
        <table border="2" style="border-collapse: collapse; width: 100%;">
            <tr>
                <th>No.</th>
                <th>Nama Gerai</th>
                <th>Pemilik Gerai</th>
                <th>Kontak</th>
                <th>Skor</th>
            </tr>
            @foreach ($gerai as $item)
                <tr>
                    <td style="text-align: center; vertical-align: top">{{ $loop->iteration }}</td>
                    <td style="text-align: center; vertical-align: top">{{ $item['subjek'] }}</td>
                    <td style="text-align: center; vertical-align: top">{{ $item['pengelola'] }}</td>
                    <td style="text-align: center; vertical-align: top">{{ $item['kontak'] }}</td>
                    <td style="text-align: center; vertical-align: top">{{ $item['skor'] }}</td>
                </tr>
            @endforeach
        </table>
    @endif
    <p style="white-space: pre-wrap;">Hasil IKL :<br>{{ $catatan ?? '-' }}</p>
    <p style="white-space: pre-wrap;">Rencana Tindak Lanjut :<br>{{ $rencana ?? '-' }}</p>
    <p>Demikian berita acara ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
    <p>Mengetahui</p>
    <table border="0" style="border-collapse: collapse; width: 100%;">
        <tr>
            <td style="width:55%">Pengelola/Penanggung Jawab</td>
            <td style="width:45%">Petugas Pemeriksa,</td>
        </tr>
        <tr>
            @if (strpos($pengelola, '&') !== false)
                <td style="padding: 0; margin: 0; vertical-align: top;">
                    <table border="0" style="border-collapse: collapse; width: 100%;">
                        @foreach (explode('&', $pengelola) as $nama)
                            <tr>
                                <td style="vertical-align: top;">
                                    {{ $loop->iteration }}.
                                </td>
                                <td style="vertical-align: top;">
                                    {{ trim($nama) }}
                                </td>
                                <td style="vertical-align: top;">
                                    .....................
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </td>
            @else
                <td style="padding-top: 7em">
                    {{ $pengelola }}
                </td>
            @endif
            @if (strpos($pemeriksa, '&') !== false)
                <td style="padding: 0; margin: 0; vertical-align: top;">
                    <table border="0" style="border-collapse: collapse; width: 100%;">
                        @foreach (explode('&', $pemeriksa) as $nama)
                            <tr>
                                <td style="vertical-align: top;">
                                    {{ $loop->iteration }}.
                                </td>
                                <td style="vertical-align: top;">
                                    {{ trim($nama) }}
                                </td>
                                <td style="vertical-align: top;">
                                    .....................
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </td>
            @else
                <td style="padding-top: 7em">
                    {{ $pemeriksa }}
                </td>
            @endif
        </tr>
    </table>
</body>

</html>
