-- phpMyAdmin SQL Dump
-- version 3.2.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 26, 2011 at 07:24 PM
-- Server version: 5.1.44
-- PHP Version: 5.3.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `yellowPagesSenegal`
--

-- --------------------------------------------------------

--
-- Table structure for table `yps_cacheinfo`
--

CREATE TABLE `yps_cacheinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_url` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `yps_cacheinfo`
--


-- --------------------------------------------------------

--
-- Table structure for table `yps_category`
--

CREATE TABLE `yps_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `categoryfile` varchar(255) NOT NULL,
  `created_at` bigint(20) NOT NULL,
  `updated_at` bigint(20) NOT NULL,
  `active` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `yps_category`
--

INSERT INTO `yps_category` VALUES(1, 'Hotel', '', 1303405200, 1303405200, 'Active');
INSERT INTO `yps_category` VALUES(2, 'Pub', '', 1303405200, 1303405200, 'Active');
INSERT INTO `yps_category` VALUES(3, 'Hospital', '', 1303405200, 1303405200, 'Active');
INSERT INTO `yps_category` VALUES(4, 'School', '', 1303405200, 1303405200, 'Active');
INSERT INTO `yps_category` VALUES(5, 'College', '', 1303405200, 1303405200, 'Active');
INSERT INTO `yps_category` VALUES(6, 'University', '', 1303405200, 1303405200, 'Active');
INSERT INTO `yps_category` VALUES(7, 'Message Centre', '', 1303405200, 1303405200, 'Active');
INSERT INTO `yps_category` VALUES(8, 'ghfhjf', '1303820063_Blue.png', 1303750800, 1303750800, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `yps_cms`
--

CREATE TABLE `yps_cms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `body` text NOT NULL,
  `active` varchar(10) NOT NULL,
  `meta_description` tinytext NOT NULL,
  `meta_keyword` tinytext NOT NULL,
  `page_title` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `yps_cms`
--


-- --------------------------------------------------------

--
-- Table structure for table `yps_config`
--

CREATE TABLE `yps_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cnfig_key` varchar(100) NOT NULL,
  `cnfig_value` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;

--
-- Dumping data for table `yps_config`
--

INSERT INTO `yps_config` VALUES(19, 'paypal_api_signature', 'A2Ucufe94aDUYq5YJCa.dEVUH6RwAH3R7fVJIWwVWOUXV1Os9O-dHKCc');
INSERT INTO `yps_config` VALUES(18, 'paypal_api_pass', '3XS348A8NFZFJZP2');
INSERT INTO `yps_config` VALUES(7, 'page_suffix', '.html');
INSERT INTO `yps_config` VALUES(17, 'paypal_api_username', 'roni_api1.cybernetikz.com');
INSERT INTO `yps_config` VALUES(9, 'invalid_page', '404');
INSERT INTO `yps_config` VALUES(10, 'auth_key', '4');
INSERT INTO `yps_config` VALUES(11, 'auth_value', '6');
INSERT INTO `yps_config` VALUES(16, 'debug_mode', '1');
INSERT INTO `yps_config` VALUES(14, 'site_name', 'iSenegal Admin Panel');
INSERT INTO `yps_config` VALUES(13, 'save', 'Save');
INSERT INTO `yps_config` VALUES(20, 'paypal_api_sbncode', '');
INSERT INTO `yps_config` VALUES(21, 'paypal_api_usesandbox', '1');
INSERT INTO `yps_config` VALUES(22, 'paypal_api_proxyhost', '12');
INSERT INTO `yps_config` VALUES(23, 'paypal_api_proxyport', '');
INSERT INTO `yps_config` VALUES(24, 'paypal_api_useproxy', '0');
INSERT INTO `yps_config` VALUES(25, 'paypal_api_return', 'http://localhost:8888/amarboimela/checkout/books/paypalfinalize');
INSERT INTO `yps_config` VALUES(26, 'paypal_api_cancel', 'http://localhost:8888/amarboimela/cart.php');
INSERT INTO `yps_config` VALUES(27, 'paypal_api_currency', 'USD');
INSERT INTO `yps_config` VALUES(28, 'date_format', 'mm-dd-yy');
INSERT INTO `yps_config` VALUES(29, 'per_page', '10');
INSERT INTO `yps_config` VALUES(30, 'admin_name', 'Roni Alam');
INSERT INTO `yps_config` VALUES(31, 'admin_email', 'roni@annanovas.com');
INSERT INTO `yps_config` VALUES(32, 'smtp_host', 'mail.annanovas.com');
INSERT INTO `yps_config` VALUES(33, 'fauth_key', 'fauth');
INSERT INTO `yps_config` VALUES(34, 'fauth_value', 'rauth');
INSERT INTO `yps_config` VALUES(35, 'user_role', '2');
INSERT INTO `yps_config` VALUES(36, 'default_user_role', '1');
INSERT INTO `yps_config` VALUES(37, 'site_url', 'http://localhost/iSenegal/');
INSERT INTO `yps_config` VALUES(38, 'facebook_url', 'http://www.facebook.com/annanovas');
INSERT INTO `yps_config` VALUES(39, 'flicker_url', 'http://www.flickr.com/photos/annanovas');
INSERT INTO `yps_config` VALUES(40, 'twitter_url', 'http://twitter.com/annanovas');
INSERT INTO `yps_config` VALUES(41, 'paypal_user_name', 'saro_1278593380_biz@annanovas.com');
INSERT INTO `yps_config` VALUES(42, 'paypal_url', 'https://www.sandbox.paypal.com/cgi-bin/webscr');

-- --------------------------------------------------------

--
-- Table structure for table `yps_item`
--

CREATE TABLE `yps_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `available` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `phone_no` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `web_address` varchar(255) NOT NULL,
  `latitude` varchar(255) NOT NULL,
  `longitude` varchar(255) NOT NULL,
  `itemfile` varchar(255) NOT NULL,
  `created_at` bigint(20) NOT NULL,
  `updated_at` bigint(20) NOT NULL,
  `modified_user_id` varchar(255) NOT NULL,
  `active` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `yps_item`
--

INSERT INTO `yps_item` VALUES(1, 'Lamantin Beach Hotel', '1', 'Situated right on the sea, with direct access to the beach & marina, Le Lamantin Beach Hotel is a universe of well-being, in the heart of the beautiful touristic town of Saly. Constructed in a African style, the resort is composed of traditional freestanding huts offering luxurious amenities & purely traditional Serere décor.', 'From 10.00 am - 12.00 pm ', 'LAMANTIN BEACH HOTEL BP 2383 SALY NORD - M''BOUR SENEGAL', '[00221] 33 957 07 77', 'info@hds.com', 'http://www.lelamantin.com/uk/hotel.html', '14.42197', '-16.964279', '1303457231_hotel.jpg', 1303405200, 1303405200, '1', 'Active');
INSERT INTO `yps_item` VALUES(2, 'Hôpital Principal de Dakar', '3', 'Le partenariat entre l''association "Horizon Sahel Solidarité" et l''Hôpital Principal de Dakar se porte bien. Ce partenariat  scellé au mois de février 2009, lorsque l''association avait mis à la disposition de l''hôpital un important lot de matériel hospitalier,  se poursuit. C''est ainsi que le 31 mars 2011, une délégation de l''association s''est rendue une nouvelle à l''Hôpital Principal de Dakar, sur invitation de la direction de l''établissement.\r\n\r\nLa délégation conduite par Monsieur Daniel MILLIERE, président de l''association était composée, entre autres membres, de Madame Coumba Bathily BA sa représentante au Sénégal,  Monsieur Thierry TOUTAY et de Monsieur Jean-Yves KRISTNER, membres du conseil d''administration.  Elle a été reçue à la salle d''honneur de l''Hôpital Principal par le Médecin Colonel Boubacar WADE, Médecin-chef de l''établissement. ', '24 Hours', 'Avenue Nelson Mendela', '(221) 8395050', 'communication@hpd.sn', 'http://www.hopitalprincipal.sn/', '-4.318171', '15.285874', '1303460511_Ho&#770;pital Principal de Dakar.jpg', 1303405200, 1303405200, '1', 'Active');
INSERT INTO `yps_item` VALUES(3, 'La Clinique du Cap', '3', 'Située sur la pointe la plus avancée du continent africain (Cap Manuel), face à L'' Océan Atlantique, la Clinique du Cap a été conçue pour satisfaire une demande en soins de qualité et mettre à la disposition du corps médical un équipement des plus performants.\r\n\r\nLa direction et toute l''équipe de la clinique sont très honorées de vous accueillir et vous souhaitent un agréable séjour.\r\n\r\nCe site réalisé à votre intention, vous permettra d''y trouver les renseignements qui contribueront à améliorer la qualité de votre séjour.', '24 Hours', 'AVENUE PASTEUR - B.P. 583 DAKAR  SENEGAL', 'Tél : (221) 33.889.02.02', '', 'http://www.cliniqueducap.com/', '14.65556', '-17.434637', '1303460102_La Clinique du Cap.jpg', 1303405200, 1303405200, '1', 'Active');
INSERT INTO `yps_item` VALUES(4, 'Université Cheikh Anta Diop', '6', 'réée le 24 février 1957, l’Université de Dakar fut officiellement inaugurée le 09 décembre 1959 après une longue évolution marquée par :\r\n\r\n    - la création d’une école africaine de médecine, première ébauche d’un enseignement supérieur en Afrique en 1918 (décret du 14 janvier 1918) ;\r\n    - la création de l’Institut Français d’Afrique Noire (IFAN) en 1936;\r\n    - la création d’un certificat de Physique, Chimie et Biologie (PCB), préparatoire aux études médicales et par l’ouverture au début des années cinquante d’écoles supérieures académiquement rattachées à l’Université de Bordeaux dans le cadre de ce qui fût appelé en 1950, Institut des Hautes Etudes de Dakar ;\r\n    - l’érection de facultés indépendantes  en lieu et place de ces écoles supérieures pour former la 18ème Université Française, académiquement rattachée aux Universités de Paris et de Bordeaux, en 1957 ;\r\n    - le changement de dénomination de l’Université de Dakar, qui devient Université Cheikh Anta Diop de Dakar le 30 mars 1987 ;\r\n    - la réforme pédagogique issue de la Concertation Nationale sur l’Enseignement Supérieure et la création la faculté des Sciences Economiques et de Gestion (FASEG) en 1994 ;\r\n    - l’introduction de la réforme LMD en 2003 ;\r\n    - la création de la Faculté des Sciences et Technologies de l’Education et de la Formation (FASTEF) en 2004 ;\r\n    -la réforme des études doctorales en 2005.\r\n', 'From 10.00 am - 6.00 pm', 'BP 5005 Dakar Dakar', '+221 825 7528', '', 'http://www.ucad.sn/', '14.75', '-17.333333', '', 1303405200, 1303405200, '1', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `yps_modulemanager`
--

CREATE TABLE `yps_modulemanager` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=96 ;

--
-- Dumping data for table `yps_modulemanager`
--

INSERT INTO `yps_modulemanager` VALUES(18, 'userrole');
INSERT INTO `yps_modulemanager` VALUES(37, 'banner');
INSERT INTO `yps_modulemanager` VALUES(46, 'user');
INSERT INTO `yps_modulemanager` VALUES(81, 'designation');
INSERT INTO `yps_modulemanager` VALUES(80, 'department');
INSERT INTO `yps_modulemanager` VALUES(79, 'userinfo');
INSERT INTO `yps_modulemanager` VALUES(69, 'expCategoty');
INSERT INTO `yps_modulemanager` VALUES(70, 'expCategoty');
INSERT INTO `yps_modulemanager` VALUES(76, 'salary');
INSERT INTO `yps_modulemanager` VALUES(75, 'expance');
INSERT INTO `yps_modulemanager` VALUES(74, 'expance');
INSERT INTO `yps_modulemanager` VALUES(77, 'salary');
INSERT INTO `yps_modulemanager` VALUES(78, 'employee');
INSERT INTO `yps_modulemanager` VALUES(82, 'city');
INSERT INTO `yps_modulemanager` VALUES(83, 'city');
INSERT INTO `yps_modulemanager` VALUES(84, 'city');
INSERT INTO `yps_modulemanager` VALUES(85, 'brand');
INSERT INTO `yps_modulemanager` VALUES(86, 'pub');
INSERT INTO `yps_modulemanager` VALUES(87, 'blacklistedemail');
INSERT INTO `yps_modulemanager` VALUES(88, 'blacklistedemail');
INSERT INTO `yps_modulemanager` VALUES(89, 'requestupdate');
INSERT INTO `yps_modulemanager` VALUES(90, 'roa');
INSERT INTO `yps_modulemanager` VALUES(91, 'roa');
INSERT INTO `yps_modulemanager` VALUES(92, 'cms');
INSERT INTO `yps_modulemanager` VALUES(93, 'cms');
INSERT INTO `yps_modulemanager` VALUES(94, 'category');
INSERT INTO `yps_modulemanager` VALUES(95, 'item');

-- --------------------------------------------------------

--
-- Table structure for table `yps_page`
--

CREATE TABLE `yps_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unique_name` varchar(250) NOT NULL,
  `filter` mediumtext NOT NULL,
  `template` varchar(250) NOT NULL,
  `is_cache` tinyint(4) NOT NULL,
  `default` tinyint(4) NOT NULL,
  `active` varchar(10) NOT NULL,
  `is_ajax` tinyint(4) NOT NULL,
  `page_title` varchar(250) NOT NULL,
  `meta_keyword` mediumtext NOT NULL,
  `meta_description` mediumtext NOT NULL,
  `navigation_name` varchar(250) NOT NULL,
  `page_bottom` text NOT NULL,
  `is_secure` tinyint(4) NOT NULL,
  `is_navigate` tinyint(4) NOT NULL,
  `navigation_order` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=61 ;

--
-- Dumping data for table `yps_page`
--

INSERT INTO `yps_page` VALUES(36, 'captcha', '', 'captcha.php', 0, 0, 'Active', 0, 'captcha', 'captcha', 'captcha', 'captcha', '', 0, 0, 0);
INSERT INTO `yps_page` VALUES(60, 'index', '', 'index.php', 0, 0, 'Active', 0, 'Home', '2', '1', 'index', '', 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `yps_user`
--

CREATE TABLE `yps_user` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `login_name` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(250) NOT NULL,
  `login_password` varchar(100) DEFAULT NULL,
  `role` int(11) NOT NULL,
  `active` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login_name` (`login_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=22 ;

--
-- Dumping data for table `yps_user`
--

INSERT INTO `yps_user` VALUES(1, 'admin', 'admin@annanovas.com', '21232f297a57a5a743894a0e4a801fc3', 1, 'Active');
INSERT INTO `yps_user` VALUES(20, 'superadmin', 'roni@annanovas.com', '17c4520f6cfd1ab53d8745e84681eb49', 1, 'Active');
INSERT INTO `yps_user` VALUES(21, 'mhkhan', 'mhkhn@annanovas.com', 'e9f72e133bc24250d011a6dc6b442c98', 1, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `yps_userrole`
--

CREATE TABLE `yps_userrole` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL,
  `active` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `yps_userrole`
--

INSERT INTO `yps_userrole` VALUES(1, 'admin', 'Active');
INSERT INTO `yps_userrole` VALUES(3, 'backend', 'Active');
INSERT INTO `yps_userrole` VALUES(6, 'endusers', 'Active');
