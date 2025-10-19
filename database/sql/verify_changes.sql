-- ============================================================================
-- SQL Script VERIFIKASI - Cek Tipe Data Kolom
-- Database: dsimfoniku / siskl_depok
-- Tanggal: 2025-10-18
-- ============================================================================
-- 
-- Script ini untuk memverifikasi apakah perubahan TINYINT ke INT berhasil
-- Jalankan script ini SETELAH menjalankan change_tinyint_to_int.sql
-- ============================================================================

USE siskl_depok;

-- ============================================================================
-- VERIFIKASI 1: Cek semua kolom skor, u004, u005, u009
-- ============================================================================
SELECT 
    TABLE_NAME as 'Tabel', 
    COLUMN_NAME as 'Kolom', 
    DATA_TYPE as 'Tipe Data',
    COLUMN_TYPE as 'Detail Tipe',
    IS_NULLABLE as 'Nullable',
    COLUMN_DEFAULT as 'Default'
FROM 
    INFORMATION_SCHEMA.COLUMNS 
WHERE 
    TABLE_SCHEMA = 'siskl_depok' 
    AND COLUMN_NAME IN ('skor', 'u004', 'u005', 'u009')
ORDER BY 
    TABLE_NAME, COLUMN_NAME;

-- ============================================================================
-- VERIFIKASI 2: Hitung jumlah kolom per tipe data
-- ============================================================================
SELECT 
    DATA_TYPE as 'Tipe Data',
    COUNT(*) as 'Jumlah Kolom'
FROM 
    INFORMATION_SCHEMA.COLUMNS 
WHERE 
    TABLE_SCHEMA = 'siskl_depok' 
    AND COLUMN_NAME IN ('skor', 'u004', 'u005', 'u009')
GROUP BY 
    DATA_TYPE
ORDER BY 
    DATA_TYPE;

-- Hasil yang diharapkan setelah change_tinyint_to_int.sql:
-- DATA_TYPE | JUMLAH
-- ----------|-------
-- int       | 39-40 (semua sudah INT)

-- ============================================================================
-- VERIFIKASI 3: Detail per tabel
-- ============================================================================

-- Tabel Jasa Boga Katering
SELECT 'jasa_boga_katering' as Tabel, COLUMN_NAME, DATA_TYPE 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'siskl_depok' 
  AND TABLE_NAME = 'jasa_boga_katering' 
  AND COLUMN_NAME IN ('skor', 'u004', 'u005');

-- Tabel Restoran
SELECT 'restoran' as Tabel, COLUMN_NAME, DATA_TYPE 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'siskl_depok' 
  AND TABLE_NAME = 'restoran' 
  AND COLUMN_NAME IN ('skor', 'u004', 'u005');

-- Tabel TPP Tertentu
SELECT 'tpp_tertentu' as Tabel, COLUMN_NAME, DATA_TYPE 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'siskl_depok' 
  AND TABLE_NAME = 'tpp_tertentu' 
  AND COLUMN_NAME = 'skor';

-- Tabel Depot Air Minum
SELECT 'depot_air_minum' as Tabel, COLUMN_NAME, DATA_TYPE 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'siskl_depok' 
  AND TABLE_NAME = 'depot_air_minum' 
  AND COLUMN_NAME IN ('skor', 'u004', 'u005');

-- Tabel Gerai Pangan Jajanan
SELECT 'gerai_pangan_jajanan' as Tabel, COLUMN_NAME, DATA_TYPE 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'siskl_depok' 
  AND TABLE_NAME = 'gerai_pangan_jajanan' 
  AND COLUMN_NAME IN ('skor', 'u004', 'u005');

-- Tabel Gerai Jajanan Keliling
SELECT 'gerai_jajanan_keliling' as Tabel, COLUMN_NAME, DATA_TYPE 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'siskl_depok' 
  AND TABLE_NAME = 'gerai_jajanan_keliling' 
  AND COLUMN_NAME = 'skor';

-- Tabel Tempat Olahraga
SELECT 'tempat_olahraga' as Tabel, COLUMN_NAME, DATA_TYPE 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'siskl_depok' 
  AND TABLE_NAME = 'tempat_olahraga' 
  AND COLUMN_NAME = 'skor';

-- Tabel Renang Pemandian
SELECT 'renang_pemandian' as Tabel, COLUMN_NAME, DATA_TYPE 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'siskl_depok' 
  AND TABLE_NAME = 'renang_pemandian' 
  AND COLUMN_NAME = 'skor';

-- Tabel Tempat Rekreasi
SELECT 'tempat_rekreasi' as Tabel, COLUMN_NAME, DATA_TYPE 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'siskl_depok' 
  AND TABLE_NAME = 'tempat_rekreasi' 
  AND COLUMN_NAME = 'skor';

-- Tabel Akomodasi
SELECT 'akomodasi' as Tabel, COLUMN_NAME, DATA_TYPE 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'siskl_depok' 
  AND TABLE_NAME = 'akomodasi' 
  AND COLUMN_NAME = 'skor';

-- Tabel Akomodasi Lain
SELECT 'akomodasi_lain' as Tabel, COLUMN_NAME, DATA_TYPE 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'siskl_depok' 
  AND TABLE_NAME = 'akomodasi_lain' 
  AND COLUMN_NAME = 'skor';

-- Tabel Sumur Gali
SELECT 'sumur_gali' as Tabel, COLUMN_NAME, DATA_TYPE 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'siskl_depok' 
  AND TABLE_NAME = 'sumur_gali' 
  AND COLUMN_NAME = 'skor';

-- Tabel Sumur Bor Pompa
SELECT 'sumur_bor_pompa' as Tabel, COLUMN_NAME, DATA_TYPE 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'siskl_depok' 
  AND TABLE_NAME = 'sumur_bor_pompa' 
  AND COLUMN_NAME = 'skor';

-- Tabel Perpipaan
SELECT 'perpipaan' as Tabel, COLUMN_NAME, DATA_TYPE 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'siskl_depok' 
  AND TABLE_NAME = 'perpipaan' 
  AND COLUMN_NAME = 'skor';

-- Tabel Perpipaan Non PDAM
SELECT 'perpipaan_non_pdam' as Tabel, COLUMN_NAME, DATA_TYPE 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'siskl_depok' 
  AND TABLE_NAME = 'perpipaan_non_pdam' 
  AND COLUMN_NAME = 'skor';

-- Tabel Perlindungan Mata Air
SELECT 'perlindungan_mata_air' as Tabel, COLUMN_NAME, DATA_TYPE 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'siskl_depok' 
  AND TABLE_NAME = 'perlindungan_mata_air' 
  AND COLUMN_NAME = 'skor';

-- Tabel Penyimpanan Air Hujan
SELECT 'penyimpanan_air_hujan' as Tabel, COLUMN_NAME, DATA_TYPE 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'siskl_depok' 
  AND TABLE_NAME = 'penyimpanan_air_hujan' 
  AND COLUMN_NAME = 'skor';

-- Tabel Rumah Makan
SELECT 'rumah_makan' as Tabel, COLUMN_NAME, DATA_TYPE 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'siskl_depok' 
  AND TABLE_NAME = 'rumah_makan' 
  AND COLUMN_NAME IN ('skor', 'u004', 'u005');

-- Tabel Kantin
SELECT 'kantin' as Tabel, COLUMN_NAME, DATA_TYPE 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'siskl_depok' 
  AND TABLE_NAME = 'kantin' 
  AND COLUMN_NAME IN ('skor', 'u004', 'u005', 'u009');

-- Tabel Gerai Kantin
SELECT 'gerai_kantin' as Tabel, COLUMN_NAME, DATA_TYPE 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'siskl_depok' 
  AND TABLE_NAME = 'gerai_kantin' 
  AND COLUMN_NAME = 'skor';

-- Tabel Sekolah
SELECT 'sekolah' as Tabel, COLUMN_NAME, DATA_TYPE 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'siskl_depok' 
  AND TABLE_NAME = 'sekolah' 
  AND COLUMN_NAME IN ('skor', 'u005');

-- Tabel Puskesmas
SELECT 'puskesmas' as Tabel, COLUMN_NAME, DATA_TYPE 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'siskl_depok' 
  AND TABLE_NAME = 'puskesmas' 
  AND COLUMN_NAME IN ('skor', 'u004');

-- Tabel Pasar
SELECT 'pasar' as Tabel, COLUMN_NAME, DATA_TYPE 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'siskl_depok' 
  AND TABLE_NAME = 'pasar' 
  AND COLUMN_NAME = 'skor';

-- Tabel Pasar Internal
SELECT 'pasar_internal' as Tabel, COLUMN_NAME, DATA_TYPE 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'siskl_depok' 
  AND TABLE_NAME = 'pasar_internal' 
  AND COLUMN_NAME = 'skor';

-- Tabel Stasiun
SELECT 'stasiun' as Tabel, COLUMN_NAME, DATA_TYPE 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'siskl_depok' 
  AND TABLE_NAME = 'stasiun' 
  AND COLUMN_NAME = 'skor';

-- ============================================================================
-- VERIFIKASI 4: Cek apakah masih ada TINYINT
-- ============================================================================
SELECT 
    TABLE_NAME as 'Tabel Dengan TINYINT', 
    COLUMN_NAME as 'Kolom', 
    COLUMN_TYPE as 'Tipe'
FROM 
    INFORMATION_SCHEMA.COLUMNS 
WHERE 
    TABLE_SCHEMA = 'siskl_depok' 
    AND COLUMN_NAME IN ('skor', 'u004', 'u005', 'u009')
    AND DATA_TYPE = 'tinyint';

-- Hasil yang diharapkan: 0 rows (tidak ada TINYINT lagi)

-- ============================================================================
-- VERIFIKASI 5: Cek sample data untuk memastikan data tidak hilang
-- ============================================================================

-- Sample dari jasa_boga_katering
SELECT 'jasa_boga_katering' as Tabel, COUNT(*) as Total, MAX(skor) as Skor_Max, MIN(skor) as Skor_Min
FROM jasa_boga_katering;

-- Sample dari restoran
SELECT 'restoran' as Tabel, COUNT(*) as Total, MAX(skor) as Skor_Max, MIN(skor) as Skor_Min
FROM restoran;

-- Sample dari kantin
SELECT 'kantin' as Tabel, COUNT(*) as Total, MAX(skor) as Skor_Max, MIN(skor) as Skor_Min
FROM kantin;

-- ============================================================================
-- SELESAI VERIFIKASI
-- ============================================================================
-- Jika semua query di atas menunjukkan DATA_TYPE = 'int', 
-- maka perubahan berhasil!
-- ============================================================================
