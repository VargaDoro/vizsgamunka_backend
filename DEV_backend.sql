-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: db2
-- Létrehozás ideje: 2026. Ápr 24. 07:54
-- Kiszolgáló verziója: 9.0.1
-- PHP verzió: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `DEV_backend`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `appointments`
--

CREATE TABLE `appointments` (
  `id` bigint UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `patient_id` bigint UNSIGNED NOT NULL,
  `appointment_time` datetime NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `appointments`
--

INSERT INTO `appointments` (`id`, `doctor_id`, `patient_id`, `appointment_time`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 6, '2026-04-10 09:00:00', 'booked', '2026-04-17 10:29:00', '2026-04-17 10:29:00'),
(2, 3, 7, '2026-04-10 10:00:00', 'booked', '2026-04-17 10:29:00', '2026-04-17 10:29:00'),
(3, 4, 6, '2026-04-11 11:00:00', 'booked', '2026-04-17 10:29:00', '2026-04-17 10:29:00'),
(4, 4, 7, '2026-04-14 14:30:00', 'booked', '2026-04-17 10:29:00', '2026-04-17 10:29:00'),
(5, 5, 6, '2026-04-15 08:30:00', 'booked', '2026-04-17 10:29:00', '2026-04-17 10:29:00'),
(6, 5, 7, '2026-04-16 13:00:00', 'booked', '2026-04-17 10:29:00', '2026-04-17 10:29:00');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `documents`
--

CREATE TABLE `documents` (
  `id` bigint UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `patient_id` bigint UNSIGNED NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `documents`
--

INSERT INTO `documents` (`id`, `doctor_id`, `patient_id`, `type`, `file_path`, `created_at`) VALUES
(1, 3, 1, 'Teszt dokumentum', 'documents/test.pdf', '2026-04-17 10:29:00'),
(2, 4, 6, 'Teszt dokumentum', 'documents/test.pdf', '2026-04-17 10:29:00'),
(3, 5, 7, 'Teszt dokumentum', 'documents/test.pdf', '2026-04-17 10:29:00'),
(4, 5, 8, 'Teszt dokumentum', 'documents/test.pdf', '2026-04-17 10:29:00'),
(5, 4, 9, 'Teszt dokumentum', 'documents/test.pdf', '2026-04-17 10:29:00'),
(6, 4, 10, 'Teszt dokumentum', 'documents/test.pdf', '2026-04-17 10:29:00'),
(7, 3, 11, 'Teszt dokumentum', 'documents/test.pdf', '2026-04-17 10:29:00'),
(8, 5, 12, 'Teszt dokumentum', 'documents/test.pdf', '2026-04-17 10:29:00'),
(9, 5, 13, 'Teszt dokumentum', 'documents/test.pdf', '2026-04-17 10:29:00');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `document_types`
--

CREATE TABLE `document_types` (
  `id` bigint UNSIGNED NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `document_types`
--

INSERT INTO `document_types` (`id`, `type`, `created_at`, `updated_at`) VALUES
(1, 'Lelet', '2026-04-17 10:29:00', '2026-04-17 10:29:00'),
(2, 'Recept', '2026-04-17 10:29:00', '2026-04-17 10:29:00'),
(3, 'Zarojelentes', '2026-04-17 10:29:00', '2026-04-17 10:29:00'),
(4, 'Beutalo', '2026-04-17 10:29:00', '2026-04-17 10:29:00'),
(5, 'Laboreredmeny', '2026-04-17 10:29:00', '2026-04-17 10:29:00'),
(6, 'Kepalkoto vizsgalat', '2026-04-17 10:29:00', '2026-04-17 10:29:00'),
(7, 'Korlap', '2026-04-17 10:29:00', '2026-04-17 10:29:00'),
(8, 'Egyeb', '2026-04-17 10:29:00', '2026-04-17 10:29:00');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_12_22_145237_create_office_locations_table', 1),
(5, '2025_12_22_145310_create_document_types_table', 1),
(6, '2025_12_22_145422_create_documents_table', 1),
(7, '2025_12_22_145537_create_prescriptions_table', 1),
(8, '2025_12_22_145602_create_appointments_table', 1),
(9, '2026_02_05_073224_create_personal_access_tokens_table', 1);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `office_locations`
--

CREATE TABLE `office_locations` (
  `id` bigint UNSIGNED NOT NULL,
  `room_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `building` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `office_locations`
--

INSERT INTO `office_locations` (`id`, `room_number`, `building`, `created_at`, `updated_at`) VALUES
(1, '13', '2. emelet', '2026-04-17 10:29:00', '2026-04-17 10:29:00'),
(2, '5', '1. emelet', '2026-04-17 10:29:00', '2026-04-17 10:29:00'),
(3, '101', 'Földszint', '2026-04-17 10:29:00', '2026-04-17 10:29:00');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 6, 'auth_token', '7123a3e60fdca25a426a126bd03694e947417d242eedebdbc70c5556d7d0f8d9', '[\"*\"]', NULL, NULL, '2026-04-17 10:29:55', '2026-04-17 10:29:55'),
(2, 'App\\Models\\User', 3, 'auth_token', '61b31f232d24557c0f1f7725a70548a4a8e27d01d76bf62fdde7bb8b9a7dee5e', '[\"*\"]', NULL, NULL, '2026-04-17 10:31:26', '2026-04-17 10:31:26'),
(3, 'App\\Models\\User', 6, 'auth_token', '2c90bad63928391888e026148f82e1512e20a6c70db9fe670364c8475045dbe0', '[\"*\"]', NULL, NULL, '2026-04-17 10:36:51', '2026-04-17 10:36:51'),
(4, 'App\\Models\\User', 6, 'auth_token', '8af88e0f84f73c538c484e3fe77fc109e2da780e07b999c2ce749b8c728d2c5a', '[\"*\"]', NULL, NULL, '2026-04-17 10:45:18', '2026-04-17 10:45:18');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `prescriptions`
--

CREATE TABLE `prescriptions` (
  `id` bigint UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `patient_id` bigint UNSIGNED NOT NULL,
  `medicine_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dosage` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `issued_at` date NOT NULL,
  `valid_until` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `prescriptions`
--

INSERT INTO `prescriptions` (`id`, `doctor_id`, `patient_id`, `medicine_name`, `dosage`, `issued_at`, `valid_until`, `created_at`, `updated_at`) VALUES
(1, 3, 6, 'Amlodipin', 'Napi 1x 5mg', '2026-03-01', '2026-06-01', '2026-04-17 10:29:00', '2026-04-17 10:29:00'),
(2, 3, 7, 'Metoprolol', 'Napi 2x 50mg', '2026-03-05', '2026-05-05', '2026-04-17 10:29:00', '2026-04-17 10:29:00'),
(3, 4, 6, 'Hidrokortison krem', 'Napi 2x vekonyan felvinni', '2026-03-10', '2026-04-10', '2026-04-17 10:29:00', '2026-04-17 10:29:00'),
(4, 4, 8, 'Doxiciklin', 'Napi 1x 100mg', '2026-03-15', '2026-04-15', '2026-04-17 10:29:00', '2026-04-17 10:29:00'),
(5, 5, 7, 'Sertralin', 'Napi 1x 50mg', '2026-02-20', '2026-08-20', '2026-04-17 10:29:00', '2026-04-17 10:29:00'),
(6, 5, 9, 'Alprazolam', 'Napi 2x 0.25mg', '2026-03-20', '2026-06-20', '2026-04-17 10:29:00', '2026-04-17 10:29:00');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `social_security_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `country` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `street_address` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `license_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `specialization` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `office_location_id` bigint UNSIGNED DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','patient','doctor') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'patient',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`id`, `name`, `social_security_number`, `birth_date`, `country`, `city`, `postal_code`, `street_address`, `phone_number`, `license_number`, `specialization`, `office_location_id`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Test User', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'test@example.com', '2026-04-17 10:28:57', '$2y$12$va0c9j6iDwpdUImQWRYahOWC6fjjN4VQR.NESNjScbZUilbt3E8jS', 'patient', 'HFeQPQMCVl', '2026-04-17 10:28:58', '2026-04-17 10:28:58'),
(2, 'Admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'admin@test.com', NULL, '$2y$12$8QjMavnUv9MHdm1/EPOJju4e3Il8.OUg0.VmraR7b9sMc.x.ycit2', 'admin', NULL, '2026-04-17 10:28:58', '2026-04-17 10:28:58'),
(3, 'Dr. Kovács Béla', NULL, NULL, NULL, NULL, NULL, NULL, '+36 30 123 4567', 'LIC1001', 'Kardiológus', 1, 'kovacs.bela@clinic.hu', NULL, '$2y$12$B2iYSmKxka1QpAMAtKDpuOkRigL1e74P084Y8qcyJ7/yMD8XoDmEW', 'doctor', NULL, '2026-04-17 10:28:58', '2026-04-17 10:28:58'),
(4, 'Dr. Szabó Anna', NULL, NULL, NULL, NULL, NULL, NULL, '+36 30 987 6543', 'LIC1002', 'Bőrgyógyász', 2, 'szabo.anna@clinic.hu', NULL, '$2y$12$uCztOd30HegqmDwnlHz4aueZQNU/7VLo1.iUAhNjs56uQT1RgjIlq', 'doctor', NULL, '2026-04-17 10:28:58', '2026-04-17 10:28:58'),
(5, 'Dr. Tóth Gergely', NULL, NULL, NULL, NULL, NULL, NULL, '+36 30 444 1122', 'LIC1003', 'Pszichológus', 3, 'toth.gergely@clinic.hu', NULL, '$2y$12$nxGmzvVaskdSVxT0cceiCOvelSe81myYetvHV8hWH6sujBsVDYBEa', 'doctor', NULL, '2026-04-17 10:28:58', '2026-04-17 10:28:58'),
(6, 'Beteg1', '200000001', '1993-02-10', 'Hungary', 'Budapest', '1111', 'Fo utca 1.', '+36301111111', NULL, NULL, NULL, 'patient1@test.com', '2026-04-17 10:28:58', '$2y$12$DNJnhcgvHbVU3y9tYZF2eOtynNvCsMePfyccMtcOC0fSZOTO52TMm', 'patient', NULL, '2026-04-17 10:28:58', '2026-04-17 10:28:58'),
(7, 'Beteg2', '200000002', '1988-07-24', 'Hungary', 'Szeged', '6722', 'Kossuth Lajos sgt. 12.', '+36302222222', NULL, NULL, NULL, 'patient2@test.com', '2026-04-17 10:28:58', '$2y$12$.WvIfm5d4q3mLuy7D8L9sOTeOzXgSSEeLjlNhIjwhLn4tV/VkxPR6', 'patient', NULL, '2026-04-17 10:28:59', '2026-04-17 10:28:59'),
(8, 'Beteg3', '200000003', '1997-11-05', 'Hungary', 'Debrecen', '4025', 'Piac utca 8.', '+36303333333', NULL, NULL, NULL, 'patient3@test.com', '2026-04-17 10:28:59', '$2y$12$nwfxBJAqkH6rgiNdW.AroeaYiFGt5NimqEgZYibXRCvgZ3TiMhdDG', 'patient', NULL, '2026-04-17 10:28:59', '2026-04-17 10:28:59'),
(9, 'Beteg4', '200000004', '1979-01-30', 'Hungary', 'Pecs', '7621', 'Rakoczi ut 15.', '+36304444444', NULL, NULL, NULL, 'patient4@test.com', '2026-04-17 10:28:59', '$2y$12$f3xFwA3cJ73pPeGK5ZKESeLTkjKgmTJjBL5CzL4iDLJd0GZ.gsMIC', 'patient', NULL, '2026-04-17 10:28:59', '2026-04-17 10:28:59'),
(10, 'Beteg5', '200000005', '2001-05-18', 'Hungary', 'Gyor', '9021', 'Arpad ut 3.', '+36305555555', NULL, NULL, NULL, 'patient5@test.com', '2026-04-17 10:28:59', '$2y$12$YFNmJh8onYjxoMbZDyLhDe1clE2adOJiri3jDwwWD.aqLKtz7MP5q', 'patient', NULL, '2026-04-17 10:28:59', '2026-04-17 10:28:59'),
(11, 'Beteg6', '200000006', '1984-12-09', 'Hungary', 'Kecskemet', '6000', 'Dozsa Gyorgy ut 22.', '+36306666666', NULL, NULL, NULL, 'patient6@test.com', '2026-04-17 10:28:59', '$2y$12$HL1rY3EkQDmQzGEDMKGyc.OXhIcTd3nXVi2xDmtX4BAo2R54UjwHK', 'patient', NULL, '2026-04-17 10:28:59', '2026-04-17 10:28:59'),
(12, 'Beteg7', '200000007', '1998-04-27', 'Hungary', 'Miskolc', '3525', 'Szechenyi ut 40.', '+36307777777', NULL, NULL, NULL, 'patient7@test.com', '2026-04-17 10:28:59', '$2y$12$sKTaH7ZuPIPqi8qQLBPsX.KaUxBOK7podWZV/2aALq662lEiKXDDq', 'patient', NULL, '2026-04-17 10:28:59', '2026-04-17 10:28:59'),
(13, 'Beteg8', '200000008', '1976-09-13', 'Hungary', 'Nyiregyhaza', '4400', 'Bethlen Gabor ut 2.', '+36308888888', NULL, NULL, NULL, 'patient8@test.com', '2026-04-17 10:28:59', '$2y$12$LGxfAf/W/fh.3yMU43XoDOLemrnsmo63mxxyLwp9NLqOuIQivcphq', 'patient', NULL, '2026-04-17 10:29:00', '2026-04-17 10:29:00');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointments_doctor_id_foreign` (`doctor_id`),
  ADD KEY `appointments_patient_id_foreign` (`patient_id`);

--
-- A tábla indexei `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- A tábla indexei `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- A tábla indexei `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documents_doctor_id_foreign` (`doctor_id`),
  ADD KEY `documents_patient_id_foreign` (`patient_id`);

--
-- A tábla indexei `document_types`
--
ALTER TABLE `document_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `document_types_type_unique` (`type`);

--
-- A tábla indexei `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- A tábla indexei `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- A tábla indexei `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `office_locations`
--
ALTER TABLE `office_locations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `office_locations_room_number_unique` (`room_number`);

--
-- A tábla indexei `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- A tábla indexei `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- A tábla indexei `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prescriptions_doctor_id_foreign` (`doctor_id`),
  ADD KEY `prescriptions_patient_id_foreign` (`patient_id`);

--
-- A tábla indexei `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- A tábla indexei `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_social_security_number_unique` (`social_security_number`),
  ADD UNIQUE KEY `users_license_number_unique` (`license_number`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT a táblához `documents`
--
ALTER TABLE `documents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT a táblához `document_types`
--
ALTER TABLE `document_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT a táblához `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT a táblához `office_locations`
--
ALTER TABLE `office_locations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT a táblához `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT a táblához `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `documents_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD CONSTRAINT `prescriptions_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescriptions_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
