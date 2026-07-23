-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 21 juil. 2026 à 21:12
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gesco`
--

-- --------------------------------------------------------

--
-- Structure de la table `absences`
--

CREATE TABLE `absences` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `eleve_id` bigint(20) UNSIGNED NOT NULL,
  `classe_id` bigint(20) UNSIGNED NOT NULL,
  `emploi_temps_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date_absence` date NOT NULL,
  `type` enum('absence','retard') NOT NULL DEFAULT 'absence',
  `justifiee` tinyint(1) NOT NULL DEFAULT 0,
  `motif` text DEFAULT NULL,
  `signale_par` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `affectations`
--

CREATE TABLE `affectations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `enseignant_id` bigint(20) UNSIGNED NOT NULL,
  `matiere_id` bigint(20) UNSIGNED NOT NULL,
  `classe_id` bigint(20) UNSIGNED NOT NULL,
  `annee_scolaire_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `affectations`
--

INSERT INTO `affectations` (`id`, `enseignant_id`, `matiere_id`, `classe_id`, `annee_scolaire_id`, `created_at`, `updated_at`) VALUES
(6, 5, 7, 2, 1, '2026-07-01 17:38:12', '2026-07-01 17:38:12'),
(7, 5, 7, 1, 1, '2026-07-01 17:38:28', '2026-07-01 17:38:28'),
(12, 4, 2, 1, 1, '2026-07-03 11:38:38', '2026-07-03 11:38:38'),
(14, 5, 8, 1, 1, '2026-07-04 13:16:05', '2026-07-04 13:16:05'),
(17, 1, 1, 6, 1, '2026-07-04 18:09:58', '2026-07-04 18:09:58'),
(22, 6, 7, 18, 1, '2026-07-07 13:59:12', '2026-07-07 13:59:12'),
(24, 5, 7, 18, 1, '2026-07-07 14:00:30', '2026-07-07 14:00:30'),
(25, 3, 5, 1, 1, '2026-07-09 17:01:44', '2026-07-09 17:01:44'),
(26, 2, 2, 1, 1, '2026-07-09 17:02:40', '2026-07-09 17:02:40'),
(27, 1, 1, 1, 1, '2026-07-09 17:03:18', '2026-07-09 17:03:18'),
(28, 1, 6, 1, 1, '2026-07-09 17:03:31', '2026-07-09 17:03:31'),
(29, 6, 3, 1, 1, '2026-07-09 17:13:31', '2026-07-09 17:13:31'),
(30, 4, 4, 1, 1, '2026-07-09 17:13:58', '2026-07-09 17:13:58'),
(31, 2, 2, 2, 1, '2026-07-15 11:32:54', '2026-07-15 11:32:54'),
(32, 4, 4, 16, 1, '2026-07-15 16:51:43', '2026-07-15 16:51:43'),
(33, 5, 7, 16, 1, '2026-07-15 16:52:17', '2026-07-15 16:52:17'),
(34, 3, 7, 16, 1, '2026-07-15 16:53:08', '2026-07-15 16:53:08'),
(35, 7, 20, 10, 1, '2026-07-15 16:58:40', '2026-07-15 16:58:40'),
(36, 7, 9, 10, 1, '2026-07-15 16:58:51', '2026-07-15 16:58:51'),
(37, 7, 22, 10, 1, '2026-07-15 16:59:06', '2026-07-15 16:59:06');

-- --------------------------------------------------------

--
-- Structure de la table `annees_scolaires`
--

CREATE TABLE `annees_scolaires` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `libelle` varchar(255) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `initialisee` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `annees_scolaires`
--

INSERT INTO `annees_scolaires` (`id`, `libelle`, `date_debut`, `date_fin`, `active`, `initialisee`, `created_at`, `updated_at`) VALUES
(1, '2025-2026', '2025-09-01', '2026-07-31', 1, 0, '2026-06-24 23:31:21', '2026-07-18 13:41:53'),
(2, '2026-2027', '2026-09-05', '2027-04-30', 0, 0, '2026-06-29 17:19:03', '2026-07-18 13:41:53'),
(3, '2027-2028', '2027-09-05', '2028-04-30', 0, 0, '2026-06-29 17:46:51', '2026-06-29 17:46:51'),
(4, '2028-2029', '2028-09-01', '2029-03-31', 0, 0, '2026-07-01 14:07:50', '2026-07-01 14:07:50'),
(5, '2029-2030', '2029-09-05', '2030-06-30', 0, 0, '2026-07-05 14:06:21', '2026-07-06 21:06:46');

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `candidatures`
--

CREATE TABLE `candidatures` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reference` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `date_naissance` date NOT NULL,
  `lieu_naissance` varchar(255) DEFAULT NULL,
  `sexe` enum('M','F') NOT NULL,
  `classe_demandee` varchar(255) NOT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `etablissement_origine` varchar(255) DEFAULT NULL,
  `nom_parent` varchar(255) NOT NULL,
  `telephone_parent` varchar(255) NOT NULL,
  `email_parent` varchar(255) DEFAULT NULL,
  `adresse` text DEFAULT NULL,
  `statut` enum('en_attente','en_cours_examen','acceptee','refusee') NOT NULL DEFAULT 'en_attente',
  `motif_refus` text DEFAULT NULL,
  `eleve_id` bigint(20) UNSIGNED DEFAULT NULL,
  `traite_par` bigint(20) UNSIGNED DEFAULT NULL,
  `notifie_le` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `candidatures`
--

INSERT INTO `candidatures` (`id`, `reference`, `nom`, `prenom`, `date_naissance`, `lieu_naissance`, `sexe`, `classe_demandee`, `section_id`, `etablissement_origine`, `nom_parent`, `telephone_parent`, `email_parent`, `adresse`, `statut`, `motif_refus`, `eleve_id`, `traite_par`, `notifie_le`, `created_at`, `updated_at`) VALUES
(10, 'CAND2026-0001', 'TCHEDJEU NGANKOUE', 'MAELYS', '2022-02-22', 'Douala', 'F', 'Sixième', 1, NULL, 'NGANKOUE ALAIN', '675513490', NULL, NULL, 'refusee', 'Pas satisfesant', NULL, 1, '2026-07-09 21:04:58', '2026-07-07 07:02:46', '2026-07-09 21:04:58'),
(11, 'CAND2026-0002', 'Nana', 'Charlotte', '2002-02-11', NULL, 'M', 'Upper Sixth', 2, NULL, 'Djoumessi Arnold', '658987436', NULL, NULL, 'acceptee', NULL, NULL, 1, '2026-07-13 20:25:23', '2026-07-13 19:21:32', '2026-07-13 20:25:23'),
(12, 'CAND2026-0003', 'NANA', 'BROS', '2005-03-21', NULL, 'M', 'Upper Sixth', 1, 'GBS Les Merveilles', 'MINUNGOU GEORGE', '655254789', NULL, NULL, 'refusee', 'Pas satisfesant', NULL, 1, '2026-07-13 20:38:21', '2026-07-13 20:32:24', '2026-07-13 20:38:21'),
(13, 'CAND2026-0004', 'HAYAM', 'YANELLE', '2018-07-15', NULL, 'F', '5e', 1, NULL, 'TCHAMOU PIVOINE', '655223366', NULL, NULL, 'acceptee', NULL, NULL, 1, '2026-07-15 15:12:12', '2026-07-13 21:46:11', '2026-07-15 15:12:12'),
(14, 'CAND2026-0005', 'EWANE', 'EDWIGE', '2006-12-14', NULL, 'F', 'Lower Sith', 2, 'Conquête', 'NKOH PASCAL', '677885589', NULL, NULL, 'acceptee', NULL, NULL, 1, '2026-07-16 18:42:05', '2026-07-16 17:38:51', '2026-07-16 18:42:05'),
(15, 'CAND2026-0006', 'FOTA', 'PATRICK SERGE', '2007-06-15', NULL, 'M', 'Lower Sith', 2, 'Conquête', 'NFE OLIVIER', '655432312', NULL, NULL, 'acceptee', NULL, NULL, 1, '2026-07-16 19:24:44', '2026-07-16 19:24:25', '2026-07-16 19:24:44');

-- --------------------------------------------------------

--
-- Structure de la table `candidature_documents`
--

CREATE TABLE `candidature_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `candidature_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('acte_naissance','bulletin_precedent','certificat_scolarite','photo','autre') NOT NULL,
  `nom_fichier` varchar(255) NOT NULL,
  `chemin` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `candidature_documents`
--

INSERT INTO `candidature_documents` (`id`, `candidature_id`, `type`, `nom_fichier`, `chemin`, `created_at`, `updated_at`) VALUES
(20, 10, 'acte_naissance', 'images.png', 'candidatures/CAND2026-0001/KT56hQNGjpf0agBfTK5QxoTJRgSM2NlpTCbhZkH6.png', '2026-07-07 07:02:47', '2026-07-07 07:02:47'),
(21, 10, 'bulletin_precedent', 'BULL 1 ESP.pdf', 'candidatures/CAND2026-0001/u5dYKO2RvZwPU9m7HX9d9Juwb9N6nNOrG1epnH7c.pdf', '2026-07-07 07:02:47', '2026-07-07 07:02:47'),
(22, 10, 'photo', 'images.jpg', 'candidatures/CAND2026-0001/43jprKpnNjwjsKLuKoL2VMeaIVAqYW1SDN94TFTp.jpg', '2026-07-07 07:02:47', '2026-07-07 07:02:47'),
(23, 11, 'acte_naissance', 'LISTE DES COMPTES FLOTTES MAMONI.pdf', 'candidatures/CAND2026-0002/UYo4kFMg9HBDbVa0y8UuQcj4gU7YPjthwVK2Xibm.pdf', '2026-07-13 19:21:32', '2026-07-13 19:21:32'),
(24, 12, 'acte_naissance', 'trimestre_ELV20260003_1_page-0001.jpg', 'candidatures/CAND2026-0003/6izfx7HY8oXwjy7AnyeSvBVeKR0jXi5bE9nWY8U3.jpg', '2026-07-13 20:32:25', '2026-07-13 20:32:25'),
(25, 13, 'acte_naissance', 'Photo.jpeg', 'candidatures/CAND2026-0004/gfoWjivp0JA1qy3Q52fQPCPgc3O7nAlBv1FeO1Rs.jpg', '2026-07-13 21:46:11', '2026-07-13 21:46:11'),
(26, 13, 'bulletin_precedent', 'trimestre_ELV20260001_1.pdf', 'candidatures/CAND2026-0004/ZMEoMGmfBMiBQ2BYpx5vZzTIHSKc8EcEwFy0OBEL.pdf', '2026-07-13 21:46:11', '2026-07-13 21:46:11'),
(27, 14, 'acte_naissance', 'gestion-stock-recap-phases-1-5.pdf', 'candidatures/CAND2026-0005/EoQa6H5Xa2U6uh6CpQsqYAy869a1yBHYd1kU0kBn.pdf', '2026-07-16 17:38:51', '2026-07-16 17:38:51'),
(28, 14, 'bulletin_precedent', 'WhatsApp Image 2026-07-14 at 14.56.28.jpeg', 'candidatures/CAND2026-0005/M75oE9OQIMEriFAqYJhDxpg1gxitY2Axzt0Wpw6L.jpg', '2026-07-16 17:38:51', '2026-07-16 17:38:51'),
(29, 15, 'acte_naissance', 'lettre de motivation modifiée_092251.pdf', 'candidatures/CAND2026-0006/GouA4S9Zx0RNqVACVZ3b2RJ0pnJiMnpOoOhuEDVX.pdf', '2026-07-16 19:24:25', '2026-07-16 19:24:25');

-- --------------------------------------------------------

--
-- Structure de la table `classes`
--

CREATE TABLE `classes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `niveau_id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `groupe_matiere_id` bigint(20) UNSIGNED DEFAULT NULL,
  `professeur_principal_id` bigint(20) UNSIGNED DEFAULT NULL,
  `annee_scolaire_id` bigint(20) UNSIGNED NOT NULL,
  `capacite_max` int(11) NOT NULL DEFAULT 50,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `classes`
--

INSERT INTO `classes` (`id`, `nom`, `niveau_id`, `section_id`, `groupe_matiere_id`, `professeur_principal_id`, `annee_scolaire_id`, `capacite_max`, `created_at`, `updated_at`) VALUES
(1, 'Tle A', 7, 1, NULL, 4, 1, 40, '2026-06-26 15:20:32', '2026-07-13 20:05:22'),
(2, '6E', 1, 1, NULL, 2, 1, 50, '2026-06-26 15:23:02', '2026-07-13 20:06:07'),
(3, 'F1', 8, 2, NULL, NULL, 1, 70, '2026-06-26 15:24:05', '2026-07-13 20:06:43'),
(5, 'F2', 9, 2, NULL, NULL, 1, 52, '2026-06-26 18:11:37', '2026-07-13 20:05:38'),
(6, 'Tle D', 7, 1, NULL, NULL, 1, 25, '2026-06-26 20:53:07', '2026-07-13 20:05:50'),
(8, 'PD', 6, 1, NULL, NULL, 1, 30, '2026-07-01 12:07:12', '2026-07-14 10:35:51'),
(9, 'F3', 10, 2, NULL, NULL, 1, 45, '2026-07-02 14:46:50', '2026-07-14 10:36:47'),
(10, 'USS', 14, 2, NULL, NULL, 1, 70, '2026-07-02 21:14:58', '2026-07-14 10:39:25'),
(11, 'USA', 14, 2, NULL, NULL, 1, 70, '2026-07-02 21:15:31', '2026-07-14 10:38:53'),
(12, 'F4S', 11, 2, NULL, NULL, 1, 50, '2026-07-02 21:16:10', '2026-07-14 10:37:20'),
(13, 'F4A', 11, 2, NULL, NULL, 1, 50, '2026-07-02 21:17:01', '2026-07-14 10:36:56'),
(14, 'Tle C', 0, 1, NULL, 5, 1, 20, '2026-07-02 22:37:35', '2026-07-06 14:22:49'),
(15, 'PA', 6, 1, NULL, NULL, 1, 30, '2026-07-02 22:38:00', '2026-07-14 10:35:13'),
(16, 'PC', 6, 1, NULL, NULL, 1, 30, '2026-07-02 22:38:29', '2026-07-14 10:35:22'),
(17, '5E', 2, 1, NULL, NULL, 1, 60, '2026-07-02 22:41:26', '2026-07-13 20:07:28'),
(18, '4ESP', 3, 1, NULL, NULL, 1, 65, '2026-07-02 22:41:53', '2026-07-14 10:35:01'),
(19, '4ALL', 3, 1, NULL, NULL, 1, 65, '2026-07-02 22:43:01', '2026-07-14 10:34:52'),
(20, '3ESP', 4, 1, NULL, NULL, 1, 22, '2026-07-02 22:43:48', '2026-07-14 10:34:38'),
(21, '3ALL', 4, 1, NULL, NULL, 1, 15, '2026-07-02 22:44:18', '2026-07-14 10:34:25'),
(22, 'F5S', 12, 2, NULL, NULL, 1, 30, '2026-07-02 22:46:17', '2026-07-14 10:37:42'),
(23, 'F5A', 12, 2, NULL, NULL, 1, 31, '2026-07-02 22:46:39', '2026-07-14 10:37:31'),
(24, 'LSS', 13, 2, NULL, NULL, 1, 65, '2026-07-02 22:47:25', '2026-07-14 10:38:11'),
(25, 'LSA', 13, 2, NULL, NULL, 1, 68, '2026-07-02 22:47:43', '2026-07-14 10:37:54'),
(49, 'Tle A', 7, 1, NULL, NULL, 2, 40, '2026-07-17 15:19:30', '2026-07-17 15:19:30'),
(50, '6E', 1, 1, NULL, NULL, 2, 50, '2026-07-17 15:19:31', '2026-07-17 15:19:31'),
(51, 'F1', 8, 2, NULL, NULL, 2, 70, '2026-07-17 15:19:31', '2026-07-17 15:19:31'),
(52, 'F2', 9, 2, NULL, NULL, 2, 52, '2026-07-17 15:19:31', '2026-07-17 15:19:31'),
(53, 'Tle D', 7, 1, NULL, NULL, 2, 25, '2026-07-17 15:19:31', '2026-07-17 15:19:31'),
(54, 'PD', 6, 1, NULL, NULL, 2, 30, '2026-07-17 15:19:31', '2026-07-17 15:19:31'),
(55, 'F3', 10, 2, NULL, NULL, 2, 45, '2026-07-17 15:19:31', '2026-07-17 15:19:31'),
(56, 'USS', 14, 2, NULL, NULL, 2, 70, '2026-07-17 15:19:31', '2026-07-17 15:19:31'),
(57, 'USA', 14, 2, NULL, NULL, 2, 70, '2026-07-17 15:19:31', '2026-07-17 15:19:31'),
(58, 'F4S', 11, 2, NULL, NULL, 2, 50, '2026-07-17 15:19:31', '2026-07-17 15:19:31'),
(59, 'F4A', 11, 2, NULL, NULL, 2, 50, '2026-07-17 15:19:31', '2026-07-17 15:19:31'),
(60, 'Tle C', 0, 1, NULL, NULL, 2, 20, '2026-07-17 15:19:31', '2026-07-17 15:19:31'),
(61, 'PA', 6, 1, NULL, NULL, 2, 30, '2026-07-17 15:19:31', '2026-07-17 15:19:31'),
(62, 'PC', 6, 1, NULL, NULL, 2, 30, '2026-07-17 15:19:31', '2026-07-17 15:19:31'),
(63, '5E', 2, 1, NULL, NULL, 2, 60, '2026-07-17 15:19:31', '2026-07-17 15:19:31'),
(64, '4ESP', 3, 1, NULL, NULL, 2, 65, '2026-07-17 15:19:31', '2026-07-17 15:19:31'),
(65, '4ALL', 3, 1, NULL, NULL, 2, 65, '2026-07-17 15:19:31', '2026-07-17 15:19:31'),
(66, '3ESP', 4, 1, NULL, NULL, 2, 22, '2026-07-17 15:19:31', '2026-07-17 15:19:31'),
(67, '3ALL', 4, 1, NULL, NULL, 2, 15, '2026-07-17 15:19:31', '2026-07-17 15:19:31'),
(68, 'F5S', 12, 2, NULL, NULL, 2, 30, '2026-07-17 15:19:31', '2026-07-17 15:19:31'),
(69, 'F5A', 12, 2, NULL, NULL, 2, 31, '2026-07-17 15:19:31', '2026-07-17 15:19:31'),
(70, 'LSS', 13, 2, NULL, NULL, 2, 65, '2026-07-17 15:19:31', '2026-07-17 15:19:31'),
(71, 'LSA', 13, 2, NULL, NULL, 2, 68, '2026-07-17 15:19:31', '2026-07-17 15:19:31');

-- --------------------------------------------------------

--
-- Structure de la table `classe_matiere`
--

CREATE TABLE `classe_matiere` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `classe_id` bigint(20) UNSIGNED NOT NULL,
  `matiere_id` bigint(20) UNSIGNED NOT NULL,
  `coefficient` decimal(4,2) NOT NULL DEFAULT 1.00,
  `groupe` tinyint(4) NOT NULL DEFAULT 1,
  `ordre` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `classe_matiere`
--

INSERT INTO `classe_matiere` (`id`, `classe_id`, `matiere_id`, `coefficient`, `groupe`, `ordre`, `created_at`, `updated_at`) VALUES
(10, 10, 9, 1.00, 1, 0, NULL, NULL),
(11, 11, 9, 3.00, 1, 0, NULL, NULL),
(19, 1, 2, 3.00, 1, 1, NULL, NULL),
(20, 1, 5, 4.00, 1, 2, NULL, NULL),
(21, 1, 4, 2.00, 1, 3, NULL, NULL),
(22, 1, 1, 2.00, 2, 4, NULL, NULL),
(23, 1, 3, 1.00, 2, 5, NULL, NULL),
(24, 1, 7, 1.00, 3, 6, NULL, NULL),
(25, 1, 8, 1.00, 3, 8, NULL, NULL),
(27, 1, 6, 1.00, 3, 7, NULL, NULL),
(28, 17, 1, 2.00, 1, 1, NULL, NULL),
(29, 17, 7, 2.00, 1, 2, NULL, NULL),
(30, 17, 3, 2.00, 1, 3, NULL, NULL),
(31, 17, 4, 2.00, 2, 4, NULL, NULL),
(32, 17, 2, 1.00, 2, 5, NULL, NULL),
(33, 17, 5, 2.00, 2, 6, NULL, NULL),
(34, 17, 8, 3.00, 3, 7, NULL, NULL),
(35, 15, 5, 5.00, 1, 1, NULL, NULL),
(36, 15, 8, 1.00, 1, 1, NULL, NULL),
(37, 3, 10, 1.00, 1, 0, NULL, NULL),
(38, 3, 9, 2.00, 1, 1, NULL, NULL),
(39, 18, 1, 1.00, 1, 0, NULL, NULL),
(40, 18, 3, 1.00, 1, 1, NULL, NULL),
(41, 18, 4, 1.00, 2, 2, NULL, NULL),
(42, 18, 2, 1.00, 2, 3, NULL, NULL),
(43, 18, 5, 1.00, 2, 4, NULL, NULL),
(45, 18, 7, 1.00, 3, 6, NULL, NULL),
(46, 18, 8, 1.00, 3, 7, NULL, NULL),
(47, 18, 6, 1.00, 3, 5, NULL, NULL),
(48, 2, 2, 4.00, 1, 0, NULL, NULL),
(49, 2, 1, 1.00, 2, 1, NULL, NULL),
(51, 2, 3, 1.00, 2, 2, NULL, NULL),
(52, 2, 4, 1.00, 1, 3, NULL, NULL),
(53, 16, 1, 4.00, 1, 0, NULL, NULL),
(54, 16, 13, 3.00, 1, 1, NULL, NULL),
(55, 16, 14, 2.00, 1, 2, NULL, NULL),
(56, 16, 7, 2.00, 1, 3, NULL, NULL),
(57, 16, 4, 2.00, 2, 4, NULL, NULL),
(58, 16, 11, 1.00, 2, 5, NULL, NULL),
(59, 16, 2, 2.00, 2, 6, NULL, NULL),
(60, 16, 5, 1.00, 2, 7, NULL, NULL),
(61, 16, 17, 2.00, 3, 8, NULL, NULL),
(62, 16, 18, 1.00, 3, 9, NULL, NULL),
(63, 10, 20, 2.00, 1, 1, NULL, NULL),
(64, 10, 23, 1.00, 1, 2, NULL, NULL),
(65, 10, 10, 2.00, 2, 3, NULL, NULL),
(66, 10, 19, 3.00, 2, 4, NULL, NULL),
(67, 10, 21, 2.00, 3, 5, NULL, NULL),
(68, 10, 22, 1.00, 3, 6, NULL, NULL),
(118, 49, 1, 2.00, 2, 4, NULL, NULL),
(119, 49, 2, 3.00, 1, 1, NULL, NULL),
(120, 49, 3, 1.00, 2, 5, NULL, NULL),
(121, 49, 4, 2.00, 1, 3, NULL, NULL),
(122, 49, 5, 4.00, 1, 2, NULL, NULL),
(123, 49, 6, 1.00, 3, 7, NULL, NULL),
(124, 49, 7, 1.00, 3, 6, NULL, NULL),
(125, 49, 8, 1.00, 3, 8, NULL, NULL),
(126, 50, 1, 1.00, 2, 1, NULL, NULL),
(127, 50, 2, 4.00, 1, 0, NULL, NULL),
(128, 50, 3, 1.00, 2, 2, NULL, NULL),
(129, 50, 4, 1.00, 1, 3, NULL, NULL),
(130, 51, 9, 2.00, 1, 1, NULL, NULL),
(131, 51, 10, 1.00, 1, 0, NULL, NULL),
(132, 56, 9, 1.00, 1, 0, NULL, NULL),
(133, 56, 10, 2.00, 2, 3, NULL, NULL),
(134, 56, 19, 3.00, 2, 4, NULL, NULL),
(135, 56, 20, 2.00, 1, 1, NULL, NULL),
(136, 56, 21, 2.00, 3, 5, NULL, NULL),
(137, 56, 22, 1.00, 3, 6, NULL, NULL),
(138, 56, 23, 1.00, 1, 2, NULL, NULL),
(139, 57, 9, 3.00, 1, 0, NULL, NULL),
(140, 61, 5, 5.00, 1, 1, NULL, NULL),
(141, 61, 8, 1.00, 1, 1, NULL, NULL),
(142, 62, 1, 4.00, 1, 0, NULL, NULL),
(143, 62, 2, 2.00, 2, 6, NULL, NULL),
(144, 62, 4, 2.00, 2, 4, NULL, NULL),
(145, 62, 5, 1.00, 2, 7, NULL, NULL),
(146, 62, 7, 2.00, 1, 3, NULL, NULL),
(147, 62, 11, 1.00, 2, 5, NULL, NULL),
(148, 62, 13, 3.00, 1, 1, NULL, NULL),
(149, 62, 14, 2.00, 1, 2, NULL, NULL),
(150, 62, 17, 2.00, 3, 8, NULL, NULL),
(151, 62, 18, 1.00, 3, 9, NULL, NULL),
(152, 63, 1, 2.00, 1, 1, NULL, NULL),
(153, 63, 2, 1.00, 2, 5, NULL, NULL),
(154, 63, 3, 2.00, 1, 3, NULL, NULL),
(155, 63, 4, 2.00, 2, 4, NULL, NULL),
(156, 63, 5, 2.00, 2, 6, NULL, NULL),
(157, 63, 7, 2.00, 1, 2, NULL, NULL),
(158, 63, 8, 3.00, 3, 7, NULL, NULL),
(159, 64, 1, 1.00, 1, 0, NULL, NULL),
(160, 64, 2, 1.00, 2, 3, NULL, NULL),
(161, 64, 3, 1.00, 1, 1, NULL, NULL),
(162, 64, 4, 1.00, 2, 2, NULL, NULL),
(163, 64, 5, 1.00, 2, 4, NULL, NULL),
(164, 64, 6, 1.00, 3, 5, NULL, NULL),
(165, 64, 7, 1.00, 3, 6, NULL, NULL),
(166, 64, 8, 1.00, 3, 7, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `comptes_generes`
--

CREATE TABLE `comptes_generes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `eleve_lie` varchar(255) DEFAULT NULL,
  `exporte` tinyint(1) NOT NULL DEFAULT 0,
  `exporte_le` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `comptes_generes`
--

INSERT INTO `comptes_generes` (`id`, `user_id`, `nom`, `email`, `mot_de_passe`, `role`, `eleve_lie`, `exporte`, `exporte_le`, `created_at`, `updated_at`) VALUES
(2, 19, 'Njapounke Brunde', 'belle@gmail.com', 'password123', 'surveillant_general', NULL, 0, NULL, '2026-07-16 16:00:41', '2026-07-16 16:00:41'),
(3, 20, 'Tsague Valentine', 'tsague@gmail.com', 'password123', 'prefet_etudes', NULL, 0, NULL, '2026-07-19 15:38:47', '2026-07-19 15:38:47'),
(4, 21, 'Nekui Alain', 'alain@gmail.com', 'password123', 'bibliothecaire', NULL, 0, NULL, '2026-07-21 12:19:09', '2026-07-21 12:19:09');

-- --------------------------------------------------------

--
-- Structure de la table `conseils_classe`
--

CREATE TABLE `conseils_classe` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `classe_id` bigint(20) UNSIGNED NOT NULL,
  `annee_scolaire_id` bigint(20) UNSIGNED NOT NULL,
  `trimestre_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date_conseil` date NOT NULL,
  `observations_generales` text DEFAULT NULL,
  `statut` varchar(255) NOT NULL DEFAULT 'planifie',
  `preside_par` bigint(20) UNSIGNED DEFAULT NULL,
  `cree_par` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `conseils_classe`
--

INSERT INTO `conseils_classe` (`id`, `classe_id`, `annee_scolaire_id`, `trimestre_id`, `date_conseil`, `observations_generales`, `statut`, `preside_par`, `cree_par`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '2026-07-19', NULL, 'cloture', NULL, 1, '2026-07-19 15:34:22', '2026-07-19 15:36:36'),
(2, 10, 1, 1, '2026-07-20', NULL, 'cloture', NULL, 1, '2026-07-20 08:54:59', '2026-07-20 08:55:34');

-- --------------------------------------------------------

--
-- Structure de la table `decisions_conseil`
--

CREATE TABLE `decisions_conseil` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `conseil_classe_id` bigint(20) UNSIGNED NOT NULL,
  `eleve_id` bigint(20) UNSIGNED NOT NULL,
  `type_decision` enum('passage','redoublement','exclusion_temporaire','exclusion_definitive','avertissement','felicitations','encouragements','blame','tableau_honneur','autre') NOT NULL,
  `motif` text DEFAULT NULL,
  `observation` text DEFAULT NULL,
  `date_application` date DEFAULT NULL,
  `decidee_par` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `decisions_conseil`
--

INSERT INTO `decisions_conseil` (`id`, `conseil_classe_id`, `eleve_id`, `type_decision`, `motif`, `observation`, `date_application`, `decidee_par`, `created_at`, `updated_at`) VALUES
(1, 1, 20, 'passage', NULL, NULL, NULL, 1, '2026-07-19 15:35:01', '2026-07-19 15:35:01'),
(2, 1, 21, 'felicitations', NULL, NULL, NULL, 1, '2026-07-19 15:37:07', '2026-07-19 15:37:07'),
(3, 2, 27, 'redoublement', NULL, NULL, NULL, 1, '2026-07-20 08:55:26', '2026-07-20 08:55:26');

-- --------------------------------------------------------

--
-- Structure de la table `eleves`
--

CREATE TABLE `eleves` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `matricule` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `date_naissance` date NOT NULL,
  `lieu_naissance` varchar(255) DEFAULT NULL,
  `sexe` enum('M','F') NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `telephone_parent` varchar(255) DEFAULT NULL,
  `adresse` text DEFAULT NULL,
  `parent_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `eleve_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `classe_id` bigint(20) UNSIGNED DEFAULT NULL,
  `statut` enum('actif','inactif','transfere','diplome') NOT NULL DEFAULT 'actif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `eleves`
--

INSERT INTO `eleves` (`id`, `matricule`, `nom`, `prenom`, `date_naissance`, `lieu_naissance`, `sexe`, `photo`, `telephone_parent`, `adresse`, `parent_user_id`, `eleve_user_id`, `classe_id`, `statut`, `created_at`, `updated_at`) VALUES
(20, 'ELV20260003', 'NGANKOUE', 'CELESTIN', '2008-12-25', 'Douala', 'M', 'eleves/IJaWSHwGeT5PFnUltvq7Y7lf1r1Lfh5hYZXuVMSB.jpg', '698854731', NULL, 13, NULL, 1, 'actif', '2026-07-09 16:58:00', '2026-07-18 12:58:05'),
(21, 'ELV20260004', 'NOUBISSI TCHAKKO', 'LAURENE MORELLE', '2005-03-01', 'Yaoundé', 'F', 'eleves/7VzkTuQuFokoSx6KFlVdwU3RMYnzyTN10JExOnjk.jpg', NULL, NULL, NULL, NULL, 1, 'actif', '2026-07-09 16:59:21', '2026-07-18 12:58:05'),
(25, 'ELV20260005', 'TCHEDJEU NGANKOUE', 'MAELYSE', '2024-02-27', 'Douala', 'F', NULL, '691975928', 'Logpom', NULL, NULL, 17, 'actif', '2026-07-15 12:24:19', '2026-07-18 12:58:05'),
(26, 'ELV20260006', 'NGUEPI', 'BRYAN', '2005-04-11', 'Douala', 'M', NULL, '677865320', NULL, 15, NULL, 16, 'actif', '2026-07-15 16:23:36', '2026-07-18 12:58:05'),
(27, 'ELV20260007', 'UCHE UKKO', 'VALDES JAMES', '2002-11-25', NULL, 'M', NULL, NULL, NULL, 16, NULL, 10, 'actif', '2026-07-15 16:42:29', '2026-07-18 12:58:05'),
(28, 'ELV20260008', 'EWANE', 'EDWIGE', '2006-12-14', NULL, 'F', NULL, '677885589', NULL, 22, NULL, 24, 'actif', '2026-07-16 18:42:05', '2026-07-21 17:01:05'),
(29, 'ELV20260009', 'FOTA', 'PATRICK SERGE', '2007-06-15', NULL, 'M', NULL, '655432312', NULL, NULL, NULL, NULL, 'actif', '2026-07-16 19:24:44', '2026-07-16 19:24:44'),
(32, 'ELV20260010', 'NGAH TABI', 'THERESE', '1998-12-15', 'Douala', 'F', 'eleves/z0yGRlMuRrjO2VeijNP7nNQLDUAAdDHkphorATno.png', NULL, NULL, NULL, NULL, NULL, 'actif', '2026-07-18 13:33:50', '2026-07-18 13:33:50'),
(33, 'ELV20260011', 'DILANE', 'CABREL', '1996-02-11', 'Loum', 'M', NULL, '690500714', NULL, 24, NULL, 16, 'actif', '2026-07-21 17:04:11', '2026-07-21 17:15:00');

-- --------------------------------------------------------

--
-- Structure de la table `emplois_temps`
--

CREATE TABLE `emplois_temps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `classe_id` bigint(20) UNSIGNED NOT NULL,
  `matiere_id` bigint(20) UNSIGNED NOT NULL,
  `enseignant_id` bigint(20) UNSIGNED NOT NULL,
  `annee_scolaire_id` bigint(20) UNSIGNED NOT NULL,
  `jour` enum('lundi','mardi','mercredi','jeudi','vendredi','samedi') NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `salle` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `emplois_temps`
--

INSERT INTO `emplois_temps` (`id`, `classe_id`, `matiere_id`, `enseignant_id`, `annee_scolaire_id`, `jour`, `heure_debut`, `heure_fin`, `salle`, `created_at`, `updated_at`) VALUES
(5, 17, 1, 1, 1, 'lundi', '08:30:00', '12:00:00', 'Salle A', '2026-07-05 16:00:10', '2026-07-05 16:00:10'),
(6, 17, 4, 4, 1, 'mercredi', '08:00:00', '09:00:00', NULL, '2026-07-05 21:21:57', '2026-07-05 21:21:57'),
(7, 18, 1, 5, 1, 'lundi', '08:00:00', '10:46:00', NULL, '2026-07-07 17:47:01', '2026-07-07 17:47:01'),
(8, 1, 4, 4, 1, 'lundi', '08:00:00', '10:00:00', NULL, '2026-07-15 10:35:56', '2026-07-15 10:35:56'),
(9, 1, 4, 4, 1, 'jeudi', '13:00:00', '15:00:00', NULL, '2026-07-15 10:36:41', '2026-07-15 10:36:41'),
(10, 10, 9, 7, 1, 'mardi', '11:00:00', '12:00:00', NULL, '2026-07-16 12:28:35', '2026-07-16 12:28:35'),
(11, 10, 23, 7, 1, 'mercredi', '08:00:00', '11:00:00', NULL, '2026-07-16 12:30:01', '2026-07-16 12:30:01');

-- --------------------------------------------------------

--
-- Structure de la table `emprunts`
--

CREATE TABLE `emprunts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `livre_id` bigint(20) UNSIGNED NOT NULL,
  `eleve_id` bigint(20) UNSIGNED DEFAULT NULL,
  `enseignant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date_emprunt` date NOT NULL,
  `date_retour_prevue` date NOT NULL,
  `date_retour_effective` date DEFAULT NULL,
  `statut` enum('en_cours','retourne','perdu','en_retard') NOT NULL DEFAULT 'en_cours',
  `enregistre_par` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `enseignants`
--

CREATE TABLE `enseignants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `matricule` varchar(255) NOT NULL,
  `specialite` varchar(255) DEFAULT NULL,
  `diplome` varchar(255) DEFAULT NULL,
  `date_recrutement` date DEFAULT NULL,
  `statut` enum('actif','inactif') NOT NULL DEFAULT 'actif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `enseignants`
--

INSERT INTO `enseignants` (`id`, `user_id`, `matricule`, `specialite`, `diplome`, `date_recrutement`, `statut`, `created_at`, `updated_at`) VALUES
(1, 2, 'ENS20260001', 'Electronique', 'Master', NULL, 'actif', '2026-06-26 15:21:41', '2026-06-29 16:39:03'),
(2, 3, 'ENS20260002', 'Sciences Physiques', NULL, '2026-06-26', 'actif', '2026-06-26 18:06:41', '2026-06-30 10:10:58'),
(3, 4, 'ENS20260003', 'Infromatiques', NULL, '2026-06-12', 'actif', '2026-06-26 18:07:36', '2026-06-26 18:07:36'),
(4, 5, 'ENS20260004', 'Economie', 'Doctorat', '2026-06-29', 'actif', '2026-06-29 15:31:07', '2026-06-29 15:31:07'),
(5, 6, 'ENS20260005', 'Informatique', 'Ingénieur Informaticien', NULL, 'actif', '2026-07-01 17:34:25', '2026-07-01 17:34:25'),
(6, 7, 'ENS20260006', 'Informatique', 'Master Génie Logicielle', NULL, 'actif', '2026-07-01 17:35:24', '2026-07-01 17:35:24'),
(7, 17, 'ENS20260007', 'Physics', NULL, '2026-07-15', 'actif', '2026-07-15 16:57:43', '2026-07-15 16:57:43');

-- --------------------------------------------------------

--
-- Structure de la table `etablissement`
--

CREATE TABLE `etablissement` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `sigle` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `adresse` text DEFAULT NULL,
  `ville` varchar(255) DEFAULT NULL,
  `pays` varchar(255) NOT NULL DEFAULT 'Cameroun',
  `telephone` varchar(255) DEFAULT NULL,
  `telephone2` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `site_web` varchar(255) DEFAULT NULL,
  `bp` varchar(255) DEFAULT NULL,
  `region` varchar(255) DEFAULT NULL,
  `ministre_tutelle` varchar(255) DEFAULT NULL,
  `ordre_enseignement` varchar(255) DEFAULT NULL,
  `type_etablissement` varchar(255) DEFAULT NULL,
  `code_etablissement` varchar(255) DEFAULT NULL,
  `devise` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `etablissement`
--

INSERT INTO `etablissement` (`id`, `nom`, `sigle`, `logo`, `adresse`, `ville`, `pays`, `telephone`, `telephone2`, `email`, `site_web`, `bp`, `region`, `ministre_tutelle`, `ordre_enseignement`, `type_etablissement`, `code_etablissement`, `devise`, `created_at`, `updated_at`) VALUES
(1, 'EMUNAH', 'GSE', 'etablissement/DSsLpcHIjxMlTGuM8AW4Jb294zXilvuvZ0mFaqF9.jpg', 'Akwa-Bonapriso', 'Douala', 'Cameroun', '+237691975928', NULL, 'contact@jms.cm', 'https://emunah.com', 'BP 1234', 'Littoral', 'MINESEC', 'Privé Laïc', 'Collège', NULL, 'Excellence-Intégrité-Réussite', '2026-07-03 16:20:09', '2026-07-20 19:06:04');

-- --------------------------------------------------------

--
-- Structure de la table `evaluations`
--

CREATE TABLE `evaluations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `titre` varchar(255) NOT NULL,
  `matiere_id` bigint(20) UNSIGNED NOT NULL,
  `classe_id` bigint(20) UNSIGNED NOT NULL,
  `sequence_id` bigint(20) UNSIGNED NOT NULL,
  `enseignant_id` bigint(20) UNSIGNED NOT NULL,
  `note_sur` decimal(5,2) NOT NULL DEFAULT 20.00,
  `date_evaluation` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `evaluations`
--

INSERT INTO `evaluations` (`id`, `titre`, `matiere_id`, `classe_id`, `sequence_id`, `enseignant_id`, `note_sur`, `date_evaluation`, `created_at`, `updated_at`) VALUES
(1, 'Devoir 1', 4, 1, 1, 4, 20.00, '2026-06-29', '2026-06-29 22:12:47', '2026-06-29 22:12:47'),
(2, 'Devoir 1', 1, 2, 1, 3, 20.00, '2026-06-29', '2026-06-29 23:00:04', '2026-06-29 23:00:04'),
(3, 'Devoir 1', 3, 6, 1, 3, 20.00, '2026-06-30', '2026-06-30 07:15:11', '2026-06-30 07:15:11'),
(5, 'Composition 1', 4, 1, 2, 4, 20.00, '2026-06-30', '2026-06-30 17:06:19', '2026-06-30 17:06:19'),
(6, 'Control Contnue', 4, 6, 1, 4, 20.00, '2026-07-01', '2026-07-01 21:22:10', '2026-07-01 21:22:10'),
(7, 'Composition 1', 4, 6, 1, 4, 20.00, '2026-07-01', '2026-07-01 21:23:03', '2026-07-01 21:23:03');

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `frais_scolarite`
--

CREATE TABLE `frais_scolarite` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `annee_scolaire_id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `niveau` varchar(255) DEFAULT NULL,
  `frais_inscription` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tranche1` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tranche2` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tranche3` decimal(10,2) NOT NULL DEFAULT 0.00,
  `echeance_tranche1` date DEFAULT NULL,
  `echeance_tranche2` date DEFAULT NULL,
  `echeance_tranche3` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `frais_scolarite`
--

INSERT INTO `frais_scolarite` (`id`, `annee_scolaire_id`, `section_id`, `niveau`, `frais_inscription`, `tranche1`, `tranche2`, `tranche3`, `echeance_tranche1`, `echeance_tranche2`, `echeance_tranche3`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Terminale', 20000.00, 50000.00, 35000.00, 40000.00, '2026-10-13', '2026-11-23', '2027-03-15', '2026-07-13 10:18:19', '2026-07-13 10:18:19'),
(2, 1, 1, '4ème', 10000.00, 35000.00, 25000.00, 15000.00, NULL, NULL, NULL, '2026-07-14 08:49:07', '2026-07-14 08:49:07'),
(3, 1, 2, 'Upper Sixth', 25000.00, 90000.00, 60000.00, 40000.00, NULL, NULL, NULL, '2026-07-14 14:33:26', '2026-07-14 14:33:26'),
(4, 1, 1, '6ème', 5000.00, 35000.00, 20000.00, 15000.00, NULL, NULL, NULL, '2026-07-15 11:48:52', '2026-07-15 11:48:52'),
(5, 1, 1, 'Première', 20000.00, 60000.00, 45000.00, 20000.00, NULL, NULL, NULL, '2026-07-15 16:27:16', '2026-07-15 16:27:16'),
(6, 1, 1, '5ème', 5000.00, 15000.00, 30000.00, 34500.00, NULL, NULL, NULL, '2026-07-16 16:50:32', '2026-07-16 16:51:39'),
(7, 1, 2, 'Lower Sixth', 15000.00, 50000.00, 25000.00, 20000.00, NULL, NULL, NULL, '2026-07-16 18:45:22', '2026-07-16 18:45:22'),
(8, 2, 1, 'Terminale', 20000.00, 50000.00, 35000.00, 40000.00, NULL, NULL, NULL, '2026-07-17 15:19:31', '2026-07-17 15:19:31'),
(9, 2, 1, '4ème', 10000.00, 35000.00, 25000.00, 15000.00, NULL, NULL, NULL, '2026-07-17 15:19:31', '2026-07-17 15:19:31'),
(10, 2, 2, 'Upper Sixth', 25000.00, 90000.00, 60000.00, 40000.00, NULL, NULL, NULL, '2026-07-17 15:19:31', '2026-07-17 15:19:31'),
(11, 2, 1, '6ème', 5000.00, 35000.00, 20000.00, 15000.00, NULL, NULL, NULL, '2026-07-17 15:19:31', '2026-07-17 15:19:31'),
(12, 2, 1, 'Première', 20000.00, 60000.00, 45000.00, 20000.00, NULL, NULL, NULL, '2026-07-17 15:19:31', '2026-07-17 15:19:31'),
(13, 2, 1, '5ème', 5000.00, 15000.00, 30000.00, 34500.00, NULL, NULL, NULL, '2026-07-17 15:19:31', '2026-07-17 15:19:31'),
(14, 2, 2, 'Lower Sixth', 15000.00, 50000.00, 25000.00, 20000.00, NULL, NULL, NULL, '2026-07-17 15:19:31', '2026-07-17 15:19:31');

-- --------------------------------------------------------

--
-- Structure de la table `groupes_matieres`
--

CREATE TABLE `groupes_matieres` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `code` varchar(20) NOT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `groupes_matieres`
--

INSERT INTO `groupes_matieres` (`id`, `nom`, `code`, `section_id`, `created_at`, `updated_at`) VALUES
(1, 'Tronc commun', 'TC-FR', 1, '2026-06-24 23:31:21', '2026-06-24 23:31:21'),
(2, 'Groupe 1', 'G1-FR', 1, '2026-06-30 12:51:11', '2026-06-30 12:51:11');

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `livres`
--

CREATE TABLE `livres` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `titre` varchar(255) NOT NULL,
  `auteur` varchar(255) DEFAULT NULL,
  `editeur` varchar(255) DEFAULT NULL,
  `isbn` varchar(255) DEFAULT NULL,
  `categorie` varchar(255) DEFAULT NULL,
  `quantite_totale` int(11) NOT NULL DEFAULT 1,
  `quantite_disponible` int(11) NOT NULL DEFAULT 1,
  `emplacement` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `matieres`
--

CREATE TABLE `matieres` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `code` varchar(20) NOT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `matieres`
--

INSERT INTO `matieres` (`id`, `nom`, `code`, `section_id`, `created_at`, `updated_at`) VALUES
(1, 'Mathématiques', 'MATH-FR', 1, '2026-06-24 23:31:21', '2026-06-24 23:31:21'),
(2, 'Français', 'FR-FR', 1, '2026-06-24 23:31:21', '2026-06-24 23:31:21'),
(3, 'Physique-Chimie', 'PC-FR', 1, '2026-06-24 23:31:21', '2026-06-24 23:31:21'),
(4, 'Histoire-Géographie', 'HG-FR', 1, '2026-06-24 23:31:21', '2026-06-24 23:31:21'),
(5, 'Anglais', 'ANG-FR', 1, '2026-06-24 23:31:21', '2026-06-24 23:31:21'),
(6, 'Electronique', 'ELECT-FR', 1, '2026-06-30 07:55:34', '2026-06-30 07:55:34'),
(7, 'Informatique', 'INFO-FR', 1, '2026-07-01 17:36:52', '2026-07-01 17:36:52'),
(8, 'Logiciel', 'LOG-FR', 1, '2026-07-01 17:37:39', '2026-07-01 17:37:39'),
(9, 'Physic', 'PHY-ANG', 2, '2026-07-02 17:09:30', '2026-07-05 14:16:48'),
(10, 'French', 'FR-ANG', 2, '2026-07-05 14:17:13', '2026-07-05 14:17:13'),
(11, 'ECM', 'EC-FR', 1, '2026-07-15 16:34:13', '2026-07-15 16:34:13'),
(12, 'PCT', 'PT-FR', 1, '2026-07-15 16:34:47', '2026-07-15 16:34:47'),
(13, 'Physique', 'PH-FR', 1, '2026-07-15 16:35:14', '2026-07-15 16:35:14'),
(14, 'Chimie', 'CH-FR', 1, '2026-07-15 16:35:34', '2026-07-15 16:35:34'),
(15, 'Espagnol', 'ES-FR', 1, '2026-07-15 16:35:57', '2026-07-15 16:35:57'),
(16, 'Allemand', 'ALL-FR', 1, '2026-07-15 16:36:21', '2026-07-15 16:36:21'),
(17, 'EPS', 'EP-FR', 1, '2026-07-15 16:36:47', '2026-07-15 16:36:47'),
(18, 'Travail Manuel', 'TM-FR', 1, '2026-07-15 16:37:10', '2026-07-15 16:37:10'),
(19, 'Intensive English', 'IEN-AN', 2, '2026-07-15 16:39:22', '2026-07-15 16:39:22'),
(20, 'Biology', 'BI-AN', 2, '2026-07-15 16:39:39', '2026-07-15 16:39:39'),
(21, 'Mathematic', 'MATH-AN', 2, '2026-07-15 16:39:57', '2026-07-15 16:39:57'),
(22, 'Science', 'SC-AN', 2, '2026-07-15 16:40:13', '2026-07-15 16:40:13'),
(23, 'Chemestry', 'CHE-AN', 2, '2026-07-15 16:40:33', '2026-07-15 16:40:33');

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_06_24_154045_create_sections_table', 1),
(5, '2026_06_24_154131_create_annees_scolaires_table', 1),
(6, '2026_06_24_154201_create_classes_table', 1),
(7, '2026_06_24_154221_create_matieres_table', 1),
(8, '2026_06_24_154258_add_fields_to_users_table', 1),
(9, '2026_06_24_155131_create_permission_tables', 1),
(10, '2026_06_24_160057_create_eleves_table', 1),
(11, '2026_06_24_160130_create_inscriptions_table', 1),
(12, '2026_06_24_161348_create_enseignants_table', 1),
(13, '2026_06_24_161421_create_affectations_table', 1),
(14, '2026_06_24_161530_create_groupes_matieres_table', 1),
(15, '2026_06_24_161550_create_groupe_matiere_table', 1),
(16, '2026_06_24_161629_add_groupe_matiere_to_classes_table', 1),
(17, '2026_06_24_163711_create_trimestres_table', 1),
(18, '2026_06_24_163730_create_sequences_table', 1),
(19, '2026_06_24_163819_create_evaluations_table', 1),
(20, '2026_06_24_163831_create_notes_table', 1),
(21, '2026_06_24_170123_create_emplois_temps_table', 1),
(22, '2026_06_24_170206_create_absences_table', 1),
(23, '2026_06_24_190845_create_candidatures_table', 1),
(24, '2026_06_24_190916_create_candidature_documents_table', 1),
(25, '2026_07_02_165552_remove_coefficient_from_matieres_table', 2),
(26, '2026_07_02_165642_create_classe_matiere_table', 2),
(27, '2026_07_02_165742_remove_coefficient_from_groupe_matiere_table', 2),
(28, '2026_07_03_103442_add_professeur_principal_to_classes_table', 3),
(29, '2026_07_03_103451_restructure_notes_table', 3),
(30, '2026_07_03_170311_create_etablissement_table', 4),
(31, '2026_07_04_121844_add_groupe_to_classe_matiere_table', 5),
(32, '2026_07_06_173048_add_classe_id_to_eleves_table', 6),
(33, '2026_07_06_221523_create_frais_scolarite_table', 7),
(34, '2026_07_06_221524_create_zones_transport_table', 7),
(35, '2026_07_06_221522_create_inscriptions_scolarite_table', 8),
(36, '2026_07_09_153027_create_niveaux_table', 9),
(37, '2026_07_13_094611_add_parent_user_id_to_eleves_table', 10),
(38, '2026_07_13_100301_create_travaux_diriges_table', 10),
(39, '2026_07_13_103052_create_frais_scolarite_table', 11),
(40, '2026_07_13_103116_create_zones_transport_table', 11),
(41, '2026_07_13_103117_create_scolarites_table', 11),
(42, '2026_07_13_103118_create_paiements_scolarite_table', 11),
(43, '2026_07_13_142757_create_transferts_table', 12),
(44, '2026_07_14_114615_create_requetes_table', 13),
(45, '2026_07_14_114737_add_eleve_user_id_to_eleves_table', 13),
(46, '2026_07_15_093113_add_est_terminale_to_niveaux_table', 14),
(47, '2026_07_15_101045_create_comptes_generes_table', 14),
(48, '2026_07_18_083231_ensure_scolarites_structure', 15),
(49, '2026_07_18_142315_add_initialisee_to_annees_scolaires_table', 16),
(50, '2026_07_19_161548_create_conseils_classe_table', 17),
(51, '2026_07_21_120102_create_bibliotheque_tables', 18),
(52, '2026_07_21_121852_create_paiements_mobile_money_table', 19);

-- --------------------------------------------------------

--
-- Structure de la table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 11),
(3, 'App\\Models\\User', 2),
(3, 'App\\Models\\User', 3),
(3, 'App\\Models\\User', 4),
(3, 'App\\Models\\User', 5),
(3, 'App\\Models\\User', 6),
(3, 'App\\Models\\User', 7),
(3, 'App\\Models\\User', 17),
(4, 'App\\Models\\User', 19),
(5, 'App\\Models\\User', 12),
(5, 'App\\Models\\User', 18),
(6, 'App\\Models\\User', 13),
(6, 'App\\Models\\User', 15),
(6, 'App\\Models\\User', 16),
(6, 'App\\Models\\User', 22),
(6, 'App\\Models\\User', 24),
(8, 'App\\Models\\User', 20),
(10, 'App\\Models\\User', 21);

-- --------------------------------------------------------

--
-- Structure de la table `niveaux`
--

CREATE TABLE `niveaux` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `nom_en` varchar(255) DEFAULT NULL,
  `code` varchar(20) NOT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `ordre` int(11) NOT NULL DEFAULT 0,
  `est_terminale` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `niveaux`
--

INSERT INTO `niveaux` (`id`, `nom`, `nom_en`, `code`, `section_id`, `ordre`, `est_terminale`, `created_at`, `updated_at`) VALUES
(1, '6ème', NULL, '6EME', 1, 1, 0, '2026-07-09 14:36:13', '2026-07-15 09:36:42'),
(2, '5ème', NULL, '5EME', 1, 2, 0, '2026-07-09 14:36:13', '2026-07-15 09:36:42'),
(3, '4ème', NULL, '4EME', 1, 3, 0, '2026-07-09 14:36:13', '2026-07-15 09:36:42'),
(4, '3ème', NULL, '3EME', 1, 4, 1, '2026-07-09 14:36:13', '2026-07-15 09:36:42'),
(5, 'Seconde', NULL, 'SEC', 1, 5, 0, '2026-07-09 14:36:13', '2026-07-15 09:36:42'),
(6, 'Première', NULL, '1ERE', 1, 6, 0, '2026-07-09 14:36:13', '2026-07-15 09:36:42'),
(7, 'Terminale', NULL, 'TERM', 1, 7, 1, '2026-07-09 14:36:13', '2026-07-15 09:36:42'),
(8, 'Form 1', 'Form 1', 'F1', 2, 1, 0, '2026-07-09 14:36:13', '2026-07-15 09:36:42'),
(9, 'Form 2', 'Form 2', 'F2', 2, 2, 0, '2026-07-09 14:36:13', '2026-07-15 09:36:42'),
(10, 'Form 3', 'Form 3', 'F3', 2, 3, 0, '2026-07-09 14:36:13', '2026-07-15 09:36:42'),
(11, 'Form 4', 'Form 4', 'F4', 2, 4, 0, '2026-07-09 14:36:13', '2026-07-15 09:36:42'),
(12, 'Form 5', 'Form 5', 'F5', 2, 5, 1, '2026-07-09 14:36:13', '2026-07-15 09:36:42'),
(13, 'Lower Sixth', 'Lower Sixth', 'LS', 2, 6, 0, '2026-07-09 14:36:13', '2026-07-15 09:36:42'),
(14, 'Upper Sixth', 'Upper Sixth', 'US', 2, 7, 1, '2026-07-09 14:36:13', '2026-07-15 09:36:42');

-- --------------------------------------------------------

--
-- Structure de la table `notes`
--

CREATE TABLE `notes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `eleve_id` bigint(20) UNSIGNED NOT NULL,
  `matiere_id` bigint(20) UNSIGNED NOT NULL,
  `classe_id` bigint(20) UNSIGNED NOT NULL,
  `sequence_id` bigint(20) UNSIGNED NOT NULL,
  `enseignant_id` bigint(20) UNSIGNED NOT NULL,
  `note` decimal(5,2) DEFAULT NULL,
  `absent` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `notes`
--

INSERT INTO `notes` (`id`, `eleve_id`, `matiere_id`, `classe_id`, `sequence_id`, `enseignant_id`, `note`, `absent`, `created_at`, `updated_at`) VALUES
(21, 20, 1, 1, 1, 1, 12.00, 0, '2026-07-09 17:04:47', '2026-07-09 17:04:47'),
(22, 21, 1, 1, 1, 1, 10.00, 0, '2026-07-09 17:04:47', '2026-07-09 17:04:47'),
(23, 20, 6, 1, 1, 1, 15.00, 0, '2026-07-09 17:05:07', '2026-07-09 17:05:07'),
(24, 21, 6, 1, 1, 1, 13.00, 0, '2026-07-09 17:05:07', '2026-07-09 17:05:07'),
(25, 20, 2, 1, 1, 4, 11.00, 0, '2026-07-09 17:06:13', '2026-07-09 21:03:27'),
(26, 21, 2, 1, 1, 4, 17.00, 0, '2026-07-09 17:06:13', '2026-07-09 21:03:27'),
(27, 20, 5, 1, 1, 3, 8.00, 0, '2026-07-09 17:07:13', '2026-07-09 17:07:13'),
(28, 21, 5, 1, 1, 3, 9.00, 0, '2026-07-09 17:07:13', '2026-07-09 17:07:13'),
(29, 20, 7, 1, 1, 5, 14.00, 0, '2026-07-09 17:08:18', '2026-07-09 17:08:18'),
(30, 21, 7, 1, 1, 5, 13.00, 0, '2026-07-09 17:08:18', '2026-07-09 17:08:18'),
(31, 20, 8, 1, 1, 5, 9.00, 0, '2026-07-09 17:08:42', '2026-07-09 17:08:42'),
(32, 21, 8, 1, 1, 5, 15.00, 0, '2026-07-09 17:08:42', '2026-07-09 17:08:42'),
(33, 20, 4, 1, 1, 4, 11.00, 0, '2026-07-09 17:14:55', '2026-07-09 17:14:55'),
(34, 21, 4, 1, 1, 4, 13.00, 0, '2026-07-09 17:14:55', '2026-07-09 17:14:55'),
(35, 20, 3, 1, 1, 6, 15.00, 0, '2026-07-09 17:16:05', '2026-07-09 17:16:05'),
(36, 21, 3, 1, 1, 6, 17.00, 0, '2026-07-09 17:16:05', '2026-07-09 17:16:05'),
(37, 20, 4, 1, 2, 4, 12.00, 0, '2026-07-14 22:27:24', '2026-07-14 22:27:24'),
(38, 21, 4, 1, 2, 4, 14.00, 0, '2026-07-14 22:27:24', '2026-07-14 22:27:24'),
(39, 20, 2, 1, 2, 4, 8.00, 0, '2026-07-14 22:27:51', '2026-07-14 22:27:51'),
(40, 21, 2, 1, 2, 4, 10.00, 0, '2026-07-14 22:27:51', '2026-07-14 22:27:51'),
(41, 26, 7, 16, 1, 5, 14.00, 0, '2026-07-15 16:54:24', '2026-07-15 16:54:24'),
(42, 26, 7, 16, 2, 5, 17.00, 0, '2026-07-15 16:54:42', '2026-07-15 16:54:42'),
(43, 26, 4, 16, 1, 4, 13.00, 0, '2026-07-15 16:55:20', '2026-07-15 16:55:20'),
(44, 26, 4, 16, 2, 4, 11.00, 0, '2026-07-15 16:55:40', '2026-07-15 16:55:40'),
(45, 27, 9, 10, 1, 7, 11.00, 0, '2026-07-15 17:00:02', '2026-07-15 17:00:02'),
(46, 27, 9, 10, 2, 7, 12.00, 0, '2026-07-15 17:00:23', '2026-07-15 17:00:23'),
(47, 27, 20, 10, 1, 7, 13.00, 0, '2026-07-15 17:00:45', '2026-07-15 17:00:45'),
(48, 27, 20, 10, 2, 7, NULL, 1, '2026-07-15 17:01:02', '2026-07-15 17:01:02'),
(49, 27, 22, 10, 1, 7, NULL, 1, '2026-07-15 17:01:19', '2026-07-15 17:01:19'),
(50, 27, 22, 10, 2, 7, 16.00, 0, '2026-07-15 17:01:38', '2026-07-15 17:01:38');

-- --------------------------------------------------------

--
-- Structure de la table `paiements_eleves`
--

CREATE TABLE `paiements_eleves` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `inscription_scolarite_id` bigint(20) UNSIGNED NOT NULL,
  `eleve_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('inscription','tranche1','tranche2','tranche3','transport') NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `date_paiement` date NOT NULL,
  `recu_numero` varchar(255) DEFAULT NULL,
  `observation` text DEFAULT NULL,
  `enregistre_par` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `paiements_eleves`
--

INSERT INTO `paiements_eleves` (`id`, `inscription_scolarite_id`, `eleve_id`, `type`, `montant`, `date_paiement`, `recu_numero`, `observation`, `enregistre_par`, `created_at`, `updated_at`) VALUES
(1, 2, 19, 'tranche1', 100000.00, '2026-07-09', NULL, NULL, 1, '2026-07-09 11:23:02', '2026-07-09 11:23:02'),
(2, 2, 19, 'inscription', 15000.00, '2026-07-09', NULL, NULL, 1, '2026-07-09 11:23:41', '2026-07-09 11:23:41'),
(3, 2, 19, 'transport', 50000.00, '2026-07-09', NULL, NULL, 1, '2026-07-09 11:24:08', '2026-07-09 11:24:08');

-- --------------------------------------------------------

--
-- Structure de la table `paiements_mobile_money`
--

CREATE TABLE `paiements_mobile_money` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `scolarite_id` bigint(20) UNSIGNED NOT NULL,
  `operateur` enum('mtn_momo','orange_money') NOT NULL,
  `numero_telephone` varchar(255) NOT NULL,
  `type_paiement` varchar(255) NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `reference_transaction` varchar(255) DEFAULT NULL,
  `statut` enum('en_attente','confirme','echoue','annule') NOT NULL DEFAULT 'en_attente',
  `initie_par` bigint(20) UNSIGNED DEFAULT NULL,
  `verifie_par` bigint(20) UNSIGNED DEFAULT NULL,
  `verifie_le` timestamp NULL DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `paiements_mobile_money`
--

INSERT INTO `paiements_mobile_money` (`id`, `scolarite_id`, `operateur`, `numero_telephone`, `type_paiement`, `montant`, `reference_transaction`, `statut`, `initie_par`, `verifie_par`, `verifie_le`, `note`, `created_at`, `updated_at`) VALUES
(1, 10, 'orange_money', '691975928', 'inscription', 20000.00, 'OM-6A5FB7C18A21C', 'en_attente', NULL, NULL, NULL, NULL, '2026-07-21 17:17:37', '2026-07-21 17:17:37');

-- --------------------------------------------------------

--
-- Structure de la table `paiements_scolarite`
--

CREATE TABLE `paiements_scolarite` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `scolarite_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('inscription','tranche1','tranche2','tranche3','transport') NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `date_paiement` date NOT NULL,
  `numero_recu` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `enregistre_par` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `paiements_scolarite`
--

INSERT INTO `paiements_scolarite` (`id`, `scolarite_id`, `type`, `montant`, `date_paiement`, `numero_recu`, `note`, `enregistre_par`, `created_at`, `updated_at`) VALUES
(1, 1, 'inscription', 20000.00, '2026-07-14', 'REC202600001', NULL, 1, '2026-07-14 12:02:21', '2026-07-14 12:02:21'),
(2, 1, 'tranche1', 25000.00, '2026-07-14', 'REC202600002', NULL, 1, '2026-07-14 12:10:08', '2026-07-14 12:10:08'),
(3, 1, 'tranche3', 40000.00, '2026-07-14', 'REC202600003', NULL, 1, '2026-07-14 12:10:24', '2026-07-14 12:10:24'),
(4, 1, 'transport', 80000.00, '2026-07-14', 'REC202600004', NULL, 1, '2026-07-14 12:10:35', '2026-07-14 12:10:35'),
(10, 3, 'inscription', 15000.00, '2026-07-14', 'REC202600010', NULL, 1, '2026-07-14 13:38:51', '2026-07-14 13:38:51'),
(11, 3, 'inscription', 5000.00, '2026-07-14', 'REC202600011', NULL, 1, '2026-07-14 13:39:34', '2026-07-14 13:39:34'),
(12, 3, 'tranche1', 50000.00, '2026-07-14', 'REC202600012', NULL, 1, '2026-07-14 13:40:02', '2026-07-14 13:40:02'),
(13, 3, 'tranche2', 35000.00, '2026-07-14', 'REC202600013', NULL, 1, '2026-07-14 13:40:12', '2026-07-14 13:40:12'),
(14, 3, 'tranche3', 40000.00, '2026-07-14', 'REC202600014', NULL, 1, '2026-07-14 13:40:38', '2026-07-14 13:40:38'),
(17, 5, 'inscription', 20000.00, '2026-07-15', 'REC202600010', NULL, 1, '2026-07-15 16:28:19', '2026-07-15 16:28:19'),
(18, 6, 'inscription', 25000.00, '2026-07-15', 'REC202600011', NULL, 1, '2026-07-15 16:43:30', '2026-07-15 16:43:30'),
(19, 7, 'inscription', 4400.00, '2026-07-16', 'REC202600012', NULL, 12, '2026-07-16 16:52:06', '2026-07-16 16:52:06'),
(20, 7, 'tranche1', 15000.00, '2026-07-16', 'REC202600013', NULL, 12, '2026-07-16 16:52:30', '2026-07-16 16:52:30'),
(21, 7, 'tranche2', 15000.00, '2026-07-16', 'REC202600014', NULL, 12, '2026-07-16 16:52:41', '2026-07-16 16:52:41'),
(22, 7, 'tranche2', 15000.00, '2026-07-16', 'REC202600015', NULL, 12, '2026-07-16 16:52:57', '2026-07-16 16:52:57'),
(23, 7, 'tranche3', 34500.00, '2026-07-16', 'REC202600016', NULL, 12, '2026-07-16 16:53:19', '2026-07-16 16:53:19'),
(24, 8, 'inscription', 15000.00, '2026-07-16', 'REC202600017', 'Ras', 12, '2026-07-16 19:04:20', '2026-07-16 19:04:20'),
(25, 8, 'tranche1', 50000.00, '2026-07-16', 'REC202600018', NULL, 12, '2026-07-16 19:04:30', '2026-07-16 19:04:30'),
(26, 8, 'tranche2', 25000.00, '2026-07-16', 'REC202600019', NULL, 12, '2026-07-16 19:04:46', '2026-07-16 19:04:46'),
(27, 8, 'tranche3', 20000.00, '2026-07-16', 'REC202600020', NULL, 12, '2026-07-16 19:05:19', '2026-07-16 19:05:19'),
(28, 8, 'transport', 50000.00, '2026-07-16', 'REC202600021', NULL, 12, '2026-07-16 19:05:38', '2026-07-16 19:05:38'),
(29, 6, 'tranche1', 90000.00, '2026-07-18', 'REC202600022', NULL, 1, '2026-07-18 11:11:16', '2026-07-18 11:11:16'),
(30, 6, 'tranche2', 59997.00, '2026-07-18', 'REC202600023', NULL, 1, '2026-07-18 11:11:26', '2026-07-18 11:11:26'),
(31, 6, 'tranche2', 1.00, '2026-07-18', 'REC202600024', NULL, 1, '2026-07-18 11:11:40', '2026-07-18 11:11:40'),
(32, 6, 'tranche2', 2.00, '2026-07-18', 'REC202600025', NULL, 1, '2026-07-18 11:11:53', '2026-07-18 11:11:53'),
(33, 6, 'tranche3', 40000.00, '2026-07-18', 'REC202600026', NULL, 1, '2026-07-18 11:12:06', '2026-07-18 11:12:06'),
(37, 5, 'tranche1', 60000.00, '2026-07-18', 'REC202600027', NULL, 1, '2026-07-18 13:27:30', '2026-07-18 13:27:30'),
(38, 5, 'tranche2', 45000.00, '2026-07-18', 'REC202600028', NULL, 1, '2026-07-18 13:27:41', '2026-07-18 13:27:41'),
(39, 5, 'tranche3', 20000.00, '2026-07-18', 'REC202600029', NULL, 1, '2026-07-18 13:27:50', '2026-07-18 13:27:50'),
(40, 5, 'transport', 50000.00, '2026-07-18', 'REC202600030', NULL, 1, '2026-07-18 13:27:59', '2026-07-18 13:27:59');

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `requetes`
--

CREATE TABLE `requetes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `eleve_id` bigint(20) UNSIGNED NOT NULL,
  `annee_scolaire_id` bigint(20) UNSIGNED NOT NULL,
  `objet` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` enum('attestation','certificat_scolarite','bulletin','transfert','autre') NOT NULL DEFAULT 'autre',
  `statut` enum('en_attente','en_cours','traitee','rejetee') NOT NULL DEFAULT 'en_attente',
  `reponse` text DEFAULT NULL,
  `traitee_par` bigint(20) UNSIGNED DEFAULT NULL,
  `traitee_le` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', '2026-06-24 23:31:21', '2026-06-24 23:31:21'),
(2, 'proviseur', 'web', '2026-06-24 23:31:21', '2026-06-24 23:31:21'),
(3, 'enseignant', 'web', '2026-06-24 23:31:21', '2026-06-24 23:31:21'),
(4, 'surveillant_general', 'web', '2026-06-24 23:31:21', '2026-06-24 23:31:21'),
(5, 'secretaire_intendant', 'web', '2026-06-24 23:31:21', '2026-06-24 23:31:21'),
(6, 'parent', 'web', '2026-06-24 23:31:21', '2026-06-24 23:31:21'),
(7, 'eleve', 'web', '2026-07-14 11:05:41', '2026-07-14 11:05:41'),
(8, 'prefet_etudes', 'web', '2026-07-19 15:15:06', '2026-07-19 15:15:06'),
(9, 'prefecture_etudes', 'web', '2026-07-21 11:04:33', '2026-07-21 11:04:33'),
(10, 'bibliothecaire', 'web', '2026-07-21 11:04:33', '2026-07-21 11:04:33');

-- --------------------------------------------------------

--
-- Structure de la table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `scolarites`
--

CREATE TABLE `scolarites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `eleve_id` bigint(20) UNSIGNED NOT NULL,
  `classe_id` bigint(20) UNSIGNED NOT NULL,
  `annee_scolaire_id` bigint(20) UNSIGNED NOT NULL,
  `zone_transport_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date_inscription` date NOT NULL,
  `type_inscription` enum('nouvelle','redoublant','transfert') NOT NULL DEFAULT 'nouvelle',
  `frais_inscription` decimal(10,2) NOT NULL DEFAULT 0.00,
  `montant_tranche1` decimal(10,2) NOT NULL DEFAULT 0.00,
  `montant_tranche2` decimal(10,2) NOT NULL DEFAULT 0.00,
  `montant_tranche3` decimal(10,2) NOT NULL DEFAULT 0.00,
  `montant_transport` decimal(10,2) NOT NULL DEFAULT 0.00,
  `paye_inscription` decimal(10,2) NOT NULL DEFAULT 0.00,
  `paye_tranche1` decimal(10,2) NOT NULL DEFAULT 0.00,
  `paye_tranche2` decimal(10,2) NOT NULL DEFAULT 0.00,
  `paye_tranche3` decimal(10,2) NOT NULL DEFAULT 0.00,
  `paye_transport` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `scolarites`
--

INSERT INTO `scolarites` (`id`, `eleve_id`, `classe_id`, `annee_scolaire_id`, `zone_transport_id`, `date_inscription`, `type_inscription`, `frais_inscription`, `montant_tranche1`, `montant_tranche2`, `montant_tranche3`, `montant_transport`, `paye_inscription`, `paye_tranche1`, `paye_tranche2`, `paye_tranche3`, `paye_transport`, `created_at`, `updated_at`) VALUES
(1, 20, 1, 1, 1, '2026-07-14', 'nouvelle', 20000.00, 25000.00, 0.00, 40000.00, 80000.00, 20000.00, 25000.00, 0.00, 40000.00, 80000.00, '2026-07-14 11:55:21', '2026-07-14 12:10:35'),
(3, 21, 1, 1, NULL, '2026-07-14', 'nouvelle', 20000.00, 50000.00, 35000.00, 40000.00, 0.00, 20000.00, 50000.00, 35000.00, 40000.00, 0.00, '2026-07-14 12:47:02', '2026-07-14 13:40:38'),
(5, 26, 16, 1, 2, '2026-07-15', 'nouvelle', 20000.00, 60000.00, 45000.00, 20000.00, 50000.00, 20000.00, 60000.00, 45000.00, 20000.00, 50000.00, '2026-07-15 16:27:59', '2026-07-18 13:27:59'),
(6, 27, 10, 1, NULL, '2026-07-15', 'nouvelle', 25000.00, 90000.00, 60000.00, 40000.00, 0.00, 25000.00, 90000.00, 60000.00, 40000.00, 0.00, '2026-07-15 16:43:18', '2026-07-18 11:12:06'),
(7, 25, 17, 1, NULL, '2026-07-16', 'nouvelle', 4400.00, 15000.00, 30000.00, 34500.00, 0.00, 4400.00, 15000.00, 30000.00, 34500.00, 0.00, '2026-07-16 16:51:14', '2026-07-16 16:53:19'),
(8, 28, 24, 1, 2, '2026-07-16', 'nouvelle', 15000.00, 50000.00, 25000.00, 20000.00, 50000.00, 15000.00, 50000.00, 25000.00, 20000.00, 50000.00, '2026-07-16 18:46:19', '2026-07-16 19:05:38'),
(10, 33, 16, 1, 2, '2026-07-21', 'nouvelle', 20000.00, 60000.00, 45000.00, 20000.00, 50000.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-07-21 17:12:15', '2026-07-21 17:12:15');

-- --------------------------------------------------------

--
-- Structure de la table `sections`
--

CREATE TABLE `sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `code` varchar(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sections`
--

INSERT INTO `sections` (`id`, `nom`, `code`, `created_at`, `updated_at`) VALUES
(1, 'Francophone', 'FR', '2026-06-24 23:31:21', '2026-06-24 23:31:21'),
(2, 'Anglophone', 'ANG', '2026-06-24 23:31:21', '2026-06-24 23:31:21');

-- --------------------------------------------------------

--
-- Structure de la table `sequences`
--

CREATE TABLE `sequences` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `numero` int(11) NOT NULL,
  `trimestre_id` bigint(20) UNSIGNED NOT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sequences`
--

INSERT INTO `sequences` (`id`, `nom`, `numero`, `trimestre_id`, `date_debut`, `date_fin`, `created_at`, `updated_at`) VALUES
(1, 'Séquence 1', 1, 1, NULL, NULL, '2026-06-24 23:31:21', '2026-06-24 23:31:21'),
(2, 'Séquence 2', 2, 1, NULL, NULL, '2026-06-24 23:31:22', '2026-06-24 23:31:22'),
(3, 'Séquence 3', 3, 2, NULL, NULL, '2026-06-24 23:31:22', '2026-06-24 23:31:22'),
(4, 'Séquence 4', 4, 2, NULL, NULL, '2026-06-24 23:31:22', '2026-06-24 23:31:22'),
(5, 'Séquence 5', 5, 3, NULL, NULL, '2026-06-24 23:31:22', '2026-06-24 23:31:22'),
(6, 'Séquence 6', 6, 3, NULL, NULL, '2026-06-24 23:31:22', '2026-06-24 23:31:22'),
(7, 'Séquence 1', 1, 4, NULL, NULL, '2026-07-17 15:19:31', '2026-07-17 15:19:31'),
(8, 'Séquence 2', 2, 4, NULL, NULL, '2026-07-17 15:19:31', '2026-07-17 15:19:31'),
(9, 'Séquence 3', 3, 5, NULL, NULL, '2026-07-17 15:19:31', '2026-07-17 15:19:31'),
(10, 'Séquence 4', 4, 5, NULL, NULL, '2026-07-17 15:19:31', '2026-07-17 15:19:31'),
(11, 'Séquence 5', 5, 6, NULL, NULL, '2026-07-17 15:19:31', '2026-07-17 15:19:31'),
(12, 'Séquence 6', 6, 6, NULL, NULL, '2026-07-17 15:19:31', '2026-07-17 15:19:31');

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('5BZLDZoXp0ITAZBnUXwt6W698wv6XicVp2xBJobW', 21, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWnNDU0lPdXJYcU14NEN4aVlzN0V2V1hqOU5EMlNiSWV1MlhsbWZsbiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjIxO30=', 1784656650),
('bJlFsJK4TC4snlZDmyuN9TydcwNv3xkvZUdVl46g', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicnhiRFliSjM1SkVKU1RJbGZMMGk2MkVMMWJRU1ZmTjN2ZGViUktlRCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9zY29sYXJpdGUiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1784657978),
('fGRggT9GtnEAa201IHLfZ3olP2QnhZg7a68Ao8xM', 21, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaWl6anBKaFd5QmhiellPS0tPVnpxeW4yckhHNGVvWUNGMXZvaUd3ayI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjIxO30=', 1784654059),
('JVfov4epOUThaoaqhrVbj9cHmESbfXMnsv4Ypnnm', 24, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiajI0QjZvNDB6RGpVRVBOQ1JmbnJEbUJWRzdUNVg3UG1ZRk1zWlBOZyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wYXJlbnQvcGFpZW1lbnQtbW9tbyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI0O3M6MzoidXJsIjthOjA6e319', 1784660861);

-- --------------------------------------------------------

--
-- Structure de la table `transferts`
--

CREATE TABLE `transferts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `eleve_id` bigint(20) UNSIGNED NOT NULL,
  `classe_source_id` bigint(20) UNSIGNED NOT NULL,
  `classe_destination_id` bigint(20) UNSIGNED NOT NULL,
  `annee_scolaire_id` bigint(20) UNSIGNED NOT NULL,
  `date_transfert` date NOT NULL,
  `motif` text DEFAULT NULL,
  `effectue_par` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `transferts`
--

INSERT INTO `transferts` (`id`, `eleve_id`, `classe_source_id`, `classe_destination_id`, `annee_scolaire_id`, `date_transfert`, `motif`, `effectue_par`, `created_at`, `updated_at`) VALUES
(4, 25, 2, 17, 1, '2026-07-15', NULL, 1, '2026-07-15 12:36:58', '2026-07-15 12:36:58');

-- --------------------------------------------------------

--
-- Structure de la table `travaux_diriges`
--

CREATE TABLE `travaux_diriges` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `enseignant_id` bigint(20) UNSIGNED NOT NULL,
  `matiere_id` bigint(20) UNSIGNED NOT NULL,
  `classe_id` bigint(20) UNSIGNED NOT NULL,
  `annee_scolaire_id` bigint(20) UNSIGNED NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `contenu` text NOT NULL,
  `fichier` varchar(255) DEFAULT NULL,
  `date_publication` datetime NOT NULL,
  `date_limite_acces` datetime NOT NULL,
  `publie` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `travaux_diriges`
--

INSERT INTO `travaux_diriges` (`id`, `enseignant_id`, `matiere_id`, `classe_id`, `annee_scolaire_id`, `titre`, `description`, `contenu`, `fichier`, `date_publication`, `date_limite_acces`, `publie`, `created_at`, `updated_at`) VALUES
(1, 4, 2, 1, 1, 'Littérature', NULL, 'Exercices', 'td/Ae3N1dCVuNjJupph1uGErgPVdSdlmKalMQfb6hAw.docx', '2026-07-13 16:38:00', '2026-07-30 17:39:00', 1, '2026-07-13 15:39:25', '2026-07-13 15:39:25'),
(2, 7, 9, 10, 1, 'Physic', NULL, 'Execise', 'td/vTA7VnNZluKuLz7B3L9qkEr7mSPDOe2g0ulU62bJ.pdf', '2026-07-15 18:09:00', '2026-08-15 19:00:00', 1, '2026-07-15 17:10:26', '2026-07-15 17:10:26');

-- --------------------------------------------------------

--
-- Structure de la table `trimestres`
--

CREATE TABLE `trimestres` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `numero` int(11) NOT NULL,
  `annee_scolaire_id` bigint(20) UNSIGNED NOT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `trimestres`
--

INSERT INTO `trimestres` (`id`, `nom`, `numero`, `annee_scolaire_id`, `date_debut`, `date_fin`, `created_at`, `updated_at`) VALUES
(1, 'Trimestre 1', 1, 1, NULL, NULL, '2026-06-24 23:31:21', '2026-06-24 23:31:21'),
(2, 'Trimestre 2', 2, 1, NULL, NULL, '2026-06-24 23:31:22', '2026-06-24 23:31:22'),
(3, 'Trimestre 3', 3, 1, NULL, NULL, '2026-06-24 23:31:22', '2026-06-24 23:31:22'),
(4, 'Trimestre 1', 1, 2, NULL, NULL, '2026-07-17 15:19:31', '2026-07-17 15:19:31'),
(5, 'Trimestre 2', 2, 2, NULL, NULL, '2026-07-17 15:19:31', '2026-07-17 15:19:31'),
(6, 'Trimestre 3', 3, 2, NULL, NULL, '2026-07-17 15:19:31', '2026-07-17 15:19:31');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `telephone`, `photo`, `actif`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrateur Général', 'admin@gmail.cm', NULL, NULL, 1, NULL, '$2y$12$3jYZ9KfyNSdPWucam/tNzuMq9sMr4d3ZigCUwnD9j2.GfJf3S5NdG', NULL, '2026-06-24 23:31:22', '2026-06-25 12:48:50'),
(2, 'KEGNE SIMON', 'simon@gmail.com', '677854145', NULL, 1, NULL, '$2y$12$O1y3mdc4LpsyXsNYyxOBW.SLkThK6H1endOB0FUQ9rw6Fe5BoaomK', NULL, '2026-06-26 15:21:41', '2026-06-29 16:39:03'),
(3, 'TCHAMOU PIVOINE FABIOLA', 'pivoine@gmail.com', '659585214', NULL, 1, NULL, '$2y$12$KkBjldgFr.hOCNMYUKMjdeYRmjYU4.2FYKz9bqSmecdK/K4x5Fe7S', NULL, '2026-06-26 18:06:41', '2026-06-29 16:40:11'),
(4, 'NJAMEN MANUEL NATSU', 'njamen@gmail.com', '677458896', NULL, 1, NULL, '$2y$12$ofpU8LNsvtKWeqSWFUen/uqZ1n36kNxhR9bxkG4Wto9RdKLgX5Xw.', NULL, '2026-06-26 18:07:36', '2026-07-06 22:11:49'),
(5, 'NANA THEOPHILE', 'nana@gmail.com', '655214789', NULL, 1, NULL, '$2y$12$c.elbZ4uQIrFHBC4vdqzFe.pEXrOWakCgBw97nMr89dVtE819uCES', NULL, '2026-06-29 15:31:07', '2026-06-29 15:31:07'),
(6, 'BOGNI STEPHANE', 'mcs@yahoo.fr', '67786532', NULL, 1, NULL, '$2y$12$LCI7CzVlbVXaN.ZbN1fnnuDHLaOuDadJBFS8IoG82OJAnvM/389Qi', NULL, '2026-07-01 17:34:25', '2026-07-01 17:34:25'),
(7, 'TSINDA ENGELBERT', 'tsinda@gmail.com', '695852536', NULL, 1, NULL, '$2y$12$Aoz1SLgYwL2iWP27m4DVm.dmrYZ2XrvluYoQob4tJKBCJI1SGK2D2', NULL, '2026-07-01 17:35:24', '2026-07-01 17:35:24'),
(11, 'NKOUNG ALEXANDRE', 'test@gmail.com', NULL, NULL, 1, NULL, '$2y$12$NVZ/44YcRD43ulanHrWfSejJAf8gqz/uEz6jFm4HMCdqDopDvGUDW', NULL, '2026-07-14 11:08:11', '2026-07-14 11:08:11'),
(12, 'KUISSI MARTHE', 'compta@gmail.com', NULL, NULL, 1, NULL, '$2y$12$DPWauO8ztZc1FQdI9hh53.7P7rAD8QE6EJt2TnZ2UqWPxmMskd0n.', NULL, '2026-07-14 11:36:52', '2026-07-14 11:36:52'),
(13, 'TCHEDJEU ALEX', 'test2@gmail.com', '698854731', NULL, 1, NULL, '$2y$12$pj54pgg933Wuk9zu54l5ZOpG0KwQCyRQOXZw2e/S9LjBytEpMov8O', NULL, '2026-07-14 21:49:29', '2026-07-14 21:51:51'),
(15, 'NGUEDIA JOSEPHINE DELPHINE', 'jose@gmail.com', '677865320', NULL, 1, NULL, '$2y$12$MWuwSf2dqrP0S7YdOTi2nu23DXwcUzrIwyMUgfN0mN8IBID/C1y9a', NULL, '2026-07-15 16:24:36', '2026-07-15 16:24:36'),
(16, 'OKUKO RALPH', 'ralph@gmail.com', NULL, NULL, 1, NULL, '$2y$12$Jz3pAEnp0F38b4UgY2xVm.mKyKUueTxPyVToKPtIXxYGL1cAfk2.y', NULL, '2026-07-15 16:42:29', '2026-07-15 16:42:29'),
(17, 'UGOCHUKWU INAO', 'inao@gmail.com', NULL, NULL, 1, NULL, '$2y$12$TR6Jf5qsTEnKZKehgPasz.MxnPSCToV7RvMmbF7CisStqzp3I9xJS', NULL, '2026-07-15 16:57:43', '2026-07-15 16:57:43'),
(18, 'MENGUELE GHYLAINE', 'tysia@gmail.com', NULL, NULL, 1, NULL, '$2y$12$0itLQSVSaiJfs5DiaYC3Ue1JxUtg.pZEpNLcfI.aWDZmYhmQrhsU2', NULL, '2026-07-16 11:56:02', '2026-07-16 11:56:02'),
(19, 'Njapounke Brunde', 'belle@gmail.com', NULL, NULL, 1, NULL, '$2y$12$RCSBXbxpASyzQQr5fzk9pOeA6q0gMBLfIeI6mHAuuK.kE3R9eSshO', NULL, '2026-07-16 16:00:41', '2026-07-16 16:00:41'),
(20, 'Tsague Valentine', 'tsague@gmail.com', NULL, NULL, 1, NULL, '$2y$12$lU/4VxU8IPfo0U5FZBxlCuuMZQhVBzOB2ppyD3.gjwpf0TczSyeeG', NULL, '2026-07-19 15:38:47', '2026-07-19 15:38:47'),
(21, 'Nekui Alain', 'alain@gmail.com', NULL, NULL, 1, NULL, '$2y$12$Hlv4NfmzLBfekeJ.AweJfuUkMy6GTxuUwV5S4wibRGrhtsGMrvlpa', NULL, '2026-07-21 12:19:09', '2026-07-21 12:19:09'),
(22, 'TESTEUR', 'test3@gmail.com', '677885589', NULL, 1, NULL, '$2y$12$CJeHGkNIqLFlFXfPYYb3FuTb/uaXKF4/q7EEh/BMv0OUw9Lnxf/26', NULL, '2026-07-21 17:01:05', '2026-07-21 17:01:05'),
(24, 'TIMAH RODRIGUE', 'tim@gmail.com', '690500714', NULL, 1, NULL, '$2y$12$tQ4TVulbwcnA9rlIu4nHsO0NjxT4qKfnD/a5uU5BR6gRQYgiFKjye', NULL, '2026-07-21 17:15:00', '2026-07-21 17:15:00');

-- --------------------------------------------------------

--
-- Structure de la table `zones_transport`
--

CREATE TABLE `zones_transport` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `annee_scolaire_id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `quartiers` text DEFAULT NULL,
  `montant` decimal(10,2) NOT NULL DEFAULT 0.00,
  `actif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `zones_transport`
--

INSERT INTO `zones_transport` (`id`, `annee_scolaire_id`, `nom`, `quartiers`, `montant`, `actif`, `created_at`, `updated_at`) VALUES
(1, 1, 'Eneo', 'Yassa', 80000.00, 1, '2026-07-14 11:52:21', '2026-07-14 11:52:21'),
(2, 1, 'Folie Douce', 'Bonamoussadi', 50000.00, 1, '2026-07-15 16:06:38', '2026-07-15 16:06:38'),
(3, 2, 'Eneo', 'Yassa', 80000.00, 1, '2026-07-17 15:19:31', '2026-07-17 15:19:31'),
(4, 2, 'Folie Douce', 'Bonamoussadi', 50000.00, 1, '2026-07-17 15:19:31', '2026-07-17 15:19:31');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `absences`
--
ALTER TABLE `absences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `absences_eleve_id_foreign` (`eleve_id`),
  ADD KEY `absences_classe_id_foreign` (`classe_id`),
  ADD KEY `absences_emploi_temps_id_foreign` (`emploi_temps_id`),
  ADD KEY `absences_signale_par_foreign` (`signale_par`);

--
-- Index pour la table `affectations`
--
ALTER TABLE `affectations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `affectation_unique` (`enseignant_id`,`matiere_id`,`classe_id`,`annee_scolaire_id`),
  ADD KEY `affectations_matiere_id_foreign` (`matiere_id`),
  ADD KEY `affectations_classe_id_foreign` (`classe_id`),
  ADD KEY `affectations_annee_scolaire_id_foreign` (`annee_scolaire_id`);

--
-- Index pour la table `annees_scolaires`
--
ALTER TABLE `annees_scolaires`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `candidatures`
--
ALTER TABLE `candidatures`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `candidatures_reference_unique` (`reference`),
  ADD KEY `candidatures_section_id_foreign` (`section_id`),
  ADD KEY `candidatures_eleve_id_foreign` (`eleve_id`),
  ADD KEY `candidatures_traite_par_foreign` (`traite_par`);

--
-- Index pour la table `candidature_documents`
--
ALTER TABLE `candidature_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `candidature_documents_candidature_id_foreign` (`candidature_id`);

--
-- Index pour la table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `classes_section_id_foreign` (`section_id`),
  ADD KEY `classes_annee_scolaire_id_foreign` (`annee_scolaire_id`),
  ADD KEY `classes_groupe_matiere_id_foreign` (`groupe_matiere_id`),
  ADD KEY `classes_professeur_principal_id_foreign` (`professeur_principal_id`);

--
-- Index pour la table `classe_matiere`
--
ALTER TABLE `classe_matiere`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `classe_matiere_classe_id_matiere_id_unique` (`classe_id`,`matiere_id`),
  ADD KEY `classe_matiere_matiere_id_foreign` (`matiere_id`);

--
-- Index pour la table `comptes_generes`
--
ALTER TABLE `comptes_generes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comptes_generes_user_id_foreign` (`user_id`);

--
-- Index pour la table `conseils_classe`
--
ALTER TABLE `conseils_classe`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conseils_classe_classe_id_foreign` (`classe_id`),
  ADD KEY `conseils_classe_annee_scolaire_id_foreign` (`annee_scolaire_id`),
  ADD KEY `conseils_classe_trimestre_id_foreign` (`trimestre_id`),
  ADD KEY `conseils_classe_preside_par_foreign` (`preside_par`),
  ADD KEY `conseils_classe_cree_par_foreign` (`cree_par`);

--
-- Index pour la table `decisions_conseil`
--
ALTER TABLE `decisions_conseil`
  ADD PRIMARY KEY (`id`),
  ADD KEY `decisions_conseil_conseil_classe_id_foreign` (`conseil_classe_id`),
  ADD KEY `decisions_conseil_eleve_id_foreign` (`eleve_id`),
  ADD KEY `decisions_conseil_decidee_par_foreign` (`decidee_par`);

--
-- Index pour la table `eleves`
--
ALTER TABLE `eleves`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `eleves_matricule_unique` (`matricule`),
  ADD KEY `eleves_parent_user_id_foreign` (`parent_user_id`),
  ADD KEY `eleves_classe_id_foreign` (`classe_id`),
  ADD KEY `eleves_eleve_user_id_foreign` (`eleve_user_id`);

--
-- Index pour la table `emplois_temps`
--
ALTER TABLE `emplois_temps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emplois_temps_classe_id_foreign` (`classe_id`),
  ADD KEY `emplois_temps_matiere_id_foreign` (`matiere_id`),
  ADD KEY `emplois_temps_enseignant_id_foreign` (`enseignant_id`),
  ADD KEY `emplois_temps_annee_scolaire_id_foreign` (`annee_scolaire_id`);

--
-- Index pour la table `emprunts`
--
ALTER TABLE `emprunts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emprunts_livre_id_foreign` (`livre_id`),
  ADD KEY `emprunts_eleve_id_foreign` (`eleve_id`),
  ADD KEY `emprunts_enseignant_id_foreign` (`enseignant_id`),
  ADD KEY `emprunts_enregistre_par_foreign` (`enregistre_par`);

--
-- Index pour la table `enseignants`
--
ALTER TABLE `enseignants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `enseignants_matricule_unique` (`matricule`),
  ADD KEY `enseignants_user_id_foreign` (`user_id`);

--
-- Index pour la table `etablissement`
--
ALTER TABLE `etablissement`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `evaluations_matiere_id_foreign` (`matiere_id`),
  ADD KEY `evaluations_classe_id_foreign` (`classe_id`),
  ADD KEY `evaluations_sequence_id_foreign` (`sequence_id`),
  ADD KEY `evaluations_enseignant_id_foreign` (`enseignant_id`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `frais_scolarite`
--
ALTER TABLE `frais_scolarite`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `frais_unique` (`annee_scolaire_id`,`section_id`,`niveau`),
  ADD KEY `frais_scolarite_section_id_foreign` (`section_id`);

--
-- Index pour la table `groupes_matieres`
--
ALTER TABLE `groupes_matieres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `groupes_matieres_code_unique` (`code`),
  ADD KEY `groupes_matieres_section_id_foreign` (`section_id`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `livres`
--
ALTER TABLE `livres`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `matieres`
--
ALTER TABLE `matieres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `matieres_code_unique` (`code`),
  ADD KEY `matieres_section_id_foreign` (`section_id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Index pour la table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Index pour la table `niveaux`
--
ALTER TABLE `niveaux`
  ADD PRIMARY KEY (`id`),
  ADD KEY `niveaux_section_id_foreign` (`section_id`);

--
-- Index pour la table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `note_unique` (`eleve_id`,`matiere_id`,`classe_id`,`sequence_id`),
  ADD KEY `notes_matiere_id_foreign` (`matiere_id`),
  ADD KEY `notes_classe_id_foreign` (`classe_id`),
  ADD KEY `notes_sequence_id_foreign` (`sequence_id`),
  ADD KEY `notes_enseignant_id_foreign` (`enseignant_id`);

--
-- Index pour la table `paiements_eleves`
--
ALTER TABLE `paiements_eleves`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `paiements_mobile_money`
--
ALTER TABLE `paiements_mobile_money`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paiements_mobile_money_scolarite_id_foreign` (`scolarite_id`),
  ADD KEY `paiements_mobile_money_initie_par_foreign` (`initie_par`),
  ADD KEY `paiements_mobile_money_verifie_par_foreign` (`verifie_par`);

--
-- Index pour la table `paiements_scolarite`
--
ALTER TABLE `paiements_scolarite`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paiements_scolarite_scolarite_id_foreign` (`scolarite_id`),
  ADD KEY `paiements_scolarite_enregistre_par_foreign` (`enregistre_par`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Index pour la table `requetes`
--
ALTER TABLE `requetes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `requetes_eleve_id_foreign` (`eleve_id`),
  ADD KEY `requetes_annee_scolaire_id_foreign` (`annee_scolaire_id`),
  ADD KEY `requetes_traitee_par_foreign` (`traitee_par`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Index pour la table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Index pour la table `scolarites`
--
ALTER TABLE `scolarites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `scolarite_unique` (`eleve_id`,`annee_scolaire_id`),
  ADD KEY `scolarites_classe_id_foreign` (`classe_id`),
  ADD KEY `scolarites_annee_scolaire_id_foreign` (`annee_scolaire_id`),
  ADD KEY `scolarites_zone_transport_id_foreign` (`zone_transport_id`);

--
-- Index pour la table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sections_code_unique` (`code`);

--
-- Index pour la table `sequences`
--
ALTER TABLE `sequences`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sequences_numero_trimestre_id_unique` (`numero`,`trimestre_id`),
  ADD KEY `sequences_trimestre_id_foreign` (`trimestre_id`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Index pour la table `transferts`
--
ALTER TABLE `transferts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transferts_eleve_id_foreign` (`eleve_id`),
  ADD KEY `transferts_classe_source_id_foreign` (`classe_source_id`),
  ADD KEY `transferts_classe_destination_id_foreign` (`classe_destination_id`),
  ADD KEY `transferts_annee_scolaire_id_foreign` (`annee_scolaire_id`),
  ADD KEY `transferts_effectue_par_foreign` (`effectue_par`);

--
-- Index pour la table `travaux_diriges`
--
ALTER TABLE `travaux_diriges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `travaux_diriges_enseignant_id_foreign` (`enseignant_id`),
  ADD KEY `travaux_diriges_matiere_id_foreign` (`matiere_id`),
  ADD KEY `travaux_diriges_classe_id_foreign` (`classe_id`),
  ADD KEY `travaux_diriges_annee_scolaire_id_foreign` (`annee_scolaire_id`);

--
-- Index pour la table `trimestres`
--
ALTER TABLE `trimestres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `trimestres_numero_annee_scolaire_id_unique` (`numero`,`annee_scolaire_id`),
  ADD KEY `trimestres_annee_scolaire_id_foreign` (`annee_scolaire_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Index pour la table `zones_transport`
--
ALTER TABLE `zones_transport`
  ADD PRIMARY KEY (`id`),
  ADD KEY `zones_transport_annee_scolaire_id_foreign` (`annee_scolaire_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `absences`
--
ALTER TABLE `absences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `affectations`
--
ALTER TABLE `affectations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT pour la table `annees_scolaires`
--
ALTER TABLE `annees_scolaires`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `candidatures`
--
ALTER TABLE `candidatures`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `candidature_documents`
--
ALTER TABLE `candidature_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT pour la table `classe_matiere`
--
ALTER TABLE `classe_matiere`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=167;

--
-- AUTO_INCREMENT pour la table `comptes_generes`
--
ALTER TABLE `comptes_generes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `conseils_classe`
--
ALTER TABLE `conseils_classe`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `decisions_conseil`
--
ALTER TABLE `decisions_conseil`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `eleves`
--
ALTER TABLE `eleves`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT pour la table `emplois_temps`
--
ALTER TABLE `emplois_temps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `emprunts`
--
ALTER TABLE `emprunts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `enseignants`
--
ALTER TABLE `enseignants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `etablissement`
--
ALTER TABLE `etablissement`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `frais_scolarite`
--
ALTER TABLE `frais_scolarite`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `groupes_matieres`
--
ALTER TABLE `groupes_matieres`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `livres`
--
ALTER TABLE `livres`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `matieres`
--
ALTER TABLE `matieres`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT pour la table `niveaux`
--
ALTER TABLE `niveaux`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT pour la table `paiements_eleves`
--
ALTER TABLE `paiements_eleves`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `paiements_mobile_money`
--
ALTER TABLE `paiements_mobile_money`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `paiements_scolarite`
--
ALTER TABLE `paiements_scolarite`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT pour la table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `requetes`
--
ALTER TABLE `requetes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `scolarites`
--
ALTER TABLE `scolarites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `sequences`
--
ALTER TABLE `sequences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `transferts`
--
ALTER TABLE `transferts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `travaux_diriges`
--
ALTER TABLE `travaux_diriges`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `trimestres`
--
ALTER TABLE `trimestres`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `zones_transport`
--
ALTER TABLE `zones_transport`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `absences`
--
ALTER TABLE `absences`
  ADD CONSTRAINT `absences_classe_id_foreign` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `absences_eleve_id_foreign` FOREIGN KEY (`eleve_id`) REFERENCES `eleves` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `absences_emploi_temps_id_foreign` FOREIGN KEY (`emploi_temps_id`) REFERENCES `emplois_temps` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `absences_signale_par_foreign` FOREIGN KEY (`signale_par`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `affectations`
--
ALTER TABLE `affectations`
  ADD CONSTRAINT `affectations_annee_scolaire_id_foreign` FOREIGN KEY (`annee_scolaire_id`) REFERENCES `annees_scolaires` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `affectations_classe_id_foreign` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `affectations_enseignant_id_foreign` FOREIGN KEY (`enseignant_id`) REFERENCES `enseignants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `affectations_matiere_id_foreign` FOREIGN KEY (`matiere_id`) REFERENCES `matieres` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `candidatures`
--
ALTER TABLE `candidatures`
  ADD CONSTRAINT `candidatures_eleve_id_foreign` FOREIGN KEY (`eleve_id`) REFERENCES `eleves` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `candidatures_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `candidatures_traite_par_foreign` FOREIGN KEY (`traite_par`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `candidature_documents`
--
ALTER TABLE `candidature_documents`
  ADD CONSTRAINT `candidature_documents_candidature_id_foreign` FOREIGN KEY (`candidature_id`) REFERENCES `candidatures` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_annee_scolaire_id_foreign` FOREIGN KEY (`annee_scolaire_id`) REFERENCES `annees_scolaires` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `classes_groupe_matiere_id_foreign` FOREIGN KEY (`groupe_matiere_id`) REFERENCES `groupes_matieres` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `classes_professeur_principal_id_foreign` FOREIGN KEY (`professeur_principal_id`) REFERENCES `enseignants` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `classes_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `classe_matiere`
--
ALTER TABLE `classe_matiere`
  ADD CONSTRAINT `classe_matiere_classe_id_foreign` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `classe_matiere_matiere_id_foreign` FOREIGN KEY (`matiere_id`) REFERENCES `matieres` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `comptes_generes`
--
ALTER TABLE `comptes_generes`
  ADD CONSTRAINT `comptes_generes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `conseils_classe`
--
ALTER TABLE `conseils_classe`
  ADD CONSTRAINT `conseils_classe_annee_scolaire_id_foreign` FOREIGN KEY (`annee_scolaire_id`) REFERENCES `annees_scolaires` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conseils_classe_classe_id_foreign` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conseils_classe_cree_par_foreign` FOREIGN KEY (`cree_par`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conseils_classe_preside_par_foreign` FOREIGN KEY (`preside_par`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `conseils_classe_trimestre_id_foreign` FOREIGN KEY (`trimestre_id`) REFERENCES `trimestres` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `decisions_conseil`
--
ALTER TABLE `decisions_conseil`
  ADD CONSTRAINT `decisions_conseil_conseil_classe_id_foreign` FOREIGN KEY (`conseil_classe_id`) REFERENCES `conseils_classe` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `decisions_conseil_decidee_par_foreign` FOREIGN KEY (`decidee_par`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `decisions_conseil_eleve_id_foreign` FOREIGN KEY (`eleve_id`) REFERENCES `eleves` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `eleves`
--
ALTER TABLE `eleves`
  ADD CONSTRAINT `eleves_classe_id_foreign` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `eleves_eleve_user_id_foreign` FOREIGN KEY (`eleve_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `eleves_parent_user_id_foreign` FOREIGN KEY (`parent_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `emplois_temps`
--
ALTER TABLE `emplois_temps`
  ADD CONSTRAINT `emplois_temps_annee_scolaire_id_foreign` FOREIGN KEY (`annee_scolaire_id`) REFERENCES `annees_scolaires` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `emplois_temps_classe_id_foreign` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `emplois_temps_enseignant_id_foreign` FOREIGN KEY (`enseignant_id`) REFERENCES `enseignants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `emplois_temps_matiere_id_foreign` FOREIGN KEY (`matiere_id`) REFERENCES `matieres` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `emprunts`
--
ALTER TABLE `emprunts`
  ADD CONSTRAINT `emprunts_eleve_id_foreign` FOREIGN KEY (`eleve_id`) REFERENCES `eleves` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `emprunts_enregistre_par_foreign` FOREIGN KEY (`enregistre_par`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `emprunts_enseignant_id_foreign` FOREIGN KEY (`enseignant_id`) REFERENCES `enseignants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `emprunts_livre_id_foreign` FOREIGN KEY (`livre_id`) REFERENCES `livres` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `enseignants`
--
ALTER TABLE `enseignants`
  ADD CONSTRAINT `enseignants_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `evaluations`
--
ALTER TABLE `evaluations`
  ADD CONSTRAINT `evaluations_classe_id_foreign` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluations_enseignant_id_foreign` FOREIGN KEY (`enseignant_id`) REFERENCES `enseignants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluations_matiere_id_foreign` FOREIGN KEY (`matiere_id`) REFERENCES `matieres` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluations_sequence_id_foreign` FOREIGN KEY (`sequence_id`) REFERENCES `sequences` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `frais_scolarite`
--
ALTER TABLE `frais_scolarite`
  ADD CONSTRAINT `frais_scolarite_annee_scolaire_id_foreign` FOREIGN KEY (`annee_scolaire_id`) REFERENCES `annees_scolaires` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `frais_scolarite_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `groupes_matieres`
--
ALTER TABLE `groupes_matieres`
  ADD CONSTRAINT `groupes_matieres_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `matieres`
--
ALTER TABLE `matieres`
  ADD CONSTRAINT `matieres_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `niveaux`
--
ALTER TABLE `niveaux`
  ADD CONSTRAINT `niveaux_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_classe_id_foreign` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notes_eleve_id_foreign` FOREIGN KEY (`eleve_id`) REFERENCES `eleves` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notes_enseignant_id_foreign` FOREIGN KEY (`enseignant_id`) REFERENCES `enseignants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notes_matiere_id_foreign` FOREIGN KEY (`matiere_id`) REFERENCES `matieres` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notes_sequence_id_foreign` FOREIGN KEY (`sequence_id`) REFERENCES `sequences` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `paiements_mobile_money`
--
ALTER TABLE `paiements_mobile_money`
  ADD CONSTRAINT `paiements_mobile_money_initie_par_foreign` FOREIGN KEY (`initie_par`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `paiements_mobile_money_scolarite_id_foreign` FOREIGN KEY (`scolarite_id`) REFERENCES `scolarites` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `paiements_mobile_money_verifie_par_foreign` FOREIGN KEY (`verifie_par`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `paiements_scolarite`
--
ALTER TABLE `paiements_scolarite`
  ADD CONSTRAINT `paiements_scolarite_enregistre_par_foreign` FOREIGN KEY (`enregistre_par`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `paiements_scolarite_scolarite_id_foreign` FOREIGN KEY (`scolarite_id`) REFERENCES `scolarites` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `requetes`
--
ALTER TABLE `requetes`
  ADD CONSTRAINT `requetes_annee_scolaire_id_foreign` FOREIGN KEY (`annee_scolaire_id`) REFERENCES `annees_scolaires` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `requetes_eleve_id_foreign` FOREIGN KEY (`eleve_id`) REFERENCES `eleves` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `requetes_traitee_par_foreign` FOREIGN KEY (`traitee_par`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `scolarites`
--
ALTER TABLE `scolarites`
  ADD CONSTRAINT `scolarites_annee_scolaire_id_foreign` FOREIGN KEY (`annee_scolaire_id`) REFERENCES `annees_scolaires` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `scolarites_classe_id_foreign` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `scolarites_eleve_id_foreign` FOREIGN KEY (`eleve_id`) REFERENCES `eleves` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `scolarites_zone_transport_id_foreign` FOREIGN KEY (`zone_transport_id`) REFERENCES `zones_transport` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `sequences`
--
ALTER TABLE `sequences`
  ADD CONSTRAINT `sequences_trimestre_id_foreign` FOREIGN KEY (`trimestre_id`) REFERENCES `trimestres` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `transferts`
--
ALTER TABLE `transferts`
  ADD CONSTRAINT `transferts_annee_scolaire_id_foreign` FOREIGN KEY (`annee_scolaire_id`) REFERENCES `annees_scolaires` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transferts_classe_destination_id_foreign` FOREIGN KEY (`classe_destination_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transferts_classe_source_id_foreign` FOREIGN KEY (`classe_source_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transferts_effectue_par_foreign` FOREIGN KEY (`effectue_par`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transferts_eleve_id_foreign` FOREIGN KEY (`eleve_id`) REFERENCES `eleves` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `travaux_diriges`
--
ALTER TABLE `travaux_diriges`
  ADD CONSTRAINT `travaux_diriges_annee_scolaire_id_foreign` FOREIGN KEY (`annee_scolaire_id`) REFERENCES `annees_scolaires` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `travaux_diriges_classe_id_foreign` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `travaux_diriges_enseignant_id_foreign` FOREIGN KEY (`enseignant_id`) REFERENCES `enseignants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `travaux_diriges_matiere_id_foreign` FOREIGN KEY (`matiere_id`) REFERENCES `matieres` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `trimestres`
--
ALTER TABLE `trimestres`
  ADD CONSTRAINT `trimestres_annee_scolaire_id_foreign` FOREIGN KEY (`annee_scolaire_id`) REFERENCES `annees_scolaires` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `zones_transport`
--
ALTER TABLE `zones_transport`
  ADD CONSTRAINT `zones_transport_annee_scolaire_id_foreign` FOREIGN KEY (`annee_scolaire_id`) REFERENCES `annees_scolaires` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
