-- Query SQL untuk memperbaiki kolom rumah_sakit agar sesuai dengan form

-- 1. Ubah pengelolaan_pangan dari 2 kolom ENUM menjadi 1 kolom VARCHAR
-- Hapus kolom lama jika ada
ALTER TABLE rumah_sakit DROP COLUMN IF EXISTS pengelolaan_pangan_internal_rs;
ALTER TABLE rumah_sakit DROP COLUMN IF EXISTS pengelolaan_pangan_pihak_ketiga;

-- Tambah kolom baru yang sesuai dengan form
ALTER TABLE rumah_sakit ADD COLUMN pengelolaan_pangan_oleh VARCHAR(50) NULL AFTER `2005i`;

-- 2. Ubah penyelenggaraan_linen dari 2 kolom ENUM menjadi 1 kolom VARCHAR  
-- Hapus kolom lama jika ada
ALTER TABLE rumah_sakit DROP COLUMN IF EXISTS penyelenggaraan_linen_internal;
ALTER TABLE rumah_sakit DROP COLUMN IF EXISTS penyelenggaraan_linen_eksternal;

-- Tambah kolom baru yang sesuai dengan form
ALTER TABLE rumah_sakit ADD COLUMN penyelenggaraan_linen_rs_oleh VARCHAR(50) NULL AFTER `7005`;

-- 3. Ubah jenis_rs dari 3 kolom ENUM menjadi 1 kolom VARCHAR
-- Hapus kolom lama jika ada
ALTER TABLE rumah_sakit DROP COLUMN IF EXISTS jenis_rs_tipe_ab;
ALTER TABLE rumah_sakit DROP COLUMN IF EXISTS jenis_rs_tipe_cd;
ALTER TABLE rumah_sakit DROP COLUMN IF EXISTS jenis_rs_tidak_ada_tenaga_kesling;

-- Tambah kolom baru yang sesuai dengan form
ALTER TABLE rumah_sakit ADD COLUMN jenis_rs VARCHAR(100) NULL AFTER `9003`;

-- 4. Kolom-kolom lain yang sudah benar (tetap sama)
ALTER TABLE rumah_sakit ADD COLUMN IF NOT EXISTS hasil_pengukuran_pm25 TEXT NULL AFTER `2005i`;
ALTER TABLE rumah_sakit ADD COLUMN IF NOT EXISTS nama_pest_control TEXT NULL AFTER `4006e`;
ALTER TABLE rumah_sakit ADD COLUMN IF NOT EXISTS nomor_izin_tahun_pest_control TEXT NULL AFTER nama_pest_control;
ALTER TABLE rumah_sakit ADD COLUMN IF NOT EXISTS nomor_tahun_perizinan_tps_lb3 TEXT NULL AFTER `6002d`;
ALTER TABLE rumah_sakit ADD COLUMN IF NOT EXISTS pihak_ketiga_berizin TEXT NULL AFTER nomor_tahun_perizinan_tps_lb3;
ALTER TABLE rumah_sakit ADD COLUMN IF NOT EXISTS nomor_tahun_perizinan_pihak_ketiga TEXT NULL AFTER pihak_ketiga_berizin;
ALTER TABLE rumah_sakit ADD COLUMN IF NOT EXISTS masa_berlaku_mou_pihak_ketiga TEXT NULL AFTER nomor_tahun_perizinan_pihak_ketiga;
ALTER TABLE rumah_sakit ADD COLUMN IF NOT EXISTS nomor_tahun_perizinan_6003a TEXT NULL AFTER `6003a`;
ALTER TABLE rumah_sakit ADD COLUMN IF NOT EXISTS parameter_limbah_kendala TEXT NULL AFTER `6003b`;

-- Catatan:
-- Sekarang struktur database akan sesuai dengan form:
-- - pengelolaan_pangan_oleh: 'Internal RS' atau 'Pihak Ketiga'
-- - penyelenggaraan_linen_rs_oleh: 'Internal RS' atau 'Eksternal RS'  
-- - jenis_rs: 'Tipe A dan B', 'Tipe C dan D', atau 'Tidak ada Tenaga Kesehatan Lingkungan'
