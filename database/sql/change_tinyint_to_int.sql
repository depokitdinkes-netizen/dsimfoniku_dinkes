-- ============================================================================
-- SQL Script untuk mengubah semua kolom TINYINT menjadi INT
-- Database: dsimfoniku / siskl_depok
-- Tanggal: 2025-10-18
-- ============================================================================
-- 
-- PENTING: 
-- 1. Backup database terlebih dahulu sebelum menjalankan script ini
-- 2. Jalankan script ini di phpMyAdmin atau MySQL client
-- 3. Script ini akan mengubah tipe data kolom tanpa menghapus data
-- ============================================================================


-- ============================================================================
-- 1. TABEL: jasa_boga_katering
-- ============================================================================
ALTER TABLE `jasa_boga_katering` 
  MODIFY COLUMN `skor` INT NOT NULL,
  MODIFY COLUMN `u004` INT NOT NULL,
  MODIFY COLUMN `u005` INT NOT NULL;

-- ============================================================================
-- 2. TABEL: restoran
-- ============================================================================
ALTER TABLE `restoran` 
  MODIFY COLUMN `skor` INT NOT NULL,
  MODIFY COLUMN `u004` INT NOT NULL,
  MODIFY COLUMN `u005` INT NOT NULL;

-- ============================================================================
-- 3. TABEL: tpp_tertentu
-- ============================================================================
ALTER TABLE `tpp_tertentu` 
  MODIFY COLUMN `skor` INT NOT NULL;

-- ============================================================================
-- 4. TABEL: depot_air_minum
-- ============================================================================
ALTER TABLE `depot_air_minum` 
  MODIFY COLUMN `skor` INT NOT NULL,
  MODIFY COLUMN `u004` INT NOT NULL,
  MODIFY COLUMN `u005` INT NOT NULL;

-- ============================================================================
-- 5. TABEL: gerai_pangan_jajanan
-- ============================================================================
ALTER TABLE `gerai_pangan_jajanan` 
  MODIFY COLUMN `skor` INT NOT NULL,
  MODIFY COLUMN `u004` INT NOT NULL,
  MODIFY COLUMN `u005` INT NOT NULL;

-- ============================================================================
-- 6. TABEL: gerai_jajanan_keliling
-- ============================================================================
ALTER TABLE `gerai_jajanan_keliling` 
  MODIFY COLUMN `skor` INT NOT NULL;

-- ============================================================================
-- 7. TABEL: tempat_olahraga
-- ============================================================================
ALTER TABLE `tempat_olahraga` 
  MODIFY COLUMN `skor` INT NOT NULL;

-- ============================================================================
-- 8. TABEL: renang_pemandian
-- ============================================================================
ALTER TABLE `renang_pemandian` 
  MODIFY COLUMN `skor` INT NOT NULL;

-- ============================================================================
-- 9. TABEL: tempat_rekreasi
-- ============================================================================
ALTER TABLE `tempat_rekreasi` 
  MODIFY COLUMN `skor` INT NOT NULL;

-- ============================================================================
-- 10. TABEL: akomodasi
-- ============================================================================
ALTER TABLE `akomodasi` 
  MODIFY COLUMN `skor` INT NOT NULL;

-- ============================================================================
-- 11. TABEL: akomodasi_lain
-- ============================================================================
ALTER TABLE `akomodasi_lain` 
  MODIFY COLUMN `skor` INT NOT NULL;

-- ============================================================================
-- 12. TABEL: sumur_gali
-- ============================================================================
ALTER TABLE `sumur_gali` 
  MODIFY COLUMN `skor` INT NOT NULL;

-- ============================================================================
-- 13. TABEL: sumur_bor_pompa
-- ============================================================================
ALTER TABLE `sumur_bor_pompa` 
  MODIFY COLUMN `skor` INT NOT NULL;

-- ============================================================================
-- 14. TABEL: perpipaan
-- ============================================================================
ALTER TABLE `perpipaan` 
  MODIFY COLUMN `skor` INT NOT NULL;

-- ============================================================================
-- 15. TABEL: perpipaan_non_pdam
-- ============================================================================
ALTER TABLE `perpipaan_non_pdam` 
  MODIFY COLUMN `skor` INT NOT NULL;

-- ============================================================================
-- 16. TABEL: perlindungan_mata_air
-- ============================================================================
ALTER TABLE `perlindungan_mata_air` 
  MODIFY COLUMN `skor` INT NOT NULL;

-- ============================================================================
-- 17. TABEL: penyimpanan_air_hujan
-- ============================================================================
ALTER TABLE `penyimpanan_air_hujan` 
  MODIFY COLUMN `skor` INT NOT NULL;

-- ============================================================================
-- 18. TABEL: rumah_makan
-- ============================================================================
ALTER TABLE `rumah_makan` 
  MODIFY COLUMN `skor` INT NOT NULL,
  MODIFY COLUMN `u004` INT NOT NULL,
  MODIFY COLUMN `u005` INT NOT NULL;

-- ============================================================================
-- 19. TABEL: kantin
-- ============================================================================
ALTER TABLE `kantin` 
  MODIFY COLUMN `skor` INT NOT NULL,
  MODIFY COLUMN `u004` INT NOT NULL,
  MODIFY COLUMN `u005` INT NOT NULL,
  MODIFY COLUMN `u009` INT NOT NULL;

-- ============================================================================
-- 20. TABEL: gerai_kantin
-- ============================================================================
ALTER TABLE `gerai_kantin` 
  MODIFY COLUMN `skor` INT NOT NULL;

-- ============================================================================
-- 21. TABEL: sekolah
-- ============================================================================
ALTER TABLE `sekolah` 
  MODIFY COLUMN `skor` INT NOT NULL,
  MODIFY COLUMN `u005` INT NOT NULL;

-- ============================================================================
-- 22. TABEL: puskesmas
-- ============================================================================
ALTER TABLE `puskesmas` 
  MODIFY COLUMN `skor` INT NOT NULL,
  MODIFY COLUMN `u004` INT NOT NULL;

-- ============================================================================
-- 23. TABEL: pasar
-- ============================================================================
ALTER TABLE `pasar` 
  MODIFY COLUMN `skor` INT NOT NULL DEFAULT 0;

-- ============================================================================
-- 24. TABEL: pasar_internal
-- ============================================================================
ALTER TABLE `pasar_internal` 
  MODIFY COLUMN `skor` INT NOT NULL DEFAULT 0;

-- ============================================================================
-- 25. TABEL: stasiun
-- ============================================================================
ALTER TABLE `stasiun` 
  MODIFY COLUMN `skor` INT NOT NULL;

-- ============================================================================
-- CATATAN KHUSUS: jobs table (attempts column)
-- Kolom attempts di tabel jobs menggunakan unsignedTinyInteger
-- Biasanya tidak perlu diubah karena hanya untuk tracking retry jobs
-- Namun jika ingin diubah, uncomment baris berikut:
-- ============================================================================
-- ALTER TABLE `jobs` 
--   MODIFY COLUMN `attempts` INT UNSIGNED NOT NULL;

-- ============================================================================
-- SELESAI
-- ============================================================================
-- Semua kolom TINYINT telah diubah menjadi INT
-- Total tabel yang dimodifikasi: 25 tabel
-- ============================================================================

-- Verifikasi perubahan (opsional - uncomment untuk melihat struktur):
-- SHOW COLUMNS FROM jasa_boga_katering WHERE Field IN ('skor', 'u004', 'u005');
-- SHOW COLUMNS FROM restoran WHERE Field IN ('skor', 'u004', 'u005');
-- SHOW COLUMNS FROM kantin WHERE Field IN ('skor', 'u004', 'u005', 'u009');
