-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 01. Jul 2026 um 08:12
-- Server-Version: 10.4.32-MariaDB
-- PHP-Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `gesco`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `absences`
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

--
-- Daten für Tabelle `absences`
--

INSERT INTO `absences` (`id`, `eleve_id`, `classe_id`, `emploi_temps_id`, `date_absence`, `type`, `justifiee`, `motif`, `signale_par`, `created_at`, `updated_at`) VALUES
(2, 4, 1, NULL, '2026-06-30', 'retard', 0, NULL, 1, '2026-06-30 08:59:17', '2026-06-30 08:59:17'),
(3, 5, 1, NULL, '2026-06-30', 'absence', 0, NULL, 1, '2026-06-30 08:59:17', '2026-06-30 08:59:17'),
(4, 3, 5, NULL, '2026-06-30', 'absence', 0, NULL, 1, '2026-06-30 09:05:23', '2026-06-30 09:05:23');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `affectations`
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
-- Daten für Tabelle `affectations`
--

INSERT INTO `affectations` (`id`, `enseignant_id`, `matiere_id`, `classe_id`, `annee_scolaire_id`, `created_at`, `updated_at`) VALUES
(1, 4, 4, 1, 1, '2026-06-29 22:11:09', '2026-06-29 22:11:09'),
(2, 3, 1, 2, 1, '2026-06-29 22:17:05', '2026-06-29 22:17:05'),
(3, 3, 3, 6, 1, '2026-06-29 22:17:48', '2026-06-29 22:17:48'),
(4, 1, 6, 7, 1, '2026-06-30 07:58:32', '2026-06-30 07:58:32'),
(5, 4, 4, 6, 1, '2026-06-30 16:52:10', '2026-06-30 16:52:10');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `annees_scolaires`
--

CREATE TABLE `annees_scolaires` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `libelle` varchar(255) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `annees_scolaires`
--

INSERT INTO `annees_scolaires` (`id`, `libelle`, `date_debut`, `date_fin`, `active`, `created_at`, `updated_at`) VALUES
(1, '2025-2026', '2025-09-01', '2026-07-31', 1, '2026-06-24 23:31:21', '2026-06-24 23:31:21'),
(2, '2026-2027', '2026-09-05', '2027-04-30', 0, '2026-06-29 17:19:03', '2026-06-29 17:19:03'),
(3, '2027-2028', '2027-09-05', '2028-04-30', 0, '2026-06-29 17:46:51', '2026-06-29 17:46:51');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `candidatures`
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
-- Daten für Tabelle `candidatures`
--

INSERT INTO `candidatures` (`id`, `reference`, `nom`, `prenom`, `date_naissance`, `lieu_naissance`, `sexe`, `classe_demandee`, `section_id`, `etablissement_origine`, `nom_parent`, `telephone_parent`, `email_parent`, `adresse`, `statut`, `motif_refus`, `eleve_id`, `traite_par`, `notifie_le`, `created_at`, `updated_at`) VALUES
(1, 'CAND2026-0001', 'TCHEDJEU NGANKOUE', 'MAELYS', '2023-02-17', 'Douala', 'F', '6ème', 2, NULL, 'JOSEPH MARTIAL', '677865320', 'josephngankoue95@gmail.com', 'Logpom-Douala', 'acceptee', NULL, 1, 1, '2026-06-26 18:50:19', '2026-06-26 18:47:27', '2026-06-26 18:50:19'),
(2, 'CAND2026-0002', 'NANA IVANA', 'CHARLOTTE', '1998-03-23', 'Yaoundé', 'F', 'Terminale', 1, 'Collège Le Nil', 'NGABOU ESTHER', '677865320', 'josephngankoue95@gmail.com', 'PK14', 'acceptee', NULL, 2, 1, '2026-06-26 20:53:49', '2026-06-26 20:33:54', '2026-06-26 20:53:49'),
(3, 'CAND2026-0003', 'RUSSEL', 'DONGO', '1994-03-20', 'LOUM', 'M', '2nde', 1, 'Conquête', 'BAMBA ALAIN', '677865320', 'paymepos1@gmail.com', 'Makepe', 'refusee', 'Note pas Convainquante', NULL, 1, '2026-06-26 20:48:14', '2026-06-26 20:46:08', '2026-06-26 20:48:14'),
(4, 'CAND2026-0004', 'NGANSO', 'Roberto', '2000-06-30', NULL, 'M', 'Terminale', 1, NULL, 'MAFFO KEGNE', '678962587', NULL, 'Kotto', 'acceptee', NULL, 8, 1, '2026-06-30 14:33:43', '2026-06-30 14:23:37', '2026-06-30 14:33:43');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `candidature_documents`
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
-- Daten für Tabelle `candidature_documents`
--

INSERT INTO `candidature_documents` (`id`, `candidature_id`, `type`, `nom_fichier`, `chemin`, `created_at`, `updated_at`) VALUES
(1, 1, 'acte_naissance', 'kyc(3).pdf', 'candidatures/CAND2026-0001/LspDdkURpFlUWNiCqDz4pFixvLa1bicngE88Yi9o.pdf', '2026-06-26 18:47:28', '2026-06-26 18:47:28'),
(2, 2, 'acte_naissance', 'conformité LBC_FT.pdf', 'candidatures/CAND2026-0002/z2CqxQzYnqpHWrUs3ePJrOugvwm6c0JILtbzfFHX.pdf', '2026-06-26 20:33:54', '2026-06-26 20:33:54'),
(3, 2, 'bulletin_precedent', 'Manuel-dutilisation-Bokeland-School-System.pdf', 'candidatures/CAND2026-0002/nN37HpXefN3OhWzJb6RANMikwDavG3q48L3qtnGM.pdf', '2026-06-26 20:33:54', '2026-06-26 20:33:54'),
(4, 2, 'certificat_scolarite', 'souris-sans-fil-hybride-rechargeable-noire-pas-cher-KeyOuest.png', 'candidatures/CAND2026-0002/7rPYXCMVCSArNmca1yF7dUV32xNGbk6Z1y3pmEwz.png', '2026-06-26 20:33:54', '2026-06-26 20:33:54'),
(5, 2, 'photo', 'WhatsApp Image 2026-06-18 at 11.26.27.jpeg', 'candidatures/CAND2026-0002/tgdVE8tVbFBByl8xaDhB96C76a845GSJF60drw6h.jpg', '2026-06-26 20:33:54', '2026-06-26 20:33:54'),
(6, 3, 'acte_naissance', 'Les Raisons de Choisir MaMoni Finance.pdf', 'candidatures/CAND2026-0003/I4wCLegUB0UyNtox5x2RkfwKSxkB2zSiJLMaapLa.pdf', '2026-06-26 20:46:08', '2026-06-26 20:46:08'),
(7, 3, 'bulletin_precedent', 'Attestation Licence_035847.pdf', 'candidatures/CAND2026-0003/Wz4Dcxj6Ixzk0dCT1j5Uq5ZCd0Ee0bS76zStcNyk.pdf', '2026-06-26 20:46:08', '2026-06-26 20:46:08'),
(8, 3, 'certificat_scolarite', 'Preparation_telc_B1_Sprechen.pdf', 'candidatures/CAND2026-0003/aWMh6GBZCE9yNfLPEgNnHZ2EPi08C0OSd6Edzvo0.pdf', '2026-06-26 20:46:08', '2026-06-26 20:46:08'),
(9, 3, 'photo', 'WhatsApp Image 2025-09-04 à 12.53.40_be3e1934.jpg', 'candidatures/CAND2026-0003/3zVFFmhhafc6PPas9yxL1XZEezs7LWiTeldSgg6l.jpg', '2026-06-26 20:46:08', '2026-06-26 20:46:08'),
(10, 4, 'acte_naissance', 'MA MONI .pdf', 'candidatures/CAND2026-0004/pDxKLZkt10767hq55HOSPT3fD3pv7n7FGMY5zFfP.pdf', '2026-06-30 14:23:38', '2026-06-30 14:23:38');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `classes`
--

CREATE TABLE `classes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `niveau` varchar(255) NOT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `groupe_matiere_id` bigint(20) UNSIGNED DEFAULT NULL,
  `annee_scolaire_id` bigint(20) UNSIGNED NOT NULL,
  `capacite_max` int(11) NOT NULL DEFAULT 50,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `classes`
--

INSERT INTO `classes` (`id`, `nom`, `niveau`, `section_id`, `groupe_matiere_id`, `annee_scolaire_id`, `capacite_max`, `created_at`, `updated_at`) VALUES
(1, 'Tle A', 'Terminale', 1, NULL, 1, 40, '2026-06-26 15:20:32', '2026-06-26 15:20:32'),
(2, '6e A', 'Sixième', 1, NULL, 1, 50, '2026-06-26 15:23:02', '2026-06-26 15:23:02'),
(3, 'F1', 'Form 1', 2, NULL, 1, 70, '2026-06-26 15:24:05', '2026-06-26 18:12:02'),
(5, 'F2', 'Form 2', 2, NULL, 1, 52, '2026-06-26 18:11:37', '2026-06-26 18:11:37'),
(6, 'Tle D', 'Terminale', 1, NULL, 1, 25, '2026-06-26 20:53:07', '2026-06-26 20:53:07'),
(7, 'PF2', 'Première', 1, NULL, 1, 70, '2026-06-30 07:56:09', '2026-06-30 07:56:09');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `eleves`
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
  `nom_pere` varchar(255) DEFAULT NULL,
  `nom_mere` varchar(255) DEFAULT NULL,
  `telephone_parent` varchar(255) DEFAULT NULL,
  `email_parent` varchar(255) DEFAULT NULL,
  `adresse` text DEFAULT NULL,
  `parent_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `statut` enum('actif','inactif','transfere','diplome') NOT NULL DEFAULT 'actif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `eleves`
--

INSERT INTO `eleves` (`id`, `matricule`, `nom`, `prenom`, `date_naissance`, `lieu_naissance`, `sexe`, `photo`, `nom_pere`, `nom_mere`, `telephone_parent`, `email_parent`, `adresse`, `parent_user_id`, `statut`, `created_at`, `updated_at`) VALUES
(1, 'ELV20260001', 'TCHEDJEU NGANKOUE', 'MAELYS', '2023-02-17', 'Douala', 'F', NULL, 'ALAIN TCHEDJEU', 'TSAGUE VALENTINE', '677865320', 'josephngankoue95@gmail.com', 'Logpom-Douala', NULL, 'actif', '2026-06-26 18:50:19', '2026-06-29 22:16:09'),
(2, 'ELV20260002', 'NANA IVANA', 'CHARLOTTE', '1998-03-23', 'Yaoundé', 'F', NULL, NULL, NULL, '677865320', 'josephngankoue95@gmail.com', 'PK14', NULL, 'actif', '2026-06-26 20:53:49', '2026-06-26 20:53:49'),
(3, 'ELV20260003', 'SALVADOR', 'RYU', '2000-02-21', 'Edea', 'M', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'actif', '2026-06-28 10:46:02', '2026-06-28 10:46:02'),
(4, 'ELV20260004', 'ABOUBACAR', 'VINCENT', '1988-02-02', NULL, 'M', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'actif', '2026-06-29 22:14:08', '2026-06-29 22:14:08'),
(5, 'ELV20260005', 'TOKO EKAMBI', 'KARL', '1996-01-12', 'Douala', 'M', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'actif', '2026-06-29 22:14:49', '2026-06-29 22:14:49'),
(6, 'ELV20260006', 'NDOUMBE LOKO', 'EMMANUEL', '2006-03-12', NULL, 'M', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'actif', '2026-06-30 07:56:56', '2026-06-30 07:56:56'),
(7, 'ELV20260007', 'SAA DE MENZI', 'IVONE', '2003-12-25', NULL, 'F', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'actif', '2026-06-30 07:57:52', '2026-06-30 07:57:52'),
(8, 'ELV20260008', 'NGANSO', 'Roberto', '2000-06-30', NULL, 'M', 'eleves/mZI6CznjDPfzf73CXpYk01SlclIA1jBfGbjqgqxp.jpg', NULL, NULL, '678962587', NULL, 'Kotto', NULL, 'actif', '2026-06-30 14:33:43', '2026-06-30 14:34:59');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `emplois_temps`
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
-- Daten für Tabelle `emplois_temps`
--

INSERT INTO `emplois_temps` (`id`, `classe_id`, `matiere_id`, `enseignant_id`, `annee_scolaire_id`, `jour`, `heure_debut`, `heure_fin`, `salle`, `created_at`, `updated_at`) VALUES
(1, 7, 1, 1, 1, 'lundi', '08:30:00', '11:30:00', 'SALLE A', '2026-06-30 10:22:23', '2026-06-30 10:22:23'),
(2, 7, 1, 1, 1, 'mercredi', '13:00:00', '16:00:00', 'SALLE A', '2026-06-30 10:22:58', '2026-06-30 10:22:58'),
(3, 7, 1, 1, 1, 'vendredi', '08:30:00', '11:30:00', 'SALLE A', '2026-06-30 10:23:27', '2026-06-30 10:23:27'),
(4, 7, 4, 2, 1, 'lundi', '12:00:00', '13:00:00', 'SALLE B', '2026-06-30 10:24:20', '2026-06-30 10:24:20');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `enseignants`
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
-- Daten für Tabelle `enseignants`
--

INSERT INTO `enseignants` (`id`, `user_id`, `matricule`, `specialite`, `diplome`, `date_recrutement`, `statut`, `created_at`, `updated_at`) VALUES
(1, 2, 'ENS20260001', 'Electronique', 'Master', NULL, 'actif', '2026-06-26 15:21:41', '2026-06-29 16:39:03'),
(2, 3, 'ENS20260002', 'Sciences Physiques', NULL, '2026-06-26', 'actif', '2026-06-26 18:06:41', '2026-06-30 10:10:58'),
(3, 4, 'ENS20260003', 'Infromatiques', NULL, '2026-06-12', 'actif', '2026-06-26 18:07:36', '2026-06-26 18:07:36'),
(4, 5, 'ENS20260004', 'Economie', 'Doctorat', '2026-06-29', 'actif', '2026-06-29 15:31:07', '2026-06-29 15:31:07');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `evaluations`
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
-- Daten für Tabelle `evaluations`
--

INSERT INTO `evaluations` (`id`, `titre`, `matiere_id`, `classe_id`, `sequence_id`, `enseignant_id`, `note_sur`, `date_evaluation`, `created_at`, `updated_at`) VALUES
(1, 'Devoir 1', 4, 1, 1, 4, 20.00, '2026-06-29', '2026-06-29 22:12:47', '2026-06-29 22:12:47'),
(2, 'Devoir 1', 1, 2, 1, 3, 20.00, '2026-06-29', '2026-06-29 23:00:04', '2026-06-29 23:00:04'),
(3, 'Devoir 1', 3, 6, 1, 3, 20.00, '2026-06-30', '2026-06-30 07:15:11', '2026-06-30 07:15:11'),
(4, 'Devoir 1', 6, 7, 1, 1, 20.00, '2026-06-30', '2026-06-30 08:02:28', '2026-06-30 08:02:28'),
(5, 'Composition 1', 4, 1, 2, 4, 20.00, '2026-06-30', '2026-06-30 17:06:19', '2026-06-30 17:06:19');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `failed_jobs`
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
-- Tabellenstruktur für Tabelle `groupes_matieres`
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
-- Daten für Tabelle `groupes_matieres`
--

INSERT INTO `groupes_matieres` (`id`, `nom`, `code`, `section_id`, `created_at`, `updated_at`) VALUES
(1, 'Tronc commun', 'TC-FR', 1, '2026-06-24 23:31:21', '2026-06-24 23:31:21'),
(2, 'Groupe 1', 'G1-FR', 1, '2026-06-30 12:51:11', '2026-06-30 12:51:11');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `groupe_matiere`
--

CREATE TABLE `groupe_matiere` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `groupe_matiere_id` bigint(20) UNSIGNED NOT NULL,
  `matiere_id` bigint(20) UNSIGNED NOT NULL,
  `coefficient_groupe` decimal(4,2) NOT NULL DEFAULT 1.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `groupe_matiere`
--

INSERT INTO `groupe_matiere` (`id`, `groupe_matiere_id`, `matiere_id`, `coefficient_groupe`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1.00, NULL, NULL),
(2, 1, 2, 1.00, NULL, NULL),
(3, 1, 3, 1.00, NULL, NULL),
(4, 1, 4, 1.00, NULL, NULL),
(5, 1, 5, 1.00, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `inscriptions`
--

CREATE TABLE `inscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `eleve_id` bigint(20) UNSIGNED NOT NULL,
  `classe_id` bigint(20) UNSIGNED NOT NULL,
  `annee_scolaire_id` bigint(20) UNSIGNED NOT NULL,
  `date_inscription` date NOT NULL,
  `type` enum('nouvelle','redoublant','transfert') NOT NULL DEFAULT 'nouvelle',
  `frais_inscription` decimal(10,2) NOT NULL DEFAULT 0.00,
  `montant_paye` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `inscriptions`
--

INSERT INTO `inscriptions` (`id`, `eleve_id`, `classe_id`, `annee_scolaire_id`, `date_inscription`, `type`, `frais_inscription`, `montant_paye`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 1, '2026-06-26', 'nouvelle', 20000.00, 0.00, '2026-06-26 18:50:19', '2026-06-26 18:50:19'),
(2, 2, 6, 1, '2026-06-26', 'nouvelle', 20000.00, 0.00, '2026-06-26 20:53:49', '2026-06-26 20:53:49'),
(3, 3, 5, 1, '2026-06-28', 'nouvelle', 15000.00, 0.00, '2026-06-28 10:46:02', '2026-06-28 10:46:02'),
(4, 4, 1, 1, '2026-06-29', 'nouvelle', 20000.00, 0.00, '2026-06-29 22:14:08', '2026-06-29 22:14:08'),
(5, 5, 1, 1, '2026-06-29', 'nouvelle', 20000.00, 0.00, '2026-06-29 22:14:49', '2026-06-29 22:14:49'),
(6, 6, 7, 1, '2026-06-30', 'nouvelle', 20000.00, 0.00, '2026-06-30 07:56:56', '2026-06-30 07:56:56'),
(7, 7, 7, 1, '2026-06-30', 'nouvelle', 20000.00, 0.00, '2026-06-30 07:57:52', '2026-06-30 07:57:52'),
(8, 8, 1, 1, '2026-06-30', 'nouvelle', 15000.00, 0.00, '2026-06-30 14:33:43', '2026-06-30 14:33:43');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `jobs`
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
-- Tabellenstruktur für Tabelle `job_batches`
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
-- Tabellenstruktur für Tabelle `matieres`
--

CREATE TABLE `matieres` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `code` varchar(20) NOT NULL,
  `coefficient` decimal(4,2) NOT NULL DEFAULT 1.00,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `matieres`
--

INSERT INTO `matieres` (`id`, `nom`, `code`, `coefficient`, `section_id`, `created_at`, `updated_at`) VALUES
(1, 'Mathématiques', 'MATH-FR', 4.00, 1, '2026-06-24 23:31:21', '2026-06-24 23:31:21'),
(2, 'Français', 'FR-FR', 4.00, 1, '2026-06-24 23:31:21', '2026-06-24 23:31:21'),
(3, 'Physique-Chimie', 'PC-FR', 3.00, 1, '2026-06-24 23:31:21', '2026-06-24 23:31:21'),
(4, 'Histoire-Géographie', 'HG-FR', 2.00, 1, '2026-06-24 23:31:21', '2026-06-24 23:31:21'),
(5, 'Anglais', 'ANG-FR', 2.00, 1, '2026-06-24 23:31:21', '2026-06-24 23:31:21'),
(6, 'Electronique', 'ELECT-FR', 8.00, 1, '2026-06-30 07:55:34', '2026-06-30 07:55:34');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `migrations`
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
(24, '2026_06_24_190916_create_candidature_documents_table', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(3, 'App\\Models\\User', 2),
(3, 'App\\Models\\User', 3),
(3, 'App\\Models\\User', 4),
(3, 'App\\Models\\User', 5);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `notes`
--

CREATE TABLE `notes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `evaluation_id` bigint(20) UNSIGNED NOT NULL,
  `eleve_id` bigint(20) UNSIGNED NOT NULL,
  `note` decimal(5,2) DEFAULT NULL,
  `absent` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `notes`
--

INSERT INTO `notes` (`id`, `evaluation_id`, `eleve_id`, `note`, `absent`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 12.00, 0, '2026-06-29 22:19:39', '2026-06-30 17:05:37'),
(2, 1, 5, 10.00, 0, '2026-06-29 22:19:39', '2026-06-29 22:19:39'),
(3, 3, 2, 12.00, 0, '2026-06-30 07:16:29', '2026-06-30 07:16:29'),
(4, 4, 6, 8.00, 0, '2026-06-30 08:06:55', '2026-06-30 08:06:55'),
(5, 4, 7, 12.00, 0, '2026-06-30 08:06:55', '2026-06-30 08:06:55'),
(6, 1, 8, 15.00, 0, '2026-06-30 17:05:37', '2026-06-30 17:05:37'),
(7, 5, 4, 7.00, 0, '2026-06-30 17:06:45', '2026-06-30 17:06:45'),
(8, 5, 5, 10.00, 0, '2026-06-30 17:06:45', '2026-06-30 17:06:45'),
(9, 5, 8, 14.00, 0, '2026-06-30 17:06:45', '2026-06-30 17:06:45');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `permissions`
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
-- Tabellenstruktur für Tabelle `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', '2026-06-24 23:31:21', '2026-06-24 23:31:21'),
(2, 'proviseur', 'web', '2026-06-24 23:31:21', '2026-06-24 23:31:21'),
(3, 'enseignant', 'web', '2026-06-24 23:31:21', '2026-06-24 23:31:21'),
(4, 'surveillant_general', 'web', '2026-06-24 23:31:21', '2026-06-24 23:31:21'),
(5, 'secretaire_intendant', 'web', '2026-06-24 23:31:21', '2026-06-24 23:31:21'),
(6, 'parent', 'web', '2026-06-24 23:31:21', '2026-06-24 23:31:21');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sections`
--

CREATE TABLE `sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `code` varchar(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `sections`
--

INSERT INTO `sections` (`id`, `nom`, `code`, `created_at`, `updated_at`) VALUES
(1, 'Francophone', 'FR', '2026-06-24 23:31:21', '2026-06-24 23:31:21'),
(2, 'Anglophone', 'ANG', '2026-06-24 23:31:21', '2026-06-24 23:31:21');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sequences`
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
-- Daten für Tabelle `sequences`
--

INSERT INTO `sequences` (`id`, `nom`, `numero`, `trimestre_id`, `date_debut`, `date_fin`, `created_at`, `updated_at`) VALUES
(1, 'Séquence 1', 1, 1, NULL, NULL, '2026-06-24 23:31:21', '2026-06-24 23:31:21'),
(2, 'Séquence 2', 2, 1, NULL, NULL, '2026-06-24 23:31:22', '2026-06-24 23:31:22'),
(3, 'Séquence 3', 3, 2, NULL, NULL, '2026-06-24 23:31:22', '2026-06-24 23:31:22'),
(4, 'Séquence 4', 4, 2, NULL, NULL, '2026-06-24 23:31:22', '2026-06-24 23:31:22'),
(5, 'Séquence 5', 5, 3, NULL, NULL, '2026-06-24 23:31:22', '2026-06-24 23:31:22'),
(6, 'Séquence 6', 6, 3, NULL, NULL, '2026-06-24 23:31:22', '2026-06-24 23:31:22');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `trimestres`
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
-- Daten für Tabelle `trimestres`
--

INSERT INTO `trimestres` (`id`, `nom`, `numero`, `annee_scolaire_id`, `date_debut`, `date_fin`, `created_at`, `updated_at`) VALUES
(1, 'Trimestre 1', 1, 1, NULL, NULL, '2026-06-24 23:31:21', '2026-06-24 23:31:21'),
(2, 'Trimestre 2', 2, 1, NULL, NULL, '2026-06-24 23:31:22', '2026-06-24 23:31:22'),
(3, 'Trimestre 3', 3, 1, NULL, NULL, '2026-06-24 23:31:22', '2026-06-24 23:31:22');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
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
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `telephone`, `photo`, `actif`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrateur Général', 'admin@gmail.cm', NULL, NULL, 1, NULL, '$2y$12$3jYZ9KfyNSdPWucam/tNzuMq9sMr4d3ZigCUwnD9j2.GfJf3S5NdG', NULL, '2026-06-24 23:31:22', '2026-06-25 12:48:50'),
(2, 'KEGNE SIMON', 'simon@gmail.com', '677854145', NULL, 1, NULL, '$2y$12$O1y3mdc4LpsyXsNYyxOBW.SLkThK6H1endOB0FUQ9rw6Fe5BoaomK', NULL, '2026-06-26 15:21:41', '2026-06-29 16:39:03'),
(3, 'TCHAMOU PIVOINE FABIOLA', 'pivoine@gmail.com', '659585214', NULL, 1, NULL, '$2y$12$KkBjldgFr.hOCNMYUKMjdeYRmjYU4.2FYKz9bqSmecdK/K4x5Fe7S', NULL, '2026-06-26 18:06:41', '2026-06-29 16:40:11'),
(4, 'NJAMEN MANUEL NATSU', 'njamen@gmail.com', '677458896', NULL, 1, NULL, '$2y$12$ofpU8LNsvtKWeqSWFUen/uqZ1n36kNxhR9bxkG4Wto9RdKLgX5Xw.', NULL, '2026-06-26 18:07:36', '2026-06-29 18:55:13'),
(5, 'NANA THEOPHILE', 'nana@gmail.com', '655214789', NULL, 1, NULL, '$2y$12$c.elbZ4uQIrFHBC4vdqzFe.pEXrOWakCgBw97nMr89dVtE819uCES', NULL, '2026-06-29 15:31:07', '2026-06-29 15:31:07');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `absences`
--
ALTER TABLE `absences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `absences_eleve_id_foreign` (`eleve_id`),
  ADD KEY `absences_classe_id_foreign` (`classe_id`),
  ADD KEY `absences_emploi_temps_id_foreign` (`emploi_temps_id`),
  ADD KEY `absences_signale_par_foreign` (`signale_par`);

--
-- Indizes für die Tabelle `affectations`
--
ALTER TABLE `affectations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `affectation_unique` (`enseignant_id`,`matiere_id`,`classe_id`,`annee_scolaire_id`),
  ADD KEY `affectations_matiere_id_foreign` (`matiere_id`),
  ADD KEY `affectations_classe_id_foreign` (`classe_id`),
  ADD KEY `affectations_annee_scolaire_id_foreign` (`annee_scolaire_id`);

--
-- Indizes für die Tabelle `annees_scolaires`
--
ALTER TABLE `annees_scolaires`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indizes für die Tabelle `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indizes für die Tabelle `candidatures`
--
ALTER TABLE `candidatures`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `candidatures_reference_unique` (`reference`),
  ADD KEY `candidatures_section_id_foreign` (`section_id`),
  ADD KEY `candidatures_eleve_id_foreign` (`eleve_id`),
  ADD KEY `candidatures_traite_par_foreign` (`traite_par`);

--
-- Indizes für die Tabelle `candidature_documents`
--
ALTER TABLE `candidature_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `candidature_documents_candidature_id_foreign` (`candidature_id`);

--
-- Indizes für die Tabelle `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `classes_section_id_foreign` (`section_id`),
  ADD KEY `classes_annee_scolaire_id_foreign` (`annee_scolaire_id`),
  ADD KEY `classes_groupe_matiere_id_foreign` (`groupe_matiere_id`);

--
-- Indizes für die Tabelle `eleves`
--
ALTER TABLE `eleves`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `eleves_matricule_unique` (`matricule`),
  ADD KEY `eleves_parent_user_id_foreign` (`parent_user_id`);

--
-- Indizes für die Tabelle `emplois_temps`
--
ALTER TABLE `emplois_temps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emplois_temps_classe_id_foreign` (`classe_id`),
  ADD KEY `emplois_temps_matiere_id_foreign` (`matiere_id`),
  ADD KEY `emplois_temps_enseignant_id_foreign` (`enseignant_id`),
  ADD KEY `emplois_temps_annee_scolaire_id_foreign` (`annee_scolaire_id`);

--
-- Indizes für die Tabelle `enseignants`
--
ALTER TABLE `enseignants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `enseignants_matricule_unique` (`matricule`),
  ADD KEY `enseignants_user_id_foreign` (`user_id`);

--
-- Indizes für die Tabelle `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `evaluations_matiere_id_foreign` (`matiere_id`),
  ADD KEY `evaluations_classe_id_foreign` (`classe_id`),
  ADD KEY `evaluations_sequence_id_foreign` (`sequence_id`),
  ADD KEY `evaluations_enseignant_id_foreign` (`enseignant_id`);

--
-- Indizes für die Tabelle `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indizes für die Tabelle `groupes_matieres`
--
ALTER TABLE `groupes_matieres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `groupes_matieres_code_unique` (`code`),
  ADD KEY `groupes_matieres_section_id_foreign` (`section_id`);

--
-- Indizes für die Tabelle `groupe_matiere`
--
ALTER TABLE `groupe_matiere`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `groupe_matiere_groupe_matiere_id_matiere_id_unique` (`groupe_matiere_id`,`matiere_id`),
  ADD KEY `groupe_matiere_matiere_id_foreign` (`matiere_id`);

--
-- Indizes für die Tabelle `inscriptions`
--
ALTER TABLE `inscriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inscriptions_eleve_id_annee_scolaire_id_unique` (`eleve_id`,`annee_scolaire_id`),
  ADD KEY `inscriptions_classe_id_foreign` (`classe_id`),
  ADD KEY `inscriptions_annee_scolaire_id_foreign` (`annee_scolaire_id`);

--
-- Indizes für die Tabelle `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indizes für die Tabelle `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `matieres`
--
ALTER TABLE `matieres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `matieres_code_unique` (`code`),
  ADD KEY `matieres_section_id_foreign` (`section_id`);

--
-- Indizes für die Tabelle `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indizes für die Tabelle `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indizes für die Tabelle `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `notes_evaluation_id_eleve_id_unique` (`evaluation_id`,`eleve_id`),
  ADD KEY `notes_eleve_id_foreign` (`eleve_id`);

--
-- Indizes für die Tabelle `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indizes für die Tabelle `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indizes für die Tabelle `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indizes für die Tabelle `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indizes für die Tabelle `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sections_code_unique` (`code`);

--
-- Indizes für die Tabelle `sequences`
--
ALTER TABLE `sequences`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sequences_numero_trimestre_id_unique` (`numero`,`trimestre_id`),
  ADD KEY `sequences_trimestre_id_foreign` (`trimestre_id`);

--
-- Indizes für die Tabelle `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indizes für die Tabelle `trimestres`
--
ALTER TABLE `trimestres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `trimestres_numero_annee_scolaire_id_unique` (`numero`,`annee_scolaire_id`),
  ADD KEY `trimestres_annee_scolaire_id_foreign` (`annee_scolaire_id`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `absences`
--
ALTER TABLE `absences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `affectations`
--
ALTER TABLE `affectations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT für Tabelle `annees_scolaires`
--
ALTER TABLE `annees_scolaires`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `candidatures`
--
ALTER TABLE `candidatures`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `candidature_documents`
--
ALTER TABLE `candidature_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT für Tabelle `classes`
--
ALTER TABLE `classes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT für Tabelle `eleves`
--
ALTER TABLE `eleves`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT für Tabelle `emplois_temps`
--
ALTER TABLE `emplois_temps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `enseignants`
--
ALTER TABLE `enseignants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT für Tabelle `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `groupes_matieres`
--
ALTER TABLE `groupes_matieres`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `groupe_matiere`
--
ALTER TABLE `groupe_matiere`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT für Tabelle `inscriptions`
--
ALTER TABLE `inscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT für Tabelle `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `matieres`
--
ALTER TABLE `matieres`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT für Tabelle `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT für Tabelle `notes`
--
ALTER TABLE `notes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT für Tabelle `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT für Tabelle `sections`
--
ALTER TABLE `sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `sequences`
--
ALTER TABLE `sequences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT für Tabelle `trimestres`
--
ALTER TABLE `trimestres`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `absences`
--
ALTER TABLE `absences`
  ADD CONSTRAINT `absences_classe_id_foreign` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `absences_eleve_id_foreign` FOREIGN KEY (`eleve_id`) REFERENCES `eleves` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `absences_emploi_temps_id_foreign` FOREIGN KEY (`emploi_temps_id`) REFERENCES `emplois_temps` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `absences_signale_par_foreign` FOREIGN KEY (`signale_par`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `affectations`
--
ALTER TABLE `affectations`
  ADD CONSTRAINT `affectations_annee_scolaire_id_foreign` FOREIGN KEY (`annee_scolaire_id`) REFERENCES `annees_scolaires` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `affectations_classe_id_foreign` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `affectations_enseignant_id_foreign` FOREIGN KEY (`enseignant_id`) REFERENCES `enseignants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `affectations_matiere_id_foreign` FOREIGN KEY (`matiere_id`) REFERENCES `matieres` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `candidatures`
--
ALTER TABLE `candidatures`
  ADD CONSTRAINT `candidatures_eleve_id_foreign` FOREIGN KEY (`eleve_id`) REFERENCES `eleves` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `candidatures_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `candidatures_traite_par_foreign` FOREIGN KEY (`traite_par`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints der Tabelle `candidature_documents`
--
ALTER TABLE `candidature_documents`
  ADD CONSTRAINT `candidature_documents_candidature_id_foreign` FOREIGN KEY (`candidature_id`) REFERENCES `candidatures` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_annee_scolaire_id_foreign` FOREIGN KEY (`annee_scolaire_id`) REFERENCES `annees_scolaires` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `classes_groupe_matiere_id_foreign` FOREIGN KEY (`groupe_matiere_id`) REFERENCES `groupes_matieres` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `classes_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `eleves`
--
ALTER TABLE `eleves`
  ADD CONSTRAINT `eleves_parent_user_id_foreign` FOREIGN KEY (`parent_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints der Tabelle `emplois_temps`
--
ALTER TABLE `emplois_temps`
  ADD CONSTRAINT `emplois_temps_annee_scolaire_id_foreign` FOREIGN KEY (`annee_scolaire_id`) REFERENCES `annees_scolaires` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `emplois_temps_classe_id_foreign` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `emplois_temps_enseignant_id_foreign` FOREIGN KEY (`enseignant_id`) REFERENCES `enseignants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `emplois_temps_matiere_id_foreign` FOREIGN KEY (`matiere_id`) REFERENCES `matieres` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `enseignants`
--
ALTER TABLE `enseignants`
  ADD CONSTRAINT `enseignants_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `evaluations`
--
ALTER TABLE `evaluations`
  ADD CONSTRAINT `evaluations_classe_id_foreign` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluations_enseignant_id_foreign` FOREIGN KEY (`enseignant_id`) REFERENCES `enseignants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluations_matiere_id_foreign` FOREIGN KEY (`matiere_id`) REFERENCES `matieres` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluations_sequence_id_foreign` FOREIGN KEY (`sequence_id`) REFERENCES `sequences` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `groupes_matieres`
--
ALTER TABLE `groupes_matieres`
  ADD CONSTRAINT `groupes_matieres_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `groupe_matiere`
--
ALTER TABLE `groupe_matiere`
  ADD CONSTRAINT `groupe_matiere_groupe_matiere_id_foreign` FOREIGN KEY (`groupe_matiere_id`) REFERENCES `groupes_matieres` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `groupe_matiere_matiere_id_foreign` FOREIGN KEY (`matiere_id`) REFERENCES `matieres` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `inscriptions`
--
ALTER TABLE `inscriptions`
  ADD CONSTRAINT `inscriptions_annee_scolaire_id_foreign` FOREIGN KEY (`annee_scolaire_id`) REFERENCES `annees_scolaires` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inscriptions_classe_id_foreign` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inscriptions_eleve_id_foreign` FOREIGN KEY (`eleve_id`) REFERENCES `eleves` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `matieres`
--
ALTER TABLE `matieres`
  ADD CONSTRAINT `matieres_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_eleve_id_foreign` FOREIGN KEY (`eleve_id`) REFERENCES `eleves` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notes_evaluation_id_foreign` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluations` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `sequences`
--
ALTER TABLE `sequences`
  ADD CONSTRAINT `sequences_trimestre_id_foreign` FOREIGN KEY (`trimestre_id`) REFERENCES `trimestres` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `trimestres`
--
ALTER TABLE `trimestres`
  ADD CONSTRAINT `trimestres_annee_scolaire_id_foreign` FOREIGN KEY (`annee_scolaire_id`) REFERENCES `annees_scolaires` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
