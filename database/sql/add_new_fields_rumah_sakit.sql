-- SQL Query untuk menambahkan kolom baru ke tabel rumah_sakit
-- Untuk pemeriksaan air hemodialisa dan limbah domestik

-- Tambah kolom untuk pemeriksaan air hemodialisa
ALTER TABLE `rumah_sakit` 
ADD COLUMN `f1005` INT NULL COMMENT 'Apakah melakukan pemeriksaan air hemodialisa?' AFTER `f1004`,
ADD COLUMN `f1006` INT NULL COMMENT 'Apakah melakukan pemeriksaan endotoksin Air Hemodialisa setiap bulan?' AFTER `f1005`,
ADD COLUMN `f1007` INT NULL COMMENT 'Apakah hasil endotoksin memenuhi 0,25 IU/ml' AFTER `f1006`;

-- Tambah kolom untuk limbah domestik
ALTER TABLE `rumah_sakit` 
ADD COLUMN `6001d` INT NULL COMMENT 'Melakukan penanganan limbah dengan 3R*' AFTER `6001c`,
ADD COLUMN `6001e` INT NULL COMMENT 'Pemilahan Limbah Domestik yang dilakukan*' AFTER `6001d`,
ADD COLUMN `6001f` INT NULL COMMENT 'Memiliki TPS limbah domestik*' AFTER `6001e`;

-- Query untuk menghapus kolom jika diperlukan rollback
-- ALTER TABLE `rumah_sakit` DROP COLUMN `f1005`, DROP COLUMN `f1006`, DROP COLUMN `f1007`, DROP COLUMN `6001d`, DROP COLUMN `6001e`, DROP COLUMN `6001f`;