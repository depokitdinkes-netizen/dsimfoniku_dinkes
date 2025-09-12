<p class="font-semibold text-lg"> {{ number_format($value, 0, ',', '.') }}
    @switch($from)
        {{-- TPP --}}
        @case('Restoran Umum')
        @case('Restoran Hotel')
        @case('Jasa Boga / Katering Gol ')
        @case('Rumah Makan Tipe A1')
        @case('Rumah Makan Tipe A2')
        @case('Sentra Kantin')
        @case('Depot Air Minum')
        @case('Gerai Pangan Jajanan')
        @case('Gerai Pangan Jajanan Keliling Gol A1')
        @case('Gerai Pangan Jajanan Keliling Gol A2')
            @if($value >= 80) - Memenuhi Syarat
            @else - Tidak Memenuhi Syarat
            @endif
            @break
        @case('SAM Sumur Gali')
            / 8
            @if($value <= 2) - Risiko Rendah
            @elseif($value <= 4) - Risiko Sedang
            @elseif($value <= 6) - Risiko Tinggi
            @else - Risiko Amat Tinggi
            @endif
            @break
        @case('SAM Sumur Gali dengan Pompa')
        @case('SAM Perpipaan PDAM')
            / 11
            @if($value <= 2) - Risiko Rendah
            @elseif($value <= 5) - Risiko Sedang
            @elseif($value <= 9) - Risiko Tinggi
            @else - Risiko Amat Tinggi
            @endif
            @break
        @case('SAM Perpipaan Non PDAM')
            / 17
            @if($value <= 4) - Risiko Rendah
            @elseif($value <= 9) - Risiko Sedang
            @elseif($value <= 14) - Risiko Tinggi
            @else - Risiko Amat Tinggi
            @endif
            @break
        @case('SAM Penyimpanan Air Hujan')
            / 13
            @if($value <= 3) - Risiko Rendah
            @elseif($value <= 6) - Risiko Sedang
            @elseif($value <= 9) - Risiko Tinggi
            @else - Risiko Amat Tinggi
            @endif
            @break
        @case('SAM Perlindungan Mata Air')
            @if($data['ada-bangunan-penangkap'])
                / 16
                @if($value <= 4) - Risiko Rendah
                @elseif($value <= 8) - Risiko Sedang
                @elseif($value <= 12) - Risiko Tinggi
                @else - Risiko Amat Tinggi
                @endif
            @else
                / 11
                @if($value <= 2) - Risiko Rendah
                @elseif($value <= 5) - Risiko Sedang
                @elseif($value <= 8) - Risiko Tinggi
                @else - Risiko Amat Tinggi
                @endif
            @endif
            @break

        {{-- TFU --}}
        @case('Sekolah')
        @case('Puskesmas')
        @case('Tempat Rekreasi')
        @case('Arena Renang / Pemandian Alam')
        @case('Akomodasi')
        @case('Akomodasi Lainnya')
        @case('Gelanggang Olahraga')
            @if($value >= 70) - Memenuhi Syarat
            @else - Tidak Memenuhi Syarat
            @endif
            @break
        @case('Rumah Sakit')
            @if($value >= 8600) - Sangat Baik
            @elseif($value >= 6500) - Baik
            @else - Kurang
            @endif
            @break

        {{-- DEFAULT --}}
        @default
            @if($value >= 70) - Memenuhi Syarat
            @else - Tidak Memenuhi Syarat
            @endif
    @endswitch
</p>

<p class="text-md">
    <br>Note:
    @switch($from)
        {{-- TPP --}}
        @case('Restoran Umum')
        @case('Restoran Hotel')
        @case('Jasa Boga / Katering Gol ')
        @case('Rumah Makan Tipe A1')
        @case('Rumah Makan Tipe A2')
        @case('Sentra Kantin')
        @case('Depot Air Minum')
        @case('Gerai Pangan Jajanan')
        @case('Gerai Pangan Jajanan Keliling Gol A1')
        @case('Gerai Pangan Jajanan Keliling Gol A2')
            <br>Memenuhi Syarat, dengan skor 80 ke atas
            <br>Tidak Memenuhi Syarat, dengan skor < 80
            @break
        @case('SAM Sumur Gali')
            <table>
                <tr><td>Risiko rendah</td><td>:</td><td>0-2</td></tr>
                <tr><td>Risiko sedang</td><td>:</td><td>3-4</td></tr>
                <tr><td>Risiko tinggi</td><td>:</td><td>5-6</td></tr>
                <tr><td>Risiko amat tinggi</td><td>:</td><td>7-8</td></tr>
            </table>
            @break
        @case('SAM Sumur Gali dengan Pompa')
        @case('SAM Perpipaan PDAM')
            <table>
                <tr><td>Risiko rendah</td><td>:</td><td>0-2</td></tr>
                <tr><td>Risiko sedang</td><td>:</td><td>3-5</td></tr>
                <tr><td>Risiko tinggi</td><td>:</td><td>6-9</td></tr>
                <tr><td>Risiko amat tinggi</td><td>:</td><td>10-11</td></tr>
            </table>
            @break
        @case('SAM Perpipaan Non PDAM')
            <table>
                <tr><td>Risiko rendah</td><td>:</td><td>0-4</td></tr>
                <tr><td>Risiko sedang</td><td>:</td><td>5-9</td></tr>
                <tr><td>Risiko tinggi</td><td>:</td><td>10-14</td></tr>
                <tr><td>Risiko amat tinggi</td><td>:</td><td>15-17</td></tr>
            </table>
            @break
        @case('SAM Penyimpanan Air Hujan')
            <table>
                <tr><td>Risiko rendah</td><td>:</td><td>0-3</td></tr>
                <tr><td>Risiko sedang</td><td>:</td><td>4-6</td></tr>
                <tr><td>Risiko tinggi</td><td>:</td><td>7-9</td></tr>
                <tr><td>Risiko amat tinggi</td><td>:</td><td>10-13</td></tr>
            </table>
            @break
        @case('SAM Perlindungan Mata Air')
            <table>
            @if($data['ada-bangunan-penangkap'])
                <tr><td>Risiko rendah</td><td>:</td><td>0-4</td></tr>
                <tr><td>Risiko sedang</td><td>:</td><td>5-8</td></tr>
                <tr><td>Risiko tinggi</td><td>:</td><td>9-12</td></tr>
                <tr><td>Risiko amat tinggi</td><td>:</td><td>13-16</td></tr>
            @else
                <tr><td>Risiko rendah</td><td>:</td><td>0-2</td></tr>
                <tr><td>Risiko sedang</td><td>:</td><td>3-5</td></tr>
                <tr><td>Risiko tinggi</td><td>:</td><td>6-8</td></tr>
                <tr><td>Risiko amat tinggi</td><td>:</td><td>9-11</td></tr>
            @endif
            </table>
            @break

        {{-- TFU --}}
        @case('Sekolah')
        @case('Puskesmas')
        @case('Tempat Rekreasi')
        @case('Arena Renang / Pemandian Alam')
        @case('Akomodasi')
        @case('Akomodasi Lainnya')
        @case('Gelanggang Olahraga')
            <br>Memenuhi Syarat, dengan skor 70 ke atas
            <br>Tidak Memenuhi Syarat, dengan skor < 70
            @break
        @case('Rumah Sakit')
            <br>Kriteria Penilaian :
            <br>a. Kategori sangat baik = 8.600 - 10.000
            <br>b. Kategori baik = 6.500 - 8.500
            <br>c. kategori kurang < 6.500
            @break

        {{-- DEFAULT --}}
        @default
            <br>Memenuhi Syarat, dengan skor 70 ke atas
            <br>Tidak Memenuhi Syarat, dengan skor < 70
    @endswitch
</p>
