-- 19Mar2017













CREATE TABLE `active` (
  `id` int(11) NOT NULL,
  `type` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




CREATE TABLE `billing_type` (
  `id` int(1) NOT NULL,
  `billing_type` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `billing_type` (`billing_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




CREATE TABLE `calendar` (
  `date` date NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




CREATE TABLE `closing_procedure` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(4) NOT NULL,
  `location_id` int(4) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `company` (
  `id` int(1) NOT NULL,
  `company_name_short` varchar(15) NOT NULL,
  `company_name_long` varchar(40) NOT NULL,
  `bill_to` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




CREATE TABLE `custom_cron_setup` (
  `id` bigint(22) unsigned NOT NULL AUTO_INCREMENT,
  `action_name` text,
  `is_active` tinyint(1) DEFAULT '1',
  `status_code` int(5) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `action_date` datetime DEFAULT NULL,
  `run_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `notes` longtext,
  `action_param1` text,
  `action_param2` text,
  `action_param3` text,
  `action_param4` text,
  `action_param5` text,
  `location_id` int(11) unsigned DEFAULT NULL,
  `location_date` datetime DEFAULT NULL,
  `debit_type_id` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;




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




CREATE TABLE `debit_type` (
  `id` int(11) NOT NULL,
  `company` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




CREATE TABLE `departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `assign_employee_ids` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `freight` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `freight_type` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `freight_type` (`freight_type`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `freight_companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(150) NOT NULL,
  `rep_name` varchar(100) NOT NULL,
  `rep_email` varchar(150) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `zip` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `freight_location_to` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `freight_order_id` int(11) DEFAULT NULL,
  `location_id` int(4) DEFAULT '0',
  `location_pro` varchar(50) DEFAULT NULL,
  `location_quote` decimal(6,2) DEFAULT NULL,
  `location_trucking_co` varchar(50) DEFAULT NULL,
  `freight_company` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_freight_location_to` (`freight_order_id`),
  CONSTRAINT `FK_freight_location_to` FOREIGN KEY (`freight_order_id`) REFERENCES `freight_orders` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




CREATE TABLE `freight_order_location_games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `freight_loc_to_id` int(11) DEFAULT NULL,
  `game_id` int(8) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `freight_pallet_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `freight_order_id` int(11) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `dimensions` varchar(100) DEFAULT NULL,
  `ship_exception` varchar(200) DEFAULT NULL,
  `new_ship_date` date DEFAULT NULL,
  `new_ship_reason` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




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
  `location_num` int(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `game_earnings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `ticket_value` decimal(6,2) NOT NULL,
  `loc_game_title` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `date_start` (`date_start`),
  KEY `date_start_2` (`date_start`),
  KEY `date_start_3` (`date_start`),
  KEY `date_end` (`date_end`),
  KEY `date_start_4` (`date_start`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `game_earnings_transfer_adjustments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_start` date NOT NULL,
  `loc_id` int(4) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `notes` varchar(100) DEFAULT 'STILL MISSING',
  `adjustment_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `game_exclude` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(8) NOT NULL,
  `loc_id` int(4) NOT NULL,
  `reason` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `game_status` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `game_status` varchar(40) NOT NULL,
  `class` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `game_type` (
  `id` int(2) NOT NULL,
  `game_type` varchar(25) NOT NULL,
  `game_type_short` varchar(4) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




CREATE TABLE `game_version` (
  `id` int(2) NOT NULL,
  `version` varchar(14) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `version` (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;

SET character_set_client = @saved_cs_client;



CREATE TABLE `img_uploads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_name` varchar(150) NOT NULL,
  `loc_id` int(4) NOT NULL,
  `users` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `image_category` varchar(6) NOT NULL,
  `video_path` varchar(250) DEFAULT NULL,
  `video_title` varchar(100) DEFAULT NULL,
  `type` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `loc_group` (
  `id` int(11) NOT NULL,
  `loc_group_name` varchar(100) NOT NULL,
  UNIQUE KEY `id_2` (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




CREATE TABLE `location` (
  `id` int(5) NOT NULL,
  `store_id` varchar(20) DEFAULT NULL,
  `district_manager_id` int(11) DEFAULT NULL,
  `location_name` varchar(60) NOT NULL,
  `location_name_short` varchar(24) NOT NULL,
  `mail_attention` varchar(20) NOT NULL,
  `street1` varchar(60) NOT NULL,
  `city` varchar(40) NOT NULL,
  `state` varchar(2) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `attn` varchar(50) NOT NULL,
  `company_id` int(1) NOT NULL,
  `self_owned` tinyint(1) NOT NULL,
  `loading_info` varchar(100) NOT NULL,
  `post_add_action_done` tinyint(1) DEFAULT '0',
  `date_added` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
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
  `Jan_2012` decimal(8,2) DEFAULT NULL,
  `Feb_2012` decimal(8,2) DEFAULT NULL,
  `Mar_2012` decimal(8,2) DEFAULT NULL,
  `Apr_2012` decimal(8,2) DEFAULT NULL,
  `May_2012` decimal(8,2) DEFAULT NULL,
  `Jun_2012` decimal(8,2) DEFAULT NULL,
  `Jul_2012` decimal(8,2) DEFAULT NULL,
  `Aug_2012` decimal(8,2) DEFAULT NULL,
  `Sep_2012` decimal(8,2) DEFAULT NULL,
  `Oct_2012` decimal(8,2) DEFAULT NULL,
  `Nov_2012` decimal(8,2) DEFAULT NULL,
  `Dec_2012` decimal(8,2) DEFAULT NULL,
  `Jan_2013` decimal(8,2) DEFAULT NULL,
  `Feb_2013` decimal(8,2) DEFAULT NULL,
  `Mar_2013` decimal(8,2) DEFAULT NULL,
  `Apr_2013` decimal(8,2) DEFAULT NULL,
  `May_2013` decimal(8,2) DEFAULT NULL,
  `Jun_2013` decimal(8,2) DEFAULT NULL,
  `Jul_2013` decimal(8,2) DEFAULT NULL,
  `Aug_2013` decimal(8,2) DEFAULT NULL,
  `Sep_2013` decimal(8,2) DEFAULT NULL,
  `Oct_2013` decimal(8,2) DEFAULT NULL,
  `Nov_2013` decimal(8,2) DEFAULT NULL,
  `Dec_2013` decimal(8,2) DEFAULT NULL,
  `Jan_2014` decimal(8,2) DEFAULT NULL,
  `Feb_2014` decimal(8,2) DEFAULT NULL,
  `Mar_2014` decimal(8,2) DEFAULT NULL,
  `Apr_2014` decimal(8,2) DEFAULT NULL,
  `May_2014` decimal(8,2) DEFAULT NULL,
  `Jun_2014` decimal(8,2) DEFAULT NULL,
  `Jul_2014` decimal(8,2) DEFAULT NULL,
  `Aug_2014` decimal(8,2) DEFAULT NULL,
  `Sep_2014` decimal(8,2) DEFAULT NULL,
  `Oct_2014` decimal(8,2) DEFAULT NULL,
  `Nov_2014` decimal(8,2) DEFAULT NULL,
  `Dec_2014` decimal(8,2) DEFAULT NULL,
  `Jan_2015` decimal(8,2) DEFAULT NULL,
  `Feb_2015` decimal(8,2) DEFAULT NULL,
  `Mar_2015` decimal(8,2) DEFAULT NULL,
  `Apr_2015` decimal(8,2) DEFAULT NULL,
  `May_2015` decimal(8,2) DEFAULT NULL,
  `Jun_2015` decimal(8,2) DEFAULT NULL,
  `Jul_2015` decimal(8,2) DEFAULT NULL,
  `Aug_2015` decimal(8,2) DEFAULT NULL,
  `Sep_2015` decimal(8,2) DEFAULT NULL,
  `Oct_2015` decimal(8,2) DEFAULT NULL,
  `Nov_2015` decimal(8,2) DEFAULT NULL,
  `Dec_2015` decimal(8,2) DEFAULT NULL,
  `Jan_2016` decimal(8,2) DEFAULT NULL,
  `Feb_2016` decimal(8,2) DEFAULT NULL,
  `Mar_2016` decimal(8,2) DEFAULT NULL,
  `Apr_2016` decimal(8,2) DEFAULT NULL,
  `May_2016` decimal(8,2) DEFAULT NULL,
  `Jun_2016` decimal(8,2) DEFAULT NULL,
  `Jul_2016` decimal(8,2) DEFAULT NULL,
  `Aug_2016` decimal(8,2) DEFAULT NULL,
  `Sep_2016` decimal(8,2) DEFAULT NULL,
  `Oct_2016` decimal(8,2) DEFAULT NULL,
  `Nov_2016` decimal(8,2) DEFAULT NULL,
  `Dec_2016` decimal(8,2) DEFAULT NULL,
  `Jan_2017` decimal(8,2) DEFAULT NULL,
  `Feb_2017` decimal(8,2) DEFAULT NULL,
  `Mar_2017` decimal(8,2) DEFAULT NULL,
  `Apr_2017` decimal(8,2) DEFAULT NULL,
  `May_2017` decimal(8,2) DEFAULT NULL,
  `Jun_2017` decimal(8,2) DEFAULT NULL,
  `Jul_2017` decimal(8,2) DEFAULT NULL,
  `Aug_2017` decimal(8,2) DEFAULT NULL,
  `Sep_2017` decimal(8,2) DEFAULT NULL,
  `Oct_2017` decimal(8,2) DEFAULT NULL,
  `Nov_2017` decimal(8,2) DEFAULT NULL,
  `Dec_2017` decimal(8,2) DEFAULT NULL,
  `Jan_2018` decimal(8,2) DEFAULT NULL,
  `Feb_2018` decimal(8,2) DEFAULT NULL,
  `Mar_2018` decimal(8,2) DEFAULT NULL,
  `Apr_2018` decimal(8,2) DEFAULT NULL,
  `May_2018` decimal(8,2) DEFAULT NULL,
  `Jun_2018` decimal(8,2) DEFAULT NULL,
  `Jul_2018` decimal(8,2) DEFAULT NULL,
  `Aug_2018` decimal(8,2) DEFAULT NULL,
  `Sep_2018` decimal(8,2) DEFAULT NULL,
  `Oct_2018` decimal(8,2) DEFAULT NULL,
  `Nov_2018` decimal(8,2) DEFAULT NULL,
  `Dec_2018` decimal(8,2) DEFAULT NULL,
  `Jan_2019` decimal(8,2) DEFAULT NULL,
  `Feb_2019` decimal(8,2) DEFAULT NULL,
  `Mar_2019` decimal(8,2) DEFAULT NULL,
  `Apr_2019` decimal(8,2) DEFAULT NULL,
  `May_2019` decimal(8,2) DEFAULT NULL,
  `Jun_2019` decimal(8,2) DEFAULT NULL,
  `Jul_2019` decimal(8,2) DEFAULT NULL,
  `Aug_2019` decimal(8,2) DEFAULT NULL,
  `Sep_2019` decimal(8,2) DEFAULT NULL,
  `Oct_2019` decimal(8,2) DEFAULT NULL,
  `Nov_2019` decimal(8,2) DEFAULT NULL,
  `Dec_2019` decimal(8,2) DEFAULT NULL,
  `Jan_2020` decimal(8,2) DEFAULT NULL,
  `Feb_2020` decimal(8,2) DEFAULT NULL,
  `Mar_2020` decimal(8,2) DEFAULT NULL,
  `Apr_2020` decimal(8,2) DEFAULT NULL,
  `May_2020` decimal(8,2) DEFAULT NULL,
  `Jun_2020` decimal(8,2) DEFAULT NULL,
  `Jul_2020` decimal(8,2) DEFAULT NULL,
  `Aug_2020` decimal(8,2) DEFAULT NULL,
  `Sep_2020` decimal(8,2) DEFAULT NULL,
  `Oct_2020` decimal(8,2) DEFAULT NULL,
  `Nov_2020` decimal(8,2) DEFAULT NULL,
  `Dec_2020` decimal(8,2) DEFAULT NULL,
  `contact_id` int(4) DEFAULT NULL,
  `merch_contact_id` int(4) DEFAULT NULL,
  `field_manager_id` int(11) DEFAULT NULL,
  `technical_contact_id` int(11) DEFAULT NULL,
  `tech_manager_id` int(11) DEFAULT NULL,
  `general_contact_id` int(11) DEFAULT NULL,
  `merchandise_contact_id` int(11) DEFAULT NULL,
  `regional_contact_id` int(11) DEFAULT NULL,
  `senior_vp_id` int(11) DEFAULT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




CREATE TABLE `location_budget` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) DEFAULT '0',
  `budget_date` date DEFAULT NULL,
  `budget_value` float(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




CREATE TABLE `location_user_roles_master` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` mediumint(8) unsigned NOT NULL,
  `role_title` varchar(200) NOT NULL,
  `unique_assignment` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;




CREATE TABLE `locations_closed` (
  `id` bigint(22) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(5) unsigned NOT NULL,
  `closed_date` date NOT NULL,
  `recorded_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `predefined` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `Location` (`location_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `merch_inventory` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `location_id` int(4) NOT NULL,
  `product_id` int(6) NOT NULL,
  `product_qty` decimal(6,2) NOT NULL,
  `date_in` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `merch_request_status` (
  `id` int(2) NOT NULL,
  `status` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




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




CREATE TABLE `new_graphics_priority` (
  `id` int(11) NOT NULL,
  `id_plus` varchar(12) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




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
  `aprrove_user_id` int(4) NOT NULL,
  `approve_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `new_graphics_request_status` (
  `id` int(2) NOT NULL,
  `status` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  `slug` varchar(128) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `numbers` (
  `id` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `order_received` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `order_line_item_id` int(11) NOT NULL,
  `quantity` int(5) NOT NULL,
  `received_by` int(11) NOT NULL,
  `date_received` date NOT NULL,
  `notes` text COLLATE utf8_unicode_ci,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;




CREATE TABLE `order_status` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `status` varchar(50) NOT NULL,
  `class` varchar(20) NOT NULL,
  `order_type_id` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `order_type` (
  `id` int(1) NOT NULL,
  `order_type` varchar(50) NOT NULL,
  `is_merch` tinyint(1) NOT NULL,
  `can_request` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




CREATE TABLE `orderdetails` (
  `orderNumber` int(11) NOT NULL,
  `productCode` varchar(15) NOT NULL,
  `quantityOrdered` int(11) NOT NULL,
  `priceEach` double NOT NULL,
  `orderLineNumber` smallint(6) NOT NULL,
  PRIMARY KEY (`orderNumber`,`productCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




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
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `past_sync_harvest` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `loc_id` int(10) unsigned NOT NULL,
  `debit_type` int(10) unsigned NOT NULL,
  `date_start` datetime NOT NULL,
  `needs_sync` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `adjustment_date` datetime NOT NULL,
  `notes` text NOT NULL,
  `source` text NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `DATELOCUNIQUE` (`loc_id`,`date_start`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `product_type` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `product_type` varchar(22) NOT NULL,
  `type_description` varchar(40) NOT NULL,
  `request_type_id` int(3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `supply_type` (`product_type`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




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
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `expense_category` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `description` (`vendor_description`,`vendor_id`,`case_price`,`prod_type_id`,`prod_sub_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `reader_exclude` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reader_id` varchar(25) NOT NULL,
  `debit_type_id` int(1) NOT NULL,
  `loc_id` int(4) NOT NULL,
  `reason` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `readers` (
  `id` bigint(22) unsigned NOT NULL AUTO_INCREMENT,
  `reader_id` text,
  `game_id` int(11) unsigned DEFAULT NULL,
  `game_name` text,
  `location_id` int(11) unsigned DEFAULT NULL,
  `first_report_date` date DEFAULT NULL,
  `last_report_date` date DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `autosynced` tinyint(1) DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1',
  `mapped_to_game` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;




CREATE TABLE `redemption_imgs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_name` varchar(150) NOT NULL,
  `loc_id` int(4) NOT NULL,
  `users` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `region` (
  `id` int(2) NOT NULL,
  `region` varchar(30) NOT NULL,
  `dist_mgr_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




CREATE TABLE `report_game_plays` (
  `id` bigint(22) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` int(8) unsigned DEFAULT NULL,
  `date_played` date DEFAULT NULL,
  `date_last_played` date DEFAULT NULL,
  `game_revenue` double DEFAULT NULL,
  `game_std_plays` int(11) DEFAULT NULL,
  `total_plays` int(11) DEFAULT NULL,
  `actual_cash` double DEFAULT NULL,
  `card_cash` double DEFAULT NULL,
  `card_bonus` double DEFAULT NULL,
  `time_plays` int(11) DEFAULT NULL,
  `product_plays` int(11) DEFAULT NULL,
  `product_notional_value` double DEFAULT NULL,
  `courtesy_plays` int(11) DEFAULT NULL,
  `product_and_courtesy_plays` int(11) DEFAULT NULL,
  `grand_total` double DEFAULT NULL,
  `location_id` int(5) unsigned DEFAULT NULL,
  `debit_type_id` int(11) unsigned DEFAULT NULL,
  `game_title_id` int(11) unsigned DEFAULT NULL,
  `game_type_id` int(11) unsigned DEFAULT NULL,
  `game_status` int(11) unsigned DEFAULT NULL,
  `game_is_sold` tinyint(1) DEFAULT NULL,
  `game_on_test` tinyint(1) DEFAULT NULL,
  `game_not_debit` tinyint(1) DEFAULT NULL,
  `report_status` int(5) DEFAULT NULL,
  `report_date` date DEFAULT NULL,
  `record_status` int(5) unsigned DEFAULT NULL,
  `related_record` bigint(22) unsigned DEFAULT NULL,
  `notes` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;




CREATE TABLE `report_locations` (
  `id` bigint(22) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(5) unsigned DEFAULT NULL,
  `date_played` date DEFAULT NULL,
  `date_last_played` date DEFAULT NULL,
  `debit_type_id` int(11) unsigned DEFAULT NULL,
  `report_status` int(5) DEFAULT NULL,
  `report_date` date DEFAULT NULL,
  `sync_record_count` int(11) unsigned DEFAULT NULL,
  `games_count` int(11) unsigned DEFAULT NULL,
  `games_played_count` int(11) unsigned DEFAULT NULL,
  `games_revenue` double DEFAULT NULL,
  `games_total_std_plays` int(11) unsigned DEFAULT NULL,
  `record_status` int(5) unsigned DEFAULT NULL,
  `related_record` bigint(22) unsigned DEFAULT NULL,
  `notes` text,
  PRIMARY KEY (`id`),
  KEY `LOCATION` (`location_id`),
  KEY `DATE` (`date_played`),
  KEY `LOCOFF` (`location_id`,`report_status`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;




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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `sacoa_location_ids` (
  `feg_id` int(4) NOT NULL,
  `sacoa_id` varchar(2) NOT NULL,
  `zone` varchar(255) NOT NULL DEFAULT 'Unzoned',
  PRIMARY KEY (`sacoa_id`,`zone`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




CREATE TABLE `sb_invoiceitems` (
  `ItemID` int(11) NOT NULL AUTO_INCREMENT,
  `InvoiceID` int(11) DEFAULT NULL,
  `ProductID` int(6) DEFAULT NULL,
  `Qty` smallint(3) DEFAULT NULL,
  `Price` decimal(10,0) DEFAULT NULL,
  `Total` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`ItemID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `sb_invoiceproducts` (
  `ProductID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) DEFAULT NULL,
  `Note` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ProductID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `sb_invoices` (
  `InvoiceID` int(11) NOT NULL AUTO_INCREMENT,
  `Number` varchar(100) DEFAULT NULL,
  `UserID` int(6) DEFAULT NULL,
  `DateIssued` date DEFAULT NULL,
  `DueDate` date DEFAULT NULL,
  `Amount` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`InvoiceID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `sb_ticketcomments` (
  `CommentID` int(11) NOT NULL AUTO_INCREMENT,
  `TicketID` int(11) DEFAULT NULL,
  `Comments` text,
  `Posted` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `UserID` int(11) DEFAULT NULL,
  `USERNAME` varchar(50) NOT NULL,
  `Attachments` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`CommentID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




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
  `debit_card` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`TicketID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `sbticket_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role1` varchar(100) DEFAULT NULL,
  `role2` varchar(100) DEFAULT NULL,
  `role3` varchar(100) DEFAULT NULL,
  `role4` varchar(100) DEFAULT NULL,
  `role5` varchar(100) DEFAULT NULL,
  `individual1` varchar(100) NOT NULL,
  `individual2` varchar(100) NOT NULL,
  `individual3` varchar(100) NOT NULL,
  `individual4` varchar(100) NOT NULL,
  `individual5` varchar(100) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `service_status` (
  `id` int(1) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




CREATE TABLE `sessions` (
  `id` varchar(50) NOT NULL DEFAULT '',
  `payload` text,
  `last_activity` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `spare_status` (
  `id` int(1) NOT NULL,
  `status` varchar(15) NOT NULL,
  `class` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




CREATE TABLE `states` (
  `state_code` varchar(3) NOT NULL,
  `state_name` varchar(40) NOT NULL,
  PRIMARY KEY (`state_code`),
  UNIQUE KEY `state_id` (`state_code`,`state_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `supply_type` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `supply_type` varchar(22) NOT NULL,
  `type_description` varchar(40) NOT NULL,
  `request_type_id` int(3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `supply_type` (`supply_type`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `system_email_report_manager` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `report_name` varchar(512) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `has_locationwise_filter` tinyint(1) NOT NULL DEFAULT '0',
  `to_email_groups` longtext,
  `to_email_location_contacts` longtext,
  `to_email_individuals` longtext,
  `to_include_emails` longtext,
  `to_exclude_emails` longtext,
  `cc_email_groups` longtext,
  `cc_email_location_contacts` longtext,
  `cc_email_individuals` longtext,
  `cc_include_emails` longtext,
  `cc_exclude_emails` longtext,
  `bcc_email_groups` longtext,
  `bcc_email_location_contacts` longtext,
  `bcc_email_individuals` longtext,
  `bcc_include_emails` longtext,
  `bcc_exclude_emails` longtext,
  `test_to_emails` longtext,
  `test_cc_emails` longtext,
  `test_bcc_emails` longtext,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;




CREATE TABLE `tb_blogcategories` (
  `CatID` int(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `alias` varchar(100) DEFAULT NULL,
  `enable` enum('0','1') DEFAULT NULL,
  PRIMARY KEY (`CatID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `tb_blogcomments` (
  `commentID` int(11) NOT NULL AUTO_INCREMENT,
  `parentID` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `blogID` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `comment` text,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`commentID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `tb_calendar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `description` mediumtext,
  `start` date DEFAULT NULL,
  `end` date DEFAULT NULL,
  `entry_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `tb_employees` (
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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `tb_groups` (
  `group_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `level` int(6) DEFAULT NULL,
  `redirect_link` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `tb_groups_access` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `group_id` int(6) DEFAULT NULL,
  `module_id` int(6) DEFAULT NULL,
  `access_data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `tb_logs` (
  `auditID` int(20) NOT NULL AUTO_INCREMENT,
  `ipaddress` varchar(50) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `module` varchar(50) DEFAULT NULL,
  `task` varchar(50) DEFAULT NULL,
  `note` text,
  `logdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`auditID`),
  UNIQUE KEY `auditID` (`auditID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `tb_restapi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `apiuser` int(11) DEFAULT NULL,
  `apikey` varchar(100) DEFAULT NULL,
  `created` date DEFAULT NULL,
  `modules` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




CREATE TABLE `tb_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(6) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(64) NOT NULL,
  `email` varchar(100) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `avatar` varchar(100) DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `login_attempt` tinyint(2) DEFAULT '0',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `reminder` varchar(64) DEFAULT NULL,
  `activation` varchar(50) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `last_activity` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `timeclock_entries` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `user_id` int(3) NOT NULL,
  `location_id` int(4) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `total_hours` decimal(5,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `user_images` (
  `id` bigint(22) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(22) unsigned NOT NULL,
  `image_url` text NOT NULL,
  `details` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




CREATE TABLE `user_level` (
  `id` int(1) NOT NULL,
  `user_level` varchar(18) NOT NULL,
  `usr_lvl` varchar(12) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




CREATE TABLE `user_locations` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `group_id` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;




CREATE TABLE `user_module_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  `config` text,
  `config_name` varchar(50) DEFAULT NULL,
  `is_private` smallint(1) DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;




CREATE TABLE `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(40) COLLATE latin1_general_ci NOT NULL,
  `last_name` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `user_name` varchar(200) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `username` varchar(200) COLLATE latin1_general_ci NOT NULL,
  `email` varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `user_level` tinyint(4) NOT NULL DEFAULT '1',
  `pwd` varchar(220) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `is_tech_contact` tinyint(4) NOT NULL DEFAULT '0',
  `approved` int(1) NOT NULL DEFAULT '0',
  `banned` int(1) NOT NULL DEFAULT '0',
  `company_id` int(2) NOT NULL,
  `loc_1` int(5) NOT NULL,
  `loc_2` int(5) NOT NULL,
  `loc_3` int(5) NOT NULL,
  `loc_4` int(5) NOT NULL,
  `loc_5` int(5) NOT NULL,
  `loc_6` int(5) NOT NULL,
  `loc_7` int(5) NOT NULL,
  `loc_8` int(5) NOT NULL,
  `loc_9` int(5) NOT NULL,
  `loc_10` int(5) NOT NULL,
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
  `avatar` varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '1.jpg',
  `group_id` tinyint(4) NOT NULL DEFAULT '1',
  `password` varchar(220) COLLATE latin1_general_ci NOT NULL,
  `remember_token` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `reminder` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `has_all_locations` tinyint(4) NOT NULL DEFAULT '0',
  `redirect_link` varchar(250) COLLATE latin1_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_email` (`email`),
  UNIQUE KEY `id` (`id`),
  KEY `email` (`email`),
  FULLTEXT KEY `idx_search` (`email`,`user_name`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;




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
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `hide` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `vendor_name` (`vendor_name`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;



SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;

SET character_set_client = @saved_cs_client;



CREATE TABLE `yes_no` (
  `id` int(1) NOT NULL,
  `yesno` varchar(3) NOT NULL,
  UNIQUE KEY `id` (`id`,`yesno`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
































