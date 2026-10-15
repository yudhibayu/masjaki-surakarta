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

 Date: 29/07/2024 15:35:02
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ref_negara
-- ----------------------------
DROP TABLE IF EXISTS `ref_negara`;
CREATE TABLE `ref_negara`  (
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ID',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Country',
  `uc_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Country Upper Case',
  `iso` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'ISO',
  `iso3` char(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'ISO3',
  `numcode` smallint NULL DEFAULT NULL COMMENT 'Numeric Code',
  `phonecode` int NULL DEFAULT NULL COMMENT 'Phone Code',
  `created_by` int NULL DEFAULT NULL COMMENT 'Created By',
  `updated_by` int NULL DEFAULT NULL COMMENT 'Updated By',
  `created_at` datetime NULL DEFAULT NULL COMMENT 'Created At',
  `updated_at` datetime NULL DEFAULT NULL COMMENT 'Updated At',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE,
  INDEX `name`(`name`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ref_negara
-- ----------------------------
INSERT INTO `ref_negara` VALUES ('1', 'Afghanistan', 'AFGHANISTAN', 'AF', 'AFG', 4, 93, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('10', 'Argentina', 'ARGENTINA', 'AR', 'ARG', 32, 54, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('100', 'Indonesia', 'INDONESIA', 'ID', 'IDN', 360, 62, 1, 2, '2021-01-01 00:00:00', '2021-09-26 14:27:19');
INSERT INTO `ref_negara` VALUES ('101', 'Iran, Islamic Republic of', 'IRAN, ISLAMIC REPUBLIC OF', 'IR', 'IRN', 364, 98, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('102', 'Iraq', 'IRAQ', 'IQ', 'IRQ', 368, 964, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('103', 'Ireland', 'IRELAND', 'IE', 'IRL', 372, 353, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('104', 'Israel', 'ISRAEL', 'IL', 'ISR', 376, 972, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('105', 'Italy', 'ITALY', 'IT', 'ITA', 380, 39, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('106', 'Jamaica', 'JAMAICA', 'JM', 'JAM', 388, 1876, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('107', 'Japan', 'JAPAN', 'JP', 'JPN', 392, 81, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('108', 'Jordan', 'JORDAN', 'JO', 'JOR', 400, 962, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('109', 'Kazakhstan', 'KAZAKHSTAN', 'KZ', 'KAZ', 398, 7, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('11', 'Armenia', 'ARMENIA', 'AM', 'ARM', 51, 374, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('110', 'Kenya', 'KENYA', 'KE', 'KEN', 404, 254, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('111', 'Kiribati', 'KIRIBATI', 'KI', 'KIR', 296, 686, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('112', 'Korea, Democratic People\'s Republic of', 'KOREA, DEMOCRATIC PEOPLE\'S REPUBLIC OF', 'KP', 'PRK', 408, 850, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('113', 'Korea, Republic of', 'KOREA, REPUBLIC OF', 'KR', 'KOR', 410, 82, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('114', 'Kuwait', 'KUWAIT', 'KW', 'KWT', 414, 965, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('115', 'Kyrgyzstan', 'KYRGYZSTAN', 'KG', 'KGZ', 417, 996, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('116', 'Lao People\'s Democratic Republic', 'LAO PEOPLE\'S DEMOCRATIC REPUBLIC', 'LA', 'LAO', 418, 856, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('117', 'Latvia', 'LATVIA', 'LV', 'LVA', 428, 371, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('118', 'Lebanon', 'LEBANON', 'LB', 'LBN', 422, 961, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('119', 'Lesotho', 'LESOTHO', 'LS', 'LSO', 426, 266, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('12', 'Aruba', 'ARUBA', 'AW', 'ABW', 533, 297, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('120', 'Liberia', 'LIBERIA', 'LR', 'LBR', 430, 231, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('121', 'Libyan Arab Jamahiriya', 'LIBYAN ARAB JAMAHIRIYA', 'LY', 'LBY', 434, 218, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('122', 'Liechtenstein', 'LIECHTENSTEIN', 'LI', 'LIE', 438, 423, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('123', 'Lithuania', 'LITHUANIA', 'LT', 'LTU', 440, 370, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('124', 'Luxembourg', 'LUXEMBOURG', 'LU', 'LUX', 442, 352, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('125', 'Macao', 'MACAO', 'MO', 'MAC', 446, 853, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('126', 'Macedonia, the Former Yugoslav Republic of', 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF', 'MK', 'MKD', 807, 389, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('127', 'Madagascar', 'MADAGASCAR', 'MG', 'MDG', 450, 261, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('128', 'Malawi', 'MALAWI', 'MW', 'MWI', 454, 265, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('129', 'Malaysia', 'MALAYSIA', 'MY', 'MYS', 458, 60, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('13', 'Australia', 'AUSTRALIA', 'AU', 'AUS', 36, 61, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('130', 'Maldives', 'MALDIVES', 'MV', 'MDV', 462, 960, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('131', 'Mali', 'MALI', 'ML', 'MLI', 466, 223, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('132', 'Malta', 'MALTA', 'MT', 'MLT', 470, 356, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('133', 'Marshall Islands', 'MARSHALL ISLANDS', 'MH', 'MHL', 584, 692, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('134', 'Martinique', 'MARTINIQUE', 'MQ', 'MTQ', 474, 596, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('135', 'Mauritania', 'MAURITANIA', 'MR', 'MRT', 478, 222, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('136', 'Mauritius', 'MAURITIUS', 'MU', 'MUS', 480, 230, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('137', 'Mayotte', 'MAYOTTE', 'YT', NULL, NULL, 269, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('138', 'Mexico', 'MEXICO', 'MX', 'MEX', 484, 52, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('139', 'Micronesia, Federated States of', 'MICRONESIA, FEDERATED STATES OF', 'FM', 'FSM', 583, 691, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('14', 'Austria', 'AUSTRIA', 'AT', 'AUT', 40, 43, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('140', 'Moldova, Republic of', 'MOLDOVA, REPUBLIC OF', 'MD', 'MDA', 498, 373, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('141', 'Monaco', 'MONACO', 'MC', 'MCO', 492, 377, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('142', 'Mongolia', 'MONGOLIA', 'MN', 'MNG', 496, 976, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('143', 'Montserrat', 'MONTSERRAT', 'MS', 'MSR', 500, 1664, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('144', 'Morocco', 'MOROCCO', 'MA', 'MAR', 504, 212, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('145', 'Mozambique', 'MOZAMBIQUE', 'MZ', 'MOZ', 508, 258, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('146', 'Myanmar', 'MYANMAR', 'MM', 'MMR', 104, 95, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('147', 'Namibia', 'NAMIBIA', 'NA', 'NAM', 516, 264, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('148', 'Nauru', 'NAURU', 'NR', 'NRU', 520, 674, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('149', 'Nepal', 'NEPAL', 'NP', 'NPL', 524, 977, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('15', 'Azerbaijan', 'AZERBAIJAN', 'AZ', 'AZE', 31, 994, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('150', 'Netherlands', 'NETHERLANDS', 'NL', 'NLD', 528, 31, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('151', 'Netherlands Antilles', 'NETHERLANDS ANTILLES', 'AN', 'ANT', 530, 599, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('152', 'New Caledonia', 'NEW CALEDONIA', 'NC', 'NCL', 540, 687, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('153', 'New Zealand', 'NEW ZEALAND', 'NZ', 'NZL', 554, 64, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('154', 'Nicaragua', 'NICARAGUA', 'NI', 'NIC', 558, 505, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('155', 'Niger', 'NIGER', 'NE', 'NER', 562, 227, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('156', 'Nigeria', 'NIGERIA', 'NG', 'NGA', 566, 234, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('157', 'Niue', 'NIUE', 'NU', 'NIU', 570, 683, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('158', 'Norfolk Island', 'NORFOLK ISLAND', 'NF', 'NFK', 574, 672, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('159', 'Northern Mariana Islands', 'NORTHERN MARIANA ISLANDS', 'MP', 'MNP', 580, 1670, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('16', 'Bahamas', 'BAHAMAS', 'BS', 'BHS', 44, 1242, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('160', 'Norway', 'NORWAY', 'NO', 'NOR', 578, 47, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('161', 'Oman', 'OMAN', 'OM', 'OMN', 512, 968, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('162', 'Pakistan', 'PAKISTAN', 'PK', 'PAK', 586, 92, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('163', 'Palau', 'PALAU', 'PW', 'PLW', 585, 680, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('164', 'Palestinian Territory, Occupied', 'PALESTINIAN TERRITORY, OCCUPIED', 'PS', NULL, NULL, 970, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('165', 'Panama', 'PANAMA', 'PA', 'PAN', 591, 507, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('166', 'Papua New Guinea', 'PAPUA NEW GUINEA', 'PG', 'PNG', 598, 675, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('167', 'Paraguay', 'PARAGUAY', 'PY', 'PRY', 600, 595, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('168', 'Peru', 'PERU', 'PE', 'PER', 604, 51, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('169', 'Philippines', 'PHILIPPINES', 'PH', 'PHL', 608, 63, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('17', 'Bahrain', 'BAHRAIN', 'BH', 'BHR', 48, 973, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('170', 'Pitcairn', 'PITCAIRN', 'PN', 'PCN', 612, 0, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('171', 'Poland', 'POLAND', 'PL', 'POL', 616, 48, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('172', 'Portugal', 'PORTUGAL', 'PT', 'PRT', 620, 351, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('173', 'Puerto Rico', 'PUERTO RICO', 'PR', 'PRI', 630, 1787, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('174', 'Qatar', 'QATAR', 'QA', 'QAT', 634, 974, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('175', 'Reunion', 'REUNION', 'RE', 'REU', 638, 262, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('176', 'Romania', 'ROMANIA', 'RO', 'ROM', 642, 40, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('177', 'Russian Federation', 'RUSSIAN FEDERATION', 'RU', 'RUS', 643, 70, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('178', 'Rwanda', 'RWANDA', 'RW', 'RWA', 646, 250, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('179', 'Saint Helena', 'SAINT HELENA', 'SH', 'SHN', 654, 290, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('18', 'Bangladesh', 'BANGLADESH', 'BD', 'BGD', 50, 880, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('180', 'Saint Kitts and Nevis', 'SAINT KITTS AND NEVIS', 'KN', 'KNA', 659, 1869, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('181', 'Saint Lucia', 'SAINT LUCIA', 'LC', 'LCA', 662, 1758, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('182', 'Saint Pierre and Miquelon', 'SAINT PIERRE AND MIQUELON', 'PM', 'SPM', 666, 508, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('183', 'Saint Vincent and the Grenadines', 'SAINT VINCENT AND THE GRENADINES', 'VC', 'VCT', 670, 1784, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('184', 'Samoa', 'SAMOA', 'WS', 'WSM', 882, 684, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('185', 'San Marino', 'SAN MARINO', 'SM', 'SMR', 674, 378, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('186', 'Sao Tome and Principe', 'SAO TOME AND PRINCIPE', 'ST', 'STP', 678, 239, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('187', 'Saudi Arabia', 'SAUDI ARABIA', 'SA', 'SAU', 682, 966, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('188', 'Senegal', 'SENEGAL', 'SN', 'SEN', 686, 221, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('189', 'Serbia and Montenegro', 'SERBIA AND MONTENEGRO', 'CS', NULL, NULL, 381, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('19', 'Barbados', 'BARBADOS', 'BB', 'BRB', 52, 1246, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('190', 'Seychelles', 'SEYCHELLES', 'SC', 'SYC', 690, 248, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('191', 'Sierra Leone', 'SIERRA LEONE', 'SL', 'SLE', 694, 232, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('192', 'Singapore', 'SINGAPORE', 'SG', 'SGP', 702, 65, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('193', 'Slovakia', 'SLOVAKIA', 'SK', 'SVK', 703, 421, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('194', 'Slovenia', 'SLOVENIA', 'SI', 'SVN', 705, 386, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('195', 'Solomon Islands', 'SOLOMON ISLANDS', 'SB', 'SLB', 90, 677, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('196', 'Somalia', 'SOMALIA', 'SO', 'SOM', 706, 252, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('197', 'South Africa', 'SOUTH AFRICA', 'ZA', 'ZAF', 710, 27, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('198', 'South Georgia and the South Sandwich Islands', 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS', 'GS', NULL, NULL, 0, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('199', 'Spain', 'SPAIN', 'ES', 'ESP', 724, 34, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('2', 'Albania', 'ALBANIA', 'AL', 'ALB', 8, 355, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('20', 'Belarus', 'BELARUS', 'BY', 'BLR', 112, 375, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('200', 'Sri Lanka', 'SRI LANKA', 'LK', 'LKA', 144, 94, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('201', 'Sudan', 'SUDAN', 'SD', 'SDN', 736, 249, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('202', 'Suriname', 'SURINAME', 'SR', 'SUR', 740, 597, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('203', 'Svalbard and Jan Mayen', 'SVALBARD AND JAN MAYEN', 'SJ', 'SJM', 744, 47, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('204', 'Swaziland', 'SWAZILAND', 'SZ', 'SWZ', 748, 268, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('205', 'Sweden', 'SWEDEN', 'SE', 'SWE', 752, 46, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('206', 'Switzerland', 'SWITZERLAND', 'CH', 'CHE', 756, 41, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('207', 'Syrian Arab Republic', 'SYRIAN ARAB REPUBLIC', 'SY', 'SYR', 760, 963, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('208', 'Taiwan, Province of China', 'TAIWAN, PROVINCE OF CHINA', 'TW', 'TWN', 158, 886, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('209', 'Tajikistan', 'TAJIKISTAN', 'TJ', 'TJK', 762, 992, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('21', 'Belgium', 'BELGIUM', 'BE', 'BEL', 56, 32, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('210', 'Tanzania, United Republic of', 'TANZANIA, UNITED REPUBLIC OF', 'TZ', 'TZA', 834, 255, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('211', 'Thailand', 'THAILAND', 'TH', 'THA', 764, 66, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('212', 'Timor-Leste', 'TIMOR-LESTE', 'TL', NULL, NULL, 670, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('213', 'Togo', 'TOGO', 'TG', 'TGO', 768, 228, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('214', 'Tokelau', 'TOKELAU', 'TK', 'TKL', 772, 690, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('215', 'Tonga', 'TONGA', 'TO', 'TON', 776, 676, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('216', 'Trinidad and Tobago', 'TRINIDAD AND TOBAGO', 'TT', 'TTO', 780, 1868, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('217', 'Tunisia', 'TUNISIA', 'TN', 'TUN', 788, 216, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('218', 'Turkey', 'TURKEY', 'TR', 'TUR', 792, 90, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('219', 'Turkmenistan', 'TURKMENISTAN', 'TM', 'TKM', 795, 7370, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('22', 'Belize', 'BELIZE', 'BZ', 'BLZ', 84, 501, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('220', 'Turks and Caicos Islands', 'TURKS AND CAICOS ISLANDS', 'TC', 'TCA', 796, 1649, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('221', 'Tuvalu', 'TUVALU', 'TV', 'TUV', 798, 688, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('222', 'Uganda', 'UGANDA', 'UG', 'UGA', 800, 256, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('223', 'Ukraine', 'UKRAINE', 'UA', 'UKR', 804, 380, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('224', 'United Arab Emirates', 'UNITED ARAB EMIRATES', 'AE', 'ARE', 784, 971, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('225', 'United Kingdom', 'UNITED KINGDOM', 'GB', 'GBR', 826, 44, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('226', 'United States', 'UNITED STATES', 'US', 'USA', 840, 1, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('227', 'United States Minor Outlying Islands', 'UNITED STATES MINOR OUTLYING ISLANDS', 'UM', NULL, NULL, 1, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('228', 'Uruguay', 'URUGUAY', 'UY', 'URY', 858, 598, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('229', 'Uzbekistan', 'UZBEKISTAN', 'UZ', 'UZB', 860, 998, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('23', 'Benin', 'BENIN', 'BJ', 'BEN', 204, 229, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('230', 'Vanuatu', 'VANUATU', 'VU', 'VUT', 548, 678, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('231', 'Venezuela', 'VENEZUELA', 'VE', 'VEN', 862, 58, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('232', 'Viet Nam', 'VIET NAM', 'VN', 'VNM', 704, 84, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('233', 'Virgin Islands, British', 'VIRGIN ISLANDS, BRITISH', 'VG', 'VGB', 92, 1284, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('234', 'Virgin Islands, U.s.', 'VIRGIN ISLANDS, U.S.', 'VI', 'VIR', 850, 1340, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('235', 'Wallis and Futuna', 'WALLIS AND FUTUNA', 'WF', 'WLF', 876, 681, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('236', 'Western Sahara', 'WESTERN SAHARA', 'EH', 'ESH', 732, 212, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('237', 'Yemen', 'YEMEN', 'YE', 'YEM', 887, 967, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('238', 'Zambia', 'ZAMBIA', 'ZM', 'ZMB', 894, 260, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('239', 'Zimbabwe', 'ZIMBABWE', 'ZW', 'ZWE', 716, 263, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('24', 'Bermuda', 'BERMUDA', 'BM', 'BMU', 60, 1441, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('25', 'Bhutan', 'BHUTAN', 'BT', 'BTN', 64, 975, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('26', 'Bolivia', 'BOLIVIA', 'BO', 'BOL', 68, 591, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('27', 'Bosnia and Herzegovina', 'BOSNIA AND HERZEGOVINA', 'BA', 'BIH', 70, 387, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('28', 'Botswana', 'BOTSWANA', 'BW', 'BWA', 72, 267, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('29', 'Bouvet Island', 'BOUVET ISLAND', 'BV', NULL, NULL, 0, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('3', 'Algeria', 'ALGERIA', 'DZ', 'DZA', 12, 213, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('30', 'Brazil', 'BRAZIL', 'BR', 'BRA', 76, 55, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('31', 'British Indian Ocean Territory', 'BRITISH INDIAN OCEAN TERRITORY', 'IO', NULL, NULL, 246, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('32', 'Brunei Darussalam', 'BRUNEI DARUSSALAM', 'BN', 'BRN', 96, 673, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('33', 'Bulgaria', 'BULGARIA', 'BG', 'BGR', 100, 359, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('34', 'Burkina Faso', 'BURKINA FASO', 'BF', 'BFA', 854, 226, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('35', 'Burundi', 'BURUNDI', 'BI', 'BDI', 108, 257, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('36', 'Cambodia', 'CAMBODIA', 'KH', 'KHM', 116, 855, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('37', 'Cameroon', 'CAMEROON', 'CM', 'CMR', 120, 237, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('38', 'Canada', 'CANADA', 'CA', 'CAN', 124, 1, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('39', 'Cape Verde', 'CAPE VERDE', 'CV', 'CPV', 132, 238, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('4', 'American Samoa', 'AMERICAN SAMOA', 'AS', 'ASM', 16, 1684, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('40', 'Cayman Islands', 'CAYMAN ISLANDS', 'KY', 'CYM', 136, 1345, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('41', 'Central African Republic', 'CENTRAL AFRICAN REPUBLIC', 'CF', 'CAF', 140, 236, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('42', 'Chad', 'CHAD', 'TD', 'TCD', 148, 235, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('43', 'Chile', 'CHILE', 'CL', 'CHL', 152, 56, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('44', 'China', 'CHINA', 'CN', 'CHN', 156, 86, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('45', 'Christmas Island', 'CHRISTMAS ISLAND', 'CX', NULL, NULL, 61, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('46', 'Cocos (Keeling) Islands', 'COCOS (KEELING) ISLANDS', 'CC', NULL, NULL, 672, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('47', 'Colombia', 'COLOMBIA', 'CO', 'COL', 170, 57, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('48', 'Comoros', 'COMOROS', 'KM', 'COM', 174, 269, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('49', 'Congo', 'CONGO', 'CG', 'COG', 178, 242, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('5', 'Andorra', 'ANDORRA', 'AD', 'AND', 20, 376, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('50', 'Congo, the Democratic Republic of the', 'CONGO, THE DEMOCRATIC REPUBLIC OF THE', 'CD', 'COD', 180, 242, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('51', 'Cook Islands', 'COOK ISLANDS', 'CK', 'COK', 184, 682, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('52', 'Costa Rica', 'COSTA RICA', 'CR', 'CRI', 188, 506, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('53', 'Cote D\'Ivoire', 'COTE D\'IVOIRE', 'CI', 'CIV', 384, 225, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('54', 'Croatia', 'CROATIA', 'HR', 'HRV', 191, 385, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('55', 'Cuba', 'CUBA', 'CU', 'CUB', 192, 53, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('56', 'Cyprus', 'CYPRUS', 'CY', 'CYP', 196, 357, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('57', 'Czech Republic', 'CZECH REPUBLIC', 'CZ', 'CZE', 203, 420, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('58', 'Denmark', 'DENMARK', 'DK', 'DNK', 208, 45, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('59', 'Djibouti', 'DJIBOUTI', 'DJ', 'DJI', 262, 253, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('6', 'Angola', 'ANGOLA', 'AO', 'AGO', 24, 244, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('60', 'Dominica', 'DOMINICA', 'DM', 'DMA', 212, 1767, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('61', 'Dominican Republic', 'DOMINICAN REPUBLIC', 'DO', 'DOM', 214, 1809, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('62', 'Ecuador', 'ECUADOR', 'EC', 'ECU', 218, 593, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('63', 'Egypt', 'EGYPT', 'EG', 'EGY', 818, 20, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('64', 'El Salvador', 'EL SALVADOR', 'SV', 'SLV', 222, 503, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('65', 'Equatorial Guinea', 'EQUATORIAL GUINEA', 'GQ', 'GNQ', 226, 240, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('66', 'Eritrea', 'ERITREA', 'ER', 'ERI', 232, 291, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('67', 'Estonia', 'ESTONIA', 'EE', 'EST', 233, 372, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('68', 'Ethiopia', 'ETHIOPIA', 'ET', 'ETH', 231, 251, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('69', 'Falkland Islands (Malvinas)', 'FALKLAND ISLANDS (MALVINAS)', 'FK', 'FLK', 238, 500, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('7', 'Anguilla', 'ANGUILLA', 'AI', 'AIA', 660, 1264, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('70', 'Faroe Islands', 'FAROE ISLANDS', 'FO', 'FRO', 234, 298, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('71', 'Fiji', 'FIJI', 'FJ', 'FJI', 242, 679, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('72', 'Finland', 'FINLAND', 'FI', 'FIN', 246, 358, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('73', 'France', 'FRANCE', 'FR', 'FRA', 250, 33, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('74', 'French Guiana', 'FRENCH GUIANA', 'GF', 'GUF', 254, 594, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('75', 'French Polynesia', 'FRENCH POLYNESIA', 'PF', 'PYF', 258, 689, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('76', 'French Southern Territories', 'FRENCH SOUTHERN TERRITORIES', 'TF', NULL, NULL, 0, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('77', 'Gabon', 'GABON', 'GA', 'GAB', 266, 241, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('78', 'Gambia', 'GAMBIA', 'GM', 'GMB', 270, 220, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('79', 'Georgia', 'GEORGIA', 'GE', 'GEO', 268, 995, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('8', 'Antarctica', 'ANTARCTICA', 'AQ', NULL, NULL, 0, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('80', 'Germany', 'GERMANY', 'DE', 'DEU', 276, 49, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('81', 'Ghana', 'GHANA', 'GH', 'GHA', 288, 233, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('82', 'Gibraltar', 'GIBRALTAR', 'GI', 'GIB', 292, 350, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('83', 'Greece', 'GREECE', 'GR', 'GRC', 300, 30, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('84', 'Greenland', 'GREENLAND', 'GL', 'GRL', 304, 299, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('85', 'Grenada', 'GRENADA', 'GD', 'GRD', 308, 1473, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('86', 'Guadeloupe', 'GUADELOUPE', 'GP', 'GLP', 312, 590, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('87', 'Guam', 'GUAM', 'GU', 'GUM', 316, 1671, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('88', 'Guatemala', 'GUATEMALA', 'GT', 'GTM', 320, 502, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('89', 'Guinea', 'GUINEA', 'GN', 'GIN', 324, 224, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('9', 'Antigua and Barbuda', 'ANTIGUA AND BARBUDA', 'AG', 'ATG', 28, 1268, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('90', 'Guinea-Bissau', 'GUINEA-BISSAU', 'GW', 'GNB', 624, 245, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('91', 'Guyana', 'GUYANA', 'GY', 'GUY', 328, 592, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('92', 'Haiti', 'HAITI', 'HT', 'HTI', 332, 509, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('93', 'Heard Island and Mcdonald Islands', 'HEARD ISLAND AND MCDONALD ISLANDS', 'HM', NULL, NULL, 0, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('94', 'Holy See (Vatican City State)', 'HOLY SEE (VATICAN CITY STATE)', 'VA', 'VAT', 336, 39, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('95', 'Honduras', 'HONDURAS', 'HN', 'HND', 340, 504, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('96', 'Hong Kong', 'HONG KONG', 'HK', 'HKG', 344, 852, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('97', 'Hungary', 'HUNGARY', 'HU', 'HUN', 348, 36, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('98', 'Iceland', 'ICELAND', 'IS', 'ISL', 352, 354, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
INSERT INTO `ref_negara` VALUES ('99', 'India', 'INDIA', 'IN', 'IND', 356, 91, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');

SET FOREIGN_KEY_CHECKS = 1;
