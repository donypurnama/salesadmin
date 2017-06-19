-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 10, 2011 at 01:19 PM
-- Server version: 5.1.33
-- PHP Version: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `marketing_old`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_defaultoverdsrules`
--

CREATE TABLE IF NOT EXISTS `tb_defaultoverdsrules` (
  `divisionid` varchar(3) NOT NULL,
  `overdsgroupcode` varchar(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_defaultoverdsrules`
--

INSERT INTO `tb_defaultoverdsrules` (`divisionid`, `overdsgroupcode`) VALUES
('A', 'AB02'),
('B', 'WG'),
('C', 'BXIA'),
('E', 'MG'),
('F', 'DB01'),
('H', 'ZL'),
('I', 'GL1'),
('O', 'WDS'),
('Q', 'FS'),
('P', 'WB'),
('K', 'WGC'),
('L', 'OS1'),
('M', 'ZLS'),
('N', 'MGS'),
('R', 'SLS'),
('S', 'GLS');
