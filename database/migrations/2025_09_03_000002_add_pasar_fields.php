<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pasar', function (Blueprint $table) {
            // Konstruksi - Atap
            $table->enum('ka001', [0, 2])->default(0);
            $table->enum('ka002', [0, 2])->default(0);
            $table->enum('ka003', [0, 2])->default(0);
            
            // Konstruksi - Dinding
            $table->enum('kd001', [0, 2])->default(0);
            $table->enum('kd002', [0, 2])->default(0);
            $table->enum('kd003', [0, 2])->default(0);
            
            // Konstruksi - Lantai
            $table->enum('kl001', [0, 2])->default(0);
            $table->enum('kl002', [0, 2])->default(0);
            
            // Konstruksi - Tangga
            $table->enum('kt001', [0, 2])->default(0);
            $table->enum('kt002', [0, 2])->default(0);
            $table->enum('kt003', [0, 2])->default(0);
            $table->enum('kt004', [0, 2])->default(0);
            
            // Konstruksi - Kualitas Udara
            $table->enum('ku001', [0, 2])->default(0);
            $table->enum('ku002', [0, 2])->default(0);
            $table->enum('ku003', [0, 2])->default(0);
            $table->enum('ku004', [0, 2])->default(0);
            $table->enum('ku005', [0, 2])->default(0);
            
            // Konstruksi - Pencahayaan
            $table->enum('kp001', [0, 2])->default(0);
            
            // Konstruksi - Pintu
            $table->enum('kpi001', [0, 3])->default(0);
            
            // C. Sanitasi - Air untuk Kebutuhan Higiene Sanitasi
            $table->enum('ahs001', [0, 2])->default(0);
            $table->enum('ahs002', [0, 2])->default(0);
            $table->enum('ahs003', [0, 2])->default(0);
            $table->enum('ahs004', [0, 2])->default(0);
            $table->enum('ahs005', [0, 2])->default(0);
            
            // Kamar Mandi dan Toilet
            $table->enum('kmt001', [0, 2])->default(0);
            $table->enum('kmt002', [0, 2])->default(0);
            $table->enum('kmt003', [0, 2])->default(0);
            $table->enum('kmt004', [0, 3])->default(0);
            $table->enum('kmt005', [0, 3])->default(0);
            $table->enum('kmt006', [0, 2])->default(0);
            $table->enum('kmt007', [0, 2])->default(0);
            $table->enum('kmt008', [0, 3])->default(0);
            $table->enum('kmt009', [0, 2])->default(0);
            $table->enum('kmt010', [0, 2])->default(0);
            $table->enum('kmt011', [0, 2])->default(0);
            
            // Pengelolaan Sampah
            $table->enum('ps001', [0, 2])->default(0);
            $table->enum('ps002', [0, 2])->default(0);
            $table->enum('ps003', [0, 2])->default(0);
            $table->enum('ps004', [0, 2])->default(0);
            $table->enum('ps005', [0, 3])->default(0);
            $table->enum('ps006', [0, 3])->default(0);
            
            // Saluran Pembuangan Air Limbah
            $table->enum('spal001', [0, 2])->default(0);
            $table->enum('spal002', [0, 2])->default(0);
            $table->enum('spal003', [0, 2])->default(0);
            $table->enum('spal004', [0, 3])->default(0);
            
            // IPAL
            $table->enum('ipal001', [0, 2])->default(0);
            $table->enum('ipal002', [0, 2])->default(0);
            
            // Tempat Cuci Tangan
            $table->enum('tct001', [0, 3])->default(0);
            $table->enum('tct002', [0, 3])->default(0);
            $table->enum('tct003', [0, 3])->default(0);
            $table->enum('tct004', [0, 3])->default(0);
            
            // Pengendalian Vektor dan Binatang Pembawa Penyakit
            $table->enum('pv001', [0, 3])->default(0);
            $table->enum('pv002', [0, 2])->default(0);
            $table->enum('pv003', [0, 2])->default(0);
            $table->enum('pv004', [0, 2])->default(0);
            $table->enum('pv005', [0, 2])->default(0);
            
            // Kualitas Makanan dan Bahan Pangan
            $table->enum('kmbp001', [0, 3])->default(0);
            $table->enum('kmbp002', [0, 3])->default(0);
            $table->enum('kmbp003', [0, 2])->default(0);
            $table->enum('kmbp004', [0, 3])->default(0);
            $table->enum('kmbp005', [0, 2])->default(0);
            $table->enum('kmbp006', [0, 2])->default(0);
            $table->enum('kmbp007', [0, 3])->default(0);
            $table->enum('kmbp008', [0, 3])->default(0);
            
            // Desinfeksi Pasar
            $table->enum('dp001', [0, 2])->default(0);
            $table->enum('dp002', [0, 2])->default(0);
            
            // D. Manajemen Sanitasi
            $table->enum('ms001', [0, 3])->default(0);
            $table->enum('ms002', [0, 3])->default(0);
            $table->enum('ms003', [0, 3])->default(0);
            $table->enum('ms004', [0, 3])->default(0);
            
            // E. Pemberdayaan Masyarakat - Pedagang dan Pekerja
            $table->enum('pp001', [0, 3])->default(0);
            $table->enum('pp002', [0, 3])->default(0);
            $table->enum('pp003', [0, 3])->default(0);
            $table->enum('pp004', [0, 3])->default(0);
            
            // E. Pemberdayaan Masyarakat - Pengunjung
            $table->enum('pg001', [0, 2])->default(0);
            $table->enum('pg002', [0, 3])->default(0);
            
            // E. Pemberdayaan Masyarakat - Pengelola
            $table->enum('pn001', [0, 2])->default(0);
            $table->enum('pn002', [0, 3])->default(0);
            $table->enum('pn003', [0, 3])->default(0);
            
            // E. Pemberdayaan Masyarakat - POKJA
            $table->enum('pokja001', [0, 3])->default(0);
            $table->enum('pokja002', [0, 3])->default(0);
            $table->enum('pokja003', [0, 3])->default(0);
            $table->enum('pokja004', [0, 3])->default(0);
            $table->enum('pokja005', [0, 3])->default(0);
            
            // F. Keamanan - Pemadam Kebakaran
            $table->enum('pk001', [0, 2])->default(0);
            $table->enum('pk002', [0, 2])->default(0);
            $table->enum('pk003', [0, 2])->default(0);
            $table->enum('pk004', [0, 2])->default(0);
            $table->enum('pk005', [0, 2])->default(0);
            $table->enum('pk006', [0, 2])->default(0);
            
            // F. Keamanan - Keamanan Umum
            $table->enum('keu001', [0, 2])->default(0);
            $table->enum('keu002', [0, 2])->default(0);
            
            // G. Sarana Penunjang
            $table->enum('sp001', [0, 2])->default(0);
            $table->enum('sp002', [0, 2])->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pasar', function (Blueprint $table) {
            $table->dropColumn([
                'ka001', 'ka002', 'ka003',
                'kd001', 'kd002', 'kd003',
                'kl001', 'kl002',
                'kt001', 'kt002', 'kt003', 'kt004',
                'ku001', 'ku002', 'ku003', 'ku004', 'ku005',
                'kp001',
                'kpi001',
                'ahs001', 'ahs002', 'ahs003', 'ahs004', 'ahs005',
                'kmt001', 'kmt002', 'kmt003', 'kmt004', 'kmt005', 'kmt006', 'kmt007', 'kmt008', 'kmt009', 'kmt010', 'kmt011',
                'ps001', 'ps002', 'ps003', 'ps004', 'ps005', 'ps006',
                'spal001', 'spal002', 'spal003', 'spal004',
                'ipal001', 'ipal002',
                'tct001', 'tct002', 'tct003', 'tct004',
                'pv001', 'pv002', 'pv003', 'pv004', 'pv005',
                'kmbp001', 'kmbp002', 'kmbp003', 'kmbp004', 'kmbp005', 'kmbp006', 'kmbp007', 'kmbp008',
                'dp001', 'dp002',
                'ms001', 'ms002', 'ms003', 'ms004',
                'pp001', 'pp002', 'pp003', 'pp004',
                'pg001', 'pg002',
                'pn001', 'pn002', 'pn003',
                'pokja001', 'pokja002', 'pokja003', 'pokja004', 'pokja005',
                'pk001', 'pk002', 'pk003', 'pk004', 'pk005', 'pk006',
                'keu001', 'keu002',
                'sp001', 'sp002'
            ]);
        });
    }
};
