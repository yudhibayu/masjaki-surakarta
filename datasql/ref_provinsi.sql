/*
 Navicat Premium Data Transfer

 Source Server         : local.mysql
 Source Server Type    : MySQL
 Source Server Version : 110402
 Source Host           : localhost:3306
 Source Schema         : kerjasama

 Target Server Type    : MySQL
 Target Server Version : 110402
 File Encoding         : 65001

 Date: 29/07/2024 15:35:09
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ref_provinsi
-- ----------------------------
DROP TABLE IF EXISTS `ref_provinsi`;
CREATE TABLE `ref_provinsi`  (
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ID',
  `ref_negara_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Country',
  `ref_negara_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Country',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Province',
  `created_by` int NULL DEFAULT NULL COMMENT 'Created By',
  `updated_by` int NULL DEFAULT NULL COMMENT 'Updated By',
  `created_at` datetime NULL DEFAULT NULL COMMENT 'Created At',
  `updated_at` datetime NULL DEFAULT NULL COMMENT 'Updated At',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE,
  INDEX `ref_negara_id`(`ref_negara_id`) USING BTREE,
  INDEX `ref_negara_name`(`ref_negara_name`) USING BTREE,
  INDEX `name`(`name`) USING BTREE,
  CONSTRAINT `ref_provinsi_ibfk_1` FOREIGN KEY (`ref_negara_id`) REFERENCES `ref_negara` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ref_provinsi
-- ----------------------------
INSERT INTO `ref_provinsi` VALUES ('11', '100', 'Indonesia', 'Aceh', 1, 2, '2021-01-01 00:00:00', '2021-09-26 13:22:19');
INSERT INTO `ref_provinsi` VALUES ('12', '100', 'Indonesia', 'Sumatera Utara', 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_provinsi` VALUES ('13', '100', 'Indonesia', 'Sumatera Barat', 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_provinsi` VALUES ('14', '100', 'Indonesia', 'Riau', 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_provinsi` VALUES ('15', '100', 'Indonesia', 'Jambi', 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_provinsi` VALUES ('16', '100', 'Indonesia', 'Sumatera Selatan', 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_provinsi` VALUES ('17', '100', 'Indonesia', 'Bengkulu', 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_provinsi` VALUES ('18', '100', 'Indonesia', 'Lampung', 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_provinsi` VALUES ('19', '100', 'Indonesia', 'Kepulauan Bangka Belitung', 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_provinsi` VALUES ('21', '100', 'Indonesia', 'Kepulauan Riau', 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_provinsi` VALUES ('31', '100', 'Indonesia', 'DKI Jakarta', 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_provinsi` VALUES ('32', '100', 'Indonesia', 'Jawa Barat', 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_provinsi` VALUES ('33', '100', 'Indonesia', 'Jawa Tengah', 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_provinsi` VALUES ('34', '100', 'Indonesia', 'DI Yogyakarta', 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_provinsi` VALUES ('35', '100', 'Indonesia', 'Jawa Timur', 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_provinsi` VALUES ('36', '100', 'Indonesia', 'Banten', 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_provinsi` VALUES ('51', '100', 'Indonesia', 'Bali', 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_provinsi` VALUES ('52', '100', 'Indonesia', 'Nusa Tenggara Barat', 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_provinsi` VALUES ('53', '100', 'Indonesia', 'Nusa Tenggara Timur', 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_provinsi` VALUES ('61', '100', 'Indonesia', 'Kalimantan Barat', 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_provinsi` VALUES ('62', '100', 'Indonesia', 'Kalimantan Tengah', 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_provinsi` VALUES ('63', '100', 'Indonesia', 'Kalimantan Selatan', 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_provinsi` VALUES ('64', '100', 'Indonesia', 'Kalimantan Timur', 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_provinsi` VALUES ('65', '100', 'Indonesia', 'Kalimantan Utara', 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_provinsi` VALUES ('71', '100', 'Indonesia', 'Sulawesi Utara', 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_provinsi` VALUES ('72', '100', 'Indonesia', 'Sulawesi Tengah', 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_provinsi` VALUES ('73', '100', 'Indonesia', 'Sulawesi Selatan', 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_provinsi` VALUES ('74', '100', 'Indonesia', 'Sulawesi Tenggara', 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_provinsi` VALUES ('75', '100', 'Indonesia', 'Gorontalo', 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_provinsi` VALUES ('76', '100', 'Indonesia', 'Sulawesi Barat', 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_provinsi` VALUES ('81', '100', 'Indonesia', 'Maluku', 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_provinsi` VALUES ('82', '100', 'Indonesia', 'Maluku Utara', 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_provinsi` VALUES ('91', '100', 'Indonesia', 'Papua', 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_provinsi` VALUES ('92', '100', 'Indonesia', 'Papua Barat', 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');

SET FOREIGN_KEY_CHECKS = 1;
