-- phpMyAdmin SQL Dump
-- version 4.0.6deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 28-11-2013 a las 18:11:25
-- Versión del servidor: 5.5.34-0ubuntu0.13.10.1
-- Versión de PHP: 5.5.3-1ubuntu2

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `rbu`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rbu_account`
--

CREATE TABLE IF NOT EXISTS `rbu_account` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class` enum('fund','system','user','group') COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'user',
  `credit` bigint(20) NOT NULL DEFAULT '0',
  `earned` bigint(20) NOT NULL DEFAULT '0',
  `spended` bigint(20) NOT NULL DEFAULT '0',
  `balance` bigint(20) NOT NULL DEFAULT '0',
  `title` varchar(127) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `access` enum('private','public') COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'private',
  `added` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_action` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `blocked` timestamp NULL DEFAULT NULL,
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rbu_activity_log`
--

CREATE TABLE IF NOT EXISTS `rbu_activity_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `entity_id` int(11) unsigned DEFAULT NULL,
  `action` varchar(127) COLLATE latin1_german2_ci NOT NULL,
  `related_sid` varchar(127) COLLATE latin1_german2_ci DEFAULT NULL,
  `ip` varchar(41) COLLATE latin1_german2_ci NOT NULL,
  `added` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `FK_activity_log` (`entity_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rbu_authorization`
--

CREATE TABLE IF NOT EXISTS `rbu_authorization` (
  `entity_id` int(11) unsigned NOT NULL,
  `account_id` int(10) unsigned NOT NULL,
  `code` char(1) COLLATE utf8_spanish2_ci NOT NULL,
  `class` enum('holder','authorized','consultant') COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'holder',
  `title` varchar(127) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `salt` varchar(128) COLLATE utf8_spanish2_ci NOT NULL,
  `password` varchar(128) COLLATE utf8_spanish2_ci NOT NULL,
  `wrong_pass_count` tinyint(4) NOT NULL DEFAULT '0',
  `added` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `blocked` timestamp NULL DEFAULT NULL,
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`entity_id`,`account_id`),
  KEY `FK_authorization_account` (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rbu_brand`
--

CREATE TABLE IF NOT EXISTS `rbu_brand` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(127) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `summary` text COLLATE utf8_spanish2_ci,
  `description` text COLLATE utf8_spanish2_ci,
  `image` varchar(254) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `last_action` timestamp NULL DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `culture` varchar(7) COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'es_es',
  `added` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_market_ad_created_by` (`created_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rbu_entity`
--

CREATE TABLE IF NOT EXISTS `rbu_entity` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `class` varchar(32) NOT NULL,
  `object_id` int(11) unsigned NOT NULL,
  `points` int(11) NOT NULL DEFAULT '0',
  `rates` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rbu_exemption`
--

CREATE TABLE IF NOT EXISTS `rbu_exemption` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(127) COLLATE utf8_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rbu_invitation`
--

CREATE TABLE IF NOT EXISTS `rbu_invitation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `note` varchar(127) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '',
  `code` varchar(8) COLLATE utf8_spanish2_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sent` timestamp NULL DEFAULT NULL,
  `used` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_invitation_user` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rbu_link`
--

CREATE TABLE IF NOT EXISTS `rbu_link` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `entity_id` int(10) unsigned NOT NULL,
  `url` text NOT NULL,
  `text` varchar(255) NOT NULL,
  `logo` enum('none','facebook','twitter','googleplus','youtube','rss','email') NOT NULL DEFAULT 'none',
  `public` tinyint(1) NOT NULL,
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `entity_id` (`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rbu_market_ad`
--

CREATE TABLE IF NOT EXISTS `rbu_market_ad` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(127) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `class` enum('product','service') COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'service',
  `type` enum('offer','requirement') COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'offer',
  `summary` text COLLATE utf8_spanish2_ci,
  `price` bigint(20) unsigned NOT NULL DEFAULT '0',
  `description` text COLLATE utf8_spanish2_ci,
  `image` varchar(254) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `mailmode` enum('instantly','daily','exired') COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'instantly',
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `expiration` date NOT NULL,
  `zip` varchar(16) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `added` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `FK_market_ad_created_by` (`created_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rbu_market_joined`
--

CREATE TABLE IF NOT EXISTS `rbu_market_joined` (
  `ad_id` bigint(20) unsigned NOT NULL,
  `entity_id` int(11) unsigned NOT NULL,
  `comment` text COLLATE utf8_spanish2_ci,
  `show_mail` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('pending','accepted','substitute','rejected') COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'pending',
  `added` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ad_id`,`entity_id`),
  KEY `FK_market_joined_user_id` (`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rbu_notification`
--

CREATE TABLE IF NOT EXISTS `rbu_notification` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(127) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `message` text COLLATE utf8_spanish2_ci,
  `subject` varchar(127) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `view` varchar(127) COLLATE utf8_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rbu_notification_configuration`
--

CREATE TABLE IF NOT EXISTS `rbu_notification_configuration` (
  `entity_id` int(11) unsigned NOT NULL,
  `notification_id` int(10) unsigned NOT NULL,
  `mailmode` enum('instantly','daily','weekly','none') COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'instantly',
  `webmode` enum('popup','active','none') COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'active',
  `pushmode` enum('active','noweb','none') COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'active',
  PRIMARY KEY (`entity_id`,`notification_id`),
  KEY `FK_configuration_notification_id` (`notification_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rbu_notification_message`
--

CREATE TABLE IF NOT EXISTS `rbu_notification_message` (
  `entity_id` int(11) unsigned NOT NULL,
  `notification_id` int(10) unsigned NOT NULL,
  `sid` varchar(127) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '',
  `data` text COLLATE utf8_spanish2_ci,
  `added` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sent` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `read` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `shown` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`entity_id`,`notification_id`,`sid`),
  KEY `FK_notification_user_noti` (`notification_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rbu_pending`
--

CREATE TABLE IF NOT EXISTS `rbu_pending` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `executed_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `class` enum('salary','tax','transfer','charge','movement') COLLATE utf8_spanish2_ci NOT NULL,
  `amount` bigint(20) unsigned NOT NULL,
  `charge_account` int(10) unsigned NOT NULL,
  `deposit_account` int(10) unsigned NOT NULL,
  `charge_entity` int(11) unsigned NOT NULL,
  `deposit_entity` int(11) unsigned NOT NULL,
  `subject` varchar(255) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `FK_pending_charge_account` (`charge_account`),
  KEY `FK_pending_deposit_account` (`deposit_account`),
  KEY `FK_pending_charge_user` (`charge_entity`),
  KEY `FK_ppending_deposit_user` (`deposit_entity`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rbu_period`
--

CREATE TABLE IF NOT EXISTS `rbu_period` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `added` date NOT NULL,
  `movements` int(10) unsigned NOT NULL DEFAULT '0',
  `active_users` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rbu_rate`
--

CREATE TABLE IF NOT EXISTS `rbu_rate` (
  `to_id` int(11) unsigned NOT NULL,
  `from_id` int(11) unsigned NOT NULL,
  `sid` varchar(127) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL DEFAULT '',
  `type` enum('neutral','client','vendor') CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'neutral',
  `puntuation` tinyint(4) NOT NULL DEFAULT '3',
  `comment` text CHARACTER SET utf8 COLLATE utf8_spanish2_ci,
  `added` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`to_id`,`from_id`,`sid`),
  KEY `FK_rate_entity_from` (`from_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rbu_record`
--

CREATE TABLE IF NOT EXISTS `rbu_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `added` date NOT NULL,
  `total_amount` bigint(20) unsigned NOT NULL,
  `user_count` bigint(20) unsigned NOT NULL,
  `account_count` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rbu_role`
--

CREATE TABLE IF NOT EXISTS `rbu_role` (
  `actor_id` int(11) unsigned NOT NULL DEFAULT '0',
  `part_id` int(11) unsigned NOT NULL DEFAULT '0',
  `added` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`actor_id`,`part_id`),
  KEY `FK_role_part` (`part_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rbu_rule`
--

CREATE TABLE IF NOT EXISTS `rbu_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `added` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `salary` bigint(20) unsigned NOT NULL,
  `min_salary` bigint(20) unsigned NOT NULL,
  `multiplier` smallint(5) unsigned NOT NULL,
  `system_adapted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rbu_transaction`
--

CREATE TABLE IF NOT EXISTS `rbu_transaction` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `executed_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `class` enum('salary','tax','transfer','charge','movement','system') COLLATE utf8_spanish2_ci NOT NULL,
  `amount` bigint(20) unsigned NOT NULL,
  `charge_account` int(10) unsigned NOT NULL,
  `deposit_account` int(10) unsigned NOT NULL,
  `charge_entity` int(11) unsigned NOT NULL,
  `deposit_entity` int(11) unsigned NOT NULL,
  `subject` varchar(255) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `FK_transaction_charge_account` (`charge_account`),
  KEY `FK_transaction_deposit_account` (`deposit_account`),
  KEY `FK_transaction_charge_user` (`charge_entity`),
  KEY `FK_transaction_deposit_user` (`deposit_entity`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rbu_user`
--

CREATE TABLE IF NOT EXISTS `rbu_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(128) COLLATE utf8_spanish2_ci NOT NULL,
  `salt` varchar(128) COLLATE utf8_spanish2_ci NOT NULL,
  `password` varchar(128) COLLATE utf8_spanish2_ci NOT NULL,
  `name` varchar(127) COLLATE utf8_spanish2_ci NOT NULL,
  `surname` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `birthday` date DEFAULT NULL,
  `identification` text COLLATE utf8_spanish2_ci NOT NULL,
  `contribution_title` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `contribution_text` text COLLATE utf8_spanish2_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `contact` text COLLATE utf8_spanish2_ci NOT NULL,
  `zip` varchar(16) COLLATE utf8_spanish2_ci NOT NULL,
  `culture` varchar(7) COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'es_es',
  `exemption_id` int(10) unsigned DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned DEFAULT NULL,
  `last_login` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_action` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` datetime NOT NULL,
  `magic` varchar(64) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `blocked` timestamp NULL DEFAULT NULL,
  `deleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `FK_user_created_by` (`created_by`),
  KEY `FK_exemption_exemption_id` (`exemption_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `rbu_authorization`
--
ALTER TABLE `rbu_authorization`
  ADD CONSTRAINT `FK_authorization_account` FOREIGN KEY (`account_id`) REFERENCES `rbu_account` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rbu_authorization_ibfk_1` FOREIGN KEY (`entity_id`) REFERENCES `rbu_entity` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `rbu_invitation`
--
ALTER TABLE `rbu_invitation`
  ADD CONSTRAINT `FK_invitation_user` FOREIGN KEY (`user_id`) REFERENCES `rbu_account` (`id`) ON DELETE NO ACTION;

--
-- Filtros para la tabla `rbu_link`
--
ALTER TABLE `rbu_link`
  ADD CONSTRAINT `FK_LINK_ENTITY` FOREIGN KEY (`entity_id`) REFERENCES `rbu_entity` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `rbu_market_ad`
--
ALTER TABLE `rbu_market_ad`
  ADD CONSTRAINT `rbu_market_ad_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `rbu_entity` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `rbu_market_joined`
--
ALTER TABLE `rbu_market_joined`
  ADD CONSTRAINT `FK_market_joined_ad_id` FOREIGN KEY (`ad_id`) REFERENCES `rbu_market_ad` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rbu_market_joined_ibfk_1` FOREIGN KEY (`entity_id`) REFERENCES `rbu_entity` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `rbu_notification_configuration`
--
ALTER TABLE `rbu_notification_configuration`
  ADD CONSTRAINT `FK_configuration_notification_id` FOREIGN KEY (`notification_id`) REFERENCES `rbu_notification` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rbu_notification_configuration_ibfk_1` FOREIGN KEY (`entity_id`) REFERENCES `rbu_entity` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `rbu_notification_message`
--
ALTER TABLE `rbu_notification_message`
  ADD CONSTRAINT `FK_notification_user_noti` FOREIGN KEY (`notification_id`) REFERENCES `rbu_notification` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rbu_notification_message_ibfk_1` FOREIGN KEY (`entity_id`) REFERENCES `rbu_entity` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `rbu_pending`
--
ALTER TABLE `rbu_pending`
  ADD CONSTRAINT `FK_pending_charge_account` FOREIGN KEY (`charge_account`) REFERENCES `rbu_account` (`id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `FK_pending_deposit_account` FOREIGN KEY (`deposit_account`) REFERENCES `rbu_account` (`id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `rbu_pending_ibfk_1` FOREIGN KEY (`charge_entity`) REFERENCES `rbu_entity` (`id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `rbu_pending_ibfk_2` FOREIGN KEY (`deposit_entity`) REFERENCES `rbu_entity` (`id`) ON DELETE NO ACTION;

--
-- Filtros para la tabla `rbu_rate`
--
ALTER TABLE `rbu_rate`
  ADD CONSTRAINT `FK_rate_entity_from` FOREIGN KEY (`from_id`) REFERENCES `rbu_entity` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_rate_entity_to` FOREIGN KEY (`to_id`) REFERENCES `rbu_entity` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `rbu_role`
--
ALTER TABLE `rbu_role`
  ADD CONSTRAINT `FK_role_actor` FOREIGN KEY (`actor_id`) REFERENCES `rbu_entity` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_role_part` FOREIGN KEY (`part_id`) REFERENCES `rbu_entity` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `rbu_transaction`
--
ALTER TABLE `rbu_transaction`
  ADD CONSTRAINT `FK_transaction_charge_account` FOREIGN KEY (`charge_account`) REFERENCES `rbu_account` (`id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `FK_transaction_deposit_account` FOREIGN KEY (`deposit_account`) REFERENCES `rbu_account` (`id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `rbu_transaction_ibfk_1` FOREIGN KEY (`charge_entity`) REFERENCES `rbu_entity` (`id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `rbu_transaction_ibfk_2` FOREIGN KEY (`deposit_entity`) REFERENCES `rbu_entity` (`id`) ON DELETE NO ACTION;

--
-- Filtros para la tabla `rbu_user`
--
ALTER TABLE `rbu_user`
  ADD CONSTRAINT `FK_exemption_exemption_id` FOREIGN KEY (`exemption_id`) REFERENCES `rbu_exemption` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_user_created_by` FOREIGN KEY (`created_by`) REFERENCES `rbu_user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;






--
-- Volcado de datos para la tabla `rbu_account`
--

INSERT INTO `rbu_account` (`id`, `class`, `credit`, `earned`, `spended`, `title`, `access`, `added`, `blocked`, `deleted`) VALUES
(1, 'fund', 5000000, 0, 0, 'System Fund, where the non asigned money stay', 'public', '2012-06-12 19:49:07', NULL, NULL),
(2, 'system', 0, 0, 0, 'System Account to pay system needs', 'public', '2012-06-12 18:58:51', NULL, NULL);


--
-- Volcado de datos para la tabla `rbu_rule`
--

INSERT INTO `rbu_rule` (`id`, `added`, `salary`, `min_salary`, `multiplier`) VALUES
(1, '2012-06-12 18:47:13', 500000, 200000, 10);

--
-- Volcado de datos para la tabla `rbu_user`
--

INSERT INTO `rbu_user` (`id`, `username`, `salt`, `password`, `name`, `surname`, `birthday`, `identification`, `contribution_title`, `contribution_text`, `email`, `contact`, `zip`, `exemption_id`, `created`, `created_by`, `blocked`, `deleted`) VALUES
(1, 'Fund', '', '', 'System Fund', 'System Fund', '2012-05-20', 'DNI: ', '', '', 'contacto@monedademos.es', '', '35009', NULL, '2012-06-12 19:49:07', NULL, NULL, NULL);

--
-- Volcado de datos para la tabla `rbu_authorization`
--

INSERT INTO `rbu_authorization` (`user_id`, `account_id`, `code`, `class`, `title`, `salt`, `password`, `wrong_pass_count`, `added`, `blocked`, `deleted`) VALUES
(1, 1, 'G', 'holder', 'Personal account', '!', '!', 0, '2012-06-12 19:49:07', NULL, NULL),
(1, 2, 'A', 'holder', 'System Account to pay system needs', '!', '!', 0, '2012-06-12 19:24:42', NULL, NULL);


--
-- Volcado de datos para la tabla `rbu_notification`
--

INSERT INTO `rbu_notification` (`id`, `title`, `message`, `subject`, `view`) VALUES
(1, 'New payment', 'Received {amount} from {charge_account_number} by {charge_user_name} to your account {deposit_account_number}, subject: "{subject}".', '[DEMOS] New Payment', NULL),
(2, 'New charge', 'Paid {amount} from your account {charge_account_number} to {deposit_account_number} ({deposit_user_name}), subject: "{subject}".', '[DEMOS] New charge', NULL),
(3, 'New salary', 'Salary assigned: {amount} "{subject}".', '[DEMOS] Salary assigned', NULL),
(4, 'Taxes charged', 'Taxes charged: {amount} "{subject}".', '[DEMOS] Taxes Charged', NULL),
(5, 'New pending transaction', 'You have receive a pending charge from your account {charge_account_number} to {deposit_account_number} ({deposit_user_name}) with an amount of {amount}, subject: "{subject}".', '[DEMOS] New pending transaction', NULL),
(6, 'advice not connected', 'Advice not connected so you will not get salary', 'Advice not connected', NULL),
(7, 'New user joined to advertisement', '{user_name} has signed up in the advertisement: "{ad_title}".|<b>N_{user_name}</b> {user_name} have signed up in the advertisement: "{ad_title}".', '[DEMOS Market] {user_name} signed up in {ad_title}', NULL),
(8, 'New market comunication', 'New communication by {user_name} in advert {ad_title}|New communications by {user_name} in advert {ad_title}', '[DEMOS Market] New Market comunication in "{ad_title}"', NULL),
(9, 'Advertisement is to expire', 'Advertisement {title} is to expire, update expiration time to keep it available.', '[DEMOS Market] Advert "{title}" is to expire soon', NULL),
(10, 'Advert expired', 'The advertisement "{title}" has expired, update it to keep it available.', '[DEMOS Market] Advert "{title}" expired', NULL),
(11, 'New comment in advertisement', 'New communication in advert {ad_title}|New communications in advert {ad_title}', '[DEMOS Market] New comment in {ad_title}', NULL),
(12, 'New advert status', 'New advert status', 'New advert status', NULL),
(13, 'New contribution communication', 'New contribution communication', 'New contribution communication', NULL),
(14, 'System Transaction', 'Moved {amount} from {charge_account_number} to {deposit_account_number}, subject: "{subject}".', '[DEMOS] System Transaction', NULL),
(15, 'New Payment', 'Received {amount} from {charge_account_number} ({charge_user_name}) to your account {deposit_account_number}, subject: "{subject}".', '[DEMOS] New Payment', NULL),
(16, 'New charge', 'Paid {amount} from your account {charge_account_number} to {deposit_account_number} ({deposit_user_name}), subject: "{subject}".', '[DEMOS] New charge', NULL),
(17, 'New salary', 'Salary assigned: {amount} "{subject}".\r\n\r\n Demos es un sistema que busca la reciprocidad entre las personas participantes.\r\n\r\n Llevas un tiempo sin aportar, por eso te animamos a que busques dónde puedes ayudar. Tu sueldo se restablecerá tan pronto como lo consigas.\r\n\r\n ¡Ánimo!', '[DEMOS] Salary assigned', NULL);

--
-- Volcado de datos para la tabla `rbu_notification_configuration`
--

INSERT INTO `rbu_notification_configuration` (`entity_id`, `notification_id`, `mailmode`, `webmode`, `pushmode`) VALUES
(1, 1, 'instantly', 'active', 'active'),
(1, 2, 'instantly', 'active', 'active'),
(1, 3, 'instantly', 'active', 'active'),
(1, 4, 'none', 'active', 'none'),
(1, 5, 'instantly', 'active', 'active'),
(1, 6, 'instantly', 'active', 'active'),
(1, 7, 'instantly', 'active', 'active'),
(1, 8, 'instantly', 'active', 'active'),
(1, 9, 'instantly', 'active', 'active'),
(1, 10, 'instantly', 'active', 'active'),
(1, 11, 'instantly', 'active', 'active'),
(1, 12, 'instantly', 'active', 'active'),
(1, 13, 'instantly', 'active', 'active'),
(1, 15, 'none', 'active', 'active'),
(1, 16, 'none', 'active', 'active');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
