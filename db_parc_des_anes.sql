-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 11 sep. 2026 à 16:37
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
-- Base de données : `db_parc_des_anes`
--

-- --------------------------------------------------------

--
-- Structure de la table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `message` text NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `last_name`, `first_name`, `email`, `phone`, `message`, `status`, `created_at`) VALUES
(1, 'Petit', 'Julie', 'julie.petit@example.com', '0611223344', 'Bonjour, proposez-vous des visites du parc pendant les vacances scolaires ?', 'processed', '2026-03-03 09:14:00'),
(2, 'Renard', 'Marc', 'marc.renard@example.com', '0622334455', 'Bonjour, je souhaiterais connaître les horaires d\'ouverture du parc pendant les vacances scolaires.', 'processed', '2026-03-18 14:27:00'),
(3, 'Gautier', 'Élodie', 'elodie.gautier@example.com', NULL, 'Bonjour, est-il possible de venir avec de jeunes enfants ?', 'processed', '2026-04-01 10:42:00'),
(4, 'Blanc', 'Thomas', 'thomas.blanc@example.com', '0633445566', 'Je souhaiterais offrir une visite du parc à ma famille. Avez-vous des bons cadeaux ?', 'processed', '2026-04-14 16:08:00'),
(5, 'Henry', 'Laura', 'laura.henry@example.com', NULL, 'Bonjour, vos produits sont-ils disponibles directement au parc ?', 'processed', '2026-04-29 11:35:00'),
(6, 'Renaud', 'Pierre', 'pierre.renaud@example.com', '0644556677', 'Bonjour, peut-on réserver une visite pour un groupe de dix personnes ?', 'processed', '2026-05-11 15:21:00'),
(7, 'Marchand', 'Sophie', 'sophie.marchand@example.com', '0655667788', 'Je viens de passer une commande et je souhaiterais connaître son délai de livraison.', 'processed', '2026-05-26 09:48:00'),
(8, 'Barbier', 'Lucas', 'lucas.barbier@example.com', NULL, 'Bonjour, faites-vous des animations pédagogiques pour les écoles ?', 'processed', '2026-06-04 13:16:00'),
(9, 'Roy', 'Camille', 'camille.roy@example.com', '0666778899', 'Votre association accepte-t-elle les dons de matériel pour les animaux ?', 'processed', '2026-06-17 10:03:00'),
(10, 'Lemoine', 'Nicolas', 'nicolas.lemoine@example.com', NULL, 'Bonjour, j\'aimerais venir visiter le parc avec mon chien. Est-ce autorisé ?', 'processed', '2026-06-30 17:12:00'),
(11, 'Caron', 'Marie', 'marie.caron@example.com', '0677889900', 'Merci pour votre accueil lors de notre visite. Les enfants ont adoré les ânes !', 'processed', '2026-07-07 12:39:00'),
(12, 'Leclerc', 'Antoine', 'antoine.leclerc@example.com', NULL, 'Bonjour, avez-vous des produits sans parfum dans votre gamme de soins ?', 'processed', '2026-07-16 09:25:00'),
(13, 'Poirier', 'Emma', 'emma.poirier@example.com', '0688990011', 'Je souhaiterais organiser une sortie pour une association. Pouvez-vous me renseigner ?', 'processed', '2026-07-24 14:51:00'),
(14, 'Robin', 'Julien', 'julien.robin@example.com', NULL, 'Bonjour, quelle est la durée moyenne d\'une visite du parc ?', 'processed', '2026-08-03 10:17:00'),
(15, 'Muller', 'Claire', 'claire.muller@example.com', '0699001122', 'Je souhaiterais savoir si les animaux peuvent être nourris pendant la visite.', 'processed', '2026-08-09 15:44:00'),
(16, 'Besson', 'Hugo', 'hugo.besson@example.com', NULL, 'Bonjour, j\'ai vu vos produits en ligne et je voulais savoir s\'ils sont également vendus sur place.', 'pending', '2026-08-15 11:06:00'),
(17, 'Gilles', 'Manon', 'manon.gilles@example.com', '0600112233', 'Merci pour votre réponse rapide concernant ma précédente demande.', 'answered', '2026-08-20 16:29:00'),
(18, 'Chevalier', 'Sarah', 'sarah.chevalier@example.com', NULL, 'Bonjour, avez-vous prévu des événements particuliers pour la rentrée ?', 'pending', '2026-08-24 09:52:00'),
(19, 'Chabert', 'Noé', 'noe.chabert@example.com', '0647821935', 'Bonjour,\r\nJe tiens un blog et je souhaiterais parler de votre Parc.\r\nPourrions-nous en discuter.\r\nEn vous remerciant par avance.', 'pending', '2026-08-25 17:46:00');

-- --------------------------------------------------------

--
-- Structure de la table `donations`
--

CREATE TABLE `donations` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `donation_number` varchar(20) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `comment` text DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `donations`
--

INSERT INTO `donations` (`id`, `user_id`, `donation_number`, `last_name`, `first_name`, `email`, `phone`, `amount`, `comment`, `status`, `created_at`) VALUES
(1, 2, 'DON-2026-000001', 'Martin', 'Alice', 'alice.martin@example.com', '0612345678', 25.00, 'Merci pour votre travail auprès des animaux.', 'processed', '2026-03-15 11:22:00'),
(2, 3, 'DON-2026-000002', 'Bernard', 'Julien', 'julien.bernard@example.com', '0623456789', 50.00, NULL, 'processed', '2026-04-02 09:47:00'),
(3, 5, 'DON-2026-000003', 'Moreau', 'Thomas', 'thomas.moreau@example.com', '0645678901', 20.00, 'Une petite contribution pour le parc.', 'processed', '2026-04-27 16:18:00'),
(4, 7, 'DON-2026-000004', 'Garcia', 'Nicolas', 'nicolas.garcia@example.com', '0667890123', 100.00, 'Continuez à prendre soin de nos ânes !', 'processed', '2026-05-09 14:36:00'),
(5, 8, 'DON-2026-000005', 'Robert', 'Emma', 'emma.robert@example.com', '0678901234', 30.00, NULL, 'processed', '2026-05-24 10:05:00'),
(6, 9, 'DON-2026-000006', 'Dupont', 'Antoine', 'antoine.dupont@example.com', '0689012345', 75.00, 'Bravo pour cette belle association.', 'processed', '2026-06-07 12:41:00'),
(7, 10, 'DON-2026-000007', 'Lambert', 'Camille', 'camille.lambert@example.com', '0690123456', 15.00, NULL, 'processed', '2026-06-21 15:29:00'),
(8, 11, 'DON-2026-000008', 'Fournier', 'Maxime', 'maxime.fournier@example.com', '0601234567', 50.00, 'Pour contribuer aux soins des animaux.', 'processed', '2026-07-03 09:16:00'),
(9, 13, 'DON-2026-000009', 'Andre', 'Paul', 'paul.andre@example.com', '0623456780', 200.00, 'Merci à toute l\'équipe du Parc des Anes.\nPourriez-vous m\'envoyer un reçu fiscal s\'il vous plait.\nTrès Bonne journée.', 'completed', '2026-07-15 13:54:00'),
(10, 14, 'DON-2026-000010', 'Rousseau', 'Marie', 'marie.rousseau@example.com', '0634567891', 35.00, NULL, 'pending', '2026-07-29 17:08:00'),
(11, 16, 'DON-2026-000011', 'Mercier', 'Chloe', 'chloe.mercier@example.com', '0656789013', 25.00, 'Avec plaisir pour soutenir le parc.', 'completed', '2026-08-05 11:33:00'),
(12, 17, 'DON-2026-000012', 'Chevalier', 'Hugo', 'hugo.chevalier@example.com', '0667890124', 50.00, NULL, 'completed', '2026-08-13 14:27:00'),
(13, 19, 'DON-2026-000013', 'Perrin', 'Gabriel', 'gabriel.perrin@example.com', '0689012346', 40.00, 'Longue vie au Parc des Anes !', 'completed', '2026-08-19 10:51:00'),
(14, 20, 'DON-2026-000014', 'Boyer', 'Manon', 'manon.boyer@example.com', '0690123457', 20.00, NULL, 'pending', '2026-08-23 16:12:00'),
(15, 2, 'DON-2026-000015', 'Martin', 'Alice', 'alice.martin@example.com', '0612345678', 80.00, 'Bonjour,\r\nJe vous renouvelle mes félicitations pour votre parc.', 'pending', '2026-08-25 17:34:43');

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_number` varchar(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `postcode` varchar(5) NOT NULL,
  `city` varchar(100) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `last_name`, `first_name`, `email`, `phone`, `address`, `postcode`, `city`, `status`, `created_at`) VALUES
(1, 'CMD-2026-000001', 2, 'Martin', 'Alice', 'alice.martin@example.com', '0612345678', '12 rue des Pommiers', '14000', 'Caen', 'shipped', '2026-03-08 10:24:00'),
(2, 'CMD-2026-000002', 3, 'Bernard', 'Julien', 'julien.bernard@example.com', '0623456789', '8 rue Saint-Pierre', '14000', 'Caen', 'shipped', '2026-03-22 14:12:00'),
(3, 'CMD-2026-000003', 4, 'Dubois', 'Claire', 'claire.dubois@example.com', '0634567890', '25 avenue de la Mer', '14150', 'Ouistreham', 'shipped', '2026-04-05 09:41:00'),
(4, 'CMD-2026-000004', 5, 'Moreau', 'Thomas', 'thomas.moreau@example.com', '0645678901', '4 chemin du Bocage', '14130', 'Pont-l\'Évêque', 'shipped', '2026-04-18 16:35:00'),
(5, 'CMD-2026-000005', 6, 'Leroy', 'Sophie', 'sophie.leroy@example.com', '0656789012', '17 rue des Tilleuls', '14200', 'Hérouville-Saint-Clair', 'shipped', '2026-05-02 11:08:00'),
(6, 'CMD-2026-000006', 7, 'Garcia', 'Nicolas', 'nicolas.garcia@example.com', '0667890123', '31 rue de la Fontaine', '14370', 'Argences', 'shipped', '2026-05-17 13:27:00'),
(7, 'CMD-2026-000007', 8, 'Robert', 'Emma', 'emma.robert@example.com', '0678901234', '6 rue des Écoles', '14500', 'Vire Normandie', 'shipped', '2026-06-01 09:18:00'),
(8, 'CMD-2026-000008', 9, 'Dupont', 'Antoine', 'antoine.dupont@example.com', '0689012345', '19 rue de la Gare', '14000', 'Caen', 'shipped', '2026-06-14 15:46:00'),
(9, 'CMD-2026-000009', 10, 'Lambert', 'Camille', 'camille.lambert@example.com', '0690123456', '42 rue du Calvaire', '14100', 'Lisieux', 'cancelled', '2026-06-28 12:03:00'),
(10, 'CMD-2026-000010', 11, 'Fournier', 'Maxime', 'maxime.fournier@example.com', '0601234567', '3 rue des Jardins', '14000', 'Caen', 'shipped', '2026-07-05 17:22:00'),
(11, 'CMD-2026-000011', 12, 'Michel', 'Laura', 'laura.michel@example.com', '0612345670', '15 rue du Port', '14200', 'Hérouville-Saint-Clair', 'shipped', '2026-07-12 10:31:00'),
(12, 'CMD-2026-000012', 13, 'Andre', 'Paul', 'paul.andre@example.com', '0623456780', '7 rue des Roses', '14123', 'Ifs', 'shipped', '2026-07-19 14:48:00'),
(13, 'CMD-2026-000013', 14, 'Rousseau', 'Marie', 'marie.rousseau@example.com', '0634567891', '28 rue de l\'Église', '14400', 'Bayeux', 'shipped', '2026-07-26 11:17:00'),
(14, 'CMD-2026-000014', 15, 'Fontaine', 'Lucas', 'lucas.fontaine@example.com', '0645678902', '10 rue du Château', '14000', 'Caen', 'pending', '2026-08-02 16:02:00'),
(15, 'CMD-2026-000015', 16, 'Mercier', 'Chloe', 'chloe.mercier@example.com', '0656789013', '21 rue des Vignes', '14130', 'Deauville', 'completed', '2026-08-08 09:54:00'),
(16, 'CMD-2026-000016', 17, 'Chevalier', 'Hugo', 'hugo.chevalier@example.com', '0667890124', '5 rue des Moulins', '14000', 'Caen', 'completed', '2026-08-12 13:39:00'),
(17, 'CMD-2026-000017', 18, 'Francois', 'Lea', 'lea.francois@example.com', '0678901235', '16 rue Victor Hugo', '14120', 'Mondeville', 'pending', '2026-08-18 15:21:00'),
(18, 'CMD-2026-000018', 19, 'Perrin', 'Gabriel', 'gabriel.perrin@example.com', '0689012346', '9 rue des Prés', '14210', 'Évrecy', 'pending', '2026-08-22 10:45:00'),
(19, 'CMD-2026-000019', 17, 'Chevalier', 'Hugo', 'hugo.chevalier@example.com', '0667890124', '5 rue des Moulins', '14000', 'Caen', 'pending', '2026-08-25 17:24:41');

-- --------------------------------------------------------

--
-- Structure de la table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_reference` varchar(100) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_reference`, `product_name`, `unit_price`, `quantity`, `subtotal`) VALUES
(1, 1, 1, 'PROD-000001', 'Peluche', 20.00, 1, 20.00),
(2, 1, 6, 'PROD-000006', 'Mug', 9.00, 1, 9.00),
(3, 2, 4, 'PROD-000004', 'Savon au lait d\'ânesse', 5.00, 2, 10.00),
(4, 2, 7, 'PROD-000007', 'Crème beauté au lait d\'ânesse', 8.00, 1, 8.00),
(5, 2, 11, 'PROD-000011', 'Sels de bain au lait d\'ânesse', 9.00, 1, 9.00),
(6, 3, 3, 'PROD-000003', 'Lait d\'ânesse en poudre', 30.00, 1, 30.00),
(7, 3, 5, 'PROD-000005', 'Biscuits au lait d\'ânesse', 18.00, 2, 36.00),
(8, 4, 2, 'PROD-000002', 'Peinture sur toile', 32.00, 1, 32.00),
(9, 4, 10, 'PROD-000010', 'Porte-clé', 5.00, 2, 10.00),
(10, 5, 8, 'PROD-000008', 'Pâte à tartiner au lait d\'ânesse', 14.00, 1, 14.00),
(11, 5, 12, 'PROD-000012', 'Bonbons miel et lait d\'ânesse', 16.00, 1, 16.00),
(12, 5, 13, 'PROD-000013', 'Chocolats au lait d\'ânesse', 23.00, 1, 23.00),
(13, 6, 15, 'PROD-000015', 'Coussin en relief', 24.00, 1, 24.00),
(14, 6, 16, 'PROD-000016', 'Figurine', 12.00, 1, 12.00),
(15, 7, 9, 'PROD-000009', 'Baume corporel lait d\'ânesse', 7.50, 2, 15.00),
(16, 7, 14, 'PROD-000014', 'Gel douche surgras sans savon', 8.00, 1, 8.00),
(17, 8, 1, 'PROD-000001', 'Peluche', 20.00, 1, 20.00),
(18, 8, 4, 'PROD-000004', 'Savon au lait d\'ânesse', 5.00, 3, 15.00),
(19, 8, 10, 'PROD-000010', 'Porte-clé', 5.00, 1, 5.00),
(20, 9, 3, 'PROD-000003', 'Lait d\'ânesse en poudre', 30.00, 1, 30.00),
(21, 9, 8, 'PROD-000008', 'Pâte à tartiner au lait d\'ânesse', 14.00, 1, 14.00),
(22, 10, 6, 'PROD-000006', 'Mug', 9.00, 2, 18.00),
(23, 10, 15, 'PROD-000015', 'Coussin en relief', 24.00, 1, 24.00),
(24, 11, 5, 'PROD-000005', 'Biscuits au lait d\'ânesse', 18.00, 1, 18.00),
(25, 11, 12, 'PROD-000012', 'Bonbons miel et lait d\'ânesse', 16.00, 1, 16.00),
(26, 12, 7, 'PROD-000007', 'Crème beauté au lait d\'ânesse', 8.00, 1, 8.00),
(27, 12, 9, 'PROD-000009', 'Baume corporel lait d\'ânesse', 7.50, 1, 7.50),
(28, 12, 11, 'PROD-000011', 'Sels de bain au lait d\'ânesse', 9.00, 1, 9.00),
(29, 13, 2, 'PROD-000002', 'Peinture sur toile', 32.00, 1, 32.00),
(30, 13, 16, 'PROD-000016', 'Figurine', 12.00, 2, 24.00),
(31, 14, 4, 'PROD-000004', 'Savon au lait d\'ânesse', 5.00, 2, 10.00),
(32, 14, 14, 'PROD-000014', 'Gel douche surgras sans savon', 8.00, 1, 8.00),
(33, 15, 13, 'PROD-000013', 'Chocolats au lait d\'ânesse', 23.00, 1, 23.00),
(34, 15, 12, 'PROD-000012', 'Bonbons miel et lait d\'ânesse', 16.00, 1, 16.00),
(35, 16, 1, 'PROD-000001', 'Peluche', 20.00, 1, 20.00),
(36, 16, 6, 'PROD-000006', 'Mug', 9.00, 1, 9.00),
(37, 16, 10, 'PROD-000010', 'Porte-clé', 5.00, 2, 10.00),
(38, 17, 3, 'PROD-000003', 'Lait d\'ânesse en poudre', 30.00, 1, 30.00),
(39, 17, 5, 'PROD-000005', 'Biscuits au lait d\'ânesse', 18.00, 1, 18.00),
(40, 18, 8, 'PROD-000008', 'Pâte à tartiner au lait d\'ânesse', 14.00, 1, 14.00),
(41, 18, 13, 'PROD-000013', 'Chocolats au lait d\'ânesse', 23.00, 1, 23.00),
(42, 19, 5, 'PROD-000005', 'Biscuits au lait d’ânesse', 18.00, 1, 18.00),
(43, 19, 12, 'PROD-000012', 'Bonbons miel et lait d’ânesse', 16.00, 1, 16.00),
(44, 19, 13, 'PROD-000013', 'Chocolats au lait d’ânesse', 23.00, 1, 23.00),
(45, 19, 1, 'PROD-000001', 'Peluche', 20.00, 1, 20.00);

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `reference` varchar(20) NOT NULL,
  `name` varchar(60) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `weight` varchar(10) DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id`, `reference`, `name`, `description`, `price`, `weight`, `is_available`, `image`, `category_id`) VALUES
(1, 'PROD-000001', 'Peluche', 'Réalisée à la main au crochet, notre peluche d’âne deviendra vite le compagnon préféré des enfants… et des grands enfants !', 20.00, NULL, 1, '6a7f0e45f1ea66.24319899.jpg', 3),
(2, 'PROD-000002', 'Peinture sur toile', 'Jolie toile peinte à la main représentant nos ânes, parfaite pour apporter une touche champêtre à votre intérieur.', 32.00, NULL, 1, '6a7f0eabcf8887.33583798.jpg', 3),
(3, 'PROD-000003', 'Lait d’ânesse en poudre', 'Pur et subtil, notre lait d’ânesse en poudre est reconnu pour sa finesse et ses vertus naturelles. Facile à reconstituer. A savourer au quotidien.', 30.00, '100g', 1, '6a7f0f1e6bae58.82100860.jpg', 1),
(4, 'PROD-000004', 'Savon au lait d’ânesse', 'Un savon doux et hydratant au lait d’ânesse, idéal pour toute la famille. Sa mousse fine laisse la peau souple et parfumée.', 5.00, NULL, 1, '6a7f0f533de867.33804254.jpg', 2),
(5, 'PROD-000005', 'Biscuits au lait d’ânesse', 'Délicatement croustillants, nos biscuits au lait d’ânesse révèlent une saveur douce et équilibrée. Un plaisir simple à partager.', 18.00, '190g', 1, '6a7f0fd81b8687.20918629.jpg', 1),
(6, 'PROD-000006', 'Mug', 'Un mug en céramique illustré de notre âne farceur, idéal pour commencer la journée avec un sourire et un souvenir du parc.', 9.00, NULL, 1, '6a7f0ffaa950f7.12395987.jpg', 3),
(7, 'PROD-000007', 'Crème beauté au lait d’ânesse', 'Une crème légère et nourrissante, à base de lait d’ânesse, pour hydrater la peau au quotidien et retrouver un teint éclatant.', 8.00, NULL, 1, '6a7f102527f866.21938607.jpg', 2),
(8, 'PROD-000008', 'Pâte à tartiner au lait d’ânesse', 'Onctueuse et gourmande, notre pâte à tartiner au lait d’ânesse séduit par sa texture fondante et son goût subtil. A déguster sans modération.', 14.00, '230g', 1, '6a7f104f98dd69.21398609.jpg', 1),
(9, 'PROD-000009', 'Baume corporel lait d\'ânesse', 'Ce baume généreux combine les vertus du lait d’ânesse à celles des huiles végétales. Il apaise, nourrit et laisse un délicat parfum de nature.', 7.50, NULL, 1, '6a7f1081cf1644.93806509.jpg', 2),
(10, 'PROD-000010', 'Porte-clé', 'Léger et amusant, notre porte-clé en forme d’âne accompagne vos clés ou votre sac avec une touche de douceur.', 5.00, NULL, 1, '6a7f10c1493928.54786370.jpg', 3),
(11, 'PROD-000011', 'Sels de bain au lait d’ânesse', 'Plongez dans un moment de détente absolue : nos sels de bain au lait d’ânesse adoucissent la peau et apaisent les tensions.', 9.00, NULL, 1, '6a7f10ef9d5a55.64727226.jpg', 2),
(12, 'PROD-000012', 'Bonbons miel et lait d’ânesse', 'Alliance subtile de miel et de lait d’ânesse pour une friandise naturelle et réconfortante. Parfaite pour une pause sucrée.', 16.00, '200g', 1, '6a7f111ba17b93.05386190.jpg', 1),
(13, 'PROD-000013', 'Chocolats au lait d’ânesse', 'Des chocolats au lait d’ânesse fondants et raffinés, fabriqués artisanalement. À offrir… ou à s’offrir sans culpabilité !', 23.00, '110g', 1, '6a7f1140b45f21.50229046.jpg', 1),
(14, 'PROD-000014', 'Gel douche surgras sans savon', 'Formulé sans savon, ce gel douche surgras nettoie en douceur tout en préservant l’équilibre naturel de la peau.', 8.00, NULL, 1, '6a7f1171e43ba2.32867344.jpg', 2),
(15, 'PROD-000015', 'Coussin en relief', 'Moelleux et décoratif, ce coussin à l’effigie de nos ânes apporte confort et tendresse à votre maison.', 24.00, NULL, 1, '6a7f1193eb8dc1.94706224.jpg', 3),
(16, 'PROD-000016', 'Figurine', 'Petite figurine d’âne en résine, pleine de charme, à collectionner ou à offrir à un amoureux des animaux.', 12.00, NULL, 1, '6a83358c481ec4.20525727.jpg', 3);

-- --------------------------------------------------------

--
-- Structure de la table `products_categories`
--

CREATE TABLE `products_categories` (
  `id` int(11) NOT NULL,
  `label` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `display_order` int(10) UNSIGNED NOT NULL COMMENT 'Display order of products''s categories in the shop'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `products_categories`
--

INSERT INTO `products_categories` (`id`, `label`, `description`, `display_order`) VALUES
(1, 'Les produits à déguster', 'Savourez la douceur et les bienfaits du lait d’ânesse à travers nos produits gourmands, faits avec soin et inspirés de la nature.\r\nDe quoi régaler petits et grands tout en prolongeant la visite du parc à la maison !', 1),
(2, 'Les soins du corps', 'Retrouvez la douceur du lait d’ânesse dans une gamme de soins naturels, fabriqués artisanalement.\r\nDes produits simples, respectueux de la peau et inspirés de la nature qui nous entoure au parc.', 2),
(3, 'Les souvenir du Parc', 'Repartez avec un souvenir du Parc des Anes !\r\nChaque objet est choisi avec soin pour prolonger la magie de votre visite et garder un petit morceau de tendresse à la maison.', 3);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`) VALUES
(1, 'admin@example.com', '$2y$10$mKzMA0CN6mdWA4I/7qNncuPyL70LTzViheh3EnOlGFiJN/aVH3N1m', 'admin'),
(2, 'alice.martin@example.com', '$2y$10$VJ2MEoYO3KUJB9u.cNoVVOdgVX2KgBubfxKTg8UgzVweJ1.Us04Wu', 'user'),
(3, 'julien.bernard@example.com', '$2y$10$VJ2MEoYO3KUJB9u.cNoVVOdgVX2KgBubfxKTg8UgzVweJ1.Us04Wu', 'user'),
(4, 'claire.dubois@example.com', '$2y$10$VJ2MEoYO3KUJB9u.cNoVVOdgVX2KgBubfxKTg8UgzVweJ1.Us04Wu', 'user'),
(5, 'thomas.moreau@example.com', '$2y$10$VJ2MEoYO3KUJB9u.cNoVVOdgVX2KgBubfxKTg8UgzVweJ1.Us04Wu', 'user'),
(6, 'sophie.leroy@example.com', '$2y$10$VJ2MEoYO3KUJB9u.cNoVVOdgVX2KgBubfxKTg8UgzVweJ1.Us04Wu', 'user'),
(7, 'nicolas.garcia@example.com', '$2y$10$VJ2MEoYO3KUJB9u.cNoVVOdgVX2KgBubfxKTg8UgzVweJ1.Us04Wu', 'user'),
(8, 'emma.robert@example.com', '$2y$10$VJ2MEoYO3KUJB9u.cNoVVOdgVX2KgBubfxKTg8UgzVweJ1.Us04Wu', 'user'),
(9, 'antoine.dupont@example.com', '$2y$10$VJ2MEoYO3KUJB9u.cNoVVOdgVX2KgBubfxKTg8UgzVweJ1.Us04Wu', 'user'),
(10, 'camille.lambert@example.com', '$2y$10$VJ2MEoYO3KUJB9u.cNoVVOdgVX2KgBubfxKTg8UgzVweJ1.Us04Wu', 'user'),
(11, 'maxime.fournier@example.com', '$2y$10$VJ2MEoYO3KUJB9u.cNoVVOdgVX2KgBubfxKTg8UgzVweJ1.Us04Wu', 'user'),
(12, 'laura.michel@example.com', '$2y$10$VJ2MEoYO3KUJB9u.cNoVVOdgVX2KgBubfxKTg8UgzVweJ1.Us04Wu', 'user'),
(13, 'paul.andre@example.com', '$2y$10$VJ2MEoYO3KUJB9u.cNoVVOdgVX2KgBubfxKTg8UgzVweJ1.Us04Wu', 'user'),
(14, 'marie.rousseau@example.com', '$2y$10$VJ2MEoYO3KUJB9u.cNoVVOdgVX2KgBubfxKTg8UgzVweJ1.Us04Wu', 'user'),
(15, 'lucas.fontaine@example.com', '$2y$10$VJ2MEoYO3KUJB9u.cNoVVOdgVX2KgBubfxKTg8UgzVweJ1.Us04Wu', 'user'),
(16, 'chloe.mercier@example.com', '$2y$10$VJ2MEoYO3KUJB9u.cNoVVOdgVX2KgBubfxKTg8UgzVweJ1.Us04Wu', 'user'),
(17, 'hugo.chevalier@example.com', '$2y$10$VJ2MEoYO3KUJB9u.cNoVVOdgVX2KgBubfxKTg8UgzVweJ1.Us04Wu', 'user'),
(18, 'lea.francois@example.com', '$2y$10$VJ2MEoYO3KUJB9u.cNoVVOdgVX2KgBubfxKTg8UgzVweJ1.Us04Wu', 'user'),
(19, 'gabriel.perrin@example.com', '$2y$10$VJ2MEoYO3KUJB9u.cNoVVOdgVX2KgBubfxKTg8UgzVweJ1.Us04Wu', 'user'),
(20, 'manon.boyer@example.com', '$2y$10$VJ2MEoYO3KUJB9u.cNoVVOdgVX2KgBubfxKTg8UgzVweJ1.Us04Wu', 'user'),
(21, 'arthur.girard@example.com', '$2y$10$VJ2MEoYO3KUJB9u.cNoVVOdgVX2KgBubfxKTg8UgzVweJ1.Us04Wu', 'user');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_donations_donation_number` (`donation_number`),
  ADD KEY `index_donations_status` (`status`),
  ADD KEY `index_donations_created_at` (`created_at`),
  ADD KEY `index_donations_user_id` (`user_id`);

--
-- Index pour la table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_orders_order_number` (`order_number`),
  ADD KEY `index_orders_user_id` (`user_id`);

--
-- Index pour la table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `index_order_items_order_id` (`order_id`),
  ADD KEY `index_order_items_product_id` (`product_id`);

--
-- Index pour la table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_products_reference` (`reference`),
  ADD KEY `index_products_category_id` (`category_id`);

--
-- Index pour la table `products_categories`
--
ALTER TABLE `products_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_products_categories_display_order` (`display_order`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_users_email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT pour la table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `products_categories`
--
ALTER TABLE `products_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `fk_donations_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Contraintes pour la table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `products_categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
