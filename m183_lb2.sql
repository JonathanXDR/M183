-- -------------------------------------------------------------
-- TablePlus 5.9.0(538)
--
-- https://tableplus.com/
--
-- Database: m183_lb2
-- Generation Time: 2024-04-03 22:13:45.1190
-- -------------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

DROP DATABASE IF EXISTS `m183_lb2`;
CREATE DATABASE `m183_lb2`;
USE `m183_lb2`;

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `userID` bigint(20) NOT NULL,
  `roleID` bigint(20) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `ID` bigint(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

DROP TABLE IF EXISTS `tasks`;
CREATE TABLE `tasks` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `userID` bigint(20) NOT NULL,
  `state` enum('open','in progress','done') NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

INSERT INTO `permissions` (`ID`, `userID`, `roleID`) VALUES
(1, 1, 1),
(2, 2, 2);

INSERT INTO `roles` (`ID`, `title`) VALUES
(1, 'Admin'),
(2, 'User');

INSERT INTO `tasks` (`ID`, `title`, `userID`, `state`) VALUES
(1, 'Website Design überarbeiten', 1, 'open'),
(2, 'Kundensupport E-Mails beantworten', 1, 'in progress'),
(3, 'Produktpräsentation für nächste Woche vorbereiten', 1, 'done'),
(4, 'Neue Blogbeiträge verfassen', 2, 'open'),
(5, 'Meeting mit dem Entwicklerteam', 2, 'in progress'),
(6, 'Budgetplan für nächstes Quartal erstellen', 2, 'done');

INSERT INTO `users` (`ID`, `username`, `password`) VALUES
(1, 'admin1', '$2y$10$3uADXmuSRHQA5px8aQnvu.3NiElA2QgXr8haEevS/1sL.AuvOcvOC'),
(2, 'user1', '$2y$10$IXO4ym2N2GF//B2btCj0COpAkTXkupRyAMNgjzXV.KIT3PKCp77F.');



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;