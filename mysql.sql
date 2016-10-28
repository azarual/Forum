-- phpMyAdmin SQL Dump
-- version 4.0.1
-- http://www.phpmyadmin.net
--
-- Värd: localhost
-- Skapad: 28 okt 2016 kl 19:23
-- Serverversion: 5.1.52
-- PHP-version: 5.3.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Tabellstruktur `yap_posts`
--

CREATE TABLE IF NOT EXISTS `yap_posts` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `thread_id` int(11) NOT NULL,
  `post_message` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL,
  `post_reply` int(11) NOT NULL,
  `post_ip` varchar(16) NOT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;

-- --------------------------------------------------------

--
-- Tabellstruktur `yap_simple_users`
--

CREATE TABLE IF NOT EXISTS `yap_simple_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_alias` varchar(30) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_alias` (`user_alias`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;

-- --------------------------------------------------------

--
-- Tabellstruktur `yap_threads`
--

CREATE TABLE IF NOT EXISTS `yap_threads` (
  `thread_id` int(11) NOT NULL AUTO_INCREMENT,
  `thread_name` varchar(255) NOT NULL,
  `thread_views` int(11) NOT NULL,
  `thread_status` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`thread_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
