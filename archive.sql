-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 09 août 2024 à 23:07
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `archive`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('view','edit') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `role`) VALUES
(1, 'test', 'test', 'edit'),
(6, 'ouma', '$2y$10$Z3mPJ1Y9aMzqHwjvpX.t4OJvy3I8.BnC4vnLLMyFIkeTr31uMKsbK', 'view');

-- --------------------------------------------------------

--
-- Structure de la table `boite`
--

CREATE TABLE `boite` (
  `id_boite` int(11) NOT NULL,
  `nom_boite` varchar(255) DEFAULT NULL,
  `libelle_cotation` varchar(255) DEFAULT NULL,
  `date_creation` date DEFAULT NULL,
  `nom_admin` varchar(255) DEFAULT NULL,
  `duree_conservation` int(11) DEFAULT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `id_societe` int(11) DEFAULT NULL,
  `id_site` int(11) DEFAULT NULL,
  `id_service` int(11) DEFAULT NULL,
  `id_direction` int(11) DEFAULT NULL,
  `id_type_boite` int(11) DEFAULT NULL,
  `id_etagere` int(11) DEFAULT NULL,
  `archived` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `boite`
--

INSERT INTO `boite` (`id_boite`, `nom_boite`, `libelle_cotation`, `date_creation`, `nom_admin`, `duree_conservation`, `date_debut`, `date_fin`, `id_societe`, `id_site`, `id_service`, `id_direction`, `id_type_boite`, `id_etagere`, `archived`) VALUES
(10, 'Bon de livraison', 'palletes et chevrons', '2024-06-01', 'Oumaima', 5, '2024-06-01', '2029-06-01', 52, 14, 32, 78, 5, 115, 1),
(11, 'bon de paiment ', 'palletes et chevrons', '2024-02-01', 'Oumaima', 10, '2024-02-01', '2034-02-01', 44, 14, 5, 57, 4, 115, 1),
(12, 'bon de paiment ', 'palletes et chevrons', '2022-02-14', 'Oumaima', 10, '2022-02-14', '2032-02-14', 53, 19, 22, 65, 5, 115, 1),
(13, 'bon de paiment ', 'palletes et chevrons', '2024-08-08', 'Oumaima', 10, '2024-08-08', '2034-08-08', 51, 16, 18, 57, 6, 114, 1),
(14, 'bon de paiment', 'palletes et chevrons', '2024-08-08', 'Oumaima', 10, '2024-08-08', '2034-08-08', 53, 18, 20, 57, 5, 115, 1),
(15, 'bon de livrasion', 'palletes et chevrons', '2012-11-12', 'Oumaima', 10, '2012-11-12', '2022-11-12', 56, 19, 17, 62, 5, 115, 1);

-- --------------------------------------------------------

--
-- Structure de la table `colonne`
--

CREATE TABLE `colonne` (
  `id_colonne` int(11) NOT NULL,
  `longueur` decimal(10,2) DEFAULT NULL,
  `largeur` decimal(10,2) DEFAULT NULL,
  `id_etagere` int(11) DEFAULT NULL,
  `disponible` tinyint(1) DEFAULT 1,
  `disponibilite` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `direction`
--

CREATE TABLE `direction` (
  `id_direction` int(11) NOT NULL,
  `intitule` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `direction`
--

INSERT INTO `direction` (`id_direction`, `intitule`) VALUES
(51, 'COMMERCIALE'),
(52, 'RESSOURCES HUMAINES'),
(53, 'DIRECTION GENERALE'),
(54, 'FINANCIERE'),
(55, 'EXPLOITATION'),
(56, 'CONTRÔLE DE GESTION'),
(57, 'ACHAT'),
(58, 'PRESIDENCE'),
(59, 'COMMUNICATION'),
(60, 'MANAGEMENT DE PROJETS'),
(61, 'CONSEIL ET APPUI'),
(62, 'TECHNIQUE'),
(63, 'TRAVAUX ET MAINTENANCE'),
(64, 'TRANSPORT BPE'),
(65, 'MAINTENANCE INDUSTRIELLE'),
(66, 'EXPLOITATION TRANSPORT'),
(67, 'INDUSTRIELLE'),
(68, 'SYSTEME INFORMATIQUE'),
(69, 'JURIDIQUE'),
(70, 'FORMATION ET RECHERCHE'),
(71, 'ORGANISATION ET METHODES'),
(72, 'PLANIFICATION ET CONTROLE REALISATION'),
(73, 'RECOUVREMENT & CREDIT MANAGEMENT'),
(74, 'HYGIENE, SECURITE ET ENVIRONNEMENT'),
(75, 'PATRIMOINE'),
(76, 'SUIVI DE PROJETS ET MAINTENANCE'),
(77, 'INFRASTRUCTURE INFORMATIQUE'),
(78, 'ARCHIVE'),
(79, 'LOGISTIQUE'),
(80, 'PRESCRIPTION'),
(81, 'SUPPLY CHAIN'),
(82, 'DIRECTION TECHNIQUE'),
(83, 'EXPLOITATION GRANULAT'),
(84, 'CONTROLE QUALITE PRODUIT & SUPPORT TECHNIQUE'),
(85, 'SMI CONTROLE QUALITE PRODUITS ET R&D'),
(86, 'CONTROLE INTERNE'),
(87, 'TRAVAUX'),
(88, 'MARKETING'),
(89, 'AUDIT INTERNE'),
(90, 'SYSTEME DE MANAGEMENT INTEGRE'),
(91, 'LABORATOIRE'),
(92, 'SUPPORT'),
(93, 'RESTAURANT');

-- --------------------------------------------------------

--
-- Structure de la table `document`
--

CREATE TABLE `document` (
  `id_document` int(11) NOT NULL,
  `type_document` varchar(255) DEFAULT NULL,
  `id_service` int(11) DEFAULT NULL,
  `id_site` int(11) DEFAULT NULL,
  `id_societe` int(11) DEFAULT NULL,
  `informations_completes` text DEFAULT NULL,
  `annee` int(11) DEFAULT NULL,
  `responsable_classement` varchar(255) DEFAULT NULL,
  `id_boite` int(11) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `document`
--

INSERT INTO `document` (`id_document`, `type_document`, `id_service`, `id_site`, `id_societe`, `informations_completes`, `annee`, `responsable_classement`, `id_boite`, `file_path`) VALUES
(3, 'contrat', 33, 17, 48, 'contrat des employés', 2024, 'oumaima', 10, 'uploads/Rapport de stage.pdf'),
(4, 'PAIEMENET', 11, 16, 53, '2023-2024', 2024, 'oumaima', 12, 'uploads/Liste affectation (1).xlsx'),
(5, 'PAIEMENET', 7, 17, 54, 'paiement', 2024, 'oumaima', 13, 'uploads/atte.png'),
(6, 'PAIEMENET', 8, 16, 50, 'info', 2024, 'oumaima', 14, 'uploads/bulletein.png'),
(7, 'PAIEMENET', 7, 6, 55, 'INFORM', 2024, 'oumaima', 15, 'uploads/attestation.pdf');

-- --------------------------------------------------------

--
-- Structure de la table `etagere`
--

CREATE TABLE `etagere` (
  `id_etagere` int(11) NOT NULL,
  `longueur` decimal(10,2) DEFAULT NULL,
  `largeur` decimal(10,2) DEFAULT NULL,
  `disponibilite` tinyint(1) DEFAULT 1,
  `profondeur` decimal(10,2) DEFAULT NULL,
  `id_rayonnage` int(11) DEFAULT NULL,
  `espace_disponible` decimal(10,2) DEFAULT NULL,
  `numero` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `etagere`
--

INSERT INTO `etagere` (`id_etagere`, `longueur`, `largeur`, `disponibilite`, `profondeur`, `id_rayonnage`, `espace_disponible`, `numero`) VALUES
(114, 12.00, 10.00, 1, 9.00, 56, 90.00, 1),
(115, 60.00, 50.00, 1, 12.00, 56, 1545.00, 2),
(116, 12.00, 10.00, 1, 9.00, 57, NULL, 1),
(117, 60.00, 50.00, 1, 12.00, 57, NULL, 2);

-- --------------------------------------------------------

--
-- Structure de la table `rayonnage`
--

CREATE TABLE `rayonnage` (
  `id_rayonnage` int(11) NOT NULL,
  `id_site` int(11) DEFAULT NULL,
  `id_salle` int(11) DEFAULT NULL,
  `numero` int(11) NOT NULL,
  `longueur` decimal(10,2) DEFAULT NULL,
  `largeur` decimal(10,2) DEFAULT NULL,
  `disponibilite` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `rayonnage`
--

INSERT INTO `rayonnage` (`id_rayonnage`, `id_site`, `id_salle`, `numero`, `longueur`, `largeur`, `disponibilite`) VALUES
(56, NULL, 40, 1, 100.00, 50.00, 1),
(57, NULL, 41, 1, 100.00, 50.00, 1);

-- --------------------------------------------------------

--
-- Structure de la table `salle`
--

CREATE TABLE `salle` (
  `id_salle` int(11) NOT NULL,
  `localisation` varchar(255) NOT NULL,
  `id_site` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `salle`
--

INSERT INTO `salle` (`id_salle`, `localisation`, `id_site`) VALUES
(40, 'archive courant', 14),
(41, 'archive intermediaire', 14);

-- --------------------------------------------------------

--
-- Structure de la table `service`
--

CREATE TABLE `service` (
  `id_service` int(11) NOT NULL,
  `intitule` varchar(255) NOT NULL,
  `id_direction` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `service`
--

INSERT INTO `service` (`id_service`, `intitule`, `id_direction`) VALUES
(5, 'ADMINISTRATION DES VENTES', 51),
(6, 'FORMATION', 52),
(7, 'DIRECTION GENERALE', 53),
(8, 'COMPTABILITE', 54),
(9, 'EXPLOITATION SITE', 55),
(10, 'AUDIT INTERNE', 56),
(11, 'ACHATS', 57),
(12, 'PRESIDENCE', 58),
(13, 'COMMUNICATION INTERNE', 59),
(14, 'GESTION DE PROJETS', 60),
(15, 'CONSEIL EN MANAGEMENT', 61),
(16, 'BUREAU D\'ETUDES', 62),
(17, 'MAINTENANCE DES BATIMENTS', 63),
(18, 'GESTION DES TRANSPORTS', 64),
(19, 'MAINTENANCE EQUIPEMENTS', 65),
(20, 'GESTION DU PERSONNEL', 66),
(21, 'PRODUCTION INDUSTRIELLE', 67),
(22, 'GESTION DES SYSTEMES', 68),
(23, 'GESTION JURIDIQUE', 69),
(24, 'RECHERCHE & DEVELOPPEMENT', 70),
(25, 'ORGANISATION', 71),
(26, 'PLANIFICATION', 72),
(27, 'CREDIT MANAGEMENT', 73),
(28, 'SECURITE', 74),
(29, 'GESTION PATRIMOINE', 75),
(30, 'MAINTENANCE BATIMENTS', 76),
(31, 'INFRASTRUCTURE', 77),
(32, 'ARCHIVE', 78),
(33, 'LOGISTIQUE', 79),
(34, 'PRESCRIPTION', 80),
(35, 'SUPPLY CHAIN', 81),
(36, 'TECHNIQUE', 82),
(37, 'EXPLOITATION GRANULAT', 83),
(38, 'CONTROLE QUALITE', 84),
(39, 'RECHERCHE ET DEVELOPPEMENT', 85),
(40, 'CONTROLE INTERNE', 86),
(41, 'TRAVAUX', 87),
(42, 'MARKETING', 88),
(43, 'AUDIT', 89),
(44, 'MANAGEMENT INTEGRE', 90),
(45, 'LABORATOIRE', 91),
(46, 'SUPPORT', 92),
(47, 'RESTAURATION', 93);

-- --------------------------------------------------------

--
-- Structure de la table `site`
--

CREATE TABLE `site` (
  `id_site` int(11) NOT NULL,
  `code_site` int(11) DEFAULT NULL,
  `intitule` varchar(255) NOT NULL,
  `id_direction` int(11) DEFAULT NULL,
  `id_service` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `site`
--

INSERT INTO `site` (`id_site`, `code_site`, `intitule`, `id_direction`, `id_service`) VALUES
(5, NULL, 'AAKAR DEVELOPPEMENT MARRAKECH', 53, 7),
(6, NULL, 'ACADEMIE MENARA MARRAKECH', 52, 6),
(7, NULL, 'ML ADMINISTRATION MARRAKECH', 55, 5),
(8, NULL, 'MENARA LOGISTIQUE', 79, 33),
(9, NULL, 'MENARA INVESTISSEMENTS', 54, 8),
(10, NULL, 'MENARA TRAVAUX', 87, 41),
(11, NULL, 'MENARA INDUSTRIES', 67, 21),
(12, NULL, 'MENARA PREFABRIQUE', 62, 16),
(13, NULL, 'MENARA GRANULAT', 83, 9),
(14, NULL, 'MENARA HOLDING', 58, 12),
(15, NULL, 'MENARA SERVICES', NULL, 18),
(16, NULL, 'MENARA CARRIERES', NULL, NULL),
(17, NULL, 'MENARA RECHERCHE ET DEVELOPPEMENT', NULL, 45),
(18, NULL, 'MENARA TECHNOLOGIES', 68, 22),
(19, NULL, 'MENARA JURIDIQUE', 69, 23),
(20, NULL, 'MENARA SECURITE', 74, 28),
(21, NULL, 'MENARA COMMUNICATION', 59, 13),
(22, NULL, 'MENARA FORMATION', 70, 6),
(23, NULL, 'MENARA ORGANISATION', 71, 25),
(24, NULL, 'MENARA CONTROLE', 56, 10);

-- --------------------------------------------------------

--
-- Structure de la table `societe`
--

CREATE TABLE `societe` (
  `id_societe` int(11) NOT NULL,
  `intitule` varchar(255) NOT NULL,
  `id_site` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `societe`
--

INSERT INTO `societe` (`id_societe`, `intitule`, `id_site`) VALUES
(40, 'MENARA HOLDING', 14),
(41, 'MENARA PREFABRIQUE', 12),
(42, 'MENARA INDUSTRIES', 11),
(43, 'MENARA GRANULAT', 13),
(44, 'MENARA LOGISTIQUE', 8),
(45, 'MENARA CARRIERES', 16),
(46, 'MENARA TRAVAUX', 10),
(47, 'MENARA SERVICES', 15),
(48, 'MENARA COMMUNICATION', 21),
(49, 'MENARA TECHNOLOGIES', 18),
(50, 'MENARA INVESTISSEMENTS', 9),
(51, 'MENARA FORMATION', 22),
(52, 'MENARA JURIDIQUE', 19),
(53, 'MENARA RECHERCHE ET DEVELOPPEMENT', 17),
(54, 'MENARA SECURITE', 20),
(55, 'MENARA ORGANISATION', 23),
(56, 'MENARA CONTROLE', 24),
(57, 'MENARA ADMINISTRATION', 7),
(58, 'AAKAR DEVELOPPEMENT', 5),
(59, 'ACADEMIE MENARA', 6);

-- --------------------------------------------------------

--
-- Structure de la table `typeboite`
--

CREATE TABLE `typeboite` (
  `id_type_boite` int(11) NOT NULL,
  `type_boite` varchar(255) NOT NULL,
  `longueur` decimal(10,2) NOT NULL,
  `largeur` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `typeboite`
--

INSERT INTO `typeboite` (`id_type_boite`, `type_boite`, `longueur`, `largeur`) VALUES
(4, 'archivex', 30.00, 38.50),
(5, 'GM135', 15.00, 5.00),
(6, 'MM199', 10.00, 3.00);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `boite`
--
ALTER TABLE `boite`
  ADD PRIMARY KEY (`id_boite`),
  ADD KEY `id_societe` (`id_societe`),
  ADD KEY `id_site` (`id_site`),
  ADD KEY `id_service` (`id_service`),
  ADD KEY `id_direction` (`id_direction`),
  ADD KEY `id_type_boite` (`id_type_boite`);

--
-- Index pour la table `colonne`
--
ALTER TABLE `colonne`
  ADD PRIMARY KEY (`id_colonne`),
  ADD KEY `id_etagere` (`id_etagere`);

--
-- Index pour la table `direction`
--
ALTER TABLE `direction`
  ADD PRIMARY KEY (`id_direction`);

--
-- Index pour la table `document`
--
ALTER TABLE `document`
  ADD PRIMARY KEY (`id_document`),
  ADD KEY `id_service` (`id_service`),
  ADD KEY `id_site` (`id_site`),
  ADD KEY `id_societe` (`id_societe`),
  ADD KEY `id_boite` (`id_boite`);

--
-- Index pour la table `etagere`
--
ALTER TABLE `etagere`
  ADD PRIMARY KEY (`id_etagere`),
  ADD KEY `id_rayonnage` (`id_rayonnage`);

--
-- Index pour la table `rayonnage`
--
ALTER TABLE `rayonnage`
  ADD PRIMARY KEY (`id_rayonnage`),
  ADD KEY `id_site` (`id_site`),
  ADD KEY `id_salle` (`id_salle`);

--
-- Index pour la table `salle`
--
ALTER TABLE `salle`
  ADD PRIMARY KEY (`id_salle`),
  ADD KEY `id_site` (`id_site`);

--
-- Index pour la table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id_service`),
  ADD KEY `id_direction` (`id_direction`);

--
-- Index pour la table `site`
--
ALTER TABLE `site`
  ADD PRIMARY KEY (`id_site`),
  ADD KEY `id_direction` (`id_direction`),
  ADD KEY `id_service` (`id_service`);

--
-- Index pour la table `societe`
--
ALTER TABLE `societe`
  ADD PRIMARY KEY (`id_societe`),
  ADD KEY `id_site` (`id_site`);

--
-- Index pour la table `typeboite`
--
ALTER TABLE `typeboite`
  ADD PRIMARY KEY (`id_type_boite`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `boite`
--
ALTER TABLE `boite`
  MODIFY `id_boite` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `colonne`
--
ALTER TABLE `colonne`
  MODIFY `id_colonne` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `direction`
--
ALTER TABLE `direction`
  MODIFY `id_direction` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT pour la table `document`
--
ALTER TABLE `document`
  MODIFY `id_document` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `etagere`
--
ALTER TABLE `etagere`
  MODIFY `id_etagere` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT pour la table `rayonnage`
--
ALTER TABLE `rayonnage`
  MODIFY `id_rayonnage` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT pour la table `salle`
--
ALTER TABLE `salle`
  MODIFY `id_salle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT pour la table `service`
--
ALTER TABLE `service`
  MODIFY `id_service` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT pour la table `site`
--
ALTER TABLE `site`
  MODIFY `id_site` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `societe`
--
ALTER TABLE `societe`
  MODIFY `id_societe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT pour la table `typeboite`
--
ALTER TABLE `typeboite`
  MODIFY `id_type_boite` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `boite`
--
ALTER TABLE `boite`
  ADD CONSTRAINT `boite_ibfk_1` FOREIGN KEY (`id_societe`) REFERENCES `societe` (`id_societe`),
  ADD CONSTRAINT `boite_ibfk_2` FOREIGN KEY (`id_site`) REFERENCES `site` (`id_site`),
  ADD CONSTRAINT `boite_ibfk_3` FOREIGN KEY (`id_service`) REFERENCES `service` (`id_service`),
  ADD CONSTRAINT `boite_ibfk_4` FOREIGN KEY (`id_direction`) REFERENCES `direction` (`id_direction`),
  ADD CONSTRAINT `boite_ibfk_5` FOREIGN KEY (`id_type_boite`) REFERENCES `typeboite` (`id_type_boite`);

--
-- Contraintes pour la table `colonne`
--
ALTER TABLE `colonne`
  ADD CONSTRAINT `colonne_ibfk_1` FOREIGN KEY (`id_etagere`) REFERENCES `etagere` (`id_etagere`);

--
-- Contraintes pour la table `document`
--
ALTER TABLE `document`
  ADD CONSTRAINT `document_ibfk_1` FOREIGN KEY (`id_service`) REFERENCES `service` (`id_service`),
  ADD CONSTRAINT `document_ibfk_2` FOREIGN KEY (`id_site`) REFERENCES `site` (`id_site`),
  ADD CONSTRAINT `document_ibfk_3` FOREIGN KEY (`id_societe`) REFERENCES `societe` (`id_societe`),
  ADD CONSTRAINT `document_ibfk_4` FOREIGN KEY (`id_boite`) REFERENCES `boite` (`id_boite`);

--
-- Contraintes pour la table `etagere`
--
ALTER TABLE `etagere`
  ADD CONSTRAINT `etagere_ibfk_1` FOREIGN KEY (`id_rayonnage`) REFERENCES `rayonnage` (`id_rayonnage`);

--
-- Contraintes pour la table `rayonnage`
--
ALTER TABLE `rayonnage`
  ADD CONSTRAINT `rayonnage_ibfk_1` FOREIGN KEY (`id_site`) REFERENCES `site` (`id_site`),
  ADD CONSTRAINT `rayonnage_ibfk_2` FOREIGN KEY (`id_salle`) REFERENCES `salle` (`id_salle`);

--
-- Contraintes pour la table `salle`
--
ALTER TABLE `salle`
  ADD CONSTRAINT `salle_ibfk_1` FOREIGN KEY (`id_site`) REFERENCES `site` (`id_site`);

--
-- Contraintes pour la table `service`
--
ALTER TABLE `service`
  ADD CONSTRAINT `service_ibfk_1` FOREIGN KEY (`id_direction`) REFERENCES `direction` (`id_direction`);

--
-- Contraintes pour la table `site`
--
ALTER TABLE `site`
  ADD CONSTRAINT `site_ibfk_1` FOREIGN KEY (`id_direction`) REFERENCES `direction` (`id_direction`),
  ADD CONSTRAINT `site_ibfk_2` FOREIGN KEY (`id_service`) REFERENCES `service` (`id_service`);

--
-- Contraintes pour la table `societe`
--
ALTER TABLE `societe`
  ADD CONSTRAINT `societe_ibfk_1` FOREIGN KEY (`id_site`) REFERENCES `site` (`id_site`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
