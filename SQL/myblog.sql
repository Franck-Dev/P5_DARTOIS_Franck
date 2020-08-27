-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  mar. 25 août 2020 à 19:02
-- Version du serveur :  10.1.34-MariaDB
-- Version de PHP :  7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `myblog`
--

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`id`, `name`, `description`) VALUES
(1, 'Projet', 'Cette catégorie servira à regrouper tous les projets réalisés par mes soins.'),
(2, 'Technologie', 'Tous les articles sur les nouveautés technologique qui pourront contribuer au développement durable.'),
(3, 'Informatique', 'Développement web et autres'),
(5, 'Menaces', 'Articles qui traitent de toutes les problématiques à venir, sur tous les thèmes.'),
(6, 'Energetique', 'Concerne tous les nouvelles sources d\'énergie');

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `CreatedAtComments` date NOT NULL,
  `user_id` int(11) NOT NULL,
  `Statut` tinyint(1) NOT NULL,
  `DateModif` date DEFAULT NULL,
  `Posts_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`id`, `Description`, `CreatedAtComments`, `user_id`, `Statut`, `DateModif`, `Posts_id`) VALUES
(6, 'Les commentaires ont l\'air de fonctionner ;-)', '2020-05-11', 2, 1, NULL, 3),
(7, 'Super génial le POO...quelle puissance, je m\'arrête plus!!!', '2020-05-12', 2, 1, NULL, 8),
(9, 'Test du commentaire', '2020-05-12', 1, 1, NULL, 8),
(11, 'Et on reteste...une fois de plus', '2020-05-14', 2, 1, NULL, 3),
(12, 'Test du design de l\'article avec ses commentaires', '2020-05-15', 1, 1, NULL, 8),
(13, 'Et maintenant on va tester si le bouton de suppression s\'affiche pour l\'auteur du commentaire seulement', '2020-05-15', 2, 1, NULL, 8),
(14, 'Test de la modification d\'un commentaire.... On en veut toujours plus. Et on le vaut bien', '2020-05-15', 2, 0, '2020-05-18', 8),
(17, 'Le plus dur à faire pour moi, a été le design du site. Wordpress contient tellement de possibilité graphique, que le site peut vite devenir surchargé .... un peu de Fengshui web ', '2020-05-17', 2, 1, '2020-05-18', 13),
(18, 'Et en plus, elle produit en continue de l\'électricité de part son écoulement ininterrompu', '2020-05-18', 2, 1, '2020-05-18', 15),
(19, 'Il est de mise de bien gérer ses entames de projet....Vive UML ;-)', '2020-05-21', 2, 0, '2020-05-21', 16),
(20, 'J\'adore ce genre d\'énergie propre', '2020-05-22', 2, 0, NULL, 15),
(21, 'L\'initiation aux diagrammes UML s\'est révélée être important pour 2 raisons:\r\noutil supplémentaire à mon métier actuel AMOA\r\nune visualisation rapide et synthétique du projet.', '2020-05-25', 2, 1, '2020-05-25', 21);

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `Title` varchar(45) NOT NULL,
  `Chapo` varchar(75) NOT NULL,
  `CreatedAt` date NOT NULL,
  `Description` varchar(2550) NOT NULL,
  `DerniereMaJ` date DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ImageUne` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `posts`
--

INSERT INTO `posts` (`id`, `Title`, `Chapo`, `CreatedAt`, `Description`, `DerniereMaJ`, `category_id`, `user_id`, `ImageUne`) VALUES
(3, 'Mon premier article', 'Le plus important c\'est....', '2020-05-11', 'C\'est une joie d\'arriver à concevoir ce Blog tout seul', '0000-00-00', 3, 1, NULL),
(8, 'Le deuxième', 'Il parait que c\'est mieux??', '2020-05-12', 'On va voir', NULL, 2, 1, NULL),
(12, 'Le dernier pour tester la grille', 'Et alors ....çà donne koi', '2020-05-15', 'Roulement de tambour pour le retour de l\'affichage......et le gagnant est..... mais en fait c\'est jamais la dernière quand on aime çà', '2020-05-18', 5, 2, NULL),
(13, 'Projet 1', 'Comment mettre le pied à l\'étrier du futur développeur....', '2020-05-16', 'Le premier projet concerné la création d\'un site vitrine à l\'aide de l\'outil Wordpress. Il permet de se familiariser avec les différents outils mis à disposition pour un développeur, ainsi qu\'une approche client-fournisseur. Ce dernier nous met directement dans le bain.', '2020-05-18', 1, 2, 'ChaletetCaviar.png'),
(15, 'Hydroélectricité', 'Une des plus vieilles sources d\'énergie, et la plus pure', '2020-05-18', 'Nos ancêtres avaient commencés à faire tourner des meules à la force de l\'eau, puis des roues à aube pour nous fournir le trésor ...l\'électricité', '2020-05-18', 6, 2, NULL),
(16, 'La conception d\'un projet', 'Etape à ne pas prendre à la légère, pour éviter les pertes de temps et les ', '2020-05-18', 'lorem ipsum', '2020-05-23', 1, 2, 'P3.png'),
(21, 'Express Food', 'Projet qui permet de se familiariser avec la conception UML.', '2020-05-24', 'Description de cas d\'utilisation, et structuration en packages pour les grosses applications, ainsi que le découpage en diagrammes de séquence, et simulation des données en base.', NULL, 1, 2, 'ExpressFood.png'),
(52, 'Twig', 'Moteur de template ergonomique', '2020-05-30', 'J\'ai utiliser le twig pour la réalisation des mes templates, pour plusieurs raisons: La sécurité L\'ergonomie de conception L\'implémentation dans Symfony', NULL, 3, 2, 'twig-logo.png');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(25) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(60) NOT NULL,
  `CreatedAt` date NOT NULL,
  `last_date_connect` date NOT NULL,
  `Statut` tinyint(1) NOT NULL,
  `Profil` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `CreatedAt`, `last_date_connect`, `Statut`, `Profil`) VALUES
(1, 'Franck', 'franck.pyren@gmail.com', 'toto', '2020-05-11', '0000-00-00', 1, 'ADMIN'),
(2, 'furibar', 'franck.pyren@orange.fr', '$2y$10$ACByYtS112tRSGC1sYBNDuJg1Ah0GpxT72DOOD627S8ukXnkygpk2', '2020-05-12', '2020-08-19', 1, 'ADMIN'),
(3, 'toto', 'magali.martin23@wanadoo.fr', '$2y$10$I2UXz.lI7VCnAyYt/GRu6.5.JX/zjXCMq.8hlOMnZFJmF9uNJ66Fq', '2020-05-13', '0000-00-00', 0, 'USER'),
(8, 'Fury65', 'franck.pyren@gmail.fr', '$2y$10$B7TzTYH1QvZjdLIKRe6/3./7W9Ij38kFSd7UjrTL31U454uea2HvC', '2020-05-16', '0000-00-00', 0, 'USER');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Comments_user1_idx` (`user_id`),
  ADD KEY `fk_Comments_Posts1_idx` (`Posts_id`);

--
-- Index pour la table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Posts_category1_idx` (`category_id`),
  ADD KEY `fk_Posts_user1_idx` (`user_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `email` (`email`) USING BTREE;

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `fk_Comments_Posts1` FOREIGN KEY (`Posts_id`) REFERENCES `posts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Comments_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_Posts_category1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Posts_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
