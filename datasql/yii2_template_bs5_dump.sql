/*
 Navicat Premium Data Transfer

 Source Server         : Mariadb-10.3
 Source Server Type    : MariaDB
 Source Server Version : 100337
 Source Host           : localhost:3306
 Source Schema         : yii2_template_bs5

 Target Server Type    : MariaDB
 Target Server Version : 100337
 File Encoding         : 65001

 Date: 20/12/2022 14:09:27
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for app_setting
-- ----------------------------
DROP TABLE IF EXISTS `app_setting`;
CREATE TABLE `app_setting`  (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Setting Name',
  `hints` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Hints',
  `segment_id` int(5) NOT NULL COMMENT 'Segment',
  `segment_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Segment Name',
  `input_type_id` int(5) NOT NULL COMMENT 'Input Type',
  `input_data_type` enum('Static','Dynamic','Single Input') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Input Data Type',
  `data_content` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Data Type Content',
  `size` enum('1','2','3','4','5','6','7','8','9','10','11','12') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '5' COMMENT 'Size',
  `row` int(3) NULL DEFAULT 0 COMMENT 'Row Size',
  `sort` int(5) NULL DEFAULT 0 COMMENT 'Sort',
  `status` enum('Active','Inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active' COMMENT 'Status',
  `key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Key',
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Value',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `key`(`key`) USING BTREE,
  INDEX `status`(`status`) USING BTREE,
  INDEX `segment_id`(`segment_id`) USING BTREE,
  INDEX `segment_name`(`segment_name`) USING BTREE,
  CONSTRAINT `app_setting_ibfk_1` FOREIGN KEY (`segment_id`) REFERENCES `app_setting_segment` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `app_setting_ibfk_2` FOREIGN KEY (`segment_name`) REFERENCES `app_setting_segment` (`name`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of app_setting
-- ----------------------------
INSERT INTO `app_setting` VALUES (1, 'Company Name', 'Input your company name', 1, 'General', 1, 'Single Input', NULL, '6', NULL, 10, 'Active', 'company_name', 'Yii2 Template BS5');
INSERT INTO `app_setting` VALUES (2, 'Email', 'Input your company email', 3, 'Messaging', 1, 'Single Input', NULL, '5', 0, 10, 'Active', 'company_email', 'admin@infinindotech.com');
INSERT INTO `app_setting` VALUES (3, 'Address', 'Input yout company address', 2, 'Display', 1, 'Single Input', NULL, '5', 0, 10, 'Active', 'company_address', 'Jl. Cocak I nomor 17, Surakarta');

-- ----------------------------
-- Table structure for app_setting_input_type
-- ----------------------------
DROP TABLE IF EXISTS `app_setting_input_type`;
CREATE TABLE `app_setting_input_type`  (
  `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Name',
  `sort` int(5) NULL DEFAULT 10 COMMENT 'Sort',
  `status` enum('Active','Inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active' COMMENT 'Status',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of app_setting_input_type
-- ----------------------------
INSERT INTO `app_setting_input_type` VALUES (1, 'Input', 10, 'Active');
INSERT INTO `app_setting_input_type` VALUES (2, 'Text Area', 20, 'Active');
INSERT INTO `app_setting_input_type` VALUES (3, 'Drop Down', 30, 'Active');
INSERT INTO `app_setting_input_type` VALUES (4, 'Check Box', 40, 'Inactive');
INSERT INTO `app_setting_input_type` VALUES (5, 'Radio', 50, 'Inactive');

-- ----------------------------
-- Table structure for app_setting_segment
-- ----------------------------
DROP TABLE IF EXISTS `app_setting_segment`;
CREATE TABLE `app_setting_segment`  (
  `id` int(5) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Name',
  `sort` int(5) NULL DEFAULT 10 COMMENT 'Sort',
  `status` enum('Active','Inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active' COMMENT 'Status',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `status`(`status`) USING BTREE,
  INDEX `name`(`name`) USING BTREE,
  INDEX `id`(`id`, `name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of app_setting_segment
-- ----------------------------
INSERT INTO `app_setting_segment` VALUES (1, 'General', 10, 'Active');
INSERT INTO `app_setting_segment` VALUES (2, 'Display', 20, 'Active');
INSERT INTO `app_setting_segment` VALUES (3, 'Messaging', 30, 'Active');
INSERT INTO `app_setting_segment` VALUES (4, 'API', 40, 'Active');
INSERT INTO `app_setting_segment` VALUES (5, 'Social', 50, 'Active');

-- ----------------------------
-- Table structure for auth_assignment
-- ----------------------------
DROP TABLE IF EXISTS `auth_assignment`;
CREATE TABLE `auth_assignment`  (
  `item_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`item_name`, `user_id`) USING BTREE,
  INDEX `idx-auth_assignment-user_id`(`user_id`) USING BTREE,
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of auth_assignment
-- ----------------------------
INSERT INTO `auth_assignment` VALUES ('Administrator', '2', 1561353391);
INSERT INTO `auth_assignment` VALUES ('Super Administrator', '1', 1561353379);

-- ----------------------------
-- Table structure for auth_item
-- ----------------------------
DROP TABLE IF EXISTS `auth_item`;
CREATE TABLE `auth_item`  (
  `name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `rule_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `data` blob NULL DEFAULT NULL,
  `created_at` int(11) NULL DEFAULT NULL,
  `updated_at` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`name`) USING BTREE,
  INDEX `rule_name`(`rule_name`) USING BTREE,
  INDEX `idx-auth_item-type`(`type`) USING BTREE,
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of auth_item
-- ----------------------------
INSERT INTO `auth_item` VALUES ('Administrator', 1, 'Top Level User', NULL, NULL, 1561353292, 1646839607);
INSERT INTO `auth_item` VALUES ('Super Administrator', 1, 'Kelas Tertinggi', NULL, NULL, 1561353318, 1561371041);

-- ----------------------------
-- Table structure for auth_item_child
-- ----------------------------
DROP TABLE IF EXISTS `auth_item_child`;
CREATE TABLE `auth_item_child`  (
  `parent` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`, `child`) USING BTREE,
  INDEX `child`(`child`) USING BTREE,
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of auth_item_child
-- ----------------------------
INSERT INTO `auth_item_child` VALUES ('Super Administrator', 'Administrator');

-- ----------------------------
-- Table structure for auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE `auth_rule`  (
  `name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `data` blob NULL DEFAULT NULL,
  `created_at` int(11) NULL DEFAULT NULL,
  `updated_at` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`name`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of auth_rule
-- ----------------------------

-- ----------------------------
-- Table structure for menu
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `parent` int(11) NULL DEFAULT NULL,
  `route` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `order` int(11) NULL DEFAULT NULL,
  `data` blob NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `parent`(`parent`) USING BTREE,
  CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `menu` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of menu
-- ----------------------------

-- ----------------------------
-- Table structure for migration
-- ----------------------------
DROP TABLE IF EXISTS `migration`;
CREATE TABLE `migration`  (
  `version` varchar(180) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `apply_time` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`version`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migration
-- ----------------------------
INSERT INTO `migration` VALUES ('m000000_000000_base', 1561348230);
INSERT INTO `migration` VALUES ('m140209_132017_init', 1561348236);
INSERT INTO `migration` VALUES ('m140403_174025_create_account_table', 1561348238);
INSERT INTO `migration` VALUES ('m140504_113157_update_tables', 1561348242);
INSERT INTO `migration` VALUES ('m140504_130429_create_token_table', 1561348243);
INSERT INTO `migration` VALUES ('m140830_171933_fix_ip_field', 1561348243);
INSERT INTO `migration` VALUES ('m140830_172703_change_account_table_name', 1561348243);
INSERT INTO `migration` VALUES ('m141222_110026_update_ip_field', 1561348244);
INSERT INTO `migration` VALUES ('m141222_135246_alter_username_length', 1561348245);
INSERT INTO `migration` VALUES ('m150614_103145_update_social_account_table', 1561348246);
INSERT INTO `migration` VALUES ('m150623_212711_fix_username_notnull', 1561348246);
INSERT INTO `migration` VALUES ('m151218_234654_add_timezone_to_profile', 1561348247);
INSERT INTO `migration` VALUES ('m160929_103127_add_last_login_at_to_user_table', 1561348248);
INSERT INTO `migration` VALUES ('m140506_102106_rbac_init', 1561352780);
INSERT INTO `migration` VALUES ('m170907_052038_rbac_add_index_on_auth_assignment_user_id', 1561352780);
INSERT INTO `migration` VALUES ('m180523_151638_rbac_updates_indexes_without_prefix', 1561352780);

-- ----------------------------
-- Table structure for profile
-- ----------------------------
DROP TABLE IF EXISTS `profile`;
CREATE TABLE `profile`  (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `public_email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `gravatar_email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `gravatar_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `location` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `website` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `bio` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `timezone` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `file_name` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT 'blank-propic.png',
  PRIMARY KEY (`user_id`) USING BTREE,
  CONSTRAINT `profile_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of profile
-- ----------------------------
INSERT INTO `profile` VALUES (1, 'Super Admin', 'test_noreply@infinindotech.com', 'test_noreply@infinindotech.com', 'ecadd8bd86d40c2383fb25c8145fa7f2', '', 'https://bajhoe.web.id', '', 'Asia/Jakarta', 'blank-propic.png');
INSERT INTO `profile` VALUES (2, 'Mr. Admin', '', 'test_noreply@infinindotech.com', '9afd594eb460090305b9c333afd6bd0f', '', '', '', 'Asia/Jakarta', 'blank-propic.png');

-- ----------------------------
-- Table structure for ref_year
-- ----------------------------
DROP TABLE IF EXISTS `ref_year`;
CREATE TABLE `ref_year`  (
  `id` int(5) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` int(5) NOT NULL COMMENT 'Year',
  `active_login` enum('F','T') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'F' COMMENT 'Active Login',
  `active` enum('F','T') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'F' COMMENT 'Active',
  `created_at` datetime NOT NULL COMMENT 'Created At',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP COMMENT 'Updated At',
  `created_by` int(15) NOT NULL COMMENT 'Created By',
  `updated_by` int(15) NOT NULL COMMENT 'Updated By',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ref_year
-- ----------------------------
INSERT INTO `ref_year` VALUES (1, 2022, 'T', 'T', '2021-11-30 13:39:50', '2022-06-14 15:49:10', 1, 1);
INSERT INTO `ref_year` VALUES (2, 2023, 'F', 'F', '2022-03-10 22:36:16', '2022-03-27 13:47:49', 1, 1);
INSERT INTO `ref_year` VALUES (3, 2024, 'T', 'T', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0);

-- ----------------------------
-- Table structure for session
-- ----------------------------
DROP TABLE IF EXISTS `session`;
CREATE TABLE `session`  (
  `id` char(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expire` int(11) NULL DEFAULT NULL,
  `data` blob NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of session
-- ----------------------------

-- ----------------------------
-- Table structure for social_account
-- ----------------------------
DROP TABLE IF EXISTS `social_account`;
CREATE TABLE `social_account`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NULL DEFAULT NULL,
  `provider` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `client_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `data` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `code` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `created_at` int(11) NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `account_unique`(`provider`, `client_id`) USING BTREE,
  UNIQUE INDEX `account_unique_code`(`code`) USING BTREE,
  INDEX `fk_user_account`(`user_id`) USING BTREE,
  CONSTRAINT `social_account_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of social_account
-- ----------------------------

-- ----------------------------
-- Table structure for token
-- ----------------------------
DROP TABLE IF EXISTS `token`;
CREATE TABLE `token`  (
  `user_id` int(11) NOT NULL,
  `code` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) NOT NULL,
  `type` smallint(6) NOT NULL,
  UNIQUE INDEX `token_unique`(`user_id`, `code`, `type`) USING BTREE,
  CONSTRAINT `token_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of token
-- ----------------------------

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `confirmed_at` int(11) NULL DEFAULT NULL,
  `unconfirmed_email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `blocked_at` int(11) NULL DEFAULT NULL,
  `registration_ip` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `flags` int(11) NOT NULL DEFAULT 0,
  `last_login_at` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `user_unique_username`(`username`) USING BTREE,
  UNIQUE INDEX `user_unique_email`(`email`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES (1, 'sadmin', 'test_noreply@infinindotech.com', '$2y$12$J8W6f.D1iQv/8RkVibi7xOFlCNsmw4.LYPt2iNOmBt5CGxHSesKJ.', 'CbHzan635hMjbov0v2RbOoIOguFO_ap_', 1561353044, NULL, NULL, '::1', 1561352979, 1646838163, 0, 1671519534);
INSERT INTO `user` VALUES (2, 'admin', 'test_noreply+1@infinindotech.com', '$2y$12$WtdAixyNiOIX2bOP4TYSqu/x0nkHcfMQyFMjGBheXa77mJOlH1KTu', '6hCl1spn3rhNjvBFesnGKOXgyNIW7RFd', 1561353085, NULL, NULL, '::1', 1561353077, 1648547525, 0, 1647323415);

-- ----------------------------
-- Triggers structure for table app_setting
-- ----------------------------
DROP TRIGGER IF EXISTS `segment_name_insert`;
delimiter ;;
CREATE TRIGGER `segment_name_insert` BEFORE INSERT ON `app_setting` FOR EACH ROW SET new.segment_name = (SELECT aps.name FROM app_setting_segment aps
WHERE aps.id = new.segment_id)
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table app_setting
-- ----------------------------
DROP TRIGGER IF EXISTS `segment_name_update`;
delimiter ;;
CREATE TRIGGER `segment_name_update` BEFORE UPDATE ON `app_setting` FOR EACH ROW SET new.segment_name = (SELECT aps.name FROM app_setting_segment aps
WHERE aps.id = new.segment_id)
;;
delimiter ;

SET FOREIGN_KEY_CHECKS = 1;


-- yii2_template_bs5_dump.oauth_clients definition

CREATE TABLE `oauth_clients` (
  `client_id` varchar(32) NOT NULL,
  `client_secret` varchar(32) DEFAULT NULL,
  `redirect_uri` varchar(1000) NOT NULL,
  `grant_types` varchar(100) NOT NULL,
  `scope` varchar(2000) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- yii2_template_bs5_dump.oauth_jwt definition

CREATE TABLE `oauth_jwt` (
  `client_id` varchar(32) NOT NULL,
  `subject` varchar(80) DEFAULT NULL,
  `public_key` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- yii2_template_bs5_dump.oauth_public_keys definition

CREATE TABLE `oauth_public_keys` (
  `client_id` varchar(255) NOT NULL,
  `public_key` varchar(2000) DEFAULT NULL,
  `private_key` varchar(2000) DEFAULT NULL,
  `encryption_algorithm` varchar(100) DEFAULT 'RS256'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- yii2_template_bs5_dump.oauth_scopes definition

CREATE TABLE `oauth_scopes` (
  `scope` varchar(2000) NOT NULL,
  `is_default` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- yii2_template_bs5_dump.oauth_users definition

CREATE TABLE `oauth_users` (
  `username` varchar(255) NOT NULL,
  `password` varchar(2000) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- yii2_template_bs5_dump.oauth_access_tokens definition

CREATE TABLE `oauth_access_tokens` (
  `access_token` varchar(40) NOT NULL,
  `client_id` varchar(32) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `expires` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `scope` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`access_token`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `oauth_access_tokens_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `oauth_clients` (`client_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- yii2_template_bs5_dump.oauth_authorization_codes definition

CREATE TABLE `oauth_authorization_codes` (
  `authorization_code` varchar(40) NOT NULL,
  `client_id` varchar(32) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `redirect_uri` varchar(1000) NOT NULL,
  `expires` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `scope` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`authorization_code`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `oauth_authorization_codes_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `oauth_clients` (`client_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- yii2_template_bs5_dump.oauth_refresh_tokens definition

CREATE TABLE `oauth_refresh_tokens` (
  `refresh_token` varchar(40) NOT NULL,
  `client_id` varchar(32) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `expires` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `scope` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`refresh_token`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `oauth_refresh_tokens_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `oauth_clients` (`client_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- insert test client data
INSERT INTO `oauth_clients` (`client_id`,`client_secret`,`redirect_uri`,`grant_types`)
	VALUES ('testclient','testpass','https://infinindotech.com/','client_credentials authorization_code password implicit');
