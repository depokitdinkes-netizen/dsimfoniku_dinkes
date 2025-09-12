-- Query SQL untuk menambahkan kolom pertanyaan baru ke tabel rumah_sakit

-- Setelah 2005i (2 pertanyaan)
ALTER TABLE rumah_sakit ADD COLUMN hasil_pengukuran_pm25 TEXT NULL AFTER `2005i`;
ALTER TABLE rumah_sakit ADD COLUMN pengelolaan_pangan_internal_rs ENUM('0', '1') DEFAULT '0' AFTER hasil_pengukuran_pm25;
ALTER TABLE rumah_sakit ADD COLUMN pengelolaan_pangan_pihak_ketiga ENUM('0', '1') DEFAULT '0' AFTER pengelolaan_pangan_internal_rs;

-- Sebelum 5001a (2 pertanyaan)
ALTER TABLE rumah_sakit ADD COLUMN nama_pest_control TEXT NULL AFTER `4006e`;
ALTER TABLE rumah_sakit ADD COLUMN nomor_izin_tahun_pest_control TEXT NULL AFTER nama_pest_control;

-- Setelah 6002d (4 pertanyaan)
ALTER TABLE rumah_sakit ADD COLUMN nomor_tahun_perizinan_tps_lb3 TEXT NULL AFTER `6002d`;
ALTER TABLE rumah_sakit ADD COLUMN pihak_ketiga_berizin TEXT NULL AFTER nomor_tahun_perizinan_tps_lb3;
ALTER TABLE rumah_sakit ADD COLUMN nomor_tahun_perizinan_pihak_ketiga TEXT NULL AFTER pihak_ketiga_berizin;
ALTER TABLE rumah_sakit ADD COLUMN masa_berlaku_mou_pihak_ketiga TEXT NULL AFTER nomor_tahun_perizinan_pihak_ketiga;

-- Setelah 6003a (1 pertanyaan)
ALTER TABLE rumah_sakit ADD COLUMN nomor_tahun_perizinan_6003a TEXT NULL AFTER `6003a`;

-- Setelah 6003b (1 pertanyaan)
ALTER TABLE rumah_sakit ADD COLUMN parameter_limbah_kendala TEXT NULL AFTER `6003b`;

-- Setelah 7005 (1 pertanyaan dengan 2 pilihan)
ALTER TABLE rumah_sakit ADD COLUMN penyelenggaraan_linen_internal ENUM('0', '1') DEFAULT '0' AFTER `7005`;
ALTER TABLE rumah_sakit ADD COLUMN penyelenggaraan_linen_eksternal ENUM('0', '1') DEFAULT '0' AFTER penyelenggaraan_linen_internal;

-- Setelah 9003 (1 pertanyaan dengan 3 pilihan)
ALTER TABLE rumah_sakit ADD COLUMN jenis_rs_tipe_ab ENUM('0', '1') DEFAULT '0' AFTER `9003`;
ALTER TABLE rumah_sakit ADD COLUMN jenis_rs_tipe_cd ENUM('0', '1') DEFAULT '0' AFTER jenis_rs_tipe_ab;
ALTER TABLE rumah_sakit ADD COLUMN jenis_rs_tidak_ada_tenaga_kesling ENUM('0', '1') DEFAULT '0' AFTER jenis_rs_tipe_cd;

-- Catatan:
-- 1. Pastikan untuk backup database sebelum menjalankan query ini
-- 2. Sesuaikan nama kolom referensi (seperti `2005i`, `6002d`, dll) dengan nama kolom yang sebenarnya ada di tabel
-- 3. Untuk pertanyaan dengan pilihan, menggunakan ENUM('0', '1') dimana '0' = tidak dipilih, '1' = dipilih
-- 4. Untuk pertanyaan teks bebas, menggunakan TEXT NULL