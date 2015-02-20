-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 20, 2015 at 09:10 PM
-- Server version: 5.6.16
-- PHP Version: 5.5.9

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ragam`
--

-- --------------------------------------------------------

--
-- Table structure for table `colleges`
--

CREATE TABLE IF NOT EXISTS `colleges` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `validated` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `colleges`
--

INSERT INTO `colleges` (`id`, `name`, `validated`, `created_at`, `updated_at`) VALUES
(1, 'NIT Calicut', 0, '2015-02-20 20:09:35', '2015-02-20 20:09:35');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event_code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `category_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tags` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `event_email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contacts` varchar(3000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `prizes` varchar(3000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `short_description` varchar(3000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `long_description` varchar(10000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `team_min` int(10) unsigned NOT NULL DEFAULT '1',
  `team_max` int(10) unsigned NOT NULL DEFAULT '1',
  `validated` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `events_event_code_unique` (`event_code`),
  KEY `events_category_id_foreign` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `event_code`, `category_id`, `name`, `tags`, `event_email`, `contacts`, `prizes`, `short_description`, `long_description`, `team_min`, `team_max`, `validated`, `created_at`, `updated_at`) VALUES
(1, 'XYZ', 4, 'Sample Event', 'dolor sit amet', 'sample_event', 'Boss||@||+91-9898123456||@||boss@ragam.org.in||@||http://www.facebook.com/boss||con|| ||@|| ||@|| ||@|| ||con|| ||@|| ||@|| ||@|| ', 'First Prize:\r\nSecond Prize:\r\nThird Prize:', 'This is a short short description of a sample event.', 'Introduction||ttl||This is a sample introduction.', 1, 1, 0, '2015-02-20 20:09:35', '2015-02-20 20:09:35');

-- --------------------------------------------------------

--
-- Table structure for table `event_categories`
--

CREATE TABLE IF NOT EXISTS `event_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `event_categories`
--

INSERT INTO `event_categories` (`id`, `parent_id`, `name`) VALUES
(1, 0, 'Events'),
(2, 0, 'Workshops'),
(3, 0, 'Proshows'),
(4, 1, 'General');

-- --------------------------------------------------------

--
-- Table structure for table `managers`
--

CREATE TABLE IF NOT EXISTS `managers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `signup_data` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `roll_no` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `role` int(11) NOT NULL DEFAULT '0',
  `event_code` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `validated` tinyint(1) NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `managers_email_unique` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `managers`
--

INSERT INTO `managers` (`id`, `email`, `name`, `password`, `signup_data`, `roll_no`, `role`, `event_code`, `validated`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'xaneem@gmail.com', 'Saneem', '$2y$10$HXA5ITizrv3dtzIYfvPtT.Xq/RvA210xOe3YvaJKrSdUL2uVdRN8a', '', '', 21, NULL, 1, NULL, '2015-02-20 20:09:35', '2015-02-20 20:09:35');

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE IF NOT EXISTS `registrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fb_uid` bigint(20) DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `college_id` int(10) unsigned DEFAULT NULL,
  `runtime_id` int(11) DEFAULT NULL,
  `payment_done` tinyint(1) NOT NULL DEFAULT '0',
  `hospitality_start` tinyint(1) NOT NULL DEFAULT '0',
  `hospitality_end` tinyint(1) NOT NULL DEFAULT '0',
  `notes` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `registrations_email_unique` (`email`),
  KEY `registrations_college_id_foreign` (`college_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10002 ;

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`id`, `fb_uid`, `email`, `password`, `name`, `phone`, `college_id`, `runtime_id`, `payment_done`, `hospitality_start`, `hospitality_end`, `notes`, `remember_token`, `created_at`, `updated_at`) VALUES
(10001, NULL, 'user@example.com', '$2y$10$3/wEUWkrnBdbEglIrWf8dOS9KFZfidkYX40chwr95d82xLGmMZJQ6', 'John Doe', '9995552233', 1, NULL, 0, 0, 0, NULL, NULL, '2015-02-20 20:09:36', '2015-02-20 20:09:36');

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE IF NOT EXISTS `teams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event_code` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `team_code` int(11) NOT NULL,
  `registration_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `teams_event_code_team_code_unique` (`event_code`,`team_code`),
  KEY `teams_registration_id_foreign` (`registration_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`id`, `event_code`, `team_code`, `registration_id`, `created_at`) VALUES
(1, 'XYZ', 101, 1, '2015-02-20 20:09:36');

-- --------------------------------------------------------

--
-- Table structure for table `team_members`
--

CREATE TABLE IF NOT EXISTS `team_members` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `team_id` int(10) unsigned NOT NULL,
  `registration_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `team_members_team_id_foreign` (`team_id`),
  KEY `team_members_registration_id_foreign` (`registration_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `team_members`
--

INSERT INTO `team_members` (`id`, `team_id`, `registration_id`, `created_at`) VALUES
(1, 1, 1, '2015-02-20 20:09:36');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `event_categories` (`id`);

--
-- Constraints for table `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `registrations_college_id_foreign` FOREIGN KEY (`college_id`) REFERENCES `colleges` (`id`);

--
-- Constraints for table `teams`
--
ALTER TABLE `teams`
  ADD CONSTRAINT `teams_registration_id_foreign` FOREIGN KEY (`registration_id`) REFERENCES `registrations` (`id`);

--
-- Constraints for table `team_members`
--
ALTER TABLE `team_members`
  ADD CONSTRAINT `team_members_registration_id_foreign` FOREIGN KEY (`registration_id`) REFERENCES `registrations` (`id`),
  ADD CONSTRAINT `team_members_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`);
SET FOREIGN_KEY_CHECKS=1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
