-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 02 Agu 2026 pada 19.05
-- Versi server: 8.0.43
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rka_final`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba', 'i:2;', 1785690037),
('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba:timer', 'i:1785690037;', 1785690037);

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jabatan`
--

CREATE TABLE `jabatan` (
  `jabatanID` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan_code` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jabatan_name` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan_level` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `eselon` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `jabatan`
--

INSERT INTO `jabatan` (`jabatanID`, `jabatan_code`, `jabatan_name`, `jabatan_type`, `jabatan_level`, `eselon`) VALUES
('jbt00001', NULL, 'Pejabat Pimpinan Tinggi Utama', 'JPT', 'JPT_UTAMA', 'I'),
('jbt00002', NULL, 'Sekretaris Jenderal', 'JPT', 'JPT_MADYA', 'I'),
('jbt00003', NULL, 'Direktur Jenderal', 'JPT', 'JPT_MADYA', 'I'),
('jbt00004', NULL, 'Inspektur Jenderal', 'JPT', 'JPT_MADYA', 'I'),
('jbt00005', NULL, 'Kepala Badan', 'JPT', 'JPT_MADYA', 'I'),
('jbt00006', NULL, 'Staf Ahli Menteri', 'JPT', 'JPT_MADYA', 'I'),
('jbt00007', NULL, 'Kepala Biro', 'JPT', 'JPT_PRATAMA', 'II'),
('jbt00008', NULL, 'Direktur', 'JPT', 'JPT_PRATAMA', 'II'),
('jbt00009', NULL, 'Sekretaris Direktorat Jenderal', 'JPT', 'JPT_PRATAMA', 'II'),
('jbt00010', NULL, 'Sekretaris Badan', 'JPT', 'JPT_PRATAMA', 'II'),
('jbt00011', NULL, 'Sekretaris Inspektorat Jenderal', 'JPT', 'JPT_PRATAMA', 'II'),
('jbt00012', NULL, 'Kepala Pusat', 'JPT', 'JPT_PRATAMA', 'II'),
('jbt00013', NULL, 'Kepala Balai Besar', 'JPT', 'JPT_PRATAMA', 'II'),
('jbt00014', NULL, 'Kepala Bagian', 'ADMINISTRASI', 'ADMINISTRATOR', 'III'),
('jbt00015', NULL, 'Kepala Bidang', 'ADMINISTRASI', 'ADMINISTRATOR', 'III'),
('jbt00016', NULL, 'Kepala Subdirektorat', 'ADMINISTRASI', 'ADMINISTRATOR', 'III'),
('jbt00017', NULL, 'Kepala Balai', 'ADMINISTRASI', 'ADMINISTRATOR', 'III'),
('jbt00018', NULL, 'Kepala Subbagian', 'ADMINISTRASI', 'PENGAWAS', 'IV'),
('jbt00019', NULL, 'Kepala Seksi', 'ADMINISTRASI', 'PENGAWAS', 'IV'),
('jbt00020', NULL, 'Kepala Subbidang', 'ADMINISTRASI', 'PENGAWAS', 'IV'),
('jbt00021', NULL, 'Perencana Ahli Utama', 'FUNGSIONAL', 'AHLI_UTAMA', 'NON_ESELON'),
('jbt00022', NULL, 'Perencana Ahli Madya', 'FUNGSIONAL', 'AHLI_MADYA', 'NON_ESELON'),
('jbt00023', NULL, 'Perencana Ahli Muda', 'FUNGSIONAL', 'AHLI_MUDA', 'NON_ESELON'),
('jbt00024', NULL, 'Perencana Ahli Pertama', 'FUNGSIONAL', 'AHLI_PERTAMA', 'NON_ESELON'),
('jbt00025', NULL, 'Pranata Komputer Ahli Utama', 'FUNGSIONAL', 'AHLI_UTAMA', 'NON_ESELON'),
('jbt00026', NULL, 'Pranata Komputer Ahli Madya', 'FUNGSIONAL', 'AHLI_MADYA', 'NON_ESELON'),
('jbt00027', NULL, 'Pranata Komputer Ahli Muda', 'FUNGSIONAL', 'AHLI_MUDA', 'NON_ESELON'),
('jbt00028', NULL, 'Pranata Komputer Ahli Pertama', 'FUNGSIONAL', 'AHLI_PERTAMA', 'NON_ESELON'),
('jbt00029', NULL, 'Pranata Komputer Penyelia', 'FUNGSIONAL', 'PENYELIA', 'NON_ESELON'),
('jbt00030', NULL, 'Pranata Komputer Mahir', 'FUNGSIONAL', 'MAHIR', 'NON_ESELON'),
('jbt00031', NULL, 'Pranata Komputer Terampil', 'FUNGSIONAL', 'TERAMPIL', 'NON_ESELON'),
('jbt00032', NULL, 'Analis Kebijakan Ahli Utama', 'FUNGSIONAL', 'AHLI_UTAMA', 'NON_ESELON'),
('jbt00033', NULL, 'Analis Kebijakan Ahli Madya', 'FUNGSIONAL', 'AHLI_MADYA', 'NON_ESELON'),
('jbt00034', NULL, 'Analis Kebijakan Ahli Muda', 'FUNGSIONAL', 'AHLI_MUDA', 'NON_ESELON'),
('jbt00035', NULL, 'Analis Kebijakan Ahli Pertama', 'FUNGSIONAL', 'AHLI_PERTAMA', 'NON_ESELON'),
('jbt00036', NULL, 'Auditor Ahli Utama', 'FUNGSIONAL', 'AHLI_UTAMA', 'NON_ESELON'),
('jbt00037', NULL, 'Auditor Ahli Madya', 'FUNGSIONAL', 'AHLI_MADYA', 'NON_ESELON'),
('jbt00038', NULL, 'Auditor Ahli Muda', 'FUNGSIONAL', 'AHLI_MUDA', 'NON_ESELON'),
('jbt00039', NULL, 'Auditor Ahli Pertama', 'FUNGSIONAL', 'AHLI_PERTAMA', 'NON_ESELON'),
('jbt00040', NULL, 'Analis Pengelolaan Keuangan APBN Ahli Madya', 'FUNGSIONAL', 'AHLI_MADYA', 'NON_ESELON'),
('jbt00041', NULL, 'Analis Pengelolaan Keuangan APBN Ahli Muda', 'FUNGSIONAL', 'AHLI_MUDA', 'NON_ESELON'),
('jbt00042', NULL, 'Analis Pengelolaan Keuangan APBN Ahli Pertama', 'FUNGSIONAL', 'AHLI_PERTAMA', 'NON_ESELON'),
('jbt00043', NULL, 'Pengelola Pengadaan Barang/Jasa Ahli Madya', 'FUNGSIONAL', 'AHLI_MADYA', 'NON_ESELON'),
('jbt00044', NULL, 'Pengelola Pengadaan Barang/Jasa Ahli Muda', 'FUNGSIONAL', 'AHLI_MUDA', 'NON_ESELON'),
('jbt00045', NULL, 'Pengelola Pengadaan Barang/Jasa Ahli Pertama', 'FUNGSIONAL', 'AHLI_PERTAMA', 'NON_ESELON'),
('jbt00046', NULL, 'Arsiparis Ahli Utama', 'FUNGSIONAL', 'AHLI_UTAMA', 'NON_ESELON'),
('jbt00047', NULL, 'Arsiparis Ahli Madya', 'FUNGSIONAL', 'AHLI_MADYA', 'NON_ESELON'),
('jbt00048', NULL, 'Arsiparis Ahli Muda', 'FUNGSIONAL', 'AHLI_MUDA', 'NON_ESELON'),
('jbt00049', NULL, 'Arsiparis Ahli Pertama', 'FUNGSIONAL', 'AHLI_PERTAMA', 'NON_ESELON'),
('jbt00050', NULL, 'Arsiparis Penyelia', 'FUNGSIONAL', 'PENYELIA', 'NON_ESELON'),
('jbt00051', NULL, 'Arsiparis Mahir', 'FUNGSIONAL', 'MAHIR', 'NON_ESELON'),
('jbt00052', NULL, 'Arsiparis Terampil', 'FUNGSIONAL', 'TERAMPIL', 'NON_ESELON'),
('jbt00053', NULL, 'Pranata Hubungan Masyarakat Ahli Madya', 'FUNGSIONAL', 'AHLI_MADYA', 'NON_ESELON'),
('jbt00054', NULL, 'Pranata Hubungan Masyarakat Ahli Muda', 'FUNGSIONAL', 'AHLI_MUDA', 'NON_ESELON'),
('jbt00055', NULL, 'Pranata Hubungan Masyarakat Ahli Pertama', 'FUNGSIONAL', 'AHLI_PERTAMA', 'NON_ESELON'),
('jbt00056', NULL, 'Pranata Hubungan Masyarakat Penyelia', 'FUNGSIONAL', 'PENYELIA', 'NON_ESELON'),
('jbt00057', NULL, 'Pranata Hubungan Masyarakat Mahir', 'FUNGSIONAL', 'MAHIR', 'NON_ESELON'),
('jbt00058', NULL, 'Pranata Hubungan Masyarakat Terampil', 'FUNGSIONAL', 'TERAMPIL', 'NON_ESELON'),
('jbt00059', NULL, 'Statistisi Ahli Madya', 'FUNGSIONAL', 'AHLI_MADYA', 'NON_ESELON'),
('jbt00060', NULL, 'Statistisi Ahli Muda', 'FUNGSIONAL', 'AHLI_MUDA', 'NON_ESELON'),
('jbt00061', NULL, 'Statistisi Ahli Pertama', 'FUNGSIONAL', 'AHLI_PERTAMA', 'NON_ESELON'),
('jbt00062', NULL, 'Statistisi Penyelia', 'FUNGSIONAL', 'PENYELIA', 'NON_ESELON'),
('jbt00063', NULL, 'Statistisi Mahir', 'FUNGSIONAL', 'MAHIR', 'NON_ESELON'),
('jbt00064', NULL, 'Statistisi Terampil', 'FUNGSIONAL', 'TERAMPIL', 'NON_ESELON'),
('jbt00065', NULL, 'Analis Sumber Daya Manusia Aparatur Ahli Madya', 'FUNGSIONAL', 'AHLI_MADYA', 'NON_ESELON'),
('jbt00066', NULL, 'Analis Sumber Daya Manusia Aparatur Ahli Muda', 'FUNGSIONAL', 'AHLI_MUDA', 'NON_ESELON'),
('jbt00067', NULL, 'Analis Sumber Daya Manusia Aparatur Ahli Pertama', 'FUNGSIONAL', 'AHLI_PERTAMA', 'NON_ESELON'),
('jbt00068', NULL, 'Pengawas Perdagangan Ahli Madya', 'FUNGSIONAL', 'AHLI_MADYA', 'NON_ESELON'),
('jbt00069', NULL, 'Pengawas Perdagangan Ahli Muda', 'FUNGSIONAL', 'AHLI_MUDA', 'NON_ESELON'),
('jbt00070', NULL, 'Pengawas Perdagangan Ahli Pertama', 'FUNGSIONAL', 'AHLI_PERTAMA', 'NON_ESELON'),
('jbt00071', NULL, 'Penera Ahli Madya', 'FUNGSIONAL', 'AHLI_MADYA', 'NON_ESELON'),
('jbt00072', NULL, 'Penera Ahli Muda', 'FUNGSIONAL', 'AHLI_MUDA', 'NON_ESELON'),
('jbt00073', NULL, 'Penera Ahli Pertama', 'FUNGSIONAL', 'AHLI_PERTAMA', 'NON_ESELON'),
('jbt00074', NULL, 'Penera Penyelia', 'FUNGSIONAL', 'PENYELIA', 'NON_ESELON'),
('jbt00075', NULL, 'Penera Mahir', 'FUNGSIONAL', 'MAHIR', 'NON_ESELON'),
('jbt00076', NULL, 'Penera Terampil', 'FUNGSIONAL', 'TERAMPIL', 'NON_ESELON'),
('jbt00077', NULL, 'Analis Perencanaan', 'PELAKSANA', 'PELAKSANA', 'NON_ESELON'),
('jbt00078', NULL, 'Analis Program dan Anggaran', 'PELAKSANA', 'PELAKSANA', 'NON_ESELON'),
('jbt00079', NULL, 'Analis Data dan Informasi', 'PELAKSANA', 'PELAKSANA', 'NON_ESELON'),
('jbt00080', NULL, 'Pengolah Data dan Informasi', 'PELAKSANA', 'PELAKSANA', 'NON_ESELON'),
('jbt00081', NULL, 'Pengadministrasi Umum', 'PELAKSANA', 'PELAKSANA', 'NON_ESELON'),
('jbt00082', NULL, 'Pengadministrasi Perencanaan dan Program', 'PELAKSANA', 'PELAKSANA', 'NON_ESELON'),
('jbt00083', NULL, 'Pengadministrasi Keuangan', 'PELAKSANA', 'PELAKSANA', 'NON_ESELON'),
('jbt00084', NULL, 'Verifikator Keuangan', 'PELAKSANA', 'PELAKSANA', 'NON_ESELON'),
('jbt00085', NULL, 'Penyusun Bahan Kebijakan', 'PELAKSANA', 'PELAKSANA', 'NON_ESELON'),
('jbt00086', NULL, 'Penyusun Bahan Program dan Anggaran', 'PELAKSANA', 'PELAKSANA', 'NON_ESELON'),
('jbt00087', NULL, 'Petugas Pengelola Dokumen', 'PELAKSANA', 'PELAKSANA', 'NON_ESELON');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `roles`
--

CREATE TABLE `roles` (
  `roleID` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `roles`
--

INSERT INTO `roles` (`roleID`, `role_name`) VALUES
('role0001', 'SUPERADMIN'),
('role0002', 'RESEARCHER'),
('role0003', 'LEADER');

-- --------------------------------------------------------

--
-- Struktur dari tabel `satker`
--

CREATE TABLE `satker` (
  `satkerID` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `satker_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `satker_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `satker_type` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `unitID` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('LDbcrxCrbZfN7ivLSSWt4mvZE8gKW1zzUXe4Xkno', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRkN4Wk52V3hYcHdqQ1FsQ2k1U1p0RDdOY3ZpdXNORXVYQVhiVmplUCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1785690064),
('OEQmZRX0ygrFi3s77sQA7HPiR7J3Y0AhG7wOP2vg', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZzY3TnJjYWdrWjllZnRJVXp1QXk5MU5VRG1rblFHblZtWk9OMFNQRyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1785687534),
('vLJNiiAbor2UFtbE7Ui1owByFysqNlMWt3P2tAJb', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 'YTozOntzOjY6Il9mbGFzaCI7YToyOntzOjM6Im5ldyI7YTowOnt9czozOiJvbGQiO2E6MDp7fX1zOjY6Il90b2tlbiI7czo0MDoiT0J1RkFvQkhreXlDbWZVdWxIb2tQdVRPd0VHNlIwd3VMMk9ZSWloZyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1785689963),
('wd5l9K5G8TaXokooxDp0OYJdVlrWeMwzlWnAIkqg', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 'YTozOntzOjY6Il9mbGFzaCI7YToyOntzOjM6Im5ldyI7YTowOnt9czozOiJvbGQiO2E6MDp7fX1zOjY6Il90b2tlbiI7czo0MDoiMkd0RHREbzUwaTdJR1B3MXVGR2hEOE01Vk14OGZJbTlnNEY5WnFkSiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7Tjt9fQ==', 1785690281),
('wNaQekHVFuTWiACfEksJvbm1RCmG6pkJS8gyMJen', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOHhxYU1FRUZZZzlPc2Z2OXpIWVdxQ3g1Ykx3ODBma1ZDWGFjU3FRZiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1785689909);

-- --------------------------------------------------------

--
-- Struktur dari tabel `unit`
--

CREATE TABLE `unit` (
  `unitID` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_code` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parentUnitID` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `userID` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip` varchar(18) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `is_data_confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `data_confirmed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `roleID` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jabatanID` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unitID` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `satkerID` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`userID`, `username`, `password`, `name`, `nip`, `email`, `status`, `email_verified_at`, `last_login_at`, `is_data_confirmed`, `data_confirmed_at`, `created_at`, `updated_at`, `deleted_at`, `roleID`, `jabatanID`, `unitID`, `satkerID`) VALUES
('usr00001', 'Dhimas', '$2y$12$vdNnlca88I7fsc2eyilqLu5jwzv/sD7vZ6tAxDHJg7DwuN1ktp/cO', 'M dhimas hafizh', '111111111111111111', 'dhmzz.hfzh@gmail.com', 'active', '2026-08-02 17:01:04', '2026-08-02 17:01:35', 1, '2026-08-02 17:00:37', '2026-08-02 16:57:58', '2026-08-02 17:01:35', NULL, 'role0002', 'jbt00019', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_otps`
--

CREATE TABLE `user_otps` (
  `otpID` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `userID` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `otp_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purpose` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expired_at` timestamp NOT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `attempt_count` int NOT NULL DEFAULT '0',
  `max_attempt` int NOT NULL DEFAULT '5',
  `is_used` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `user_otps`
--

INSERT INTO `user_otps` (`otpID`, `userID`, `otp_hash`, `purpose`, `expired_at`, `verified_at`, `attempt_count`, `max_attempt`, `is_used`, `created_at`, `updated_at`) VALUES
('otp00001', 'usr00001', '$2y$12$7adc0mtpiW7YNTcEw7/bD.dgQpapMNWJoKmlItSXQTegCDdpkRB/K', 'register_verification', '2026-08-02 17:08:08', '2026-08-02 16:58:28', 0, 5, 1, '2026-08-02 16:58:08', '2026-08-02 16:58:28'),
('otp00002', 'usr00001', '$2y$12$tXOAOGoGquzwdvJHAKJhS.GaNY.VYSxzhA8YAgE1NOJ.Iv8wmhMzS', 'register_verification', '2026-08-02 17:10:37', '2026-08-02 17:01:04', 0, 5, 1, '2026-08-02 17:00:37', '2026-08-02 17:01:04');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`jabatanID`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`roleID`);

--
-- Indeks untuk tabel `satker`
--
ALTER TABLE `satker`
  ADD PRIMARY KEY (`satkerID`),
  ADD KEY `unitID` (`unitID`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `unit`
--
ALTER TABLE `unit`
  ADD PRIMARY KEY (`unitID`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `users_email_unique` (`password`),
  ADD KEY `roleID` (`roleID`,`jabatanID`,`unitID`,`satkerID`),
  ADD KEY `jabatanID` (`jabatanID`),
  ADD KEY `unitID` (`unitID`),
  ADD KEY `satkerID` (`satkerID`);

--
-- Indeks untuk tabel `user_otps`
--
ALTER TABLE `user_otps`
  ADD PRIMARY KEY (`otpID`),
  ADD KEY `idx_user_otps_userID` (`userID`),
  ADD KEY `idx_user_otps_purpose` (`purpose`),
  ADD KEY `idx_user_otps_expired_at` (`expired_at`),
  ADD KEY `idx_user_otps_is_used` (`is_used`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `satker`
--
ALTER TABLE `satker`
  ADD CONSTRAINT `satker_ibfk_1` FOREIGN KEY (`unitID`) REFERENCES `unit` (`unitID`);

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`roleID`) REFERENCES `roles` (`roleID`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`jabatanID`) REFERENCES `jabatan` (`jabatanID`),
  ADD CONSTRAINT `users_ibfk_3` FOREIGN KEY (`unitID`) REFERENCES `unit` (`unitID`),
  ADD CONSTRAINT `users_ibfk_4` FOREIGN KEY (`satkerID`) REFERENCES `satker` (`satkerID`);

--
-- Ketidakleluasaan untuk tabel `user_otps`
--
ALTER TABLE `user_otps`
  ADD CONSTRAINT `fk_user_otps_user` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
