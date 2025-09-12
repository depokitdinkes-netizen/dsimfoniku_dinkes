<?php

namespace App\Models\FormIKL;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RumahSakit extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "rumah_sakit";
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal-penilaian' => 'date',

        // Kolom yang namanya diubah, sekarang aman di-cast sebagai integer
        'f1001' => 'integer',
        'f1003' => 'integer',
        'f1004' => 'integer',
        'f1005' => 'string',
        'f1006' => 'string',
        'f1007' => 'string',
        'f2002' => 'integer',
        'f3001' => 'integer',
        'f3002' => 'integer',
        'f4001' => 'integer',
        'f4002' => 'integer',
        'f4005' => 'integer',
        'f5002' => 'integer',
        'f7001' => 'integer',
        'f7002' => 'integer',
        'f7003' => 'integer',
        'f7004' => 'integer',
        'f7005' => 'integer',
        'f9002' => 'integer',
        'f9003' => 'integer',

        // Kolom alfanumerik (tetap aman sebagai integer)
        '1002a' => 'integer',
        '1002b' => 'integer',
        '1002c' => 'integer',
        '2001a' => 'integer',
        '2001b' => 'integer',
        '2003a' => 'integer',
        '2003b' => 'integer',
        '2003c' => 'integer',
        '2003d' => 'integer',
        '2003e' => 'integer',
        '2003f' => 'integer',
        '2003g' => 'integer',
        '2003h' => 'integer',
        '2003i' => 'integer',
        '2003j' => 'integer',
        '2003k' => 'integer',
        '2004a' => 'integer',
        '2004b' => 'integer',
        '2004c' => 'integer',
        '2004d' => 'integer',
        '2004e' => 'integer',
        '2004f' => 'integer',
        '2004g' => 'integer',
        '2004h' => 'integer',
        '2004i' => 'integer',
        '2004j' => 'integer',
        '2004k' => 'integer',
        '2004l' => 'integer',
        '2004m' => 'integer',
        '2004n' => 'integer',
        '2004o' => 'integer',
        '2004p' => 'integer',
        '2005a' => 'integer',
        '2005b' => 'integer',
        '2005c' => 'integer',
        '2005d' => 'integer',
        '2005e' => 'integer',
        '2005f' => 'integer',
        '2005g' => 'integer',
        '2005h' => 'integer',
        '2005i' => 'integer',
        '4003a' => 'integer',
        '4003b' => 'integer',
        '4003c' => 'integer',
        '4003d' => 'integer',
        '4004a' => 'integer',
        '4004b' => 'integer',
        '4004c' => 'integer',
        '4004d' => 'integer',
        '4004e' => 'integer',
        '4004f' => 'integer',
        '4006a' => 'integer',
        '4006b' => 'integer',
        '4006c' => 'integer',
        '4006d' => 'integer',
        '4006e' => 'integer',
        '5001a' => 'integer',
        '5001b' => 'integer',
        '5001c' => 'integer',
        '5001d' => 'integer',
        '5001e' => 'integer',
        '5001f' => 'integer',
        '5001g' => 'integer',
        '5001h' => 'integer',
        '5001i' => 'integer',
        '5001j' => 'integer',
        '6001a' => 'integer',
        '6001b' => 'integer',
        '6001c' => 'integer',
        '6001d' => 'string',
        '6001e' => 'string',
        '6001f' => 'string',
        '6002a' => 'integer',
        '6002b' => 'integer',
        '6002c' => 'integer',
        '6002d' => 'integer',
        '6003b' => 'integer',
        '6004a' => 'integer',
        '6004b' => 'integer',
        '6004c' => 'integer',
        '6004d' => 'integer',
        '6004e' => 'integer',
        '8001a' => 'integer',
        '8001b' => 'integer',
        '8001c' => 'integer',
        '8001d' => 'integer',
        '8001e' => 'integer',
        '8002a' => 'integer',
        '8002b' => 'integer',
        '9001a' => 'integer',
        '9001b' => 'integer',
        '9001c' => 'integer',
        '9001d' => 'integer',
        '9001e' => 'integer',
        '9001f' => 'integer',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Informasi Umum
        'subjek', 'alamat', 'kecamatan', 'kelurahan', 'kelas', 'jumlah-tempat-tidur',
        'pengelola', 'kontak', 'nama-pemeriksa', 'instansi-pemeriksa', 'tanggal-penilaian',
        'status-operasi', 'koordinat', 'user_id',

        // Form Penilaian - Kesehatan Air (I)
        'f1001', '1002a', '1002b', '1002c', 'f1003', 'f1004', 'f1005', 'f1006', 'f1007',

        // Form Penilaian - Kesehatan Udara (II)
        '2001a', '2001b', 'f2002',
        '2003a', '2003b', '2003c', '2003d', '2003e', '2003f', '2003g', '2003h', '2003i', '2003j', '2003k',
        '2004a', '2004b', '2004c', '2004d', '2004e', '2004f', '2004g', '2004h', '2004i', '2004j', '2004k', '2004l', '2004m', '2004n', '2004o', '2004p',
        '2005a', '2005b', '2005c', '2005d', '2005e', '2005f', '2005g', '2005h', '2005i',
        
        // Kolom deskriptif
        'hasil_pengukuran_pm25', 'pengelolaan_pangan_oleh',

        // Form Penilaian - Kesehatan Pangan (III)
        'f3001', 'f3002',

        // Form Penilaian - Kesehatan Sarana dan Bangunan (IV)
        'f4001', 'f4002',
        '4003a', '4003b', '4003c', '4003d',
        '4004a', '4004b', '4004c', '4004d', '4004e', '4004f',
        'f4005',
        '4006a', '4006b', '4006c', '4006d', '4006e',

        // Form Penilaian - Pengendalian Vektor (V)
        'nama_pest_control', 'nomor_izin_tahun_pest_control',
        '5001a', '5001b', '5001c', '5001d', '5001e', '5001f', '5001g', '5001h', '5001i', '5001j',
        'f5002',

        // Form Penilaian - Pengamanan Limbah (VI)
        '6001a', '6001b', '6001c', '6001d', '6001e', '6001f',
        '6002a', '6002b', '6002c', '6002d',
        'pihak_ketiga_berizin', 'nomor_tahun_perizinan_pihak_ketiga', 'masa_berlaku_mou_pihak_ketiga',
        '6003b',
        'parameter_limbah_kendala',
        '6004a', '6004b', '6004c', '6004d', '6004e',

        // Form Penilaian - Pengamanan Radiasi (VII)
        'f7001', 'f7002', 'f7003', 'f7004', 'f7005',
        'penyelenggaraan_linen_rs_oleh',

        // Form Penilaian - Penyelenggaraan Linen (VIII)
        '8001a', '8001b', '8001c', '8001d', '8001e',
        '8002a', '8002b',

        // Form Penilaian - Manajemen Kesehatan Lingkungan (IX)
        '9001a', '9001b', '9001c', '9001d', '9001e', '9001f',
        'f9002', 'f9003',

        // Additional fields
        'skor', 'catatan-lain', 'rencana-tindak-lanjut',
        'pelaporan-elektronik', 'pengamanan-radiasi', 'penyehatan-air-hemodiolisa',
        'dokumen-rintek-tps-b3', 'nomor-dokumen-rintek-tps-b3',
        'dokumen-pertek-ipal', 'nomor-dokumen-pertek-ipal',
        'pengisian-sikelim', 'alasan-sikelim',
        'pengisian-dsmiling', 'alasan-dsmiling',
    ];

    /**
     * Get the user that owns the inspection.
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}