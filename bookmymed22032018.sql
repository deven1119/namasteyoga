-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.19-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table bookmymed.administrators
CREATE TABLE IF NOT EXISTS `administrators` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `sms_code` int(6) DEFAULT NULL,
  `confirm` tinyint(1) DEFAULT '0',
  `password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `created_at` varchar(255) DEFAULT NULL,
  `updated_at` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

-- Dumping data for table bookmymed.administrators: ~4 rows (approximately)
/*!40000 ALTER TABLE `administrators` DISABLE KEYS */;
INSERT INTO `administrators` (`id`, `name`, `email`, `phone`, `sms_code`, `confirm`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(2, 'Administrator', 'admin@bookmymed.com', NULL, NULL, 0, '$2y$10$.qF3YSPfq/lW0Ku0peXQZedQusekomreOlMtktIHaAi8rlFBN/jmy', 'ZoWaeY0ay173EplYtkvHrpn2KeHd7adG7JmCrgh7', '2017-05-29 16:02:34', '2017-05-29 16:02:34'),
	(11, 'Administrator123', 'admin12@bookmymed.com', '9958587457', NULL, 0, '$2y$10$Ep8hvlH/oOO/pYpiu7r1qOY9Aj.I36FBfg9r4.IlzuOp8HiqYp2aW', NULL, '2017-10-15 13:47:07', '2017-10-15 13:47:07'),
	(17, 'mytest', 'admin2@bookmymed.com', '9985748745', 1234, 1, '$2y$10$M1Zjgr1Ti0NnM4wJlEHhQOwXopu1XynXpDuW3zzF.Yngg6oLAdQS2', NULL, '2017-11-26 14:14:13', '2017-11-26 14:14:13');
/*!40000 ALTER TABLE `administrators` ENABLE KEYS */;

-- Dumping structure for table bookmymed.ambulance
CREATE TABLE IF NOT EXISTS `ambulance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT NULL,
  `number` varchar(30) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `type` char(50) DEFAULT 'private',
  `phone` varchar(12) DEFAULT NULL,
  `address` varchar(500) DEFAULT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

-- Dumping data for table bookmymed.ambulance: ~12 rows (approximately)
/*!40000 ALTER TABLE `ambulance` DISABLE KEYS */;
INSERT INTO `ambulance` (`id`, `name`, `number`, `status`, `type`, `phone`, `address`, `pincode`, `created_at`, `updated_at`) VALUES
	(1, 'test', '454545', 1, 'private', '8878877', 'sdfas sdf as s ', '2012546', '2018-01-09 23:42:48', NULL),
	(2, 'rav', '4578', 1, 'private', '9987458745', 'sdf sdf sd f', '321546', '2018-01-09 23:46:03', NULL),
	(3, 'eee', '2333', 1, 'private', '99877', 'sdf sdf sd f', '321546', '2018-01-09 23:46:03', NULL),
	(4, 'eee', '2333', 1, 'government', '99877', 'sdf sdf sd f', '321546', '2018-01-09 23:46:03', NULL),
	(5, 'eee', '2333', 1, 'private', '99877', 'sdf sdf sd f', '321546', '2018-01-09 23:46:03', NULL),
	(6, 'eee', '2333', 1, 'private', '99877', 'sdf sdf sd f', '321546', '2018-01-09 23:46:03', NULL),
	(7, 'eee', '2333', 1, 'government', '99877', 'sdf sdf sd f', '321546', '2018-01-09 23:46:03', NULL),
	(8, 'eee', '2333', 1, 'private', '99877', 'sdf sdf sd f', '321546', '2018-01-09 23:46:03', NULL),
	(9, 'eee', '2333', 1, 'private', '99877', 'sdf sdf sd f', '321546', '2018-01-09 23:46:03', NULL),
	(10, 'eee', '2333', 1, 'private', '99877', 'sdf sdf sd f', '321546', '2018-01-09 23:46:03', NULL),
	(11, 'eee', '2333', 1, 'government', '99877', 'sdf sdf sd f', '321546', '2018-01-09 23:46:03', NULL),
	(12, 'eee', '2333', 1, 'private', '99877', 'sdf sdf sd f', '321546', '2018-01-09 23:46:03', NULL);
/*!40000 ALTER TABLE `ambulance` ENABLE KEYS */;

-- Dumping structure for table bookmymed.carts
CREATE TABLE IF NOT EXISTS `carts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table bookmymed.carts: ~2 rows (approximately)
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
INSERT INTO `carts` (`id`, `product_id`, `user_id`, `created_at`, `updated_at`) VALUES
	(2, 1, 6, '2018-03-14 17:39:43', '2018-03-14 17:39:43');
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;

-- Dumping structure for table bookmymed.category
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table bookmymed.category: ~2 rows (approximately)
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` (`id`, `parent_id`, `name`, `status`, `created_at`, `updated_at`) VALUES
	(1, 0, 'test1', '1', '2017-08-09 17:50:38', '2017-08-09 18:07:06'),
	(2, 1, 'test14', '1', '2017-08-09 18:07:27', '2017-08-09 18:12:23');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;

-- Dumping structure for table bookmymed.cmstypes
CREATE TABLE IF NOT EXISTS `cmstypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table bookmymed.cmstypes: ~3 rows (approximately)
/*!40000 ALTER TABLE `cmstypes` DISABLE KEYS */;
INSERT INTO `cmstypes` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
	(1, 'page', 1, '2017-06-19 20:32:08', '0000-00-00 00:00:00'),
	(2, 'faq', 1, '2017-06-19 20:32:19', '0000-00-00 00:00:00'),
	(3, 'knowledge center', 1, '2017-06-19 20:32:39', '0000-00-00 00:00:00');
/*!40000 ALTER TABLE `cmstypes` ENABLE KEYS */;

-- Dumping structure for table bookmymed.configs
CREATE TABLE IF NOT EXISTS `configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `google_captcha_key` varchar(255) NOT NULL,
  `google_captcha_secret` varchar(255) NOT NULL,
  `twitter_consumer_key` varchar(255) NOT NULL,
  `twitter_consumer_secret` varchar(255) NOT NULL,
  `twitter_outh_callback` varchar(255) NOT NULL,
  `paypal_currency_code` varchar(10) NOT NULL,
  `paypal_url` varchar(255) NOT NULL,
  `paypal_email` varchar(255) NOT NULL,
  `paypal_cancel_url` varchar(255) NOT NULL,
  `paypal_success_url` varchar(255) NOT NULL,
  `paypal_notify_url` varchar(255) NOT NULL,
  `file_upload_path` varchar(255) NOT NULL,
  `file_view_path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table bookmymed.configs: ~0 rows (approximately)
/*!40000 ALTER TABLE `configs` DISABLE KEYS */;
INSERT INTO `configs` (`id`, `google_captcha_key`, `google_captcha_secret`, `twitter_consumer_key`, `twitter_consumer_secret`, `twitter_outh_callback`, `paypal_currency_code`, `paypal_url`, `paypal_email`, `paypal_cancel_url`, `paypal_success_url`, `paypal_notify_url`, `file_upload_path`, `file_view_path`, `created_at`, `updated_at`) VALUES
	(1, '6Lde1B8UAAAAACaHH6Y0WLUPLVEX0rzm4VxJkzES', '6Lde1B8UAAAAAKJvpQbkXS5M0bJFkPweKOOQZpzl', 'KoOESxaasM21L3fSmDDtlx0ln', 'qYFrDIoPm0U4Q0cRCcxpH7OpuL1P2py3ZGjFhE2e8RIUK4im1a', 'http://www.maxnewswire.com/user.php?act=twitter', 'USD', 'https://www.sandbox.paypal.com/cgi-bin/webscr', 'pro.prakriti-facilitator@gmail.com', 'http://maxnewswire.com/payment.php?act=cancel', 'http://maxnewswire.com/payment.php?act=success', 'http://maxnewswire.com/ipn.php', '/var/www/html/maxnewswires/', 'http://localhost/maxnewswires/', '2017-07-11 15:35:58', '2017-07-11 16:13:06');
/*!40000 ALTER TABLE `configs` ENABLE KEYS */;

-- Dumping structure for table bookmymed.country
CREATE TABLE IF NOT EXISTS `country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=238 DEFAULT CHARSET=latin1;

-- Dumping data for table bookmymed.country: ~237 rows (approximately)
/*!40000 ALTER TABLE `country` DISABLE KEYS */;
INSERT INTO `country` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
	(1, 'India', '1', '2017-04-12 20:52:01', '2017-07-13 22:31:09'),
	(2, 'United States', '1', '2017-04-12 20:52:01', '2017-07-13 22:31:09'),
	(3, 'Australia', '1', '2017-04-12 20:52:22', '2017-07-13 22:31:09'),
	(4, 'Canada', '1', '2017-04-30 00:25:06', '2017-07-13 22:31:09'),
	(5, 'United Kingdom', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(6, 'Afghanistan', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(7, 'Albania', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(8, 'Algeria', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(9, 'Amer.Virgin Is.', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(10, 'Andorra', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(11, 'Angola', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(12, 'Anguilla', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(13, 'Antarctica', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(14, 'Antigua/Barbads', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(15, 'Argentina', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(16, 'Armenia', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(17, 'Aruba', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(18, 'Austria', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(19, 'Azerbaijan', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(20, 'Bahamas', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(21, 'Bahrain', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(22, 'Bangladesh', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(23, 'Barbados', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(24, 'Belarus', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(25, 'Belgium', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(26, 'Belize', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(27, 'Benin', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(28, 'Bermuda', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(29, 'Bhutan', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(30, 'Bolivia', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(31, 'Bosnia-Herz.', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(32, 'Botswana', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(33, 'Bouvet Island', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(34, 'Brazil', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(35, 'Brit.Ind.Oc.Ter', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(36, 'Brit.Virgin Is.', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(37, 'Brunei', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(38, 'Bulgaria', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(39, 'Burkina-Faso', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(40, 'Burundi', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(41, 'Cambodia', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(42, 'Cameroon', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(43, 'Cape Verde', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(44, 'Cayman Islands', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(45, 'Central Afr.Rep', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(46, 'Chad', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(47, 'Channel Islands', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(48, 'Chile', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(49, 'China', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(50, 'Christmas Islnd', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(51, 'Coconut Islands', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(52, 'Colombia', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(53, 'Comoro', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(54, 'Congo', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(55, 'Cook Islands', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(56, 'Costa Rica', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(57, 'Croatia', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(58, 'Cuba', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(59, 'Cyprus', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(60, 'Czech Republic', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(61, 'Denmark', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(62, 'Djibouti', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(63, 'Dominica', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(64, 'Dominican Rep.', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(65, 'Ecuador', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(66, 'Egypt', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(67, 'El Salvador', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(68, 'Equatorial Guin', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(69, 'Eritrea', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(70, 'Estonia', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(71, 'Ethiopia', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(72, 'Faeroe Islands', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(73, 'Falkland Islnds', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(74, 'Fiji', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(75, 'Finland', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(76, 'France', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(77, 'Frenc.Polynesia', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(78, 'French Guinea', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(79, 'Gabon', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(80, 'Gambia', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(81, 'Georgia', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(82, 'Germany', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(83, 'Ghana', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(84, 'Gibraltar', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(85, 'Greece', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(86, 'Greenland', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(87, 'Grenada', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(88, 'Guadeloupe', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(89, 'Guam', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(90, 'Guatemala', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(91, 'Guinea', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(92, 'Guinea-Bissau', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(93, 'Guyana', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(94, 'Haiti', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(95, 'Heard/McDon.Isl', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(96, 'Honduras', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(97, 'Hong Kong', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(98, 'Hungary', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(99, 'Iceland', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(100, 'Indonesia', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(101, 'Iran', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(102, 'Iraq', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(103, 'Ireland', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(104, 'Israel', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(105, 'Italy', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(106, 'Ivory Coast', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(107, 'Jamaica', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(108, 'Japan', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(109, 'Jordan', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(110, 'Kazakhstan', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(111, 'Kenya', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(112, 'Kirghistan', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(113, 'Kiribati', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(114, 'Kuwait', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(115, 'Laos', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(116, 'Latvia', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(117, 'Lebanon', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(118, 'Lesotho', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(119, 'Liberia', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(120, 'Libya', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(121, 'Liechtenstein', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(122, 'Lithuania', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(123, 'Luxembourg', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(124, 'Macau', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(125, 'Macedonia', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(126, 'Madagascar', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(127, 'Malawi', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(128, 'Malaysia', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(129, 'Maldives', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(130, 'Mali', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(131, 'Malta', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(132, 'Marshall Islnds', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(133, 'Martinique', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(134, 'Mauritania', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(135, 'Mauritius', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(136, 'Mayotte', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(137, 'Mexico', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(138, 'Micronesia', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(139, 'Minor Outl.Isl.', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(140, 'Moldavia', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(141, 'Monaco', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(142, 'Mongolia', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(143, 'Montserrat', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(144, 'Morocco', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(145, 'Mozambique', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(146, 'Myanmar', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(147, 'N.Mariana Islnd', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(148, 'Namibia', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(149, 'Nauru', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(150, 'Nepal', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(151, 'Netherland Antilles', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(152, 'Netherlands', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(153, 'New Caledonia', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(154, 'New Zealand', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(155, 'Nicaragua', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(156, 'Niger', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(157, 'Nigeria', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(158, 'Niue Islands', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(159, 'Norfolk Island', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(160, 'North Korea', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(161, 'Norway', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(162, 'Oman', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(163, 'Pakistan', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(164, 'Palau', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(165, 'Panama', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(166, 'Papua New Guinea', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(167, 'Paraguay', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(168, 'Peru', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(169, 'Philippines', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(170, 'Pitcairn Islnds', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(171, 'Poland', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(172, 'Portugal', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(173, 'Puerto Rico', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(174, 'Qatar', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(175, 'Reunion', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(176, 'Romania', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(177, 'Russian Fed.', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(178, 'Rwanda', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(179, 'S.Tome,Principe', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(180, 'Samoa,American', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(181, 'San Marino', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(182, 'Saudi Arabia', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(183, 'Senegal', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(184, 'Seychelles', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(185, 'Sierra Leone', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(186, 'Singapore', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(187, 'Slovakia', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(188, 'Slovenia', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(189, 'Solomon Islands', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(190, 'Somalia', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(191, 'South Africa', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(192, 'South Korea', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(193, 'Spain', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(194, 'Sri Lanka', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(195, 'St. Helena', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(196, 'St. Lucia', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(197, 'St. Vincent', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(198, 'St.Kitts, Nevis', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(199, 'St.Pier,Miquel.', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(200, 'Sth Sandwich Is', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(201, 'Sudan', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(202, 'Suriname', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(203, 'Svalbard', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(204, 'Swaziland', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(205, 'Sweden', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(206, 'Switzerland', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(207, 'Syria', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(208, 'Tadzhikistan', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(209, 'Taiwan', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(210, 'Tanzania', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(211, 'Thailand', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(212, 'Togo', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(213, 'Tokelau Islands', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(214, 'Tonga', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(215, 'Trinidad,Tobago', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(216, 'Tunisia', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(217, 'Turkey', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(218, 'Turkmenistan', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(219, 'Turks &amp;Caicos', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(220, 'Tuvalu', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(221, 'Uganda', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(222, 'Ukraine', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(223, 'Uruguay', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(224, 'Utd.Arab.Emir.', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(225, 'Uzbekistan', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(226, 'Vanuatu', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(227, 'Vatican City', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(228, 'Venezuela', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(229, 'Vietnam', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(230, 'Wallis,Futuna', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(231, 'West Sahara', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(232, 'Western Samoa', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(233, 'Yemen', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(234, 'Yugoslavia', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(235, 'Zaire', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(236, 'Zambia', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09'),
	(237, 'Zimbabwe', '1', '2017-06-19 21:59:03', '2017-07-13 22:31:09');
/*!40000 ALTER TABLE `country` ENABLE KEYS */;

-- Dumping structure for table bookmymed.coupons
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL DEFAULT '0',
  `code` varchar(6) NOT NULL,
  `amount` float(8,2) NOT NULL DEFAULT '0.00',
  `valid_from` date NOT NULL,
  `valid_to` date NOT NULL,
  `used_date` date NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_coupons_coupon_type` (`type_id`),
  CONSTRAINT `FK_coupons_coupon_type` FOREIGN KEY (`type_id`) REFERENCES `coupon_type` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

-- Dumping data for table bookmymed.coupons: ~11 rows (approximately)
/*!40000 ALTER TABLE `coupons` DISABLE KEYS */;
INSERT INTO `coupons` (`id`, `type_id`, `code`, `amount`, `valid_from`, `valid_to`, `used_date`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, 'ADERT4', 51.00, '2017-06-14', '2017-07-14', '2017-06-14', 0, '2017-06-14 22:54:10', '2017-06-14 18:06:29'),
	(3, 1, 'YUX4NE', 25.00, '2017-01-02', '2018-01-01', '2017-06-15', 1, '2017-06-15 15:50:42', '2017-06-15 15:50:42'),
	(4, 1, 'GELFM1', 25.00, '2017-01-02', '2018-01-01', '2017-06-15', 1, '2017-06-15 15:50:43', '2017-06-15 15:50:43'),
	(5, 1, 'XI5YRK', 25.00, '2017-01-02', '2018-01-01', '2017-06-15', 1, '2017-06-15 15:50:43', '2017-06-15 15:50:43'),
	(6, 1, 'Q6LELF', 25.00, '2017-01-02', '2018-01-01', '2017-06-15', 1, '2017-06-15 15:50:43', '2017-06-15 15:50:43'),
	(7, 1, 'JQHEPG', 25.00, '2017-01-02', '2018-01-01', '2017-06-15', 1, '2017-06-15 15:50:43', '2017-06-15 15:50:43'),
	(8, 1, 'LHQSKI', 25.00, '2017-01-02', '2018-01-01', '2017-06-15', 1, '2017-06-15 15:50:43', '2017-06-15 15:50:43'),
	(9, 1, '5RG69Q', 25.00, '2017-01-02', '2018-01-01', '2017-06-15', 1, '2017-06-15 15:50:43', '2017-06-15 15:50:43'),
	(10, 1, 'YMMFD3', 25.00, '2017-01-02', '2018-01-01', '2017-06-15', 1, '2017-06-15 15:50:43', '2017-06-15 15:50:43'),
	(11, 1, 'ARDVBJ', 25.00, '2017-01-02', '2018-01-01', '2017-06-15', 1, '2017-06-15 15:50:43', '2017-06-15 15:50:43'),
	(12, 1, 'KKPJEJ', 25.00, '2017-01-02', '2018-01-01', '2017-06-15', 1, '2017-06-15 15:50:43', '2017-06-15 15:50:43');
/*!40000 ALTER TABLE `coupons` ENABLE KEYS */;

-- Dumping structure for table bookmymed.coupon_type
CREATE TABLE IF NOT EXISTS `coupon_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table bookmymed.coupon_type: ~2 rows (approximately)
/*!40000 ALTER TABLE `coupon_type` DISABLE KEYS */;
INSERT INTO `coupon_type` (`id`, `type`, `created_at`, `updated_at`) VALUES
	(1, 'Flat', '2017-06-14 22:40:06', '0000-00-00 00:00:00'),
	(2, '%', '2017-06-14 22:40:12', '0000-00-00 00:00:00');
/*!40000 ALTER TABLE `coupon_type` ENABLE KEYS */;

-- Dumping structure for table bookmymed.discount
CREATE TABLE IF NOT EXISTS `discount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `volume_discount` int(11) NOT NULL COMMENT 'In %',
  `no_of_plan` int(11) NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'normal',
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Dumping data for table bookmymed.discount: ~4 rows (approximately)
/*!40000 ALTER TABLE `discount` DISABLE KEYS */;
INSERT INTO `discount` (`id`, `volume_discount`, `no_of_plan`, `type`, `status`, `created_at`, `modified_at`) VALUES
	(1, 10, 5, 'normal', '1', '2017-04-20 21:49:19', '2017-09-16 22:28:46'),
	(2, 20, 15, 'normal', '1', '2017-04-20 21:49:19', '2017-09-16 22:28:46'),
	(3, 25, 30, 'normal', '1', '2017-04-20 21:49:52', '2017-09-16 22:28:46'),
	(4, 30, 50, 'normal', '1', '2017-04-20 21:49:52', '2017-09-16 22:28:46');
/*!40000 ALTER TABLE `discount` ENABLE KEYS */;

-- Dumping structure for table bookmymed.discount_types
CREATE TABLE IF NOT EXISTS `discount_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table bookmymed.discount_types: ~0 rows (approximately)
/*!40000 ALTER TABLE `discount_types` DISABLE KEYS */;
INSERT INTO `discount_types` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
	(3, 'sx', '1', '2017-09-16 16:57:02', '2017-09-16 16:57:21');
/*!40000 ALTER TABLE `discount_types` ENABLE KEYS */;

-- Dumping structure for table bookmymed.emails
CREATE TABLE IF NOT EXISTS `emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL,
  `body` longtext NOT NULL,
  `type` varchar(50) NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- Dumping data for table bookmymed.emails: ~9 rows (approximately)
/*!40000 ALTER TABLE `emails` DISABLE KEYS */;
INSERT INTO `emails` (`id`, `subject`, `body`, `type`, `status`, `created`, `created_at`, `updated_at`) VALUES
	(1, 'Welcome to BookMyMed', '<p>Dear #USER#,<br />\r\nYour account has been successfully created at #SITE# with the following credentials.<br />\r\n<br />\r\n<strong>Login Email:</strong> #EMAIL#<br />\r\n<strong>Password:</strong> #PASSWORD#<br />\r\n<br />\r\n<a href="#ACTIVATION#" target="_blank">Click here to Activate your account on #SITENAME# </a><br />\r\n<br />\r\n<br />\r\nShould you require any information or have a question, kindly visit #SITEURL#<br />\r\nKindly feel free to submit a support ticket, email us, or call us for any questions or clarifications regarding our services and your account. We look forward to publish and distribute your press release to offer you unmatched visibility and mileage.<br />\r\n<br />\r\nBest Regards,<br />\r\nCustomer Support Team<br />\r\n#SITENAME#</p>', 'registration', '1', '2017-04-23 00:04:07', '2017-07-13 22:23:32', '2017-08-20 17:58:31'),
	(2, 'Contact-us', 'Dear Admin,<br />\r\n\r\n<br /><br />\r\nBelow person have contacted you.\r\n<br /><br />\r\n\r\n<b>Name:</b> #Name# <br />\r\n<b>Email:</b> #Email# <br />\r\n<b>Phone:</b> #Phone# <br />\r\n<b>Message:</b> #Message# <br />', 'contactus', '1', '2017-05-06 18:47:29', '2017-07-13 22:23:32', '2017-08-20 17:58:46'),
	(3, 'Forgot Password', 'Dear #USER#\n<br /><br />\nBelow are your new password details\n<br /><br />\n<b>Password:</b> #Password#\n<br /><br />\n\nShould you require any information or have a question, kindly visit #SITEURL#\n<br />\nKindly feel free to submit a support ticket, email us, or call us for any questions or clarifications regarding our services and your account. We look forward to publish and distribute your press release to offer you unmatched visibility and mileage.\n\n<br />\n<br />\n\nBest Regards,<br />\nCustomer Support Team<br />\n#SITENAME#', 'forgot_password', '1', '2017-05-06 19:21:54', '2017-07-13 22:23:32', '2017-07-13 17:11:40'),
	(4, 'Newsletter subscription', 'Dear Customer,\n<br /><br />\nYou have been successfully subscribed newsletter \n<br /><br />\n\nShould you require any information or have a question, kindly visit #SITEURL#\n<br />\nKindly feel free to submit a support ticket, email us, or call us for any questions or clarifications regarding our services and your account. We look forward to publish and distribute your press release to offer you unmatched visibility and mileage.\n\n<br />\n<br />\n\nBest Regards,<br />\nCustomer Support Team<br />\n#SITENAME#', 'newsletter', '1', '2017-05-06 20:11:21', '2017-07-13 22:23:32', '2017-07-13 17:11:38'),
	(6, 'Query For :: #SUBJECT#', 'Dear Admin,\r\n<br /><br />\r\n<b>Name</b>: #NAME#\r\n<br /><br />\r\n<b>Email</b>: #EMAIL#\r\n<br /><br />\r\n<b>Message</b>:\r\n<br /><br />\r\n#MESSAGE#\r\n\r\n<br /><br />\r\nBest Regards,<br />\r\nCustomer Support Team<br />\r\n#SITENAME#', 'contact_admin', '1', '2017-06-19 21:59:01', '2017-07-13 22:23:32', '2017-07-13 22:23:32'),
	(7, 'BookMyMed :: Thanks for writing to us', 'Dear #USER#,\r\n<br /><br />\r\nWe have received your message.</b>\r\n<br /><br />\r\nWe will get back to you with in 2-3 working days.\r\n<br /><br />\r\nBest Regards,<br />\r\nCustomer Support Team<br />\r\n#SITENAME#', 'contact_admin_auto_reply', '1', '2017-06-19 21:59:01', '2017-07-13 22:23:32', '2017-08-20 17:59:55'),
	(8, 'BookMyMed | Password Changed', 'Dear #USER#,\r\n<br /><br />\r\nYour password has been changed successfully.</b>\r\n<br /><br />\r\nBest Regards,<br />\r\nCustomer Support Team<br />\r\n#SITENAME#', 'change_password', '1', '2017-06-19 21:59:03', '2017-07-13 22:23:32', '2017-08-20 18:00:13'),
	(9, 'BookMyMed | Support Ticket Created', 'Dear Admin,\r\n<br /><br />\r\nBelow ticket has been created by: <b>#NAME#</b>\r\n<br /><br />\r\n<b>Subject</b>: #SUBJECT#\r\n<br /><br />\r\n<b>Message</b>:\r\n<br /><br />\r\n#MESSAGE#\r\n\r\n<br /><br />\r\nBest Regards,<br />\r\nCustomer Support Team<br />\r\n#SITENAME#', 'support_ticket', '1', '2017-06-19 21:59:03', '2017-07-13 22:23:32', '2017-08-20 18:00:31'),
	(10, 'Bulk Email1', '<p>BulkEmailBulkEmailBulkEmail1</p>', 'BulkEmail1', '1', '2017-07-13 22:28:51', '2017-07-13 16:58:51', '2017-07-13 16:59:03');
/*!40000 ALTER TABLE `emails` ENABLE KEYS */;

-- Dumping structure for table bookmymed.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table bookmymed.migrations: ~9 rows (approximately)
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_resets_table', 1),
	(3, '2015_07_22_115516_create_ticketit_tables', 1),
	(4, '2015_07_22_123254_alter_users_table', 1),
	(5, '2015_09_29_123456_add_completed_at_column_to_ticketit_table', 1),
	(6, '2015_10_08_123457_create_settings_table', 1),
	(7, '2016_01_15_002617_add_htmlcontent_to_ticketit_and_comments', 1),
	(8, '2016_01_15_040207_enlarge_settings_columns', 1),
	(9, '2016_01_15_120557_add_indexes', 1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

-- Dumping structure for table bookmymed.newsletter
CREATE TABLE IF NOT EXISTS `newsletter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table bookmymed.newsletter: ~0 rows (approximately)
/*!40000 ALTER TABLE `newsletter` DISABLE KEYS */;
INSERT INTO `newsletter` (`id`, `email`, `status`, `created_at`, `updated_at`) VALUES
	(1, 'enws@gmail.com', 1, '2017-08-21 18:50:07', NULL);
/*!40000 ALTER TABLE `newsletter` ENABLE KEYS */;

-- Dumping structure for table bookmymed.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `item_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `item_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `txn_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payment_gross` float(10,2) NOT NULL,
  `currency_code` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `payment_status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table bookmymed.orders: ~3 rows (approximately)
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` (`id`, `user_id`, `item_number`, `item_name`, `txn_id`, `payment_gross`, `currency_code`, `payment_status`, `created`) VALUES
	(1, 4, '1', 'Colpol', '42G47462N6537515C', 40.00, 'USD', 'Completed', '2017-05-13 20:27:36'),
	(2, 4, '1', 'Brufen', '6T0576399L2956533', 40.00, 'USD', 'Completed', '2017-05-13 20:34:10'),
	(3, 4, '1', 'Disprin', '6T0576dd882956533', 140.00, 'USD', 'Completed', '2017-07-12 22:37:00');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;

-- Dumping structure for table bookmymed.orders_log
CREATE TABLE IF NOT EXISTS `orders_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `txn_id` varchar(255) NOT NULL,
  `response` blob NOT NULL,
  `log_form` varchar(10) NOT NULL DEFAULT 'ipn',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table bookmymed.orders_log: ~2 rows (approximately)
/*!40000 ALTER TABLE `orders_log` DISABLE KEYS */;
INSERT INTO `orders_log` (`id`, `txn_id`, `response`, `log_form`, `created`) VALUES
	(1, '42G47462N6537515C', _binary 0x613A33313A7B733A31313A2270617965725F656D61696C223B733A32323A2270726F2E7072616B7269746940676D61696C2E636F6D223B733A383A2270617965725F6964223B733A31333A2255443747415753484E5A414D47223B733A31323A2270617965725F737461747573223B733A31303A22554E5645524946494544223B733A31303A2266697273745F6E616D65223B733A363A224D6973687261223B733A393A226C6173745F6E616D65223B733A313A2250223B733A31323A22616464726573735F6E616D65223B733A383A224D69736872612050223B733A31343A22616464726573735F737472656574223B733A32333A2231373920536563746F72203120566173756E6468617261223B733A31323A22616464726573735F63697479223B733A393A224768617A6961626164223B733A31333A22616464726573735F7374617465223B733A323A22494E223B733A32303A22616464726573735F636F756E7472795F636F6465223B733A323A225553223B733A31313A22616464726573735F7A6970223B733A353A223230313233223B733A31373A227265736964656E63655F636F756E747279223B733A323A225553223B733A363A2274786E5F6964223B733A31373A2234324734373436324E3635333735313543223B733A31313A226D635F63757272656E6379223B733A333A22555344223B733A363A226D635F666565223B733A343A22312E3836223B733A383A226D635F67726F7373223B733A353A2234302E3030223B733A32323A2270726F74656374696F6E5F656C69676962696C697479223B733A383A22454C494749424C45223B733A31313A227061796D656E745F666565223B733A343A22312E3836223B733A31333A227061796D656E745F67726F7373223B733A353A2234302E3030223B733A31343A227061796D656E745F737461747573223B733A393A22436F6D706C65746564223B733A31323A227061796D656E745F74797065223B733A373A22696E7374616E74223B733A393A226974656D5F6E616D65223B733A33333A2253696E676C652050726573732052656C6561736520446973747269627574696F6E223B733A31313A226974656D5F6E756D626572223B733A313A2231223B733A383A227175616E74697479223B733A313A2231223B733A383A2274786E5F74797065223B733A31303A227765625F616363657074223B733A31323A227061796D656E745F64617465223B733A32303A22323031372D30352D31335431343A35373A31385A223B733A383A22627573696E657373223B733A33343A2270726F2E7072616B726974692D666163696C697461746F7240676D61696C2E636F6D223B733A31313A2272656365697665725F6964223B733A31333A22515A4C4738544B593736434C34223B733A31343A226E6F746966795F76657273696F6E223B733A31313A22554E56455253494F4E4544223B733A363A22637573746F6D223B733A31343A22342D312D7765625F6F6E6C792D30223B733A31313A227665726966795F7369676E223B733A35363A22414663577856323143376664307633625959595243705353526C3331415241443468795963784C6F624D443950532E4E5056483133454B43223B7D, 'url', '2017-05-13 20:27:37'),
	(2, '6T0576399L2956533', _binary 0x613A33323A7B733A31313A2270617965725F656D61696C223B733A32323A2270726F2E7072616B7269746940676D61696C2E636F6D223B733A383A2270617965725F6964223B733A31333A2255443747415753484E5A414D47223B733A31323A2270617965725F737461747573223B733A31303A22554E5645524946494544223B733A31303A2266697273745F6E616D65223B733A363A224D6973687261223B733A393A226C6173745F6E616D65223B733A313A2250223B733A31323A22616464726573735F6E616D65223B733A383A224D69736872612050223B733A31343A22616464726573735F737472656574223B733A32333A2231373920536563746F72203120566173756E6468617261223B733A31323A22616464726573735F63697479223B733A393A224768617A6961626164223B733A31333A22616464726573735F7374617465223B733A323A22494E223B733A32303A22616464726573735F636F756E7472795F636F6465223B733A323A225553223B733A31313A22616464726573735F7A6970223B733A353A223230313233223B733A31373A227265736964656E63655F636F756E747279223B733A323A225553223B733A363A2274786E5F6964223B733A31373A223654303537363339394C32393536353333223B733A31313A226D635F63757272656E6379223B733A333A22555344223B733A363A226D635F666565223B733A343A22312E3836223B733A383A226D635F67726F7373223B733A353A2234302E3030223B733A32323A2270726F74656374696F6E5F656C69676962696C697479223B733A383A22454C494749424C45223B733A31313A227061796D656E745F666565223B733A343A22312E3836223B733A31333A227061796D656E745F67726F7373223B733A353A2234302E3030223B733A31343A227061796D656E745F737461747573223B733A393A22436F6D706C65746564223B733A31323A227061796D656E745F74797065223B733A373A22696E7374616E74223B733A393A226974656D5F6E616D65223B733A33333A2253696E676C652050726573732052656C6561736520446973747269627574696F6E223B733A31313A226974656D5F6E756D626572223B733A313A2231223B733A383A227175616E74697479223B733A313A2231223B733A383A2274786E5F74797065223B733A31303A227765625F616363657074223B733A31323A227061796D656E745F64617465223B733A32303A22323031372D30352D31335431353A30343A30345A223B733A383A22627573696E657373223B733A33343A2270726F2E7072616B726974692D666163696C697461746F7240676D61696C2E636F6D223B733A31313A2272656365697665725F6964223B733A31333A22515A4C4738544B593736434C34223B733A31343A226E6F746966795F76657273696F6E223B733A31313A22554E56455253494F4E4544223B733A363A22637573746F6D223B733A31343A22342D312D7765625F6F6E6C792D30223B733A31313A227665726966795F7369676E223B733A35363A22414663577856323143376664307633625959595243705353526C333141752D6C535A576D61357456425574764F4F737A66396C3332554B47223B733A373A226C6F675F666F72223B733A333A2275726C223B7D, 'url', '2017-05-13 20:34:10');
/*!40000 ALTER TABLE `orders_log` ENABLE KEYS */;

-- Dumping structure for table bookmymed.pages
CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT '0',
  `cmstype_id` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `desc` text,
  `status` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Dumping data for table bookmymed.pages: ~2 rows (approximately)
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` (`id`, `parent_id`, `cmstype_id`, `title`, `slug`, `desc`, `status`, `created_at`, `updated_at`) VALUES
	(4, 0, 1, 'About Us', 'about-us', 'Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med Book My Med', 1, '2017-07-06 15:48:04', '2017-08-21 13:14:10'),
	(5, 0, 1, 'How It Works', 'how-it-works', 'How It WorksHow It WorksHow It WorksHow It WorksHow It WorksHow It WorksHow It WorksHow It WorksHow It WorksHow It WorksHow It Works', 1, '2017-07-06 16:12:56', '2017-08-21 13:14:28');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;

-- Dumping structure for table bookmymed.password_resets
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table bookmymed.password_resets: ~0 rows (approximately)
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;

-- Dumping structure for table bookmymed.prescription
CREATE TABLE IF NOT EXISTS `prescription` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `real_name` varchar(50) NOT NULL DEFAULT '0',
  `upload_name` varchar(50) NOT NULL DEFAULT '0',
  `status` enum('PENDING','APPROVED') NOT NULL DEFAULT 'PENDING',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table bookmymed.prescription: ~0 rows (approximately)
/*!40000 ALTER TABLE `prescription` DISABLE KEYS */;
/*!40000 ALTER TABLE `prescription` ENABLE KEYS */;

-- Dumping structure for table bookmymed.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `summary` varchar(255) DEFAULT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `price` float(8,2) NOT NULL,
  `min_qty` int(5) NOT NULL DEFAULT '1',
  `discount` float(5,2) NOT NULL DEFAULT '0.00',
  `discount_type` enum('P','F') NOT NULL DEFAULT 'P',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` varchar(50) DEFAULT NULL,
  `updated_at` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

-- Dumping data for table bookmymed.products: ~2 rows (approximately)
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` (`id`, `category_id`, `title`, `summary`, `description`, `image`, `price`, `min_qty`, `discount`, `discount_type`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, 'CROCIN', 'for fever', 'fever', NULL, 10.00, 1, 1.00, 'P', 1, '2017-08-09 18:13:06', '2017-08-09 18:13:06'),
	(13, 1, 'test1', 'asdf1', '<p>asdf1</p>', '20170903172734.jpg', 441.00, 41, 41.00, 'P', 1, '2017-09-03 16:12:43', '2017-09-03 17:27:34');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;

-- Dumping structure for table bookmymed.reviews
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `order_id` int(11) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `rating` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table bookmymed.reviews: ~0 rows (approximately)
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` (`id`, `user_id`, `order_id`, `comment`, `rating`, `status`, `created_at`, `updated_at`) VALUES
	(1, 4, 2, 'test', 3, 1, '2017-08-21 11:33:44', '2017-08-21 11:33:44');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;

-- Dumping structure for table bookmymed.site_admin
CREATE TABLE IF NOT EXISTS `site_admin` (
  `sa_site_name` varchar(255) DEFAULT NULL,
  `sa_nrpp` int(11) DEFAULT NULL,
  `sa_to_name` varchar(255) DEFAULT NULL,
  `sa_to_email` varchar(255) DEFAULT NULL,
  `sa_from_name` varchar(255) DEFAULT NULL,
  `sa_from_email` varchar(255) DEFAULT NULL,
  `sa_paypal_email` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table bookmymed.site_admin: 1 rows
/*!40000 ALTER TABLE `site_admin` DISABLE KEYS */;
INSERT INTO `site_admin` (`sa_site_name`, `sa_nrpp`, `sa_to_name`, `sa_to_email`, `sa_from_name`, `sa_from_email`, `sa_paypal_email`) VALUES
	('Maxnewswire.com', 20, 'Maxnewswire.com', 'info@maxnewswire.com', 'MAXNewswire', 'info@maxnewswire.com', 'pro.prakriti@gmail.com');
/*!40000 ALTER TABLE `site_admin` ENABLE KEYS */;

-- Dumping structure for table bookmymed.states
CREATE TABLE IF NOT EXISTS `states` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=latin1;

-- Dumping data for table bookmymed.states: ~67 rows (approximately)
/*!40000 ALTER TABLE `states` DISABLE KEYS */;
INSERT INTO `states` (`id`, `country_id`, `name`, `status`) VALUES
	(1, 2, 'ALABAMA', '1'),
	(3, 2, 'ALASKA', '1'),
	(4, 2, 'ARIZONA', '1'),
	(5, 2, 'ARKANSAS', '1'),
	(6, 2, 'CALIFORNIA', '1'),
	(7, 2, 'CANAL ZONE', '1'),
	(8, 2, 'COLORADO', '1'),
	(9, 2, 'CONNECTICUT', '1'),
	(10, 2, 'DELAWARE', '1'),
	(11, 2, 'DISTRICT OF COLUMBIA', '1'),
	(12, 2, 'FLORIDA', '1'),
	(13, 2, 'GEORGIA', '1'),
	(14, 2, 'HAWAII', '1'),
	(15, 2, 'IDAHO', '1'),
	(16, 2, 'ILLINOIS', '1'),
	(17, 2, 'INDIANA', '1'),
	(18, 2, 'IOWA', '1'),
	(19, 2, 'KANSAS', '1'),
	(20, 2, 'KENTUCKY', '1'),
	(21, 2, 'LOUISIANA', '1'),
	(22, 2, 'MAINE', '1'),
	(23, 2, 'MARYLAND', '1'),
	(24, 2, 'MASSACHUSETTS', '1'),
	(25, 2, 'MICHIGAN', '1'),
	(26, 2, 'MINNESOTA', '1'),
	(27, 2, 'MISSISSIPPI', '1'),
	(28, 2, 'MISSOURI', '1'),
	(29, 2, 'MONTANA', '1'),
	(30, 2, 'NEBRASKA', '1'),
	(31, 2, 'NEVADA', '1'),
	(32, 2, 'NEW HAMPSHIRE', '1'),
	(33, 2, 'NEW JERSEY', '1'),
	(34, 2, 'NEW MEXICO', '1'),
	(35, 2, 'NEW YORK', '1'),
	(36, 2, 'NORTH CAROLINA', '1'),
	(37, 2, 'NORTH DAKOTA', '1'),
	(38, 2, 'OHIO', '1'),
	(39, 2, 'OKLAHOMA', '1'),
	(40, 2, 'OREGON', '1'),
	(41, 2, 'PENNSYLVANIA', '1'),
	(42, 2, 'PUERTO RICO', '1'),
	(43, 2, 'RHODE ISLAND', '1'),
	(44, 2, 'SOUTH CAROLINA', '1'),
	(45, 2, 'SOUTH DAKOTA', '1'),
	(46, 2, 'TENNESSEE', '1'),
	(47, 2, 'TEXAS', '1'),
	(48, 2, 'UTAH', '1'),
	(49, 2, 'VERMONT', '1'),
	(50, 2, 'VIRGIN ISLANDS', '1'),
	(51, 2, 'VIRGINIA', '1'),
	(52, 2, 'WASHINGTON', '1'),
	(53, 2, 'WEST VIRGINIA', '1'),
	(54, 2, 'WISCONSIN', '1'),
	(55, 2, 'WYOMING', '1'),
	(56, 4, 'Alberta', '1'),
	(57, 4, 'British Columbia', '1'),
	(58, 4, 'Manitoba', '1'),
	(59, 4, 'New Brunswick', '1'),
	(60, 4, 'Newfoundland And Labrador', '1'),
	(61, 4, 'Northwest Territories', '1'),
	(62, 4, 'Nova Scotia', '1'),
	(63, 4, 'Nunavut', '1'),
	(64, 4, 'Ontario', '1'),
	(65, 4, 'Prince Edward Island', '1'),
	(66, 4, 'Quebec', '1'),
	(67, 4, 'Saskatchewan', '1'),
	(68, 4, 'Yukon', '1');
/*!40000 ALTER TABLE `states` ENABLE KEYS */;

-- Dumping structure for table bookmymed.tickets
CREATE TABLE IF NOT EXISTS `tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_no` varchar(50) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `is_reply` enum('1','0') NOT NULL DEFAULT '0',
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Dumping data for table bookmymed.tickets: ~5 rows (approximately)
/*!40000 ALTER TABLE `tickets` DISABLE KEYS */;
INSERT INTO `tickets` (`id`, `ticket_no`, `user_id`, `subject`, `description`, `is_reply`, `status`, `created_at`, `updated_at`) VALUES
	(1, '1001', 4, 'asdasd', 'asdasdasdasd', '0', '0', '2017-06-18 11:47:36', '2017-07-17 17:42:49'),
	(2, '1002', 4, 'test subject', 'test subject test subjecttest subjecttest subjecttest subjecttest subjecttest subjecttest subjecttest subject est subject test subjecttest subjecttest subjecttest subjecttest subjecttest subjecttest subjecttest subjectest subject test subjecttest subjecttest subjecttest subjecttest subjecttest subjecttest subjecttest subjectest subject test subjecttest subjecttest subjecttest subjecttest subjecttest subjecttest subjecttest subjectest subject test subjecttest subjecttest subjecttest subjecttest subjecttest subjecttest subjecttest subjectest subject test subjecttest subjecttest subjecttest subjecttest subjecttest subjecttest subjecttest subjectest subject test subjecttest subjecttest subjecttest subjecttest subjecttest subjecttest subjecttest subjectest subject test subjecttest subjecttest subjecttest subjecttest subjecttest subjecttest subjecttest subjectest subject test subjecttest subjecttest subjecttest subjecttest subjecttest subjecttest subjecttest subjectest subject test subjecttest subjecttest subjecttest subjecttest subjecttest subjecttest subjecttest subjectest subject test subjecttest subjecttest subjecttest subjecttest subjecttest subjecttest subjecttest subjectest subject test subjecttest subjecttest subjecttest subjecttest subjecttest subjecttest subjecttest subject', '0', '1', '2017-06-18 12:02:00', '2017-07-17 17:43:06'),
	(3, '1003', 4, 'sdfsdfsd', 'f dfs sdfsdfsdfsdfsdf', '0', '1', '2017-06-18 12:06:53', '2017-07-16 11:29:06'),
	(4, '1004', 4, 'sdfsdfsd', 'f dfs sdfsdfsdfsdfsdf', '1', '1', '2017-06-18 12:07:07', '2017-07-16 12:23:33'),
	(5, '1005', 4, 'asdasd', '<p>asdasdasd</p>', '1', '1', '2017-06-18 12:10:38', '2017-07-16 12:23:24');
/*!40000 ALTER TABLE `tickets` ENABLE KEYS */;

-- Dumping structure for table bookmymed.ticket_comments
CREATE TABLE IF NOT EXISTS `ticket_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comments` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- Dumping data for table bookmymed.ticket_comments: ~11 rows (approximately)
/*!40000 ALTER TABLE `ticket_comments` DISABLE KEYS */;
INSERT INTO `ticket_comments` (`id`, `ticket_id`, `user_id`, `comments`, `created_at`, `updated_at`) VALUES
	(1, 2, 2, 'test subject test subjecttest subjecttest subjecttest subjecttest subjecttest subjecttest subjecttest subject est subject test subjecttest subjecttest subjecttest subjecttest subjecttest subjecttest subjecttest subjectest subject', '2017-07-16 09:20:24', NULL),
	(2, 2, 2, 'test subject test subjecttest subjecttest subjecttest subjecttest subjecttest subjecttest subjecttest subject est subject test subjecttest ', '2017-07-16 09:20:24', NULL),
	(3, 2, 2, 'ok dear', '2017-07-16 05:18:52', '2017-07-16 10:48:52'),
	(4, 5, 2, 'my first comments', '2017-07-16 05:38:48', '2017-07-16 11:08:48'),
	(5, 5, 2, 'my first comments', '2017-07-16 05:39:24', '2017-07-16 11:09:24'),
	(6, 5, 2, 'asdasd', '2017-07-16 05:39:32', '2017-07-16 11:09:32'),
	(7, 5, 2, 'dasdasdd', '2017-07-16 05:42:12', '2017-07-16 11:12:12'),
	(8, 5, 2, 'dasdasdd', '2017-07-16 05:42:26', '2017-07-16 11:12:26'),
	(9, 5, 2, 'sdfdfsdf', '2017-07-16 05:43:54', '2017-07-16 11:13:54'),
	(10, 3, 2, 'hello', '2017-07-16 05:59:06', '2017-07-16 11:29:06'),
	(11, 4, 2, 'asasas', '2017-07-16 06:53:33', '2017-07-16 12:23:33');
/*!40000 ALTER TABLE `ticket_comments` ENABLE KEYS */;

-- Dumping structure for table bookmymed.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `social_id` varchar(255) DEFAULT NULL,
  `phone` text NOT NULL,
  `country_id` int(11) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_blocked` enum('1','0') DEFAULT '0',
  `verification_code` varchar(255) DEFAULT NULL,
  `sms_code` int(11) DEFAULT NULL,
  `user_type` enum('SITE','FB','GPLUS') DEFAULT 'SITE',
  `token` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- Dumping data for table bookmymed.users: ~2 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `social_id`, `phone`, `country_id`, `username`, `password`, `created`, `is_blocked`, `verification_code`, `sms_code`, `user_type`, `token`, `created_at`, `updated_at`) VALUES
	(4, 'Mishra12', 'Pawan1', 'test@gmail.com', NULL, '9999070721', 1, 'test@gmail.com', '123456', '2017-06-07 21:07:59', '1', '58e3bf4a528ef', 0, 'SITE', NULL, '0000-00-00 00:00:00', '2017-06-07 15:37:59'),
	(5, NULL, NULL, NULL, '', '7878787878', NULL, NULL, NULL, '2018-02-25 22:43:47', '0', NULL, NULL, 'SITE', '5a92ee39da138', '2018-01-07 18:22:57', '2018-02-25 17:11:28'),
	(6, 'ravindra', 'tripathi', NULL, '', '8826746222', NULL, NULL, NULL, '2018-02-25 22:55:51', '0', NULL, NULL, 'SITE', '5a92f19de60f2', '2018-01-09 16:40:47', '2018-02-25 17:25:51');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
