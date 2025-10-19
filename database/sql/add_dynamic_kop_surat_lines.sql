-- SQL Query untuk menambahkan dynamic kop surat lines ke tabel users
-- Jalankan query ini langsung di phpMyAdmin atau MySQL client
-- Tanggal: 2025-10-15

-- 1. Ubah kolom baris5 menjadi nullable
ALTER TABLE `users` 
MODIFY COLUMN `baris5` VARCHAR(255) NULL;

-- 2. Tambahkan kolom baris6 dan sizebaris6
ALTER TABLE `users` 
ADD COLUMN `baris6` VARCHAR(255) NULL AFTER `baris5`,
ADD COLUMN `sizebaris6` VARCHAR(255) DEFAULT '13px' AFTER `baris6`;

-- 3. Tambahkan kolom baris7 dan sizebaris7
ALTER TABLE `users` 
ADD COLUMN `baris7` VARCHAR(255) NULL AFTER `sizebaris6`,
ADD COLUMN `sizebaris7` VARCHAR(255) DEFAULT '13px' AFTER `baris7`;

-- 4. Tambahkan kolom baris8 dan sizebaris8
ALTER TABLE `users` 
ADD COLUMN `baris8` VARCHAR(255) NULL AFTER `sizebaris7`,
ADD COLUMN `sizebaris8` VARCHAR(255) DEFAULT '13px' AFTER `baris8`;

-- 5. Tambahkan kolom baris9 dan sizebaris9
ALTER TABLE `users` 
ADD COLUMN `baris9` VARCHAR(255) NULL AFTER `sizebaris8`,
ADD COLUMN `sizebaris9` VARCHAR(255) DEFAULT '13px' AFTER `baris9`;

-- 6. Tambahkan kolom baris10 dan sizebaris10
ALTER TABLE `users` 
ADD COLUMN `baris10` VARCHAR(255) NULL AFTER `sizebaris9`,
ADD COLUMN `sizebaris10` VARCHAR(255) DEFAULT '13px' AFTER `baris10`;

-- Selesai! 
-- Kolom baru telah ditambahkan ke tabel users
