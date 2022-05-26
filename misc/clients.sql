-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server Version:               10.4.22-MariaDB - mariadb.org binary distribution
-- Server Betriebssystem:        Win64
-- HeidiSQL Version:             11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Exportiere Datenbank Struktur für clients
CREATE DATABASE IF NOT EXISTS `clients` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `clients`;

-- Exportiere Struktur von Tabelle clients.client
CREATE TABLE IF NOT EXISTS `client` (
  `clt_id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `clt_pw` varchar(255) NOT NULL,
  `clt_email` varchar(35) NOT NULL,
  `clt_phone` varchar(50) DEFAULT NULL,
  `clt_company` varchar(100) DEFAULT NULL,
  `clt_adr_line1` varchar(80) DEFAULT NULL,
  `clt_adr_line2` varchar(80) DEFAULT NULL,
  `clt_zip` varchar(25) DEFAULT NULL,
  `clt_city` varchar(50) DEFAULT NULL,
  `clt_country` int(4) unsigned zerofill DEFAULT NULL,
  PRIMARY KEY (`clt_id`),
  KEY `client>country` (`clt_country`),
  CONSTRAINT `client>country` FOREIGN KEY (`clt_country`) REFERENCES `country` (`cnt_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle clients.clt_form_alert
CREATE TABLE IF NOT EXISTS `clt_form_alert` (
  `clt_alert_varname` varchar(25) NOT NULL,
  `clt_empty` varchar(100) DEFAULT NULL,
  `clt_invalid` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`clt_alert_varname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle clients.country
CREATE TABLE IF NOT EXISTS `country` (
  `cnt_id` int(4) unsigned zerofill NOT NULL,
  `cnt_name` varchar(50) NOT NULL,
  PRIMARY KEY (`cnt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Daten Export vom Benutzer nicht ausgewählt

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
