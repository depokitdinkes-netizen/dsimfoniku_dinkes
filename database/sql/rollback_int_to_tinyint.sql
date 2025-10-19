-- ============================================================================
-- SQL Script ROLLBACK - Mengembalikan kolom INT menjadi TINYINT
-- Database: dsimfoniku / siskl_depok
-- Tanggal: 2025-10-18
-- ============================================================================
-- 
-- PERINGATAN: 
-- Script ini adalah ROLLBACK dari change_tinyint_to_int.sql
-- Hanya gunakan jika Anda ingin mengembalikan perubahan
-- TINYINT memiliki range -128 sampai 127 (signed) atau 0-255 (unsigned)
-- Pastikan data Anda tidak melebihi range tersebut!
-- ============================================================================

USE siskl_depok;

-- ============================================================================
-- 1. TABEL: jasa_boga_katering
-- ============================================================================
ALTER TABLE `jasa_boga_katering` 
  MODIFY COLUMN `skor` TINYINT NOT NULL,
  MODIFY COLUMN `u004` TINYINT NOT NULL,
  MODIFY COLUMN `u005` TINYINT NOT NULL;

-- ============================================================================
-- 2. TABEL: restoran
-- ============================================================================
ALTER TABLE `restoran` 
  MODIFY COLUMN `skor` TINYINT NOT NULL,
  MODIFY COLUMN `u004` TINYINT NOT NULL,
  MODIFY COLUMN `u005` TINYINT NOT NULL;

-- ============================================================================
-- 3. TABEL: tpp_tertentu
-- ============================================================================
ALTER TABLE `tpp_tertentu` 
  MODIFY COLUMN `skor` TINYINT NOT NULL;

-- ============================================================================
-- 4. TABEL: depot_air_minum
-- ============================================================================
ALTER TABLE `depot_air_minum` 
  MODIFY COLUMN `skor` TINYINT NOT NULL,
  MODIFY COLUMN `u004` TINYINT NOT NULL,
  MODIFY COLUMN `u005` TINYINT NOT NULL;

-- ============================================================================
-- 5. TABEL: gerai_pangan_jajanan
-- ============================================================================
ALTER TABLE `gerai_pangan_jajanan` 
  MODIFY COLUMN `skor` TINYINT NOT NULL,
  MODIFY COLUMN `u004` TINYINT NOT NULL,
  MODIFY COLUMN `u005` TINYINT NOT NULL;

-- ============================================================================
-- 6. TABEL: gerai_jajanan_keliling
-- ============================================================================
ALTER TABLE `gerai_jajanan_keliling` 
  MODIFY COLUMN `skor` TINYINT NOT NULL;

-- ============================================================================
-- 7. TABEL: tempat_olahraga
-- ============================================================================
ALTER TABLE `tempat_olahraga` 
  MODIFY COLUMN `skor` TINYINT NOT NULL;

-- ============================================================================
-- 8. TABEL: renang_pemandian
-- ============================================================================
ALTER TABLE `renang_pemandian` 
  MODIFY COLUMN `skor` TINYINT NOT NULL;

-- ============================================================================
-- 9. TABEL: tempat_rekreasi
-- ============================================================================
ALTER TABLE `tempat_rekreasi` 
  MODIFY COLUMN `skor` TINYINT NOT NULL;

-- ============================================================================
-- 10. TABEL: akomodasi
-- ============================================================================
ALTER TABLE `akomodasi` 
  MODIFY COLUMN `skor` TINYINT NOT NULL;

-- ============================================================================
-- 11. TABEL: akomodasi_lain
-- ============================================================================
ALTER TABLE `akomodasi_lain` 
  MODIFY COLUMN `skor` TINYINT NOT NULL;

-- ============================================================================
-- 12. TABEL: sumur_gali
-- ============================================================================
ALTER TABLE `sumur_gali` 
  MODIFY COLUMN `skor` TINYINT NOT NULL;

-- ============================================================================
-- 13. TABEL: sumur_bor_pompa
-- ============================================================================
ALTER TABLE `sumur_bor_pompa` 
  MODIFY COLUMN `skor` TINYINT NOT NULL;

-- ============================================================================
-- 14. TABEL: perpipaan
-- ============================================================================
ALTER TABLE `perpipaan` 
  MODIFY COLUMN `skor` TINYINT NOT NULL;

-- ============================================================================
-- 15. TABEL: perpipaan_non_pdam
-- ============================================================================
ALTER TABLE `perpipaan_non_pdam` 
  MODIFY COLUMN `skor` TINYINT NOT NULL;

-- ============================================================================
-- 16. TABEL: perlindungan_mata_air
-- ============================================================================
ALTER TABLE `perlindungan_mata_air` 
  MODIFY COLUMN `skor` TINYINT NOT NULL;

-- ============================================================================
-- 17. TABEL: penyimpanan_air_hujan
-- ============================================================================
ALTER TABLE `penyimpanan_air_hujan` 
  MODIFY COLUMN `skor` TINYINT NOT NULL;

-- ============================================================================
-- 18. TABEL: rumah_makan
-- ============================================================================
ALTER TABLE `rumah_makan` 
  MODIFY COLUMN `skor` TINYINT NOT NULL,
  MODIFY COLUMN `u004` TINYINT NOT NULL,
  MODIFY COLUMN `u005` TINYINT NOT NULL;

-- ============================================================================
-- 19. TABEL: kantin
-- ============================================================================
ALTER TABLE `kantin` 
  MODIFY COLUMN `skor` TINYINT NOT NULL,
  MODIFY COLUMN `u004` TINYINT NOT NULL,
  MODIFY COLUMN `u005` TINYINT NOT NULL,
  MODIFY COLUMN `u009` TINYINT NOT NULL;

-- ============================================================================
-- 20. TABEL: gerai_kantin
-- ============================================================================
ALTER TABLE `gerai_kantin` 
  MODIFY COLUMN `skor` TINYINT NOT NULL;

-- ============================================================================
-- 21. TABEL: sekolah
-- ============================================================================
ALTER TABLE `sekolah` 
  MODIFY COLUMN `skor` TINYINT NOT NULL,
  MODIFY COLUMN `u005` TINYINT NOT NULL;

-- ============================================================================
-- 22. TABEL: puskesmas
-- ============================================================================
ALTER TABLE `puskesmas` 
  MODIFY COLUMN `skor` TINYINT NOT NULL,
  MODIFY COLUMN `u004` TINYINT NOT NULL;

-- ============================================================================
-- 23. TABEL: pasar
-- ============================================================================
ALTER TABLE `pasar` 
  MODIFY COLUMN `skor` TINYINT NOT NULL DEFAULT 0;

-- ============================================================================
-- 24. TABEL: pasar_internal
-- ============================================================================
ALTER TABLE `pasar_internal` 
  MODIFY COLUMN `skor` TINYINT NOT NULL DEFAULT 0;

-- ============================================================================
-- 25. TABEL: stasiun
-- ============================================================================
ALTER TABLE `stasiun` 
  MODIFY COLUMN `skor` TINYINT NOT NULL;

-- ============================================================================
-- SELESAI ROLLBACK
-- ============================================================================
-- Semua kolom INT telah dikembalikan menjadi TINYINT
-- Total tabel yang dimodifikasi: 25 tabel
-- ============================================================================
