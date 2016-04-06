/*
SQLyog Enterprise - MySQL GUI v8.12 
MySQL - 5.6.17 : Database - sximo
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/`sximo` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `sximo`;

/*Table structure for table `active` */

DROP TABLE IF EXISTS `active`;

CREATE TABLE `active` (
  `id` int(11) NOT NULL,
  `type` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `billing_type` */

DROP TABLE IF EXISTS `billing_type`;

CREATE TABLE `billing_type` (
  `id` int(1) NOT NULL,
  `billing_type` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `billing_type` (`billing_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `calendar` */

DROP TABLE IF EXISTS `calendar`;

CREATE TABLE `calendar` (
  `date` date NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `closing_procedure` */

DROP TABLE IF EXISTS `closing_procedure`;

CREATE TABLE `closing_procedure` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(4) NOT NULL,
  `location_id` int(4) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

/*Table structure for table `company` */

DROP TABLE IF EXISTS `company`;

CREATE TABLE `company` (
  `id` int(1) NOT NULL,
  `company_name_short` varchar(15) NOT NULL,
  `company_name_long` varchar(40) NOT NULL,
  `bill_to` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `customers` */

DROP TABLE IF EXISTS `customers`;

CREATE TABLE `customers` (
  `customerNumber` int(11) NOT NULL,
  `customerName` varchar(50) NOT NULL,
  `contactLastName` varchar(50) NOT NULL,
  `contactFirstName` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `addressLine1` varchar(50) NOT NULL,
  `addressLine2` varchar(50) DEFAULT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) DEFAULT NULL,
  `postalCode` varchar(15) DEFAULT NULL,
  `country` varchar(50) NOT NULL,
  `salesRepEmployeeNumber` int(11) DEFAULT NULL,
  `creditLimit` double DEFAULT NULL,
  PRIMARY KEY (`customerNumber`),
  KEY `salesRepEmployeeNumber` (`salesRepEmployeeNumber`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `debit_type` */

DROP TABLE IF EXISTS `debit_type`;

CREATE TABLE `debit_type` (
  `id` int(11) NOT NULL,
  `company` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `departments` */

DROP TABLE IF EXISTS `departments`;

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `assign_employee_ids` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `employees` */

DROP TABLE IF EXISTS `employees`;

CREATE TABLE `employees` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(40) NOT NULL,
  `location_id` int(11) NOT NULL,
  `street` varchar(80) NOT NULL,
  `city` varchar(30) NOT NULL,
  `state` varchar(2) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `social` varchar(12) NOT NULL,
  `email` varchar(50) NOT NULL,
  `primary_phone` varchar(20) NOT NULL,
  `secondary_phone` varchar(20) NOT NULL,
  `employment_status` int(1) NOT NULL,
  `user_id` int(3) NOT NULL,
  `full_time` tinyint(1) NOT NULL,
  `employee_status` varchar(1) NOT NULL,
  `company_id` int(1) NOT NULL,
  `using_web` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=209 DEFAULT CHARSET=latin1;

/*Table structure for table `freight` */

DROP TABLE IF EXISTS `freight`;

CREATE TABLE `freight` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `freight_type` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `freight_type` (`freight_type`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Table structure for table `freight_companies` */

DROP TABLE IF EXISTS `freight_companies`;

CREATE TABLE `freight_companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(150) NOT NULL,
  `rep_name` varchar(100) NOT NULL,
  `rep_email` varchar(150) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Table structure for table `freight_orders` */

DROP TABLE IF EXISTS `freight_orders`;

CREATE TABLE `freight_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_submitted` date NOT NULL,
  `date_booked` date NOT NULL,
  `date_paid` date NOT NULL,
  `vend_from` int(4) NOT NULL,
  `vend_to` int(4) NOT NULL,
  `loc_from` int(4) NOT NULL,
  `loc_to_1` int(4) NOT NULL,
  `loc_to_2` int(4) NOT NULL,
  `loc_to_3` int(4) NOT NULL,
  `loc_to_4` int(4) NOT NULL,
  `loc_to_5` int(4) NOT NULL,
  `loc_to_6` int(4) NOT NULL,
  `loc_to_7` int(4) NOT NULL,
  `loc_to_8` int(4) NOT NULL,
  `loc_to_9` int(4) NOT NULL,
  `loc_to_10` int(4) NOT NULL,
  `from_add_name` varchar(150) NOT NULL,
  `from_add_street` varchar(200) NOT NULL,
  `from_add_city` varchar(150) NOT NULL,
  `from_add_state` varchar(6) NOT NULL,
  `from_add_zip` varchar(10) NOT NULL,
  `from_contact_name` varchar(100) NOT NULL,
  `from_contact_email` varchar(100) NOT NULL,
  `from_contact_phone` varchar(40) NOT NULL,
  `from_loading_info` varchar(150) NOT NULL,
  `to_add_name` varchar(150) NOT NULL,
  `to_add_street` varchar(200) NOT NULL,
  `to_add_city` varchar(150) NOT NULL,
  `to_add_state` varchar(6) NOT NULL,
  `to_add_zip` varchar(10) NOT NULL,
  `to_contact_name` varchar(100) NOT NULL,
  `to_contact_email` varchar(100) NOT NULL,
  `to_contact_phone` varchar(16) NOT NULL,
  `to_loading_info` varchar(150) NOT NULL,
  `description_1` varchar(200) NOT NULL,
  `dimensions_1` varchar(100) NOT NULL,
  `notes` varchar(250) NOT NULL,
  `description_2` varchar(200) NOT NULL,
  `dimensions_2` varchar(100) NOT NULL,
  `description_3` varchar(200) NOT NULL,
  `dimensions_3` varchar(100) NOT NULL,
  `description_4` varchar(200) NOT NULL,
  `dimensions_4` varchar(100) NOT NULL,
  `description_5` varchar(200) NOT NULL,
  `dimensions_5` varchar(100) NOT NULL,
  `num_games_per_destination` int(1) NOT NULL DEFAULT '1',
  `loc_1_game_1` int(8) NOT NULL,
  `loc_1_game_2` int(8) NOT NULL,
  `loc_1_game_3` int(8) NOT NULL,
  `loc_1_game_4` int(8) NOT NULL,
  `loc_1_game_5` int(8) NOT NULL,
  `loc_2_game_1` int(8) NOT NULL,
  `loc_2_game_2` int(8) NOT NULL,
  `loc_2_game_3` int(8) NOT NULL,
  `loc_2_game_4` int(8) NOT NULL,
  `loc_2_game_5` int(8) NOT NULL,
  `loc_3_game_1` int(8) NOT NULL,
  `loc_3_game_2` int(8) NOT NULL,
  `loc_3_game_3` int(8) NOT NULL,
  `loc_3_game_4` int(8) NOT NULL,
  `loc_3_game_5` int(8) NOT NULL,
  `loc_4_game_1` int(8) NOT NULL,
  `loc_4_game_2` int(8) NOT NULL,
  `loc_4_game_3` int(8) NOT NULL,
  `loc_4_game_4` int(8) NOT NULL,
  `loc_4_game_5` int(8) NOT NULL,
  `loc_5_game_1` int(8) NOT NULL,
  `loc_5_game_2` int(8) NOT NULL,
  `loc_5_game_3` int(8) NOT NULL,
  `loc_5_game_4` int(8) NOT NULL,
  `loc_5_game_5` int(8) NOT NULL,
  `loc_6_game_1` int(8) NOT NULL,
  `loc_6_game_2` int(8) NOT NULL,
  `loc_6_game_3` int(8) NOT NULL,
  `loc_6_game_4` int(8) NOT NULL,
  `loc_6_game_5` int(8) NOT NULL,
  `loc_7_game_1` int(8) NOT NULL,
  `loc_7_game_2` int(8) NOT NULL,
  `loc_7_game_3` int(8) NOT NULL,
  `loc_7_game_4` int(8) NOT NULL,
  `loc_7_game_5` int(8) NOT NULL,
  `loc_8_game_1` int(8) NOT NULL,
  `loc_8_game_2` int(8) NOT NULL,
  `loc_8_game_3` int(8) NOT NULL,
  `loc_8_game_4` int(8) NOT NULL,
  `loc_8_game_5` int(8) NOT NULL,
  `loc_9_game_1` int(8) NOT NULL,
  `loc_9_game_2` int(8) NOT NULL,
  `loc_9_game_3` int(8) NOT NULL,
  `loc_9_game_4` int(8) NOT NULL,
  `loc_9_game_5` int(8) NOT NULL,
  `loc_10_game_1` int(8) NOT NULL,
  `loc_10_game_2` int(8) NOT NULL,
  `loc_10_game_3` int(8) NOT NULL,
  `loc_10_game_4` int(8) NOT NULL,
  `loc_10_game_5` int(8) NOT NULL,
  `loc_1_pro` varchar(40) NOT NULL,
  `loc_2_pro` varchar(40) NOT NULL,
  `loc_3_pro` varchar(40) NOT NULL,
  `loc_4_pro` varchar(40) NOT NULL,
  `loc_5_pro` varchar(40) NOT NULL,
  `loc_6_pro` varchar(40) NOT NULL,
  `loc_7_pro` varchar(40) NOT NULL,
  `loc_8_pro` varchar(40) NOT NULL,
  `loc_9_pro` varchar(40) NOT NULL,
  `loc_10_pro` varchar(40) NOT NULL,
  `loc_1_quote` decimal(6,2) NOT NULL,
  `loc_2_quote` decimal(6,2) NOT NULL,
  `loc_3_quote` decimal(6,2) NOT NULL,
  `loc_4_quote` decimal(6,2) NOT NULL,
  `loc_5_quote` decimal(6,2) NOT NULL,
  `loc_6_quote` decimal(6,2) NOT NULL,
  `loc_7_quote` decimal(6,2) NOT NULL,
  `loc_8_quote` decimal(6,2) NOT NULL,
  `loc_9_quote` decimal(6,2) NOT NULL,
  `loc_10_quote` decimal(6,2) NOT NULL,
  `loc_1_trucking_co` varchar(50) NOT NULL,
  `loc_2_trucking_co` varchar(50) NOT NULL,
  `loc_3_trucking_co` varchar(50) NOT NULL,
  `loc_4_trucking_co` varchar(50) NOT NULL,
  `loc_5_trucking_co` varchar(50) NOT NULL,
  `loc_6_trucking_co` varchar(50) NOT NULL,
  `loc_7_trucking_co` varchar(50) NOT NULL,
  `loc_8_trucking_co` varchar(50) NOT NULL,
  `loc_9_trucking_co` varchar(50) NOT NULL,
  `loc_10_trucking_co` varchar(50) NOT NULL,
  `external_ship_quote` decimal(6,2) NOT NULL,
  `external_ship_trucking_co` varchar(140) NOT NULL,
  `external_ship_pro` varchar(40) NOT NULL,
  `freight_company_1` int(2) NOT NULL,
  `freight_company_2` int(2) NOT NULL,
  `freight_company_3` int(2) NOT NULL,
  `freight_company_4` int(2) NOT NULL,
  `freight_company_5` int(2) NOT NULL,
  `freight_company_6` int(2) NOT NULL,
  `freight_company_7` int(2) NOT NULL,
  `freight_company_8` int(2) NOT NULL,
  `freight_company_9` int(2) NOT NULL,
  `freight_company_10` int(2) NOT NULL,
  `email_notes` text NOT NULL,
  `status` int(1) NOT NULL,
  `ship_exception_1` int(8) NOT NULL,
  `ship_exception_2` int(8) NOT NULL,
  `ship_exception_3` int(8) NOT NULL,
  `ship_exception_4` int(8) NOT NULL,
  `ship_exception_5` int(8) NOT NULL,
  `new_ship_date_1` date NOT NULL,
  `new_ship_date_2` date NOT NULL,
  `new_ship_date_3` date NOT NULL,
  `new_ship_date_4` date NOT NULL,
  `new_ship_date_5` date NOT NULL,
  `new_ship_reason_1` text NOT NULL,
  `new_ship_reason_2` text NOT NULL,
  `new_ship_reason_3` text NOT NULL,
  `new_ship_reason_4` text NOT NULL,
  `new_ship_reason_5` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=127 DEFAULT CHARSET=latin1;

/*Table structure for table `game` */

DROP TABLE IF EXISTS `game`;

CREATE TABLE `game` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `game_name` varchar(60) NOT NULL,
  `prev_game_name` varchar(60) DEFAULT NULL,
  `game_type_id` int(2) NOT NULL,
  `version_id` int(2) DEFAULT NULL,
  `players` int(2) DEFAULT NULL,
  `monitor_size` int(3) DEFAULT NULL,
  `dba` tinyint(1) NOT NULL,
  `sacoa` tinyint(1) NOT NULL,
  `embed` tinyint(1) NOT NULL,
  `rfid` tinyint(1) NOT NULL,
  `notes` varchar(100) NOT NULL,
  `location_id` int(4) NOT NULL,
  `mfg_id` int(3) DEFAULT NULL,
  `source` varchar(50) NOT NULL,
  `serial` varchar(30) NOT NULL,
  `date_in_service` date NOT NULL,
  `status_id` int(1) NOT NULL,
  `game_setup_status_id` int(1) NOT NULL,
  `intended_first_location` int(4) DEFAULT NULL,
  `ship_delay_reason` text NOT NULL,
  `date_shipped` date NOT NULL,
  `freight_order_id` int(6) NOT NULL,
  `date_last_move` date NOT NULL,
  `last_edited_by` int(4) NOT NULL,
  `last_edited_on` varchar(20) NOT NULL,
  `prev_location_id` int(4) NOT NULL,
  `for_sale` tinyint(1) NOT NULL,
  `sale_price` decimal(8,2) NOT NULL,
  `sale_pending` tinyint(1) NOT NULL,
  `sold` tinyint(1) NOT NULL,
  `date_sold` date DEFAULT NULL,
  `sold_to` varchar(60) DEFAULT NULL,
  `game_move_id` int(6) NOT NULL,
  `game_service_id` int(6) DEFAULT NULL,
  `game_title_id` int(4) NOT NULL,
  `version` varchar(50) NOT NULL,
  `num_prize_meters` int(2) NOT NULL DEFAULT '1',
  `num_prizes` int(1) NOT NULL DEFAULT '1',
  `product_id` int(6) NOT NULL,
  `product_id_1` int(6) NOT NULL,
  `product_qty_1` int(5) NOT NULL,
  `product_id_2` int(6) NOT NULL,
  `product_qty_2` int(5) NOT NULL,
  `product_id_3` int(6) NOT NULL,
  `product_qty_3` int(5) NOT NULL,
  `product_id_4` int(6) NOT NULL,
  `product_qty_4` int(5) NOT NULL,
  `product_id_5` int(6) NOT NULL,
  `product_qty_5` int(5) NOT NULL,
  `price_per_play` decimal(5,2) NOT NULL,
  `last_product_meter_1` int(12) DEFAULT NULL,
  `last_product_meter_2` int(12) DEFAULT NULL,
  `last_product_meter_3` int(12) DEFAULT NULL,
  `last_product_meter_4` int(12) DEFAULT NULL,
  `last_product_meter_5` int(12) DEFAULT NULL,
  `last_product_meter_6` int(12) DEFAULT NULL,
  `last_product_meter_7` int(12) DEFAULT NULL,
  `last_product_meter_8` int(12) DEFAULT NULL,
  `last_meter_date` date NOT NULL,
  `not_debit` tinyint(1) NOT NULL,
  `not_debit_reason` varchar(100) NOT NULL,
  `linked_to_game` int(8) NOT NULL,
  `test_piece` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40000137 DEFAULT CHARSET=latin1;

/*Table structure for table `game_earning_december` */

DROP TABLE IF EXISTS `game_earning_december`;

CREATE TABLE `game_earning_december` (
  `id` int(11) NOT NULL DEFAULT '0',
  `debit_type_id` int(1) NOT NULL,
  `loc_id` int(4) NOT NULL,
  `game_id` int(8) NOT NULL,
  `reader_id` varchar(32) NOT NULL,
  `play_value` decimal(9,2) NOT NULL,
  `total_notional_value` decimal(10,2) NOT NULL,
  `std_plays` int(5) NOT NULL,
  `std_card_credit` int(5) NOT NULL,
  `std_card_credit_bonus` int(5) NOT NULL,
  `std_actual_cash` decimal(8,2) NOT NULL,
  `std_card_dollar` decimal(8,2) NOT NULL,
  `std_card_dollar_bonus` decimal(8,2) NOT NULL,
  `time_plays` int(5) NOT NULL,
  `time_play_dollar` decimal(8,2) NOT NULL,
  `time_play_dollar_bonus` decimal(8,2) NOT NULL,
  `product_plays` int(5) NOT NULL,
  `service_plays` int(5) NOT NULL,
  `courtesy_plays` int(5) NOT NULL,
  `date_start` datetime NOT NULL,
  `date_end` datetime NOT NULL,
  `ticket_payout` int(5) NOT NULL,
  `ticket_value` decimal(6,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `game_earnings_transfer_adjustments` */

DROP TABLE IF EXISTS `game_earnings_transfer_adjustments`;

CREATE TABLE `game_earnings_transfer_adjustments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_start` date NOT NULL,
  `loc_id` int(4) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3335 DEFAULT CHARSET=latin1;

/*Table structure for table `game_exclude` */

DROP TABLE IF EXISTS `game_exclude`;

CREATE TABLE `game_exclude` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(8) NOT NULL,
  `loc_id` int(4) NOT NULL,
  `reason` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Table structure for table `game_move_history` */

DROP TABLE IF EXISTS `game_move_history`;

CREATE TABLE `game_move_history` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `game_id` int(8) NOT NULL,
  `from_loc` int(4) NOT NULL,
  `from_by` int(4) NOT NULL,
  `from_date` datetime NOT NULL,
  `to_loc` int(4) NOT NULL,
  `to_by` int(4) NOT NULL,
  `to_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3112 DEFAULT CHARSET=latin1;

/*Table structure for table `game_service_history` */

DROP TABLE IF EXISTS `game_service_history`;

CREATE TABLE `game_service_history` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `game_id` int(10) NOT NULL,
  `date_down` date DEFAULT NULL,
  `problem` text,
  `down_user_id` int(4) DEFAULT NULL,
  `solution` text,
  `date_up` date DEFAULT NULL,
  `up_user_id` int(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=380 DEFAULT CHARSET=latin1;

/*Table structure for table `game_status` */

DROP TABLE IF EXISTS `game_status`;

CREATE TABLE `game_status` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `game_status` varchar(40) NOT NULL,
  `class` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Table structure for table `game_title` */

DROP TABLE IF EXISTS `game_title`;

CREATE TABLE `game_title` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_title` varchar(50) NOT NULL,
  `mfg_id` int(3) NOT NULL,
  `game_type_id` int(2) NOT NULL,
  `img` varchar(60) NOT NULL,
  `has_manual` tinyint(1) NOT NULL,
  `has_servicebulletin` tinyint(1) NOT NULL,
  `num_prize_meters` int(2) NOT NULL DEFAULT '1',
  `manual` varchar(60) DEFAULT NULL,
  `bulletin` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `game_title` (`game_title`)
) ENGINE=InnoDB AUTO_INCREMENT=1128 DEFAULT CHARSET=latin1;

/*Table structure for table `game_type` */

DROP TABLE IF EXISTS `game_type`;

CREATE TABLE `game_type` (
  `id` int(2) NOT NULL,
  `game_type` varchar(25) NOT NULL,
  `game_type_short` varchar(4) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `game_version` */

DROP TABLE IF EXISTS `game_version`;

CREATE TABLE `game_version` (
  `id` int(2) NOT NULL,
  `version` varchar(14) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `version` (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `img_uploads` */

DROP TABLE IF EXISTS `img_uploads`;

CREATE TABLE `img_uploads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_name` varchar(150) NOT NULL,
  `loc_id` int(4) NOT NULL,
  `users` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `image_category` varchar(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=latin1;

/*Table structure for table `loc_group` */

DROP TABLE IF EXISTS `loc_group`;

CREATE TABLE `loc_group` (
  `id` int(11) NOT NULL,
  `loc_group_name` varchar(100) NOT NULL,
  UNIQUE KEY `id_2` (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `location` */

DROP TABLE IF EXISTS `location`;

CREATE TABLE `location` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `location_name` varchar(60) NOT NULL,
  `location_name_short` varchar(24) NOT NULL,
  `mail_attention` varchar(20) NOT NULL DEFAULT '0',
  `street1` varchar(60) NOT NULL,
  `city` varchar(40) NOT NULL,
  `state` varchar(2) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `attn` varchar(50) NOT NULL DEFAULT '0',
  `company_id` int(1) NOT NULL,
  `self_owned` tinyint(1) NOT NULL,
  `loading_info` varchar(100) NOT NULL,
  `date_opened` date NOT NULL,
  `date_closed` date NOT NULL,
  `region_id` int(2) NOT NULL,
  `loc_group_id` int(2) NOT NULL DEFAULT '0',
  `debit_type_id` int(1) NOT NULL,
  `can_ship` tinyint(1) NOT NULL,
  `loc_ship_to` int(4) NOT NULL,
  `phone` varchar(18) NOT NULL,
  `bestbuy_store_number` varchar(30) NOT NULL,
  `bill_debit_type` int(1) NOT NULL,
  `bill_debit_amt` decimal(6,3) NOT NULL,
  `bill_debit_detail` varchar(100) NOT NULL,
  `bill_ticket_type` int(1) NOT NULL,
  `bill_ticket_amt` decimal(6,2) NOT NULL,
  `bill_ticket_detail` varchar(100) NOT NULL,
  `bill_thermalpaper_type` int(1) NOT NULL,
  `bill_thermalpaper_amt` decimal(6,2) NOT NULL,
  `bill_thermalpaper_detail` varchar(100) NOT NULL,
  `bill_token_type` int(1) NOT NULL,
  `bill_token_amt` decimal(6,2) NOT NULL,
  `bill_token_detail` varchar(100) NOT NULL,
  `bill_license_type` int(1) NOT NULL,
  `bill_license_amt` decimal(6,2) NOT NULL,
  `bill_license_detail` varchar(100) NOT NULL,
  `bill_attraction_type` int(1) NOT NULL,
  `bill_attraction_amt` decimal(6,2) NOT NULL,
  `bill_attraction_detail` varchar(100) NOT NULL,
  `bill_redemption_type` int(1) NOT NULL,
  `bill_redemption_amt` decimal(6,2) NOT NULL,
  `bill_redemption_detail` varchar(100) NOT NULL,
  `bill_instant_type` int(1) NOT NULL,
  `bill_instant_amt` decimal(6,2) NOT NULL,
  `bill_instant_detail` varchar(100) NOT NULL,
  `contact_id` int(4) DEFAULT NULL,
  `merch_contact_id` int(4) DEFAULT NULL,
  `field_manager_id` int(11) NOT NULL,
  `tech_manager_id` int(11) DEFAULT NULL,
  `no_games` int(1) NOT NULL,
  `liftgate` tinyint(1) NOT NULL,
  `ipaddress` varchar(12) NOT NULL,
  `reporting` tinyint(1) NOT NULL,
  `not_reporting_Sun` tinyint(1) NOT NULL,
  `not_reporting_Mon` tinyint(1) NOT NULL,
  `not_reporting_Tue` tinyint(1) NOT NULL,
  `not_reporting_Wed` tinyint(1) NOT NULL,
  `not_reporting_Thu` tinyint(1) NOT NULL,
  `not_reporting_Fri` tinyint(1) NOT NULL,
  `not_reporting_Sat` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9007 DEFAULT CHARSET=latin1;

/*Table structure for table `merch` */

DROP TABLE IF EXISTS `merch`;

CREATE TABLE `merch` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `all_games` tinyint(1) NOT NULL,
  `game` varchar(30) NOT NULL,
  `vendor_id` int(3) NOT NULL,
  `item` varchar(100) NOT NULL,
  `pieces` int(4) NOT NULL,
  `cost_case` decimal(7,2) NOT NULL,
  `cost_unit` decimal(7,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=165 DEFAULT CHARSET=latin1;

/*Table structure for table `merch_inventory` */

DROP TABLE IF EXISTS `merch_inventory`;

CREATE TABLE `merch_inventory` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `location_id` int(4) NOT NULL,
  `product_id` int(6) NOT NULL,
  `product_qty` decimal(6,2) NOT NULL,
  `date_in` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10724 DEFAULT CHARSET=latin1;

/*Table structure for table `merch_request` */

DROP TABLE IF EXISTS `merch_request`;

CREATE TABLE `merch_request` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `merch_id` int(4) NOT NULL,
  `case_qty` int(2) NOT NULL,
  `total_cost` decimal(7,2) NOT NULL,
  `request_user_id` int(4) NOT NULL,
  `location_id` int(4) NOT NULL,
  `request_date_time` date NOT NULL,
  `process_date_time` date NOT NULL,
  `status_id` int(1) NOT NULL,
  `process_user_id` int(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

/*Table structure for table `merch_request_status` */

DROP TABLE IF EXISTS `merch_request_status`;

CREATE TABLE `merch_request_status` (
  `id` int(2) NOT NULL,
  `status` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `merch_throws` */

DROP TABLE IF EXISTS `merch_throws`;

CREATE TABLE `merch_throws` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `user_id` int(6) NOT NULL,
  `game_id` int(8) NOT NULL,
  `location_id` int(4) NOT NULL,
  `price_per_play` decimal(5,2) NOT NULL,
  `product_id_1` int(6) NOT NULL,
  `product_qty_1` int(5) NOT NULL,
  `product_cogs_1` decimal(8,2) NOT NULL,
  `product_throw_1` decimal(6,2) NOT NULL,
  `product_id_2` int(6) NOT NULL,
  `product_qty_2` int(5) NOT NULL,
  `product_cogs_2` decimal(8,2) NOT NULL,
  `product_throw_2` decimal(6,2) NOT NULL,
  `product_id_3` int(6) NOT NULL,
  `product_qty_3` int(5) NOT NULL,
  `product_cogs_3` decimal(8,2) NOT NULL,
  `product_throw_3` decimal(6,2) NOT NULL,
  `product_id_4` int(6) NOT NULL,
  `product_qty_4` int(5) NOT NULL,
  `product_cogs_4` decimal(8,2) NOT NULL,
  `product_throw_4` decimal(6,2) NOT NULL,
  `product_id_5` int(6) NOT NULL,
  `product_qty_5` int(5) NOT NULL,
  `product_cogs_5` decimal(8,2) NOT NULL,
  `product_throw_5` decimal(6,2) NOT NULL,
  `game_earnings` decimal(9,2) NOT NULL,
  `game_throw` decimal(6,2) NOT NULL,
  `notes` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2359 DEFAULT CHARSET=latin1;

/*Table structure for table `merchandiser_earnings` */

DROP TABLE IF EXISTS `merchandiser_earnings`;

CREATE TABLE `merchandiser_earnings` (
  `game_id` int(8) NOT NULL,
  `earnings_1_12` decimal(8,2) NOT NULL,
  `throw_1_13` decimal(5,2) NOT NULL,
  `earnings_2_12` decimal(8,2) NOT NULL,
  `throw_2_12` decimal(5,2) NOT NULL,
  `earnings_3_12` decimal(8,2) NOT NULL,
  `throw_3_12` decimal(5,2) NOT NULL,
  `earnings_4_12` decimal(8,2) NOT NULL,
  `throw_4_12` decimal(5,2) NOT NULL,
  `earnings_5_12` decimal(8,2) NOT NULL,
  `throw_5_12` decimal(5,2) NOT NULL,
  `earnings_6_12` decimal(8,2) NOT NULL,
  `throw_6_12` decimal(5,2) NOT NULL,
  `earnings_7_12` decimal(8,2) NOT NULL,
  `throw_7_12` decimal(5,2) NOT NULL,
  `earnings_8_12` decimal(8,2) NOT NULL,
  `throw_8_12` decimal(5,2) NOT NULL,
  `earnings_9_12` decimal(8,2) NOT NULL,
  `throw_9_12` decimal(5,2) NOT NULL,
  `earnings_10_12` decimal(8,2) NOT NULL,
  `throw_10_12` decimal(5,2) NOT NULL,
  `earnings_11_12` decimal(8,2) NOT NULL,
  `throw_11_12` decimal(5,2) NOT NULL,
  `earnings_12_12` decimal(8,2) NOT NULL,
  `throw_12_12` decimal(5,2) NOT NULL,
  `earnings_13_12` decimal(8,2) NOT NULL,
  `throw_13_12` decimal(5,2) NOT NULL,
  `earnings_14_12` decimal(8,2) NOT NULL,
  `throw_14_12` decimal(5,2) NOT NULL,
  `earnings_15_12` decimal(8,2) NOT NULL,
  `throw_15_12` decimal(5,2) NOT NULL,
  `earnings_16_12` decimal(8,2) NOT NULL,
  `throw_16_12` decimal(5,2) NOT NULL,
  `earnings_17_12` decimal(8,2) NOT NULL,
  `throw_17_12` decimal(5,2) NOT NULL,
  `earnings_18_12` decimal(8,2) NOT NULL,
  `throw_18_12` decimal(5,2) NOT NULL,
  `earnings_19_12` decimal(8,2) NOT NULL,
  `throw_19_12` decimal(5,2) NOT NULL,
  `earnings_20_12` decimal(8,2) NOT NULL,
  `throw_20_12` decimal(5,2) NOT NULL,
  `earnings_21_12` decimal(8,2) NOT NULL,
  `throw_21_12` decimal(5,2) NOT NULL,
  `earnings_22_12` decimal(8,2) NOT NULL,
  `throw_22_12` decimal(5,2) NOT NULL,
  `earnings_23_12` decimal(8,2) NOT NULL,
  `throw_23_12` decimal(5,2) NOT NULL,
  `earnings_24_12` decimal(8,2) NOT NULL,
  `throw_24_12` decimal(5,2) NOT NULL,
  `earnings_25_12` decimal(8,2) NOT NULL,
  `throw_25_12` decimal(5,2) NOT NULL,
  `earnings_26_12` decimal(8,2) NOT NULL,
  `throw_26_12` decimal(5,2) NOT NULL,
  `earnings_27_12` decimal(8,2) NOT NULL,
  `throw_27_12` decimal(5,2) NOT NULL,
  `earnings_28_12` decimal(5,2) NOT NULL,
  `throw_28_12` decimal(8,2) NOT NULL,
  `earnings_29_12` decimal(5,2) NOT NULL,
  `throw_29_12` decimal(8,2) NOT NULL,
  `earnings_30_12` decimal(5,2) NOT NULL,
  `throw_30_12` decimal(8,2) NOT NULL,
  `earnings_31_12` decimal(5,2) NOT NULL,
  `throw_31_12` decimal(8,2) NOT NULL,
  `earnings_32_12` decimal(8,2) NOT NULL,
  `throw_32_12` decimal(5,2) NOT NULL,
  `earnings_33_12` decimal(8,2) NOT NULL,
  `throw_33_12` decimal(5,2) NOT NULL,
  `earnings_34_12` decimal(8,2) NOT NULL,
  `throw_34_12` decimal(5,2) NOT NULL,
  `earnings_35_12` decimal(8,2) NOT NULL,
  `throw_35_12` decimal(5,2) NOT NULL,
  `earnings_36_12` decimal(8,2) NOT NULL,
  `throw_36_12` decimal(5,2) NOT NULL,
  `earnings_37_12` decimal(8,2) NOT NULL,
  `throw_37_12` decimal(5,2) NOT NULL,
  `earnings_38_12` decimal(8,2) NOT NULL,
  `throw_38_12` decimal(5,2) NOT NULL,
  `earnings_39_12` decimal(8,2) NOT NULL,
  `throw_39_12` decimal(5,2) NOT NULL,
  `earnings_40_12` decimal(8,2) NOT NULL,
  `throw_40_12` decimal(5,2) NOT NULL,
  `earnings_41_12` decimal(8,2) NOT NULL,
  `throw_41_12` decimal(5,2) NOT NULL,
  `earnings_42_12` decimal(8,2) NOT NULL,
  `throw_42_12` decimal(5,2) NOT NULL,
  `earnings_43_12` decimal(8,2) NOT NULL,
  `throw_43_12` decimal(5,2) NOT NULL,
  `earnings_44_12` decimal(8,2) NOT NULL,
  `throw_44_12` decimal(5,2) NOT NULL,
  `earnings_45_12` decimal(8,2) NOT NULL,
  `throw_45_12` decimal(5,2) NOT NULL,
  `earnings_46_12` decimal(8,2) NOT NULL,
  `throw_46_12` decimal(5,2) NOT NULL,
  `earnings_47_12` decimal(8,2) NOT NULL,
  `throw_47_12` decimal(5,2) NOT NULL,
  `earnings_48_12` decimal(8,2) NOT NULL,
  `throw_48_12` decimal(5,2) NOT NULL,
  `earnings_49_12` decimal(8,2) NOT NULL,
  `throw_49_12` decimal(5,2) NOT NULL,
  `earnings_50_12` decimal(8,2) NOT NULL,
  `throw_50_12` decimal(5,2) NOT NULL,
  `earnings_51_12` decimal(8,2) NOT NULL,
  `throw_51_12` decimal(5,2) NOT NULL,
  `earnings_52_12` decimal(8,2) NOT NULL,
  `throw_52_12` decimal(5,2) NOT NULL,
  PRIMARY KEY (`game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `new_graphics_priority` */

DROP TABLE IF EXISTS `new_graphics_priority`;

CREATE TABLE `new_graphics_priority` (
  `id` int(11) NOT NULL,
  `id_plus` varchar(12) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `new_graphics_request` */

DROP TABLE IF EXISTS `new_graphics_request`;

CREATE TABLE `new_graphics_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(4) NOT NULL,
  `request_user_id` int(4) NOT NULL,
  `request_date` date NOT NULL,
  `need_by_date` date NOT NULL,
  `description` text NOT NULL,
  `qty` int(4) NOT NULL,
  `status_id` int(1) NOT NULL,
  `priority_id` int(1) NOT NULL DEFAULT '1',
  `media_type` varchar(200) NOT NULL,
  `notes` varchar(250) NOT NULL,
  `img` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=136 DEFAULT CHARSET=latin1;

/*Table structure for table `new_graphics_request_status` */

DROP TABLE IF EXISTS `new_graphics_request_status`;

CREATE TABLE `new_graphics_request_status` (
  `id` int(2) NOT NULL,
  `status` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `news` */

DROP TABLE IF EXISTS `news`;

CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  `slug` varchar(128) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Table structure for table `numbers` */

DROP TABLE IF EXISTS `numbers`;

CREATE TABLE `numbers` (
  `id` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `order_contents` */

DROP TABLE IF EXISTS `order_contents`;

CREATE TABLE `order_contents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(7) NOT NULL,
  `request_id` int(7) NOT NULL,
  `product_id` int(6) NOT NULL,
  `product_description` text NOT NULL,
  `price` decimal(10,5) NOT NULL,
  `qty` int(5) NOT NULL,
  `game_id` int(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15158 DEFAULT CHARSET=latin1;

/*Table structure for table `order_status` */

DROP TABLE IF EXISTS `order_status`;

CREATE TABLE `order_status` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `status` varchar(50) NOT NULL,
  `class` varchar(20) NOT NULL,
  `order_type_id` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Table structure for table `order_type` */

DROP TABLE IF EXISTS `order_type`;

CREATE TABLE `order_type` (
  `id` int(1) NOT NULL,
  `order_type` varchar(50) NOT NULL,
  `is_merch` tinyint(1) NOT NULL,
  `can_request` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `orderdetails` */

DROP TABLE IF EXISTS `orderdetails`;

CREATE TABLE `orderdetails` (
  `orderNumber` int(11) NOT NULL,
  `productCode` varchar(15) NOT NULL,
  `quantityOrdered` int(11) NOT NULL,
  `priceEach` double NOT NULL,
  `orderLineNumber` smallint(6) NOT NULL,
  PRIMARY KEY (`orderNumber`,`productCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `orders` */

DROP TABLE IF EXISTS `orders`;

CREATE TABLE `orders` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `company_id` int(2) NOT NULL,
  `date_ordered` date NOT NULL,
  `order_total` decimal(9,2) DEFAULT NULL,
  `warranty` tinyint(1) NOT NULL DEFAULT '0',
  `location_id` int(3) NOT NULL,
  `vendor_id` int(3) NOT NULL,
  `order_description` text NOT NULL,
  `status_id` int(2) NOT NULL,
  `order_type_id` int(1) NOT NULL,
  `game_id` int(8) NOT NULL,
  `freight_id` int(2) NOT NULL,
  `po_number` varchar(15) NOT NULL,
  `po_notes` text NOT NULL,
  `notes` text NOT NULL,
  `date_received` date DEFAULT NULL,
  `received_by` int(5) DEFAULT NULL,
  `quantity` int(5) NOT NULL,
  `alt_address` varchar(300) NOT NULL,
  `request_ids` varchar(200) NOT NULL,
  `game_ids` varchar(250) NOT NULL,
  `tracking_number` varchar(40) NOT NULL,
  `added_to_inventory` tinyint(1) NOT NULL,
  `order_content` tinyint(1) NOT NULL,
  `new_format` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20385 DEFAULT CHARSET=latin1;

/*Table structure for table `product_type` */

DROP TABLE IF EXISTS `product_type`;

CREATE TABLE `product_type` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `product_type` varchar(22) NOT NULL,
  `type_description` varchar(40) NOT NULL,
  `request_type_id` int(3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `supply_type` (`product_type`)
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=latin1;

/*Table structure for table `products` */

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `sku` varchar(16) DEFAULT NULL,
  `vendor_description` varchar(200) NOT NULL,
  `item_description` varchar(150) NOT NULL,
  `size` varchar(25) NOT NULL,
  `details` text NOT NULL,
  `num_items` int(3) DEFAULT NULL,
  `vendor_id` int(3) NOT NULL,
  `unit_price` decimal(9,5) DEFAULT NULL,
  `case_price` decimal(10,5) DEFAULT NULL,
  `retail_price` decimal(10,5) NOT NULL,
  `ticket_value` int(7) DEFAULT NULL,
  `prod_type_id` int(3) NOT NULL,
  `prod_sub_type_id` int(4) NOT NULL,
  `is_reserved` tinyint(1) NOT NULL,
  `reserved_qty` int(5) NOT NULL,
  `min_order_amt` int(5) NOT NULL,
  `img` varchar(60) NOT NULL,
  `inactive` tinyint(1) NOT NULL,
  `eta` date NOT NULL,
  `in_development` tinyint(1) NOT NULL,
  `limit_to_loc_group_id` int(2) NOT NULL DEFAULT '0',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `hot_item` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `description` (`vendor_description`,`vendor_id`,`case_price`,`prod_type_id`,`prod_sub_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3406 DEFAULT CHARSET=latin1;

/*Table structure for table `reader_exclude` */

DROP TABLE IF EXISTS `reader_exclude`;

CREATE TABLE `reader_exclude` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reader_id` varchar(25) NOT NULL,
  `debit_type_id` int(1) NOT NULL,
  `loc_id` int(4) NOT NULL,
  `reason` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=latin1;

/*Table structure for table `redemption_imgs` */

DROP TABLE IF EXISTS `redemption_imgs`;

CREATE TABLE `redemption_imgs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_name` varchar(150) NOT NULL,
  `loc_id` int(4) NOT NULL,
  `users` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;

/*Table structure for table `region` */

DROP TABLE IF EXISTS `region`;

CREATE TABLE `region` (
  `id` int(2) NOT NULL,
  `region` varchar(30) NOT NULL,
  `dist_mgr_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `requests` */

DROP TABLE IF EXISTS `requests`;

CREATE TABLE `requests` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `product_id` int(4) NOT NULL,
  `description` text NOT NULL,
  `qty` int(2) NOT NULL,
  `request_user_id` int(4) NOT NULL,
  `location_id` int(4) NOT NULL,
  `request_date` date NOT NULL,
  `process_date` date NOT NULL,
  `status_id` int(1) NOT NULL,
  `process_user_id` int(4) NOT NULL,
  `request_type_id` int(3) NOT NULL,
  `notes` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22078 DEFAULT CHARSET=latin1;

/*Table structure for table `sacoa_location_ids` */

DROP TABLE IF EXISTS `sacoa_location_ids`;

CREATE TABLE `sacoa_location_ids` (
  `feg_id` int(4) NOT NULL,
  `sacoa_id` varchar(2) NOT NULL,
  `zone` varchar(255) NOT NULL DEFAULT 'Unzoned',
  PRIMARY KEY (`sacoa_id`,`zone`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `sb_invoiceitems` */

DROP TABLE IF EXISTS `sb_invoiceitems`;

CREATE TABLE `sb_invoiceitems` (
  `ItemID` int(11) NOT NULL AUTO_INCREMENT,
  `InvoiceID` int(11) DEFAULT NULL,
  `ProductID` int(6) DEFAULT NULL,
  `Qty` smallint(3) DEFAULT NULL,
  `Price` decimal(10,0) DEFAULT NULL,
  `Total` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`ItemID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `sb_invoiceproducts` */

DROP TABLE IF EXISTS `sb_invoiceproducts`;

CREATE TABLE `sb_invoiceproducts` (
  `ProductID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) DEFAULT NULL,
  `Note` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ProductID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Table structure for table `sb_invoices` */

DROP TABLE IF EXISTS `sb_invoices`;

CREATE TABLE `sb_invoices` (
  `InvoiceID` int(11) NOT NULL AUTO_INCREMENT,
  `Number` varchar(100) DEFAULT NULL,
  `UserID` int(6) DEFAULT NULL,
  `DateIssued` date DEFAULT NULL,
  `DueDate` date DEFAULT NULL,
  `Amount` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`InvoiceID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `sb_ticketcomments` */

DROP TABLE IF EXISTS `sb_ticketcomments`;

CREATE TABLE `sb_ticketcomments` (
  `CommentID` int(11) NOT NULL AUTO_INCREMENT,
  `TicketID` int(11) DEFAULT NULL,
  `Comments` text,
  `Posted` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `UserID` int(11) DEFAULT NULL,
  `USERNAME` varchar(50) NOT NULL,
  `Attachments` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`CommentID`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;

/*Table structure for table `sb_tickets` */

DROP TABLE IF EXISTS `sb_tickets`;

CREATE TABLE `sb_tickets` (
  `TicketID` int(11) NOT NULL AUTO_INCREMENT,
  `Subject` varchar(255) DEFAULT NULL,
  `Description` text,
  `need_by_date` varchar(30) NOT NULL,
  `Priority` char(20) DEFAULT NULL,
  `Created` varchar(30) DEFAULT NULL,
  `Status` char(20) DEFAULT NULL,
  `entry_by` int(11) DEFAULT NULL,
  `issue_type` varchar(20) NOT NULL,
  `location_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `closed` datetime DEFAULT NULL,
  `assign_to` varchar(50) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `game_id` int(11) NOT NULL,
  `updated` datetime DEFAULT NULL,
  `debit_card` varchar(10) NOT NULL,
  PRIMARY KEY (`TicketID`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

/*Table structure for table `service_requests` */

DROP TABLE IF EXISTS `service_requests`;

CREATE TABLE `service_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `priority_id` int(1) NOT NULL,
  `location_id` int(4) NOT NULL,
  `requestor_id` int(4) NOT NULL,
  `request_date` date NOT NULL,
  `problem` text NOT NULL,
  `need_by_date` date NOT NULL,
  `solved_date` date NOT NULL,
  `solver_id` int(4) NOT NULL,
  `solution` text NOT NULL,
  `status_id` int(1) NOT NULL,
  `request_title` varchar(100) NOT NULL,
  `attachment_path` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=189 DEFAULT CHARSET=latin1;

/*Table structure for table `service_status` */

DROP TABLE IF EXISTS `service_status`;

CREATE TABLE `service_status` (
  `id` int(1) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` varchar(50) NOT NULL DEFAULT '',
  `payload` text,
  `last_activity` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `spare_parts` */

DROP TABLE IF EXISTS `spare_parts`;

CREATE TABLE `spare_parts` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `description` varchar(150) NOT NULL,
  `loc_id` int(4) NOT NULL,
  `for_game` varchar(100) DEFAULT NULL,
  `qty` int(3) DEFAULT NULL,
  `value` decimal(8,2) DEFAULT NULL,
  `user` varchar(40) DEFAULT NULL,
  `status_id` int(1) NOT NULL,
  `user_claim` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=208 DEFAULT CHARSET=latin1;

/*Table structure for table `spare_status` */

DROP TABLE IF EXISTS `spare_status`;

CREATE TABLE `spare_status` (
  `id` int(1) NOT NULL,
  `status` varchar(15) NOT NULL,
  `class` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `states` */

DROP TABLE IF EXISTS `states`;

CREATE TABLE `states` (
  `state_code` varchar(3) NOT NULL,
  `state_name` varchar(40) NOT NULL,
  PRIMARY KEY (`state_code`),
  UNIQUE KEY `state_id` (`state_code`,`state_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `supplies` */

DROP TABLE IF EXISTS `supplies`;

CREATE TABLE `supplies` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `sku` varchar(16) DEFAULT NULL,
  `description` varchar(200) NOT NULL,
  `type_id` varchar(25) DEFAULT NULL,
  `num_items` int(3) DEFAULT NULL,
  `vendor_id` int(3) NOT NULL,
  `price` decimal(9,2) DEFAULT NULL,
  `request_type_id` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=488 DEFAULT CHARSET=latin1;

/*Table structure for table `supply_type` */

DROP TABLE IF EXISTS `supply_type`;

CREATE TABLE `supply_type` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `supply_type` varchar(22) NOT NULL,
  `type_description` varchar(40) NOT NULL,
  `request_type_id` int(3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `supply_type` (`supply_type`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

/*Table structure for table `tb_blogcategories` */

DROP TABLE IF EXISTS `tb_blogcategories`;

CREATE TABLE `tb_blogcategories` (
  `CatID` int(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `alias` varchar(100) DEFAULT NULL,
  `enable` enum('0','1') DEFAULT NULL,
  PRIMARY KEY (`CatID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Table structure for table `tb_blogcomments` */

DROP TABLE IF EXISTS `tb_blogcomments`;

CREATE TABLE `tb_blogcomments` (
  `commentID` int(11) NOT NULL AUTO_INCREMENT,
  `parentID` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `blogID` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `comment` text,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`commentID`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

/*Table structure for table `tb_blogs` */

DROP TABLE IF EXISTS `tb_blogs`;

CREATE TABLE `tb_blogs` (
  `blogID` int(11) NOT NULL AUTO_INCREMENT,
  `CatID` int(6) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `content` text,
  `created` datetime DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `status` enum('publish','unpublish','draft') DEFAULT 'draft',
  `image` varchar(100) DEFAULT NULL,
  `entryby` int(11) DEFAULT NULL,
  PRIMARY KEY (`blogID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Table structure for table `tb_calendar` */

DROP TABLE IF EXISTS `tb_calendar`;

CREATE TABLE `tb_calendar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `description` mediumtext,
  `start` date DEFAULT NULL,
  `end` date DEFAULT NULL,
  `entry_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

/*Table structure for table `tb_employees_remove` */

DROP TABLE IF EXISTS `tb_employees_remove`;

CREATE TABLE `tb_employees_remove` (
  `employeeNumber` int(11) NOT NULL AUTO_INCREMENT,
  `lastName` varchar(50) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `extension` varchar(10) NOT NULL,
  `email` varchar(100) NOT NULL,
  `reportsTo` int(11) DEFAULT NULL,
  `jobTitle` varchar(50) NOT NULL,
  `foto` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`employeeNumber`),
  KEY `reportsTo` (`reportsTo`)
) ENGINE=InnoDB AUTO_INCREMENT=1704 DEFAULT CHARSET=latin1;

/*Table structure for table `tb_groups` */

DROP TABLE IF EXISTS `tb_groups`;

CREATE TABLE `tb_groups` (
  `group_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `level` int(6) DEFAULT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

/*Table structure for table `tb_groups_access` */

DROP TABLE IF EXISTS `tb_groups_access`;

CREATE TABLE `tb_groups_access` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `group_id` int(6) DEFAULT NULL,
  `module_id` int(6) DEFAULT NULL,
  `access_data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=714 DEFAULT CHARSET=utf8;

/*Table structure for table `tb_logs` */

DROP TABLE IF EXISTS `tb_logs`;

CREATE TABLE `tb_logs` (
  `auditID` int(20) NOT NULL AUTO_INCREMENT,
  `ipaddress` varchar(50) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `module` varchar(50) DEFAULT NULL,
  `task` varchar(50) DEFAULT NULL,
  `note` text,
  `logdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`auditID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `tb_menu` */

DROP TABLE IF EXISTS `tb_menu`;

CREATE TABLE `tb_menu` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT '0',
  `module` varchar(50) DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL,
  `menu_name` varchar(100) DEFAULT NULL,
  `menu_type` char(10) DEFAULT NULL,
  `role_id` varchar(100) DEFAULT NULL,
  `deep` smallint(2) DEFAULT NULL,
  `ordering` int(6) DEFAULT NULL,
  `position` enum('top','sidebar','both') DEFAULT NULL,
  `menu_icons` varchar(30) DEFAULT NULL,
  `active` enum('0','1') DEFAULT '1',
  `access_data` text,
  `allow_guest` enum('0','1') DEFAULT '0',
  `menu_lang` text,
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

/*Table structure for table `tb_module` */

DROP TABLE IF EXISTS `tb_module`;

CREATE TABLE `tb_module` (
  `module_id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(100) DEFAULT NULL,
  `module_title` varchar(100) DEFAULT NULL,
  `module_note` varchar(255) DEFAULT NULL,
  `module_author` varchar(100) DEFAULT NULL,
  `module_created` timestamp NULL DEFAULT NULL,
  `module_desc` text,
  `module_db` varchar(255) DEFAULT NULL,
  `module_db_key` varchar(100) DEFAULT NULL,
  `module_type` enum('master','report','proccess','core','generic','addon','ajax') DEFAULT 'master',
  `module_config` longtext,
  `module_lang` text,
  PRIMARY KEY (`module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8;

/*Table structure for table `tb_module_config` */

DROP TABLE IF EXISTS `tb_module_config`;

CREATE TABLE `tb_module_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  `config` text,
  `config_name` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `tb_notification` */

DROP TABLE IF EXISTS `tb_notification`;

CREATE TABLE `tb_notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `note` text,
  `created` datetime DEFAULT NULL,
  `icon` char(20) DEFAULT NULL,
  `is_read` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `tb_orders` */

DROP TABLE IF EXISTS `tb_orders`;

CREATE TABLE `tb_orders` (
  `orderNumber` int(11) NOT NULL AUTO_INCREMENT,
  `orderDate` date NOT NULL,
  `requiredDate` date NOT NULL,
  `shippedDate` date DEFAULT NULL,
  `status` varchar(15) NOT NULL,
  `comments` text,
  `customerNumber` int(11) NOT NULL,
  PRIMARY KEY (`orderNumber`),
  KEY `customerNumber` (`customerNumber`)
) ENGINE=InnoDB AUTO_INCREMENT=10427 DEFAULT CHARSET=latin1;

/*Table structure for table `tb_pages` */

DROP TABLE IF EXISTS `tb_pages`;

CREATE TABLE `tb_pages` (
  `pageID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `alias` varchar(100) DEFAULT NULL,
  `note` text,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `filename` varchar(50) DEFAULT NULL,
  `status` enum('enable','disable') DEFAULT 'enable',
  `access` text,
  `allow_guest` enum('0','1') DEFAULT '0',
  `template` enum('frontend','backend') DEFAULT 'frontend',
  `metakey` varchar(255) DEFAULT NULL,
  `metadesc` text,
  PRIMARY KEY (`pageID`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;

/*Table structure for table `tb_products` */

DROP TABLE IF EXISTS `tb_products`;

CREATE TABLE `tb_products` (
  `productId` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `productCode` varchar(15) NOT NULL,
  `productName` varchar(70) NOT NULL,
  `productScale` varchar(10) NOT NULL,
  `productVendor` varchar(50) NOT NULL,
  `productDescription` text NOT NULL,
  `quantityInStock` smallint(6) NOT NULL,
  `buyPrice` double NOT NULL,
  `MSRP` double NOT NULL,
  PRIMARY KEY (`productId`),
  UNIQUE KEY `productCode` (`productCode`)
) ENGINE=InnoDB AUTO_INCREMENT=112 DEFAULT CHARSET=latin1;

/*Table structure for table `tb_restapi` */

DROP TABLE IF EXISTS `tb_restapi`;

CREATE TABLE `tb_restapi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `apiuser` int(11) DEFAULT NULL,
  `apikey` varchar(100) DEFAULT NULL,
  `created` date DEFAULT NULL,
  `modules` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `tb_vendor` */

DROP TABLE IF EXISTS `tb_vendor`;

CREATE TABLE `tb_vendor` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `vendor_name` varchar(50) NOT NULL,
  `street1` varchar(150) NOT NULL,
  `street2` varchar(40) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(2) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `phone` varchar(22) NOT NULL,
  `fax` varchar(16) NOT NULL,
  `contact` varchar(40) NOT NULL,
  `email` varchar(60) NOT NULL,
  `email_2` varchar(60) NOT NULL,
  `website` varchar(80) NOT NULL,
  `games_contact_name` varchar(60) NOT NULL,
  `games_contact_email` varchar(60) NOT NULL,
  `games_contact_phone` varchar(16) NOT NULL,
  `partner_hide` tinyint(1) NOT NULL,
  `isgame` tinyint(1) NOT NULL,
  `ismerch` tinyint(1) NOT NULL,
  `min_order_amt` int(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vendor_name` (`vendor_name`)
) ENGINE=InnoDB AUTO_INCREMENT=228 DEFAULT CHARSET=latin1;

/*Table structure for table `timeclock_entries` */

DROP TABLE IF EXISTS `timeclock_entries`;

CREATE TABLE `timeclock_entries` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `user_id` int(3) NOT NULL,
  `location_id` int(4) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `total_hours` decimal(5,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

/*Table structure for table `user_images` */

DROP TABLE IF EXISTS `user_images`;

CREATE TABLE `user_images` (
  `id` bigint(22) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(22) unsigned NOT NULL,
  `image_url` text NOT NULL,
  `details` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `user_level` */

DROP TABLE IF EXISTS `user_level`;

CREATE TABLE `user_level` (
  `id` int(1) NOT NULL,
  `user_level` varchar(18) NOT NULL,
  `usr_lvl` varchar(12) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `user_locations` */

DROP TABLE IF EXISTS `user_locations`;

CREATE TABLE `user_locations` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=633 DEFAULT CHARSET=latin1;

/*Table structure for table `user_module_config` */

DROP TABLE IF EXISTS `user_module_config`;

CREATE TABLE `user_module_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  `config` text,
  `config_name` varchar(50) DEFAULT NULL,
  `is_private` smallint(1) DEFAULT '0',
  `group_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(40) COLLATE latin1_general_ci NOT NULL,
  `last_name` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `username` varchar(200) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `email` varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `avatar` varchar(50) COLLATE latin1_general_ci DEFAULT '1.jpg',
  `group_id` tinyint(4) NOT NULL DEFAULT '1',
  `password` varchar(220) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `is_tech_contact` tinyint(1) NOT NULL DEFAULT '0',
  `approved` int(1) NOT NULL DEFAULT '0',
  `banned` int(1) NOT NULL DEFAULT '0',
  `company_id` int(2) NOT NULL,
  `get_locations_by_region` tinyint(1) NOT NULL,
  `reg_id` int(2) DEFAULT NULL,
  `ctime` varchar(220) COLLATE latin1_general_ci NOT NULL,
  `ckey` varchar(220) COLLATE latin1_general_ci NOT NULL,
  `using_web` tinyint(1) NOT NULL DEFAULT '0',
  `full_time` tinyint(1) NOT NULL,
  `restricted_mgr_email` tinyint(1) NOT NULL,
  `restricted_user_email` tinyint(1) NOT NULL,
  `restrict_merch` tinyint(1) NOT NULL,
  `email_2` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `primary_phone` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `secondary_phone` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `street` varchar(80) COLLATE latin1_general_ci NOT NULL,
  `city` varchar(30) COLLATE latin1_general_ci NOT NULL,
  `state` varchar(2) COLLATE latin1_general_ci NOT NULL,
  `zip` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `timeclock_status` tinyint(1) NOT NULL,
  `timeclock_id` int(6) NOT NULL,
  `tier` int(1) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_activity` timestamp NOT NULL,
  `remember_token` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_email` (`email`),
  UNIQUE KEY `id` (`id`),
  KEY `email` (`email`),
  FULLTEXT KEY `idx_search` (`email`,`username`)
) ENGINE=MyISAM AUTO_INCREMENT=273 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

/*Table structure for table `vendor` */

DROP TABLE IF EXISTS `vendor`;

CREATE TABLE `vendor` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `vendor_name` varchar(50) NOT NULL,
  `street1` varchar(150) NOT NULL,
  `street2` varchar(40) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(2) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `phone` varchar(22) NOT NULL,
  `fax` varchar(16) NOT NULL,
  `contact` varchar(40) NOT NULL,
  `email` varchar(60) NOT NULL,
  `email_2` varchar(60) NOT NULL,
  `website` varchar(80) NOT NULL,
  `games_contact_name` varchar(60) NOT NULL,
  `games_contact_email` varchar(60) NOT NULL,
  `games_contact_phone` varchar(16) NOT NULL,
  `partner_hide` tinyint(1) NOT NULL,
  `isgame` tinyint(1) NOT NULL,
  `ismerch` tinyint(1) NOT NULL,
  `min_order_amt` int(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vendor_name` (`vendor_name`)
) ENGINE=InnoDB AUTO_INCREMENT=228 DEFAULT CHARSET=latin1;

/*Table structure for table `yes_no` */

DROP TABLE IF EXISTS `yes_no`;

CREATE TABLE `yes_no` (
  `id` int(1) NOT NULL,
  `yesno` varchar(3) NOT NULL,
  UNIQUE KEY `id` (`id`,`yesno`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
