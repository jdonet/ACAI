-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 18, 2016 at 05:11 PM
-- Server version: 10.0.23-MariaDB
-- PHP Version: 5.4.45

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `appliInterro`
--

-- --------------------------------------------------------

--
-- Table structure for table `interro_classe`
--

CREATE TABLE IF NOT EXISTS `interro_classe` (
  `idClasse` int(11) NOT NULL AUTO_INCREMENT,
  `nomClasse` varchar(30) NOT NULL,
  `mail_membres` varchar(100) NOT NULL,
  PRIMARY KEY (`idClasse`),
  KEY `mail_membres` (`mail_membres`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `interro_etudiant`
--

CREATE TABLE IF NOT EXISTS `interro_etudiant` (
  `idEtudiant` int(11) NOT NULL AUTO_INCREMENT,
  `nomEtudiant` varchar(50) NOT NULL,
  `loginEtudiant` varchar(20) NOT NULL,
  `dateNaissanceEtudiant` date NOT NULL,
  `codeCorrection` varchar(6) NOT NULL,
  `refClasse` int(11) NOT NULL,
  PRIMARY KEY (`idEtudiant`),
  UNIQUE KEY `codeCorrection` (`codeCorrection`),
  KEY `refClasse` (`refClasse`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `interro_interro`
--

CREATE TABLE IF NOT EXISTS `interro_interro` (
  `idInterro` int(11) NOT NULL AUTO_INCREMENT,
  `dateInterro` date NOT NULL,
  `nbQuestions` int(11) NOT NULL,
  `mail_membres` varchar(100) NOT NULL,
  PRIMARY KEY (`idInterro`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `interro_membres`
--

CREATE TABLE IF NOT EXISTS `interro_membres` (
  `membre_mdp` varchar(40) NOT NULL,
  `membre_mail` varchar(100) NOT NULL,
  PRIMARY KEY (`membre_mail`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `interro_membres`
--

INSERT INTO `interro_membres` (`membre_mdp`, `membre_mail`) VALUES
('admin', 'admin@localhost');

-- --------------------------------------------------------

--
-- Table structure for table `interro_reponse`
--

CREATE TABLE IF NOT EXISTS `interro_reponse` (
  `idReponse` int(11) NOT NULL AUTO_INCREMENT,
  `refInterro` int(11) NOT NULL,
  `idQuestion` int(11) NOT NULL,
  `refEtudiant` int(11) NOT NULL,
  `reponse` longtext NOT NULL,
  `observation` longtext NOT NULL,
  `bareme` float NOT NULL,
  `points` float NOT NULL,
  `refaire` int(1) NOT NULL,
  PRIMARY KEY (`idReponse`),
  KEY `refInterro` (`refInterro`),
  KEY `refEtudiant` (`refEtudiant`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
