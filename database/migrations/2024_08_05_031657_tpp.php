<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tpp_tertentu', function (Blueprint $table) {
            $table->id();
            $table->string('u001');                     //  halaman 1718
            $table->string('u002');                     //  1: 05, 2: 00, 3: 01
            $table->string('u003');
            $table->string('u004');
            $table->string('u005');
            $table->string('u006');
            $table->string('u007');
            $table->date('u008');
            $table->enum('lk001', [0, 3]);              //                      a lokasi sekitar tpp
            $table->enum('lk002', [0, 1]);
            $table->enum('lk003', [0, 1]);
            $table->enum('lk004', [0, 1]);
            $table->enum('lk005', [0, 1]);
            $table->enum('lk006', [0, 1]);
            $table->enum('lk007', [0, 1]);              //  halaman 1719
            $table->enum('lk008', [0, 1]);              //  1: 21, 2: 00, 3: 00
            $table->enum('lk009', [0, 1]);
            $table->enum('lk010', [0, 1]);
            $table->enum('lk011', [0, 1]);
            $table->enum('lk012', [0, 1]);
            $table->enum('bf001', [0, 1]);              //                      b bangunan dan fasilitasnya
            $table->enum('bf002', [0, 1]);
            $table->enum('bf003', [0, 1]);
            $table->enum('bf004', [0, 1]);
            $table->enum('bf005', [0, 1]);
            $table->enum('bf006', [0, 1]);
            $table->enum('bf007', [0, 1]);
            $table->enum('bf008', [0, 1]);
            $table->enum('bf009', [0, 1]);
            $table->enum('bf010', [0, 1]);
            $table->enum('bf011', [0, 1]);
            $table->enum('bf012', [0, 1]);
            $table->enum('bf013', [0, 1]);
            $table->enum('bf014', [0, 1]);
            // $table->enum('bf01X', [0, 1]);
            $table->enum('bf015', [0, 2]);              //  halaman 1720
            $table->enum('bf016', [0, 2]);              //  1: 08, 2: 09, 3: 02
            $table->enum('bf017', [0, 1]);
            $table->enum('bf018', [0, 1]);
            $table->enum('bf019', [0, 1]);
            $table->enum('pp001', [0, 3]);              //                      penanganan pangan
            $table->enum('pp002', [0, 3]);
            $table->enum('fk001', [0, 1]);              //                      d fasilitas karyawan
            $table->enum('fk002', [0, 1]);
            $table->enum('fk003', [0, 1]);
            $table->enum('fk004', [0, 2]);
            $table->enum('fk005', [0, 1]);
            $table->enum('ab001', [0, 1]);              //                      e area penerimaan bahan baku
            $table->enum('ab002', [0, 2]);
            $table->enum('pb001', [0, 2]);              //                      f persyaratan bahan baku
            $table->enum('pb002', [0, 2]);
            $table->enum('pb003', [0, 2]);
            $table->enum('pb004', [0, 2]);
            $table->enum('pb005', [0, 2]);
            $table->enum('pb006', [0, 2]);              //  halaman 1721
            $table->enum('pb007', [0, 1]);              //  1: 11, 2: 05, 3: 00
            $table->enum('pb008', [0, 2]);
            $table->enum('pb009', [0, 2]);
            $table->enum('ap001', [0, 1]);              //                      a area penyimpanan
            $table->enum('ap002', [0, 1]);
            $table->enum('ap003', [0, 1]);
            $table->enum('ap004', [0, 1]);
            $table->enum('ap005', [0, 1]);
            $table->enum('ap006', [0, 1]);
            $table->enum('ap007', [0, 1]);
            $table->enum('ap008', [0, 1]);
            $table->enum('ap009', [0, 1]);
            $table->enum('ap010', [0, 2]);
            $table->enum('ap011', [0, 1]);
            $table->enum('ap012', [0, 2]);
            $table->enum('ap013', [0, 2]);              //  halaman 1722
            $table->enum('ap014', [0, 1]);              //  1: 10, 2: 04, 3: 02
            $table->enum('ap015', [0, 1]);
            $table->enum('ap016', [0, 1]);
            $table->enum('ap017', [0, 1]);
            $table->enum('ap018', [0, 1]);
            $table->enum('ap019', [0, 1]);
            $table->enum('ap020', [0, 1]);
            $table->enum('ap021', [0, 1]);
            $table->enum('ap022', [0, 3]);
            $table->enum('ap023', [0, 3]);
            $table->enum('bp001', [0, 2]);              //                      area penyimpanan bahan pangan
            $table->enum('bp002', [0, 2]);
            $table->enum('bp003', [0, 2]);
            $table->enum('bp004', [0, 1]);
            $table->enum('bp005', [0, 1]);
            $table->enum('bp006', [0, 1]);              //  halaman 1723
            $table->enum('bp007', [0, 1]);              //  1: 08, 2: 09, 3: 00
            $table->enum('bp008', [0, 2]);
            $table->enum('bp009', [0, 2]);
            $table->enum('bp010', [0, 2]);
            $table->enum('bp011', [0, 3]);
            $table->enum('bp012', [0, 1]);
            $table->enum('bp013', [0, 2]);
            $table->enum('bp014', [0, 1]);
            $table->enum('bp015', [0, 2]);
            $table->enum('bp016', [0, 1]);
            $table->enum('ak001', [0, 1]);              //                      area penyimpanan kemasan
            $table->enum('ak002', [0, 1]);
            $table->enum('ak003', [0, 1]);
            $table->enum('ak004', [0, 1]);
            $table->enum('ak005', [0, 2]);
            $table->enum('bpk001', [0, 2]);             //                      area penyimpanan bahan kimia non pangan
            $table->enum('bpk002', [0, 2]);
            $table->enum('bpk003', [0, 2]);
            $table->enum('bap001', [0, 1]);             //  halaman 1724        b area pencucian
            $table->enum('bap002', [0, 1]);             //  1: 09, 2: 09, 3: 00
            $table->enum('bap003', [0, 1]);
            $table->enum('bap004', [0, 1]);
            $table->enum('bap005', [0, 2]);
            $table->enum('bap006', [0, 2]);
            $table->enum('bap007', [0, 2]);
            $table->enum('bap008', [0, 1]);
            $table->enum('bap009', [0, 1]);
            $table->enum('bap010', [0, 1]);
            $table->enum('bap011', [0, 1]);
            $table->enum('bap012', [0, 1]);
            $table->enum('bap013', [0, 2]);
            $table->enum('app001', [0, 2]);             //                      c area persiapan pengolahan dan pengemasan pangan umum
            $table->enum('app002', [0, 2]);
            $table->enum('app003', [0, 2]);
            $table->enum('app004', [0, 2]);
            $table->enum('app005', [0, 2]);
            $table->enum('app006', [0, 2]);             //  halaman 1725
            $table->enum('app007', [0, 1]);             //  1: 02, 2: 08, 3: 07
            $table->enum('app008', [0, 1]);
            $table->enum('app009', [0, 2]);
            $table->enum('app010', [0, 2]);
            $table->enum('app011', [0, 2]);
            $table->enum('app012', [0, 2]);
            $table->enum('app013', [0, 3]);
            $table->enum('app014', [0, 2]);
            $table->enum('app015', [0, 3]);
            $table->enum('app016', [0, 2]);
            $table->enum('app017', [0, 3]);
            $table->enum('app018', [0, 3]);
            $table->enum('app019', [0, 2]);
            $table->enum('app020', [0, 3]);
            $table->enum('app021', [0, 3]);
            $table->enum('app022', [0, 3]);
            $table->enum('app023', [0, 3]);             //  halaman 1726
            $table->enum('app024', [0, 3]);             //  1: 05, 2: 03, 3: 09
            $table->enum('app025', [0, 3]);
            $table->enum('app026', [0, 3]);
            $table->enum('app027', [0, 3]);
            $table->enum('app028', [0, 3]);
            $table->enum('app029', [0, 3]);
            $table->enum('app030', [0, 1]);
            $table->enum('app031', [0, 2]);
            $table->enum('app032', [0, 2]);
            $table->enum('app033', [0, 1]);
            $table->enum('app034', [0, 1]);
            $table->enum('app035', [0, 3]);
            $table->enum('app036', [0, 1]);
            $table->enum('app037', [0, 1]);
            $table->enum('app038', [0, 2]);
            $table->enum('app039', [0, 3]);
            $table->enum('app040', [0, 3]);             //  halaman 1727
            $table->enum('app041', [0, 3]);             //  1: 07, 2: 12, 3: 13
            $table->enum('app042', [0, 2]);
            $table->enum('app043', [0, 2]);
            $table->enum('app044', [0, 2]);
            $table->enum('app045', [0, 1]);
            $table->enum('fhsp001', [0, 2]);            //                      fasilitas higiene sanitasi personel
            $table->enum('fhsp002', [0, 3]);
            $table->enum('fhsp003', [0, 3]);
            $table->enum('fhsp004', [0, 2]);
            $table->enum('fhsp005', [0, 1]);
            $table->enum('fhsp006', [0, 1]);
            $table->enum('fhsp007', [0, 3]);
            $table->enum('fhsp008', [0, 1]);
            $table->enum('fhsp009', [0, 1]);
            $table->enum('fhsp010', [0, 1]);
            $table->enum('fhsp011', [0, 1]);
            $table->enum('fhsp012', [0, 3]);
            $table->enum('fhsp013', [0, 3]);
            $table->enum('fhsp014', [0, 1]);
            $table->enum('fhsp015', [0, 2]);
            $table->enum('fhsp016', [0, 2]);
            $table->enum('fhsp017', [0, 2]);
            $table->enum('pup001', [0, 2]);             //                      peralatan
            $table->enum('pup002', [0, 3]);
            $table->enum('pup003', [0, 3]);
            $table->enum('pup004', [0, 3]);
            $table->enum('pup005', [0, 3]);
            $table->enum('pup006', [0, 2]);
            $table->enum('pup007', [0, 3]);
            $table->enum('pup008', [0, 3]);
            $table->enum('pup009', [0, 2]);
            $table->enum('pup010', [0, 2]);
            $table->enum('pup011', [0, 2]);             //  halaman 1728
            $table->enum('pup012', [0, 2]);             //  1: 00, 2: 14, 3: 07
            $table->enum('pps001', [0, 3]);             //                      penyimpanan pangan setengah matang matang
            $table->enum('pps002', [0, 2]);
            $table->enum('pps003', [0, 3]);
            $table->enum('pps004', [0, 2]);
            $table->enum('pps005', [0, 1]);
            $table->enum('pps006', [0, 2]);
            $table->enum('pps007', [0, 2]);
            $table->enum('pps008', [0, 2]);
            $table->enum('ppm001', [0, 3]);             //                      pengemasan pangan matang produk akhir
            $table->enum('ppm002', [0, 3]);
            $table->enum('ppm003', [0, 2]);
            $table->enum('ppm004', [0, 3]);             //                      pengangkutan pangan matang produk akhir
            $table->enum('ppm005', [0, 2]);
            $table->enum('ppm006', [0, 2]);
            $table->enum('ppm007', [0, 2]);
            $table->enum('dr001', [0, 3]);              //                      d dokumentasi dan rekaman diakses di ruangan administrasi
            $table->enum('dr002', [0, 2]);
            $table->enum('rp001', [0, 2]);              //                      rekaman personil
            $table->enum('rp002', [0, 3]);
            $table->enum('rp003', [0, 3]);              //  halaman 1729
            $table->enum('rp004', [0, 2]);              //  1: 06, 2: 01, 3: 01
            $table->enum('kkk001', [0, 1]);             //                      e keselamatan dan kesehatan kerja
            $table->enum('kkk002', [0, 1]);
            $table->enum('kkk003', [0, 1]);
            $table->enum('kkk004', [0, 1]);
            $table->enum('kkk005', [0, 1]);
            $table->enum('kkk006', [0, 1]);
            $table->text('o001')->nullable();
            $table->tinyInteger('skor')->length(3);
            $table->boolean('o002')->nullable();
            $table->boolean('o003')->nullable();
            $table->text('o004')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tpp_tertentu');
    }
};
