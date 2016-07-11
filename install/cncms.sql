-- phpMyAdmin SQL Dump
-- version 2.11.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 21, 2009 at 11:12 AM
-- Server version: 5.0.41
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cncms`
--

-- --------------------------------------------------------

--
-- Table structure for table `Cacheinfo`
--

CREATE TABLE IF NOT EXISTS `Cacheinfo` (
  `id` int(11) NOT NULL auto_increment,
  `request_url` varchar(250) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `Cacheinfo`
--


-- --------------------------------------------------------

--
-- Table structure for table `Config`
--

CREATE TABLE IF NOT EXISTS `Config` (
  `id` int(11) NOT NULL auto_increment,
  `cnfig_key` varchar(100) NOT NULL,
  `cnfig_value` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `Config`
--

INSERT INTO `Config` VALUES(7, 'page_suffix', '.html');
INSERT INTO `Config` VALUES(8, 'template_suffix', '.php');
INSERT INTO `Config` VALUES(9, 'invalid_page', '404');
INSERT INTO `Config` VALUES(10, 'auth_key', '123');
INSERT INTO `Config` VALUES(11, 'auth_value', '123');
INSERT INTO `Config` VALUES(14, 'site_name', 'Test');
INSERT INTO `Config` VALUES(13, 'save', 'Save');

-- --------------------------------------------------------

--
-- Table structure for table `ModuleManager`
--

CREATE TABLE IF NOT EXISTS `ModuleManager` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ModuleManager`
--


-- --------------------------------------------------------

--
-- Table structure for table `Page`
--

CREATE TABLE IF NOT EXISTS `Page` (
  `id` int(11) NOT NULL auto_increment,
  `unique_name` varchar(250) NOT NULL,
  `filter` mediumtext NOT NULL,
  `template` varchar(250) NOT NULL,
  `is_cache` tinyint(4) NOT NULL,
  `default` tinyint(4) NOT NULL,
  `active` tinyint(4) NOT NULL,
  `is_ajax` tinyint(4) NOT NULL,
  `page_title` varchar(250) NOT NULL,
  `meta_keyword` mediumtext NOT NULL,
  `meta_description` mediumtext NOT NULL,
  `navigation_name` varchar(250) NOT NULL,
  `page_bottom` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `Page`
--


-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE IF NOT EXISTS `User` (
  `id` tinyint(4) NOT NULL auto_increment,
  `login_name` varchar(30) NOT NULL default '',
  `login_password` varchar(100) default NULL,
  `role` int(11) NOT NULL,
  `active` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `login_name` (`login_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=3 ;

--
-- Dumping data for table `User`
--

INSERT INTO `User` VALUES(1, 'admin', '202cb962ac59075b964b07152d234b70', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `Userrole`
--

CREATE TABLE IF NOT EXISTS `Userrole` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(250) NOT NULL,
  `active` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `Userrole`
--

INSERT INTO `Userrole` VALUES(1, 'admin', 1);
INSERT INTO `Userrole` VALUES(2, 'frontend', 1);
INSERT INTO `Userrole` VALUES(3, 'backend', 1);
