/*
 Navicat Premium Dump SQL

 Source Server         : Local Mysql XAMPP
 Source Server Type    : MySQL
 Source Server Version : 100432 (10.4.32-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : deskbooking_bi

 Target Server Type    : MySQL
 Target Server Version : 100432 (10.4.32-MariaDB)
 File Encoding         : 65001

 Date: 01/09/2026 08:00:54
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for access_channel
-- ----------------------------
DROP TABLE IF EXISTS `access_channel`;
CREATE TABLE `access_channel`  (
  `id` int UNSIGNED NOT NULL,
  `channel` int NULL DEFAULT NULL,
  `is_deleted` tinyint(1) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of access_channel
-- ----------------------------
INSERT INTO `access_channel` VALUES (0, 0, 0);
INSERT INTO `access_channel` VALUES (1, 1, 0);
INSERT INTO `access_channel` VALUES (2, 2, 0);
INSERT INTO `access_channel` VALUES (3, 3, 0);
INSERT INTO `access_channel` VALUES (4, 4, 0);
INSERT INTO `access_channel` VALUES (5, 5, 0);
INSERT INTO `access_channel` VALUES (6, 6, 0);
INSERT INTO `access_channel` VALUES (7, 7, 0);
INSERT INTO `access_channel` VALUES (8, 8, 0);

-- ----------------------------
-- Table structure for access_control
-- ----------------------------
DROP TABLE IF EXISTS `access_control`;
CREATE TABLE `access_control`  (
  `id` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `ip_controller` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0.0.0.0',
  `access_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `channel` int NULL DEFAULT NULL,
  `controller_list` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `room_controller_falco` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `delay` int NULL DEFAULT 3,
  `model_controller` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'reader' COMMENT 'reader/face/cctv',
  `created_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `updated_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `is_deleted` int NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of access_control
-- ----------------------------

-- ----------------------------
-- Table structure for access_controller_falco
-- ----------------------------
DROP TABLE IF EXISTS `access_controller_falco`;
CREATE TABLE `access_controller_falco`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `access_id` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `group_access` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `unit_no` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `falco_ip` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_deleted` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of access_controller_falco
-- ----------------------------

-- ----------------------------
-- Table structure for access_controller_type
-- ----------------------------
DROP TABLE IF EXISTS `access_controller_type`;
CREATE TABLE `access_controller_type`  (
  `id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_deleted` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of access_controller_type
-- ----------------------------
INSERT INTO `access_controller_type` VALUES ('custid', 'Bio Access', 0);
INSERT INTO `access_controller_type` VALUES ('entrypassid', 'Entrypass', 0);
INSERT INTO `access_controller_type` VALUES ('falcoid', 'Falco Controller', 1);

-- ----------------------------
-- Table structure for access_integrated
-- ----------------------------
DROP TABLE IF EXISTS `access_integrated`;
CREATE TABLE `access_integrated`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `access_id` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `room_id` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `is_deleted` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 90 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of access_integrated
-- ----------------------------
INSERT INTO `access_integrated` VALUES (56, '6032519874', '324522', 0);
INSERT INTO `access_integrated` VALUES (57, '6032519874', '352', 0);
INSERT INTO `access_integrated` VALUES (58, '6032519874', '6834709521', 0);
INSERT INTO `access_integrated` VALUES (61, '1', '34', 0);
INSERT INTO `access_integrated` VALUES (62, '8073952461', '6834709521', 0);
INSERT INTO `access_integrated` VALUES (69, '6482079351', '062394', 0);
INSERT INTO `access_integrated` VALUES (71, '7520916483', '294650', 0);
INSERT INTO `access_integrated` VALUES (72, '7890364251', '638742', 0);
INSERT INTO `access_integrated` VALUES (73, '4619705328', '683470', 0);
INSERT INTO `access_integrated` VALUES (74, '4789205163', '683460', 0);
INSERT INTO `access_integrated` VALUES (75, '5421760983', '284061', 0);
INSERT INTO `access_integrated` VALUES (76, '2749016835', '854021', 0);
INSERT INTO `access_integrated` VALUES (77, '5871296430', '614392', 0);
INSERT INTO `access_integrated` VALUES (78, '0218697453', '743690', 0);
INSERT INTO `access_integrated` VALUES (80, '8120743569', '692301', 0);
INSERT INTO `access_integrated` VALUES (82, '6394782051', '4871926053', 0);
INSERT INTO `access_integrated` VALUES (85, '1723954068', '683460', 0);
INSERT INTO `access_integrated` VALUES (86, '1723954068', '638742', 0);
INSERT INTO `access_integrated` VALUES (87, '1723954068', '406215', 0);
INSERT INTO `access_integrated` VALUES (88, '1723954068', '943571', 0);
INSERT INTO `access_integrated` VALUES (89, '1723954068', '4718532960', 0);

-- ----------------------------
-- Table structure for activity_log
-- ----------------------------
DROP TABLE IF EXISTS `activity_log`;
CREATE TABLE `activity_log`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `event_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `event_time` datetime(3) NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `category` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `severity` enum('info','success','warning','error') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'info',
  `actor_nik` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `booking_id` bigint UNSIGNED NULL DEFAULT NULL,
  `room_id` int UNSIGNED NULL DEFAULT NULL,
  `desk_id` int UNSIGNED NULL DEFAULT NULL,
  `previous_status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `current_status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `source` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `visibility` enum('private','admin','all') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'private',
  `owner_nik` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` timestamp(3) NOT NULL DEFAULT current_timestamp(3),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_activity_event_id`(`event_id` ASC) USING BTREE,
  INDEX `idx_event_time`(`event_time` ASC) USING BTREE,
  INDEX `idx_code`(`code` ASC) USING BTREE,
  INDEX `idx_category`(`category` ASC) USING BTREE,
  INDEX `idx_actor_user`(`actor_nik` ASC) USING BTREE,
  INDEX `idx_owner_user`(`owner_nik` ASC) USING BTREE,
  INDEX `idx_booking`(`booking_id` ASC) USING BTREE,
  INDEX `idx_room`(`room_id` ASC) USING BTREE,
  INDEX `idx_desk`(`desk_id` ASC) USING BTREE,
  INDEX `idx_visibility`(`visibility` ASC) USING BTREE,
  INDEX `idx_monitor`(`event_time` ASC, `category` ASC, `code` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of activity_log
-- ----------------------------
INSERT INTO `activity_log` VALUES (1, 'evt_6a8fe80fee4b12.754799787397', '2026-08-27 14:32:31.000', 'SYSTEM_CONFIG_UPDATED', 'System Config Updated', 'Admin updated company profile: Bank Indonesia', 'SYSTEM', 'info', 'System', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'private', NULL, '2026-08-27 14:32:31.000');
INSERT INTO `activity_log` VALUES (2, 'evt_6a90068a8d8ac6.381332937415', '2026-08-27 16:42:34.000', 'BOOKING_CREATED', 'Booking Created', 'Booking created for Desk No.42', 'BOOKING', 'success', 'System', 6451927038, 934052, 4294967295, NULL, NULL, NULL, NULL, '{\"startTime\":\"2026-08-27 16:42:00\",\"endTime\":\"2026-08-27 16:43:00\"}', 'private', '20241220024501', '2026-08-27 16:42:34.000');
INSERT INTO `activity_log` VALUES (3, 'evt_6a91397e080312.442686956842', '2026-08-28 14:32:14.000', 'ROOM_CREATED', 'Room Created', 'Admin created desk room: IRS LT2', 'MASTER', 'success', 'System', NULL, 354196, NULL, NULL, NULL, NULL, NULL, NULL, 'private', NULL, '2026-08-28 14:32:14.000');
INSERT INTO `activity_log` VALUES (4, 'evt_6a914038bef096.055389261284', '2026-08-28 15:00:56.000', 'ROOM_UPDATED', 'Room Updated', 'Admin updated desk room: IRS LT2', 'MASTER', 'info', 'System', NULL, 354196, NULL, NULL, NULL, NULL, NULL, NULL, 'private', NULL, '2026-08-28 15:00:56.000');
INSERT INTO `activity_log` VALUES (5, 'evt_6a915eda7ed013.356143281598', '2026-08-28 17:11:38.000', 'BOOKING_CREATED', 'Booking Created', 'Booking created for Desk No.42', 'BOOKING', 'success', 'System', 3908417526, 934052, 4294967295, NULL, NULL, NULL, NULL, '{\"startTime\":\"2026-08-28 17:11:00\",\"endTime\":\"2026-08-28 17:12:00\"}', 'private', 'admin', '2026-08-28 17:11:38.000');

-- ----------------------------
-- Table structure for alarm_integration
-- ----------------------------
DROP TABLE IF EXISTS `alarm_integration`;
CREATE TABLE `alarm_integration`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `status_integration` int NULL DEFAULT 0 COMMENT '1 = connected | 0 disconnected ',
  `active` int NULL DEFAULT 0,
  `updated_at` datetime NULL DEFAULT NULL,
  `updated_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `url_auth` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `url_feedback` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `param_auth` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `param_feed` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `is_deleted` int NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of alarm_integration
-- ----------------------------
INSERT INTO `alarm_integration` VALUES (1, 0, 1, '2023-07-03 15:34:14', '', '', '', 'player', '123', '/login/integration', '/integration/alarm/redirect', '27|JAazaWEDmZF0ZkZOqccHDJVIG6zUVJiOPrVBaQ0F', 0);

-- ----------------------------
-- Table structure for alocation
-- ----------------------------
DROP TABLE IF EXISTS `alocation`;
CREATE TABLE `alocation`  (
  `_generate` int NOT NULL AUTO_INCREMENT,
  `id` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `department_code` varchar(43) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `invoice_type` int NULL DEFAULT 0,
  `invoice_status` int NULL DEFAULT 0,
  `created_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `updated_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `is_permanent` int NOT NULL,
  `is_deleted` int NULL DEFAULT NULL,
  `show_in_invitation` int NULL DEFAULT 1,
  PRIMARY KEY (`_generate`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of alocation
-- ----------------------------
INSERT INTO `alocation` VALUES (1, '10283', '10283', 'Divisi RnD', '1', NULL, 0, 'admin', 'admin', '2020-07-15 22:18:00', '2024-12-20 02:41:29', 0, 0, 1);
INSERT INTO `alocation` VALUES (2, '20201210132136j8Ip', '123456', 'Pantry', '', NULL, 1, 'admin', 'admin', '2020-12-10 13:21:36', '2020-12-10 13:21:51', 0, 0, 1);
INSERT INTO `alocation` VALUES (19, '20250109162715k6vK', '10024', 'Tamu Sinergi', '12', 0, 0, 'admin', '', '2025-01-09 16:27:15', NULL, 0, 0, 1);
INSERT INTO `alocation` VALUES (20, '20250124145356hein', '099', 'Divisi Training', '1', 0, 0, 'admin', '', '2025-01-24 14:53:56', NULL, 0, 0, 1);

-- ----------------------------
-- Table structure for alocation_matrix
-- ----------------------------
DROP TABLE IF EXISTS `alocation_matrix`;
CREATE TABLE `alocation_matrix`  (
  `_generate` int NOT NULL AUTO_INCREMENT,
  `alocation_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `nik` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  UNIQUE INDEX `_generate`(`_generate` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 102 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of alocation_matrix
-- ----------------------------
INSERT INTO `alocation_matrix` VALUES (14, '10283', 'fahrul');
INSERT INTO `alocation_matrix` VALUES (15, '10283', 'i49520200716115932');
INSERT INTO `alocation_matrix` VALUES (16, '20201210132244BF09', 'i01520201210133412');
INSERT INTO `alocation_matrix` VALUES (17, '10283', 'i31820201210134715');
INSERT INTO `alocation_matrix` VALUES (18, '10283', 'i14620211115111824');
INSERT INTO `alocation_matrix` VALUES (20, '10283', 'i41920211115112804');
INSERT INTO `alocation_matrix` VALUES (21, '10283', 'i96220211115112828');
INSERT INTO `alocation_matrix` VALUES (22, '10283', 'i72920211115112854');
INSERT INTO `alocation_matrix` VALUES (23, '10283', 'i34920220117140523');
INSERT INTO `alocation_matrix` VALUES (24, '202201181059032OeQ', 'i69420220118110242');
INSERT INTO `alocation_matrix` VALUES (25, '202201181059032OeQ', 'i54120220118110330');
INSERT INTO `alocation_matrix` VALUES (26, '202201181059032OeQ', 'i02320220118110427');
INSERT INTO `alocation_matrix` VALUES (27, '202201181059174jWY', 'i89120220118110539');
INSERT INTO `alocation_matrix` VALUES (29, '202201181059174jWY', 'i03920220118110632');
INSERT INTO `alocation_matrix` VALUES (30, '202201181059174jWY', 'i70820220118110700');
INSERT INTO `alocation_matrix` VALUES (31, '202201181059174jWY', 'i89220220118110854');
INSERT INTO `alocation_matrix` VALUES (32, '202201181059174jWY', 'i75920220118110932');
INSERT INTO `alocation_matrix` VALUES (33, '202201181059174jWY', 'i76820220118111002');
INSERT INTO `alocation_matrix` VALUES (34, '20220118105932qjxu', 'i31620220118111036');
INSERT INTO `alocation_matrix` VALUES (35, '202201181059174jWY', 'i05420220118111110');
INSERT INTO `alocation_matrix` VALUES (36, '202201181059174jWY', 'i84120220118111154');
INSERT INTO `alocation_matrix` VALUES (37, '202201181059174jWY', 'i42920220118111221');
INSERT INTO `alocation_matrix` VALUES (38, '202201181059174jWY', 'i83920220118111253');
INSERT INTO `alocation_matrix` VALUES (39, '202201181059174jWY', 'i98520220118111318');
INSERT INTO `alocation_matrix` VALUES (40, '202201181059174jWY', 'i87520220118111319');
INSERT INTO `alocation_matrix` VALUES (41, '20220118105947iuEl', 'i17220220118111345');
INSERT INTO `alocation_matrix` VALUES (42, '20220118105947iuEl', 'i89620220118111458');
INSERT INTO `alocation_matrix` VALUES (43, '20220118105947iuEl', 'i82020220118111527');
INSERT INTO `alocation_matrix` VALUES (44, '202201181059174jWY', 'i49020220118112823');
INSERT INTO `alocation_matrix` VALUES (45, '10283', 'i34820220209110728');
INSERT INTO `alocation_matrix` VALUES (46, '10283', 'i27520220523115852');
INSERT INTO `alocation_matrix` VALUES (47, '10283', 'i28420220523115918');
INSERT INTO `alocation_matrix` VALUES (48, '20220525094235o8PM', 'i31820220525094316');
INSERT INTO `alocation_matrix` VALUES (49, '20220525094014p67U', 'i74320220525094358');
INSERT INTO `alocation_matrix` VALUES (50, '202205191131459Sf1', 'i31520220525094454');
INSERT INTO `alocation_matrix` VALUES (51, '20220525094014p67U', 'i41220220525094531');
INSERT INTO `alocation_matrix` VALUES (52, '202201181059032OeQ', 'i15820220525121538');
INSERT INTO `alocation_matrix` VALUES (53, '20220519113202mHVE', 'i59320220530114528');
INSERT INTO `alocation_matrix` VALUES (54, '20220519113055j0QJ', 'i29820220530133007');
INSERT INTO `alocation_matrix` VALUES (55, '20220525094029HhV2', 'i10420220531114432');
INSERT INTO `alocation_matrix` VALUES (56, '20220525094014p67U', 'i49720220531114509');
INSERT INTO `alocation_matrix` VALUES (57, '202201181059174jWY', 'i50920220531114800');
INSERT INTO `alocation_matrix` VALUES (58, '20220519113055j0QJ', 'i35020220602145137');
INSERT INTO `alocation_matrix` VALUES (59, '20220519113123A5rC', 'i61320220603093958');
INSERT INTO `alocation_matrix` VALUES (60, '20220519113202mHVE', 'i48320220607095459');
INSERT INTO `alocation_matrix` VALUES (61, '20220118105932qjxu', 'i78520220607105202');
INSERT INTO `alocation_matrix` VALUES (62, '20220607211249m2kI', 'i72320220607211409');
INSERT INTO `alocation_matrix` VALUES (63, '20220607211314LmQs', 'i27820220607211529');
INSERT INTO `alocation_matrix` VALUES (64, '20220519113123A5rC', 'i27520220608174045');
INSERT INTO `alocation_matrix` VALUES (65, '20220608202414ldBD', 'i92720220608202455');
INSERT INTO `alocation_matrix` VALUES (66, '20220609102427JqlW', 'i08220220609102504');
INSERT INTO `alocation_matrix` VALUES (67, '20220519113055j0QJ', 'i17920220609114852');
INSERT INTO `alocation_matrix` VALUES (68, '20220519113202mHVE', 'i65420220609132523');
INSERT INTO `alocation_matrix` VALUES (69, '20220519113202mHVE', 'i59820220610191047');
INSERT INTO `alocation_matrix` VALUES (70, '20220118105947iuEl', 'i21520220118110606');
INSERT INTO `alocation_matrix` VALUES (71, '20220519113055j0QJ', 'i79220220614162801');
INSERT INTO `alocation_matrix` VALUES (72, '20220519113055j0QJ', 'i20820220614162831');
INSERT INTO `alocation_matrix` VALUES (73, '20220519113202mHVE', 'i86320220615161414');
INSERT INTO `alocation_matrix` VALUES (74, '20220519113202mHVE', 'i60420220615161541');
INSERT INTO `alocation_matrix` VALUES (75, '20220525094014p67U', 'i68720220615161945');
INSERT INTO `alocation_matrix` VALUES (76, '202205191131459Sf1', 'i14720220616101344');
INSERT INTO `alocation_matrix` VALUES (77, '202205191131459Sf1', 'i42120220621111043');
INSERT INTO `alocation_matrix` VALUES (78, '20220519113123A5rC', 'i89120220627145759');
INSERT INTO `alocation_matrix` VALUES (79, '20220118105932qjxu', 'i04820220812180441');
INSERT INTO `alocation_matrix` VALUES (80, '20220525094014p67U', 'i13420220822164618');
INSERT INTO `alocation_matrix` VALUES (81, '10283', 'i38120220909091943');
INSERT INTO `alocation_matrix` VALUES (82, '10283', '20230203130023');
INSERT INTO `alocation_matrix` VALUES (83, '10283', '20230203130055');
INSERT INTO `alocation_matrix` VALUES (84, '10283', '20230206171324');
INSERT INTO `alocation_matrix` VALUES (85, '10283', '20231017110503');
INSERT INTO `alocation_matrix` VALUES (86, NULL, 'i32420211115111902');
INSERT INTO `alocation_matrix` VALUES (88, '10283', 'i32420211115111902');
INSERT INTO `alocation_matrix` VALUES (89, '10283', '20231107121320');
INSERT INTO `alocation_matrix` VALUES (90, '10283', '20231128160531');
INSERT INTO `alocation_matrix` VALUES (91, '10283', 'admin');
INSERT INTO `alocation_matrix` VALUES (92, '10283', '20241220024501');
INSERT INTO `alocation_matrix` VALUES (93, '10283', 'EC20250109163011');
INSERT INTO `alocation_matrix` VALUES (94, '20250109162715k6vK', 'EC20250109164141');
INSERT INTO `alocation_matrix` VALUES (95, '20250109162715k6vK', 'EC20250110131102');
INSERT INTO `alocation_matrix` VALUES (96, '20250109162715k6vK', 'EC20250110132409');
INSERT INTO `alocation_matrix` VALUES (97, '20250109162715k6vK', 'EC20250124150641');
INSERT INTO `alocation_matrix` VALUES (98, '20250109162715k6vK', 'EC20251003193126');
INSERT INTO `alocation_matrix` VALUES (99, '20250109162715k6vK', 'EC20251004081530');
INSERT INTO `alocation_matrix` VALUES (100, '20250109162715k6vK', 'EC20251004081605');
INSERT INTO `alocation_matrix` VALUES (101, '20250109162715k6vK', 'EC20251004081648');

-- ----------------------------
-- Table structure for alocation_type
-- ----------------------------
DROP TABLE IF EXISTS `alocation_type`;
CREATE TABLE `alocation_type`  (
  `_generate` int NOT NULL AUTO_INCREMENT,
  `id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `invoice_status` int NULL DEFAULT NULL,
  `created_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `updated_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `is_permanent` int NOT NULL,
  `is_deleted` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `_generate`(`_generate` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of alocation_type
-- ----------------------------
INSERT INTO `alocation_type` VALUES (1, '1', 'Smart Office', 0, NULL, 'admin', NULL, '2024-12-20 02:41:13', 0, 0);
INSERT INTO `alocation_type` VALUES (6, '12', 'BI TAMU', NULL, 'admin', NULL, '2025-01-09 16:26:43', NULL, 0, 0);
INSERT INTO `alocation_type` VALUES (8, '124', 'SINERGI 1', NULL, 'admin', NULL, '2025-01-24 15:10:16', NULL, 0, 0);

-- ----------------------------
-- Table structure for auth_serial
-- ----------------------------
DROP TABLE IF EXISTS `auth_serial`;
CREATE TABLE `auth_serial`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `serial` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_deleted` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of auth_serial
-- ----------------------------
INSERT INTO `auth_serial` VALUES (1, 'NBG1BH6N2DHUXUMIK8R0M5U5U7ZQ8WH5', 0);

-- ----------------------------
-- Table structure for batch_upload
-- ----------------------------
DROP TABLE IF EXISTS `batch_upload`;
CREATE TABLE `batch_upload`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `time` datetime NOT NULL,
  `total_row` int NOT NULL,
  `total_size` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_deleted` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of batch_upload
-- ----------------------------
INSERT INTO `batch_upload` VALUES (6, 'template_employee.xlsx', '2025-01-10 13:16:41', 1, '65364', 0);
INSERT INTO `batch_upload` VALUES (7, 'template_employee.xlsx', '2025-01-10 13:20:21', 1, '85710', 0);

-- ----------------------------
-- Table structure for beacon_floor
-- ----------------------------
DROP TABLE IF EXISTS `beacon_floor`;
CREATE TABLE `beacon_floor`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `building_id` int NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `pixel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `floor_length` double NULL DEFAULT NULL,
  `floor_width` double NULL DEFAULT NULL,
  `meter_per_px` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `meter_per_px2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `plus_width` double NULL DEFAULT NULL,
  `plus_height` double NULL DEFAULT NULL,
  `center_x` int NULL DEFAULT 0,
  `center_y` int NULL DEFAULT 0,
  `created_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `updated_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `is_deleted` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of beacon_floor
-- ----------------------------

-- ----------------------------
-- Table structure for booking
-- ----------------------------
DROP TABLE IF EXISTS `booking`;
CREATE TABLE `booking`  (
  `id` int UNSIGNED NOT NULL,
  `booking_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `booking_id_365` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `booking_id_google` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `booking_devices` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `no_order` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `date` date NOT NULL,
  `room_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `room_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `is_merge` tinyint(1) NULL DEFAULT 0,
  `merge_room` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `merge_room_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `merge_room_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `cost_total_booking` bigint NULL DEFAULT NULL,
  `duration_per_meeting` int NULL DEFAULT NULL,
  `total_duration` int NULL DEFAULT 0,
  `extended_duration` int NULL DEFAULT 0,
  `pic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `alocation_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `alocation_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `canceled_note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `participants` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `external_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `external_link_365` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `external_link_google` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `end_early_meeting` int NOT NULL DEFAULT 0,
  `text_early` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `is_device` int NULL DEFAULT 0,
  `is_meal` tinyint(1) NOT NULL,
  `is_ear` int NOT NULL DEFAULT 0,
  `is_rescheduled` int NOT NULL DEFAULT 0 COMMENT '1 : meeting telah direschedule',
  `is_canceled` int NOT NULL DEFAULT 0,
  `is_expired` int NOT NULL DEFAULT 0,
  `canceled_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `canceled_at` datetime NOT NULL DEFAULT current_timestamp(),
  `expired_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `expired_at` datetime NOT NULL DEFAULT current_timestamp(),
  `rescheduled_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `rescheduled_at` datetime NOT NULL DEFAULT current_timestamp(),
  `early_ended_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `early_ended_at` datetime NOT NULL DEFAULT current_timestamp(),
  `is_alive` int NOT NULL DEFAULT 1,
  `timezone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `created_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `updated_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `is_notif_end_meeting` int NOT NULL DEFAULT 0,
  `is_notif_before_end_meeting` int NOT NULL DEFAULT 0,
  `is_access_trigger` int NULL DEFAULT 0,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `is_config_setting_enable` int NULL DEFAULT 0,
  `is_enable_approval` int NULL DEFAULT 0,
  `is_enable_permission` int NULL DEFAULT 240,
  `is_enable_recurring` int NULL DEFAULT 0,
  `is_enable_checkin` int NULL DEFAULT 0,
  `is_realease_checkin_timeout` int NULL DEFAULT 1,
  `is_released` int NULL DEFAULT 0,
  `is_enable_checkin_count` int NOT NULL DEFAULT 1,
  `category` int NULL DEFAULT NULL,
  `lastModifiedDateTime_365` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `permission_end` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'pic' COMMENT 'pic | attendees & host',
  `permission_checkin` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'pic',
  `release_room_checkin_time` int NULL DEFAULT 10,
  `checkin_count` int NULL DEFAULT 1,
  `is_vip` int NULL DEFAULT 0,
  `is_approve` int NULL DEFAULT 0 COMMENT '0 : belum | 1: approved | 2: not approved',
  `vip_user` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `user_end_meeting` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `user_checkin` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `user_approval` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `user_approval_datetime` datetime NULL DEFAULT NULL,
  `room_meeting_move` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT 'id',
  `room_meeting_old` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `is_moved` int NULL DEFAULT 0,
  `is_moved_agree` int NULL DEFAULT 0 COMMENT '0 : belum | 1: aggree | 2: not aggree',
  `moved_duration` int NULL DEFAULT 5,
  `meeting_end_note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `vip_approve_bypass` int NULL DEFAULT 0,
  `vip_limit_cap_bypass` int NULL DEFAULT 0,
  `vip_lock_room` int NULL DEFAULT 0,
  `vip_force_moved` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `duration_saved_release` int NULL DEFAULT 0,
  `is_cleaning_need` int NULL DEFAULT 0,
  `cleaning_time` int NULL DEFAULT 0,
  `cleaning_start` datetime NULL DEFAULT NULL,
  `cleaning_end` datetime NULL DEFAULT NULL,
  `user_cleaning` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `server_date` date NULL DEFAULT NULL,
  `server_start` datetime NULL DEFAULT NULL,
  `server_end` datetime NULL DEFAULT NULL,
  `booking_type` enum('general','trainingroom','noroom','specialroom') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_private` int NULL DEFAULT 0
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of booking
-- ----------------------------

-- ----------------------------
-- Table structure for booking_alive
-- ----------------------------
DROP TABLE IF EXISTS `booking_alive`;
CREATE TABLE `booking_alive`  (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of booking_alive
-- ----------------------------

-- ----------------------------
-- Table structure for booking_invitation
-- ----------------------------
DROP TABLE IF EXISTS `booking_invitation`;
CREATE TABLE `booking_invitation`  (
  `id` int NOT NULL,
  `booking_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nik` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `internal` int NOT NULL DEFAULT 1,
  `attendance_status` int NOT NULL DEFAULT 0,
  `attendance_reason` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `execute_attendance` int NULL DEFAULT 0,
  `execute_door_access` int NOT NULL DEFAULT 0,
  `email` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `company` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `position` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `is_pic` tinyint(1) NOT NULL,
  `is_vip` int NULL DEFAULT 0,
  `pin_room` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `created_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `is_deleted` int NOT NULL DEFAULT 0,
  `lastUpdate_365` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `checkin` int NULL DEFAULT 0,
  `end_meeting` int NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of booking_invitation
-- ----------------------------

-- ----------------------------
-- Table structure for booking_invoice
-- ----------------------------
DROP TABLE IF EXISTS `booking_invoice`;
CREATE TABLE `booking_invoice`  (
  `id` int UNSIGNED NOT NULL,
  `invoice_generate_no` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `invoice_no` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `invoice_format` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `booking_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `rent_cost` bigint NULL DEFAULT NULL,
  `alocation` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `memo_no` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `referensi_no` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `time_before` datetime NULL DEFAULT NULL,
  `time_send` datetime NULL DEFAULT NULL,
  `time_paid` datetime NULL DEFAULT NULL,
  `invoice_status` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `created_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `updated_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of booking_invoice
-- ----------------------------

-- ----------------------------
-- Table structure for booking_invoice_detail
-- ----------------------------
DROP TABLE IF EXISTS `booking_invoice_detail`;
CREATE TABLE `booking_invoice_detail`  (
  `id` int NOT NULL,
  `invoice_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `no_urut` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `no_invoice` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `alocation_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `alocation_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `total_cost` bigint NULL DEFAULT NULL,
  `total_duration` int NULL DEFAULT NULL,
  `total_meeting` int NULL DEFAULT NULL,
  `outstanding_status` int NULL DEFAULT NULL,
  `invoice_status` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `alocation_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `cost_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `created_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `sent_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `paid_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `created_at` datetime NULL DEFAULT NULL,
  `sent_at` datetime NULL DEFAULT NULL,
  `paid_at` datetime NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of booking_invoice_detail
-- ----------------------------

-- ----------------------------
-- Table structure for booking_invoice_generate
-- ----------------------------
DROP TABLE IF EXISTS `booking_invoice_generate`;
CREATE TABLE `booking_invoice_generate`  (
  `id` int UNSIGNED NOT NULL,
  `invoice_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `invoice_format` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `invoice_month1` int NULL DEFAULT NULL,
  `invoice_month2` int NULL DEFAULT NULL,
  `invoice_years` bigint NULL DEFAULT NULL,
  `memo_no` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `referensi_no` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `alocation_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `total_cost` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `total_meeting` int NULL DEFAULT NULL,
  `total_duration` bigint NULL DEFAULT NULL,
  `status` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `date_generate` datetime NULL DEFAULT NULL,
  `date_sending` datetime NULL DEFAULT NULL,
  `date_confirm` datetime NULL DEFAULT NULL,
  `generate_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `sending_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `confirm_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `updated_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `is_deleted` int NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of booking_invoice_generate
-- ----------------------------

-- ----------------------------
-- Table structure for booking_room_trs
-- ----------------------------
DROP TABLE IF EXISTS `booking_room_trs`;
CREATE TABLE `booking_room_trs`  (
  `room_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `booking_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `date` date NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of booking_room_trs
-- ----------------------------

-- ----------------------------
-- Table structure for building
-- ----------------------------
DROP TABLE IF EXISTS `building`;
CREATE TABLE `building`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `generate` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'default.jpeg',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `timezone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `detail_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `google_map` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `koordinate` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `is_deleted` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2147483648 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of building
-- ----------------------------
INSERT INTO `building` VALUES (1, 4, 'BIIRS Sinergi', '061237.jpeg', 'KANTOR PUSAT', 'Asia/Jakarta', 'Parungmulya, Kec. Ciampel, Karawang, Jawa Barat\r\n', 'https://maps.app.goo.gl/woummfg6Zit5vb9n9', '-6.1449754,106.8191852', '0', '2025-01-09 16:31:39', NULL, '2025-01-09 16:31:39', NULL);
INSERT INTO `building` VALUES (1602798354, 17, 'tset', '1602798354.png', 'testt', 'Asia/Jakarta', 'tes', '', NULL, '1', '2025-01-10 09:31:10', NULL, '2025-01-10 09:31:10', NULL);
INSERT INTO `building` VALUES (2147483647, 16, 'testtes 1', '7486509132.png', 'test 1', 'Asia/Jakarta', 'test', 'test', NULL, '1', '2025-01-10 09:23:55', NULL, '2025-01-10 09:23:55', NULL);

-- ----------------------------
-- Table structure for building_floor
-- ----------------------------
DROP TABLE IF EXISTS `building_floor`;
CREATE TABLE `building_floor`  (
  `_generate` int NOT NULL AUTO_INCREMENT,
  `id` bigint NOT NULL,
  `building_id` int NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `position` int NULL DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `pixel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `floor_length` double NULL DEFAULT NULL,
  `floor_width` double NULL DEFAULT NULL,
  `meter_per_px` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `meter_per_px2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `plus_width` double NULL DEFAULT NULL,
  `plus_height` double NULL DEFAULT NULL,
  `center_x` int NULL DEFAULT 0,
  `center_y` int NULL DEFAULT 0,
  `created_at` datetime NULL DEFAULT NULL,
  `created_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `updated_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `is_deleted` int NULL DEFAULT NULL,
  PRIMARY KEY (`_generate`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of building_floor
-- ----------------------------

-- ----------------------------
-- Table structure for company
-- ----------------------------
DROP TABLE IF EXISTS `company`;
CREATE TABLE `company`  (
  `id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `city` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `state` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `picture` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `menu_bar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `url_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `created_by` int NOT NULL,
  `created_at` int NOT NULL,
  `update_at` int NOT NULL,
  `is_deleted` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of company
-- ----------------------------
INSERT INTO `company` VALUES ('PR01555576919', 'Bank Indonesia', 'Parungmulya, Kec. Ciampel, Karawang, Jawa Barat', 'Karawang', 'Jawa Barat', '-', '', 'bg_logo_company.jpg', 'icon_logo_company.png', 'logo_company.png', 'menu_logo_company.png', 'https://www.bi.go.id', 0, 0, 0, 0);

-- ----------------------------
-- Table structure for department
-- ----------------------------
DROP TABLE IF EXISTS `department`;
CREATE TABLE `department`  (
  `id_department` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_perusahaan` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `department_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `foto` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_by` int NOT NULL,
  `created_at` int NOT NULL,
  `update_at` int NOT NULL,
  `is_deleted` int NOT NULL,
  PRIMARY KEY (`id_department`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of department
-- ----------------------------
INSERT INTO `department` VALUES ('DP01555576937', 'PR01555576919', 'Rekayasa Industri', './images/uploadfoto/', 0, 0, 0, 0);

-- ----------------------------
-- Table structure for desk_booking
-- ----------------------------
DROP TABLE IF EXISTS `desk_booking`;
CREATE TABLE `desk_booking`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `no_order` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `date` date NOT NULL,
  `room_id` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `desk_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `desk_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `room_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `cost_total_booking` bigint NULL DEFAULT NULL,
  `duration_per_meeting` int NULL DEFAULT NULL,
  `total_duration` int NULL DEFAULT 0,
  `extended_duration` int NULL DEFAULT 0,
  `pic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `alocation_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `alocation_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `participants` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `end_early_meeting` int NULL DEFAULT 0,
  `text_early` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `is_meal` tinyint(1) NOT NULL,
  `is_rescheduled` int NOT NULL DEFAULT 0,
  `is_canceled` int NOT NULL DEFAULT 0,
  `is_expired` int NOT NULL,
  `canceled_note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `canceled_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `canceled_at` datetime NOT NULL DEFAULT current_timestamp(),
  `expired_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `expired_at` datetime NOT NULL DEFAULT current_timestamp(),
  `rescheduled_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `rescheduled_at` datetime NOT NULL DEFAULT current_timestamp(),
  `early_ended_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `early_ended_at` datetime NOT NULL DEFAULT current_timestamp(),
  `is_alive` int NOT NULL DEFAULT 1,
  `created_at` datetime NULL DEFAULT NULL,
  `is_notif_before_end_meeting` int NOT NULL DEFAULT 0,
  `is_notif_end_meeting` int NOT NULL DEFAULT 0,
  `is_device` int NULL DEFAULT 0,
  `is_access_trigger` int NULL DEFAULT 0,
  `created_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `updated_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `status` enum('soon','active','non-active','expired','cancel') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'soon',
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5274 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of desk_booking
-- ----------------------------
INSERT INTO `desk_booking` VALUES (5260, '3015764982', '001/20250109162715k6vK-E-Meeting/08/26', 'Book a Desk in IT Vendor - No.53', '2026-08-27', '194208', '1252363004798176', 'IT Vendor - No.53', 'Ruang IT Vendor', '2026-08-27 06:00:00', '2026-08-27 10:01:00', 0, 1, 1, 0, 'Handi', '20250109162715k6vK', 'Tamu Sinergi', '', '', 0, NULL, 0, 0, 0, 0, '', '', '2026-08-26 23:27:48', '', '2026-08-26 23:27:48', '', '2026-08-26 23:27:48', '', '2026-08-26 23:27:48', 1, '2026-08-26 23:27:48', 0, 0, 0, 0, 'admin', NULL, NULL, 'soon', 0);
INSERT INTO `desk_booking` VALUES (5261, '9087546123', '001/10283-E-Meeting/08/26', 'Book a Desk in IT Vendor - No.54', '2026-08-27', '194208', '2680348512175079', 'IT Vendor - No.54', 'Ruang IT Vendor', '2026-08-27 09:27:00', '2026-08-27 09:28:00', 0, 1, 1, 0, 'Administrator', '10283', 'Divisi RnD', '', '', 0, NULL, 0, 0, 0, 0, '', '', '2026-08-27 09:28:39', '', '2026-08-27 09:28:39', '', '2026-08-27 09:28:39', '', '2026-08-27 09:28:39', 1, '2026-08-27 09:28:39', 0, 0, 0, 0, 'admin', NULL, NULL, 'soon', 0);
INSERT INTO `desk_booking` VALUES (5262, '9268145307', '001/10283-E-Meeting/08/26', 'Book a Desk in IT Staff - No.1', '2026-08-27', '934052', '8736945528947020', 'IT Staff - No.1', 'Ruang IT Staff', '2026-08-27 14:04:00', '2026-08-27 14:05:00', 0, 1, 1, 0, 'Administrator', '10283', 'Divisi RnD', '', '', 0, NULL, 0, 0, 0, 0, '', '', '2026-08-27 14:05:04', '', '2026-08-27 14:05:04', '', '2026-08-27 14:05:04', '', '2026-08-27 14:05:04', 1, '2026-08-27 14:05:04', 0, 0, 0, 0, 'admin', NULL, NULL, 'soon', 0);
INSERT INTO `desk_booking` VALUES (5263, '9467532018', '001/10283-E-Meeting/08/26', 'Book a Desk in IT Staff - No.1', '2026-08-27', '934052', '8736945528947020', 'IT Staff - No.1', 'Ruang IT Staff', '2026-08-27 14:04:00', '2026-08-27 14:05:00', 0, 1, 1, 0, 'Administrator', '10283', 'Divisi RnD', '', '', 0, NULL, 0, 0, 0, 0, '', '', '2026-08-27 14:05:51', '', '2026-08-27 14:05:51', '', '2026-08-27 14:05:51', '', '2026-08-27 14:05:51', 1, '2026-08-27 14:05:51', 0, 0, 0, 0, 'admin', NULL, NULL, 'soon', 0);
INSERT INTO `desk_booking` VALUES (5264, '4793510682', '001/10283-E-Meeting/08/26', 'Book a Desk in IT Staff - No.2', '2026-08-27', '934052', '2368547130860491', 'IT Staff - No.2', 'Ruang IT Staff', '2026-08-27 14:06:00', '2026-08-27 14:07:00', 0, 1, 1, 0, 'Administrator', '10283', 'Divisi RnD', '', '', 0, NULL, 0, 0, 0, 0, '', '', '2026-08-27 14:06:35', '', '2026-08-27 14:06:35', '', '2026-08-27 14:06:35', '', '2026-08-27 14:06:35', 1, '2026-08-27 14:06:35', 0, 0, 0, 0, 'admin', NULL, NULL, 'soon', 0);
INSERT INTO `desk_booking` VALUES (5265, '2590876143', '001/10283-E-Meeting/08/26', 'Book a Desk in IT Staff - No.2', '2026-08-27', '934052', '2368547130860491', 'IT Staff - No.2', 'Ruang IT Staff', '2026-08-27 14:06:00', '2026-08-27 14:07:00', 0, 1, 1, 0, 'Administrator', '10283', 'Divisi RnD', '', '', 0, NULL, 0, 0, 0, 0, '', '', '2026-08-27 14:08:34', '', '2026-08-27 14:08:34', '', '2026-08-27 14:08:34', '', '2026-08-27 14:08:34', 1, '2026-08-27 14:08:34', 0, 0, 0, 0, 'admin', NULL, NULL, 'soon', 0);
INSERT INTO `desk_booking` VALUES (5266, '6451927038', '001/10283-E-Meeting/08/26', 'Book a Desk in IT Staff - No.42', '2026-08-27', '934052', '4787102330916628', 'IT Staff - No.42', 'Ruang IT Staff', '2026-08-27 16:42:00', '2026-08-27 16:43:00', 0, 1, 1, 0, 'Tilis Tiadi', '10283', 'Divisi RnD', '', '', 0, NULL, 0, 0, 0, 0, '', '', '2026-08-27 16:42:34', '', '2026-08-27 16:42:34', '', '2026-08-27 16:42:34', '', '2026-08-27 16:42:34', 1, '2026-08-27 16:42:34', 0, 0, 0, 0, '20241220024501', NULL, NULL, 'soon', 0);
INSERT INTO `desk_booking` VALUES (5267, '1968537204', '001/10283-E-Meeting/08/26', 'Book a Desk in IT Staff - Desk No.74', '2026-08-27', '934052', '8441265178360097', 'IT Staff - Desk No.74', 'Ruang IT Staff', '2026-08-27 16:45:00', '2026-08-27 16:50:00', 0, 1, 5, 0, 'Tilis Tiadi', '10283', 'Divisi RnD', '', '', 0, NULL, 0, 0, 0, 0, '', '', '2026-08-27 16:45:49', '', '2026-08-27 16:45:49', '', '2026-08-27 16:45:49', '', '2026-08-27 16:45:49', 1, '2026-08-27 16:45:49', 0, 0, 0, 0, NULL, NULL, NULL, 'soon', 0);
INSERT INTO `desk_booking` VALUES (5268, '6735204891', '001/10283-E-Meeting/08/26', 'Book a Desk in Lantai 2 - No.1', '2026-08-28', '354196', '5183122340677450', 'Lantai 2 - No.1', 'IRS LT2', '2026-08-28 14:50:00', '2026-08-28 15:20:00', 0, 1, 30, 0, 'Tilis Tiadi', '10283', 'Divisi RnD', '', '', 0, NULL, 0, 0, 0, 0, '', '', '2026-08-28 14:45:37', '', '2026-08-28 14:45:37', '', '2026-08-28 14:45:37', '', '2026-08-28 14:45:37', 1, '2026-08-28 14:45:37', 0, 0, 0, 0, NULL, NULL, NULL, 'soon', 0);
INSERT INTO `desk_booking` VALUES (5269, '3785961420', '001/10283-E-Meeting/08/26', 'Book a Desk in IT Staff - No.1', '2026-08-28', '934052', '8736945528947020', 'IT Staff - No.1', 'Ruang IT Staff', '2026-08-28 15:01:00', '2026-08-28 16:01:00', 0, 1, 60, 0, 'Tilis Tiadi', '10283', 'Divisi RnD', '', '', 0, NULL, 0, 0, 0, 0, '', '', '2026-08-28 14:53:03', '', '2026-08-28 14:53:03', '', '2026-08-28 14:53:03', '', '2026-08-28 14:53:03', 1, '2026-08-28 14:53:03', 0, 0, 0, 0, NULL, NULL, NULL, 'soon', 0);
INSERT INTO `desk_booking` VALUES (5270, '2637049518', '001/10283-E-Meeting/08/26', 'Book a Desk in Lantai 2 - No.1', '2026-08-28', '354196', '5183122340677450', 'Lantai 2 - No.1', 'IRS LT2', '2026-08-28 16:19:00', '2026-08-28 17:19:00', 0, 1, 60, 60, 'Tilis Tiadi', '10283', 'Divisi RnD', '', '', 0, NULL, 0, 0, 0, 0, '', '', '2026-08-28 16:18:52', '', '2026-08-28 16:18:52', '', '2026-08-28 16:18:52', '', '2026-08-28 16:18:52', 1, '2026-08-28 16:18:52', 0, 0, 0, 0, NULL, NULL, NULL, 'soon', 0);
INSERT INTO `desk_booking` VALUES (5271, '9182476053', '001/10283-E-Meeting/08/26', 'Book a Desk in IT Vendor - No.1', '2026-08-28', '194208', '9379642051176034', 'IT Vendor - No.1', 'Ruang IT Vendor', '2026-08-28 16:47:00', '2026-08-28 17:47:00', 0, 1, 60, 0, 'Tilis Tiadi', '10283', 'Divisi RnD', '', '', 0, NULL, 0, 0, 0, 0, '', '', '2026-08-28 16:46:51', '', '2026-08-28 16:46:51', '', '2026-08-28 16:46:51', '', '2026-08-28 16:46:51', 1, '2026-08-28 16:46:50', 0, 0, 0, 0, NULL, NULL, NULL, 'soon', 0);
INSERT INTO `desk_booking` VALUES (5272, '7926043815', '001/10283-E-Meeting/08/26', 'Book a Desk in IT Staff - No.1', '2026-08-28', '934052', '8736945528947020', 'IT Staff - No.1', 'Ruang IT Staff', '2026-08-28 17:12:00', '2026-08-28 18:12:00', 0, 1, 60, 0, 'Tilis Tiadi', '10283', 'Divisi RnD', '', '', 0, NULL, 0, 0, 0, 0, '', '', '2026-08-28 17:11:16', '', '2026-08-28 17:11:16', '', '2026-08-28 17:11:16', '', '2026-08-28 17:11:16', 1, '2026-08-28 17:11:16', 0, 0, 0, 0, NULL, NULL, NULL, 'soon', 0);
INSERT INTO `desk_booking` VALUES (5273, '3908417526', '001/10283-E-Meeting/08/26', 'Book a Desk in IT Staff - No.42', '2026-08-28', '934052', '4787102330916628', 'IT Staff - No.42', 'Ruang IT Staff', '2026-08-28 17:11:00', '2026-08-28 17:12:00', 0, 1, 1, 0, 'Administrator', '10283', 'Divisi RnD', '', '', 0, NULL, 0, 0, 0, 0, '', '', '2026-08-28 17:11:38', '', '2026-08-28 17:11:38', '', '2026-08-28 17:11:38', '', '2026-08-28 17:11:38', 1, '2026-08-28 17:11:38', 0, 0, 0, 0, 'admin', NULL, NULL, 'soon', 0);

-- ----------------------------
-- Table structure for desk_booking_invitation
-- ----------------------------
DROP TABLE IF EXISTS `desk_booking_invitation`;
CREATE TABLE `desk_booking_invitation`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `booking_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nik` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `internal` int NOT NULL DEFAULT 1,
  `attendance_status` int NOT NULL DEFAULT 0,
  `attendance_reason` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `execute_attendance` int NULL DEFAULT 0,
  `execute_door_access` int NOT NULL DEFAULT 0,
  `email` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `company` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `position` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `is_pic` tinyint(1) NOT NULL,
  `pin_room` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `created_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `is_deleted` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of desk_booking_invitation
-- ----------------------------
INSERT INTO `desk_booking_invitation` VALUES (1, '3015764982', 'EC20250110131102', 1, 0, '', 0, 0, 'eMAIL@mail.com', 'Handi', '', '', 1, '802697', '2026-08-26 23:27:48', 'admin', '0000-00-00 00:00:00', NULL, 0);
INSERT INTO `desk_booking_invitation` VALUES (2, '9087546123', 'admin', 1, 0, '', 0, 0, 'admin@adminmail.com', 'Administrator', '', '', 1, '039748', '2026-08-27 09:28:39', 'admin', '0000-00-00 00:00:00', NULL, 0);
INSERT INTO `desk_booking_invitation` VALUES (3, '9268145307', 'admin', 1, 0, '', 0, 0, 'admin@adminmail.com', 'Administrator', '', '', 1, '039218', '2026-08-27 14:05:04', 'admin', '0000-00-00 00:00:00', NULL, 0);
INSERT INTO `desk_booking_invitation` VALUES (4, '9467532018', 'admin', 1, 0, '', 0, 0, 'admin@adminmail.com', 'Administrator', '', '', 1, '938251', '2026-08-27 14:05:51', 'admin', '0000-00-00 00:00:00', NULL, 0);
INSERT INTO `desk_booking_invitation` VALUES (5, '4793510682', 'admin', 1, 0, '', 0, 0, 'admin@adminmail.com', 'Administrator', '', '', 1, '912840', '2026-08-27 14:06:35', 'admin', '0000-00-00 00:00:00', NULL, 0);
INSERT INTO `desk_booking_invitation` VALUES (6, '2590876143', 'admin', 1, 0, '', 0, 0, 'admin@adminmail.com', 'Administrator', '', '', 1, '860234', '2026-08-27 14:08:34', 'admin', '0000-00-00 00:00:00', NULL, 0);
INSERT INTO `desk_booking_invitation` VALUES (7, '6451927038', '20241220024501', 1, 0, '', 0, 0, 'tilis.local@mail.com', 'Tilis Tiadi', '', '', 1, '063547', '2026-08-27 16:42:34', '20241220024501', '0000-00-00 00:00:00', NULL, 0);
INSERT INTO `desk_booking_invitation` VALUES (8, '1968537204', '20241220024501', 1, 0, '', 0, 0, 'tilis.local@mail.com', 'Tilis Tiadi', '', '', 1, '018936', '2026-08-27 16:45:49', NULL, '0000-00-00 00:00:00', NULL, 0);
INSERT INTO `desk_booking_invitation` VALUES (9, '6735204891', '20241220024501', 1, 0, '', 0, 0, 'tilis.local@mail.com', 'Tilis Tiadi', '', '', 1, '736914', '2026-08-28 14:45:37', NULL, '0000-00-00 00:00:00', NULL, 0);
INSERT INTO `desk_booking_invitation` VALUES (10, '3785961420', '20241220024501', 1, 0, '', 0, 0, 'tilis.local@mail.com', 'Tilis Tiadi', '', '', 1, '715206', '2026-08-28 14:53:03', NULL, '0000-00-00 00:00:00', NULL, 0);
INSERT INTO `desk_booking_invitation` VALUES (11, '2637049518', '20241220024501', 1, 0, '', 0, 0, 'tilis.local@mail.com', 'Tilis Tiadi', '', '', 1, '210369', '2026-08-28 16:18:52', NULL, '0000-00-00 00:00:00', NULL, 0);
INSERT INTO `desk_booking_invitation` VALUES (12, '9182476053', '20241220024501', 1, 0, '', 0, 0, 'tilis.local@mail.com', 'Tilis Tiadi', '', '', 1, '756034', '2026-08-28 16:46:50', NULL, '0000-00-00 00:00:00', NULL, 0);
INSERT INTO `desk_booking_invitation` VALUES (13, '7926043815', '20241220024501', 1, 0, '', 0, 0, 'tilis.local@mail.com', 'Tilis Tiadi', '', '', 1, '372085', '2026-08-28 17:11:16', NULL, '0000-00-00 00:00:00', NULL, 0);
INSERT INTO `desk_booking_invitation` VALUES (14, '3908417526', 'admin', 1, 0, '', 0, 0, 'admin@adminmail.com', 'Administrator', '', '', 1, '916035', '2026-08-28 17:11:38', 'admin', '0000-00-00 00:00:00', NULL, 0);

-- ----------------------------
-- Table structure for desk_controller
-- ----------------------------
DROP TABLE IF EXISTS `desk_controller`;
CREATE TABLE `desk_controller`  (
  `_generate` int NOT NULL AUTO_INCREMENT,
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `ip_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `capacity` int NULL DEFAULT NULL,
  `created_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `updated_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `is_deleted` int NULL DEFAULT NULL,
  PRIMARY KEY (`_generate`, `id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 24 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of desk_controller
-- ----------------------------
INSERT INTO `desk_controller` VALUES (8, '0ca06f9c63bdd3404ca461d24237bb22', 'C1', '10.100.17.83', 16, 'admin', '2023-05-11 13:54:28', '2025-01-17 21:29:42', NULL, 0);
INSERT INTO `desk_controller` VALUES (9, 'a4e9c22cc30b182910131d66c7a4a7a4', 'C2', '10.100.17.72', 16, 'admin', '2023-05-11 13:54:44', '2023-06-17 18:59:43', NULL, 0);
INSERT INTO `desk_controller` VALUES (10, 'c295ab06688ea9c76ee7d910529c5cff', 'C3', '10.100.17.73', 16, 'admin', '2023-05-11 13:54:56', '2023-06-17 17:02:59', NULL, 0);
INSERT INTO `desk_controller` VALUES (11, '971765504c6ec795a1bd53d518ab544d', 'C4', '10.100.17.74', 16, 'admin', '2023-05-11 13:55:08', '2023-06-17 19:25:36', NULL, 0);
INSERT INTO `desk_controller` VALUES (12, 'f197bc2e08cde46e252734dba332dfab', 'C5', '10.100.17.75', 16, 'admin', '2023-05-11 13:55:25', '2023-06-17 19:29:39', NULL, 0);
INSERT INTO `desk_controller` VALUES (13, 'a248df291f09a1942ecfbe1a053194bc', 'C8X', '10.100.17.71', 16, 'admin', '2023-05-11 13:55:35', '2025-11-25 19:20:57', NULL, 0);
INSERT INTO `desk_controller` VALUES (14, '2186b56ec33101f054e7c106388d0ea8', 'C7', '10.100.17.77', 16, 'admin', '2023-05-11 13:55:44', '2023-06-17 17:09:11', NULL, 0);
INSERT INTO `desk_controller` VALUES (15, '7bb5d4e1eb0c4a0515909074b45d939d', 'C6X', '10.100.17.76', 16, 'admin', '2023-05-11 13:55:52', '2025-11-25 18:59:58', NULL, 0);
INSERT INTO `desk_controller` VALUES (16, '3d6d13d7f7e7aa4f308787f830f5e4fe', 'C9X', '10.100.17.78', 16, 'admin', '2023-05-11 13:56:03', '2025-11-25 19:27:43', NULL, 0);
INSERT INTO `desk_controller` VALUES (17, '53625adf603ced301da9a8af6360453c', 'C10X', '10.100.17.80', 16, 'admin', '2023-05-11 14:10:34', '2025-11-25 19:55:51', NULL, 0);
INSERT INTO `desk_controller` VALUES (18, '66aa44ce69aaf500c851688ca6ec643c', 'C11', '10.100.17.81', 16, 'admin', '2023-05-11 14:10:50', '2023-06-17 16:56:50', NULL, 1);
INSERT INTO `desk_controller` VALUES (19, '16318ec4a71fc2e8495e51c6285a641f', 'C12', '10.100.17.82', 16, 'admin', '2023-05-11 14:10:59', '2023-06-17 19:19:49', NULL, 0);
INSERT INTO `desk_controller` VALUES (20, '213a6881c1b23331d218f8b076108882', 'C13', '10.100.17.79', 16, 'admin', '2023-05-11 14:11:09', '2025-11-25 18:46:23', NULL, 0);
INSERT INTO `desk_controller` VALUES (21, 'd4d7aee834cc2f14e9891b9336ea818f', 'C14', '10.100.17.84', 16, 'admin', '2023-05-11 14:11:23', '2023-06-17 18:51:06', NULL, 0);
INSERT INTO `desk_controller` VALUES (22, 'f0172d66f2de5780054f4af7d320afa9', 'C15', '10.100.17.85', 16, 'admin', '2023-05-11 14:11:36', '2023-06-17 16:46:24', NULL, 0);
INSERT INTO `desk_controller` VALUES (23, '336d3fc20ce3c9d6fe5b0c31a2640868', 'P1', '10.0.0.1', 16, 'admin', '2026-08-28 14:33:29', '2026-08-28 14:33:29', NULL, 0);

-- ----------------------------
-- Table structure for desk_controller_initial
-- ----------------------------
DROP TABLE IF EXISTS `desk_controller_initial`;
CREATE TABLE `desk_controller_initial`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `socket` int NULL DEFAULT NULL,
  `controller_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `desk_room_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `desk_id` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 338 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of desk_controller_initial
-- ----------------------------
INSERT INTO `desk_controller_initial` VALUES (1, 1, 'bcf6cd3e139af480b386c0c7c9d37550', '', '');
INSERT INTO `desk_controller_initial` VALUES (2, 2, 'bcf6cd3e139af480b386c0c7c9d37550', '', '');
INSERT INTO `desk_controller_initial` VALUES (3, 3, 'bcf6cd3e139af480b386c0c7c9d37550', '', '');
INSERT INTO `desk_controller_initial` VALUES (4, 4, 'bcf6cd3e139af480b386c0c7c9d37550', '', '');
INSERT INTO `desk_controller_initial` VALUES (5, 5, 'bcf6cd3e139af480b386c0c7c9d37550', '', '');
INSERT INTO `desk_controller_initial` VALUES (6, 6, 'bcf6cd3e139af480b386c0c7c9d37550', '', '');
INSERT INTO `desk_controller_initial` VALUES (7, 7, 'bcf6cd3e139af480b386c0c7c9d37550', '', '');
INSERT INTO `desk_controller_initial` VALUES (8, 8, 'bcf6cd3e139af480b386c0c7c9d37550', '', '');
INSERT INTO `desk_controller_initial` VALUES (9, 1, 'a4c975b2b57a411e97dca8d61edc2f18', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (10, 1, 'b86cdc3505588d4a83e101d0a94dd6c3', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (11, 2, 'b86cdc3505588d4a83e101d0a94dd6c3', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (12, 3, 'b86cdc3505588d4a83e101d0a94dd6c3', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (13, 4, 'b86cdc3505588d4a83e101d0a94dd6c3', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (14, 5, 'b86cdc3505588d4a83e101d0a94dd6c3', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (15, 6, 'b86cdc3505588d4a83e101d0a94dd6c3', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (16, 7, 'b86cdc3505588d4a83e101d0a94dd6c3', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (17, 8, 'b86cdc3505588d4a83e101d0a94dd6c3', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (18, 9, 'b86cdc3505588d4a83e101d0a94dd6c3', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (19, 10, 'b86cdc3505588d4a83e101d0a94dd6c3', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (20, 11, 'b86cdc3505588d4a83e101d0a94dd6c3', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (21, 12, 'b86cdc3505588d4a83e101d0a94dd6c3', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (22, 13, 'b86cdc3505588d4a83e101d0a94dd6c3', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (23, 14, 'b86cdc3505588d4a83e101d0a94dd6c3', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (24, 15, 'b86cdc3505588d4a83e101d0a94dd6c3', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (25, 16, 'b86cdc3505588d4a83e101d0a94dd6c3', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (26, 1, '12e40351e8346aba829280422e629835', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (27, 2, '12e40351e8346aba829280422e629835', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (28, 3, '12e40351e8346aba829280422e629835', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (29, 4, '12e40351e8346aba829280422e629835', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (30, 5, '12e40351e8346aba829280422e629835', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (31, 6, '12e40351e8346aba829280422e629835', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (32, 7, '12e40351e8346aba829280422e629835', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (33, 8, '12e40351e8346aba829280422e629835', '', '');
INSERT INTO `desk_controller_initial` VALUES (34, 1, '6c7192a79bf78ceeccf4dcdb6c6f31d2', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (35, 2, '6c7192a79bf78ceeccf4dcdb6c6f31d2', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (36, 3, '6c7192a79bf78ceeccf4dcdb6c6f31d2', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (37, 4, '6c7192a79bf78ceeccf4dcdb6c6f31d2', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (38, 5, '6c7192a79bf78ceeccf4dcdb6c6f31d2', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (39, 6, '6c7192a79bf78ceeccf4dcdb6c6f31d2', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (40, 7, '6c7192a79bf78ceeccf4dcdb6c6f31d2', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (41, 8, '6c7192a79bf78ceeccf4dcdb6c6f31d2', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (42, 9, '6c7192a79bf78ceeccf4dcdb6c6f31d2', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (43, 10, '6c7192a79bf78ceeccf4dcdb6c6f31d2', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (44, 11, '6c7192a79bf78ceeccf4dcdb6c6f31d2', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (45, 12, '6c7192a79bf78ceeccf4dcdb6c6f31d2', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (46, 13, '6c7192a79bf78ceeccf4dcdb6c6f31d2', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (47, 14, '6c7192a79bf78ceeccf4dcdb6c6f31d2', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (48, 15, '6c7192a79bf78ceeccf4dcdb6c6f31d2', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (49, 16, '6c7192a79bf78ceeccf4dcdb6c6f31d2', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (50, 1, 'f9a6b5391831f082377ef62e89636489', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (51, 2, 'f9a6b5391831f082377ef62e89636489', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (52, 3, 'f9a6b5391831f082377ef62e89636489', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (53, 4, 'f9a6b5391831f082377ef62e89636489', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (54, 5, 'f9a6b5391831f082377ef62e89636489', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (55, 6, 'f9a6b5391831f082377ef62e89636489', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (56, 7, 'f9a6b5391831f082377ef62e89636489', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (57, 8, 'f9a6b5391831f082377ef62e89636489', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (58, 9, 'f9a6b5391831f082377ef62e89636489', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (59, 10, 'f9a6b5391831f082377ef62e89636489', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (60, 11, 'f9a6b5391831f082377ef62e89636489', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (61, 12, 'f9a6b5391831f082377ef62e89636489', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (62, 13, 'f9a6b5391831f082377ef62e89636489', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (63, 14, 'f9a6b5391831f082377ef62e89636489', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (64, 15, 'f9a6b5391831f082377ef62e89636489', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (65, 16, 'f9a6b5391831f082377ef62e89636489', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (66, 1, '0ca06f9c63bdd3404ca461d24237bb22', '934052', '8736945528947020');
INSERT INTO `desk_controller_initial` VALUES (67, 2, '0ca06f9c63bdd3404ca461d24237bb22', '934052', '2368547130860491');
INSERT INTO `desk_controller_initial` VALUES (68, 3, '0ca06f9c63bdd3404ca461d24237bb22', '934052', '9103282604554737');
INSERT INTO `desk_controller_initial` VALUES (69, 4, '0ca06f9c63bdd3404ca461d24237bb22', '934052', '8107054622733594');
INSERT INTO `desk_controller_initial` VALUES (70, 5, '0ca06f9c63bdd3404ca461d24237bb22', '934052', '4335624608597809');
INSERT INTO `desk_controller_initial` VALUES (71, 6, '0ca06f9c63bdd3404ca461d24237bb22', '934052', '9239824067508314');
INSERT INTO `desk_controller_initial` VALUES (72, 7, '0ca06f9c63bdd3404ca461d24237bb22', '934052', '2470316921057598');
INSERT INTO `desk_controller_initial` VALUES (73, 8, '0ca06f9c63bdd3404ca461d24237bb22', '934052', '9229315775083841');
INSERT INTO `desk_controller_initial` VALUES (74, 9, '0ca06f9c63bdd3404ca461d24237bb22', '934052', '1396042265485318');
INSERT INTO `desk_controller_initial` VALUES (75, 10, '0ca06f9c63bdd3404ca461d24237bb22', '934052', '8089696072435741');
INSERT INTO `desk_controller_initial` VALUES (76, 11, '0ca06f9c63bdd3404ca461d24237bb22', '934052', '0608926491318747');
INSERT INTO `desk_controller_initial` VALUES (77, 12, '0ca06f9c63bdd3404ca461d24237bb22', '934052', '6146180827053275');
INSERT INTO `desk_controller_initial` VALUES (78, 13, '0ca06f9c63bdd3404ca461d24237bb22', '934052', '9329216488567074');
INSERT INTO `desk_controller_initial` VALUES (79, 14, '0ca06f9c63bdd3404ca461d24237bb22', '934052', '5290465241736807');
INSERT INTO `desk_controller_initial` VALUES (80, 15, '0ca06f9c63bdd3404ca461d24237bb22', '934052', '6234920515883797');
INSERT INTO `desk_controller_initial` VALUES (81, 16, '0ca06f9c63bdd3404ca461d24237bb22', '934052', '7316878139524426');
INSERT INTO `desk_controller_initial` VALUES (82, 1, 'a4e9c22cc30b182910131d66c7a4a7a4', '194208', '5210067549368973');
INSERT INTO `desk_controller_initial` VALUES (83, 2, 'a4e9c22cc30b182910131d66c7a4a7a4', '194208', '1482050789656339');
INSERT INTO `desk_controller_initial` VALUES (84, 3, 'a4e9c22cc30b182910131d66c7a4a7a4', '194208', '6839175870942352');
INSERT INTO `desk_controller_initial` VALUES (85, 4, 'a4e9c22cc30b182910131d66c7a4a7a4', '194208', '3205836611948707');
INSERT INTO `desk_controller_initial` VALUES (86, 5, 'a4e9c22cc30b182910131d66c7a4a7a4', '194208', '4582854207966317');
INSERT INTO `desk_controller_initial` VALUES (87, 6, 'a4e9c22cc30b182910131d66c7a4a7a4', '194208', '4508751608246792');
INSERT INTO `desk_controller_initial` VALUES (88, 7, 'a4e9c22cc30b182910131d66c7a4a7a4', '194208', '3585398097441262');
INSERT INTO `desk_controller_initial` VALUES (89, 8, 'a4e9c22cc30b182910131d66c7a4a7a4', '194208', '4678830591621390');
INSERT INTO `desk_controller_initial` VALUES (90, 9, 'a4e9c22cc30b182910131d66c7a4a7a4', '194208', '6848257409321761');
INSERT INTO `desk_controller_initial` VALUES (91, 10, 'a4e9c22cc30b182910131d66c7a4a7a4', '194208', '0119536672420853');
INSERT INTO `desk_controller_initial` VALUES (92, 11, 'a4e9c22cc30b182910131d66c7a4a7a4', '194208', '5879143412586079');
INSERT INTO `desk_controller_initial` VALUES (93, 12, 'a4e9c22cc30b182910131d66c7a4a7a4', '194208', '5918572129743308');
INSERT INTO `desk_controller_initial` VALUES (94, 13, 'a4e9c22cc30b182910131d66c7a4a7a4', '194208', '2665485899177302');
INSERT INTO `desk_controller_initial` VALUES (95, 14, 'a4e9c22cc30b182910131d66c7a4a7a4', '194208', '1958074695602782');
INSERT INTO `desk_controller_initial` VALUES (96, 15, 'a4e9c22cc30b182910131d66c7a4a7a4', '194208', '5386963724214107');
INSERT INTO `desk_controller_initial` VALUES (97, 16, 'a4e9c22cc30b182910131d66c7a4a7a4', '194208', '4660897450913257');
INSERT INTO `desk_controller_initial` VALUES (98, 1, 'c295ab06688ea9c76ee7d910529c5cff', '194208', '1435350679842297');
INSERT INTO `desk_controller_initial` VALUES (99, 2, 'c295ab06688ea9c76ee7d910529c5cff', '194208', '8319468760352041');
INSERT INTO `desk_controller_initial` VALUES (100, 3, 'c295ab06688ea9c76ee7d910529c5cff', '194208', '8656923459432107');
INSERT INTO `desk_controller_initial` VALUES (101, 4, 'c295ab06688ea9c76ee7d910529c5cff', '194208', '3065871718450364');
INSERT INTO `desk_controller_initial` VALUES (102, 5, 'c295ab06688ea9c76ee7d910529c5cff', '194208', '1252363004798176');
INSERT INTO `desk_controller_initial` VALUES (103, 6, 'c295ab06688ea9c76ee7d910529c5cff', '194208', '2680348512175079');
INSERT INTO `desk_controller_initial` VALUES (104, 7, 'c295ab06688ea9c76ee7d910529c5cff', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (105, 8, 'c295ab06688ea9c76ee7d910529c5cff', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (106, 9, 'c295ab06688ea9c76ee7d910529c5cff', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (107, 10, 'c295ab06688ea9c76ee7d910529c5cff', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (108, 11, 'c295ab06688ea9c76ee7d910529c5cff', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (109, 12, 'c295ab06688ea9c76ee7d910529c5cff', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (110, 13, 'c295ab06688ea9c76ee7d910529c5cff', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (111, 14, 'c295ab06688ea9c76ee7d910529c5cff', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (112, 15, 'c295ab06688ea9c76ee7d910529c5cff', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (113, 16, 'c295ab06688ea9c76ee7d910529c5cff', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (114, 1, '971765504c6ec795a1bd53d518ab544d', '194208', '0181324989056726');
INSERT INTO `desk_controller_initial` VALUES (115, 2, '971765504c6ec795a1bd53d518ab544d', '194208', '0918826957334654');
INSERT INTO `desk_controller_initial` VALUES (116, 3, '971765504c6ec795a1bd53d518ab544d', '194208', '0617799154532403');
INSERT INTO `desk_controller_initial` VALUES (117, 4, '971765504c6ec795a1bd53d518ab544d', '194208', '4457225161903869');
INSERT INTO `desk_controller_initial` VALUES (118, 5, '971765504c6ec795a1bd53d518ab544d', '194208', '6289047552379168');
INSERT INTO `desk_controller_initial` VALUES (119, 6, '971765504c6ec795a1bd53d518ab544d', '194208', '9368540820175376');
INSERT INTO `desk_controller_initial` VALUES (120, 7, '971765504c6ec795a1bd53d518ab544d', '194208', '5249838410017935');
INSERT INTO `desk_controller_initial` VALUES (121, 8, '971765504c6ec795a1bd53d518ab544d', '194208', '8156453263172084');
INSERT INTO `desk_controller_initial` VALUES (122, 9, '971765504c6ec795a1bd53d518ab544d', '194208', '4670179850238561');
INSERT INTO `desk_controller_initial` VALUES (123, 10, '971765504c6ec795a1bd53d518ab544d', '194208', '3146790801643892');
INSERT INTO `desk_controller_initial` VALUES (124, 11, '971765504c6ec795a1bd53d518ab544d', '194208', '4646385129177905');
INSERT INTO `desk_controller_initial` VALUES (125, 12, '971765504c6ec795a1bd53d518ab544d', '194208', '9286761854730590');
INSERT INTO `desk_controller_initial` VALUES (126, 13, '971765504c6ec795a1bd53d518ab544d', '194208', '9105262414873957');
INSERT INTO `desk_controller_initial` VALUES (127, 14, '971765504c6ec795a1bd53d518ab544d', '194208', '3108922574150846');
INSERT INTO `desk_controller_initial` VALUES (128, 15, '971765504c6ec795a1bd53d518ab544d', '194208', '1056726483949018');
INSERT INTO `desk_controller_initial` VALUES (129, 16, '971765504c6ec795a1bd53d518ab544d', '194208', '4076835141590823');
INSERT INTO `desk_controller_initial` VALUES (130, 1, 'f197bc2e08cde46e252734dba332dfab', '194208', '9379642051176034');
INSERT INTO `desk_controller_initial` VALUES (131, 2, 'f197bc2e08cde46e252734dba332dfab', '194208', '1560252038497893');
INSERT INTO `desk_controller_initial` VALUES (132, 3, 'f197bc2e08cde46e252734dba332dfab', '194208', '6413528198737495');
INSERT INTO `desk_controller_initial` VALUES (133, 4, 'f197bc2e08cde46e252734dba332dfab', '194208', '8338445727105109');
INSERT INTO `desk_controller_initial` VALUES (134, 5, 'f197bc2e08cde46e252734dba332dfab', '194208', '3981782362604701');
INSERT INTO `desk_controller_initial` VALUES (135, 6, 'f197bc2e08cde46e252734dba332dfab', '194208', '7103292588066759');
INSERT INTO `desk_controller_initial` VALUES (136, 7, 'f197bc2e08cde46e252734dba332dfab', '194208', '2389552047761490');
INSERT INTO `desk_controller_initial` VALUES (137, 8, 'f197bc2e08cde46e252734dba332dfab', '194208', '7055890926211674');
INSERT INTO `desk_controller_initial` VALUES (138, 9, 'f197bc2e08cde46e252734dba332dfab', '194208', '7533646099252170');
INSERT INTO `desk_controller_initial` VALUES (139, 10, 'f197bc2e08cde46e252734dba332dfab', '194208', '5105063492621778');
INSERT INTO `desk_controller_initial` VALUES (140, 11, 'f197bc2e08cde46e252734dba332dfab', '194208', '3694817260589234');
INSERT INTO `desk_controller_initial` VALUES (141, 12, 'f197bc2e08cde46e252734dba332dfab', '194208', '1208687709515439');
INSERT INTO `desk_controller_initial` VALUES (142, 13, 'f197bc2e08cde46e252734dba332dfab', '194208', '4757602360915891');
INSERT INTO `desk_controller_initial` VALUES (143, 14, 'f197bc2e08cde46e252734dba332dfab', '194208', '0595487309142831');
INSERT INTO `desk_controller_initial` VALUES (144, 15, 'f197bc2e08cde46e252734dba332dfab', '194208', '6437306418925120');
INSERT INTO `desk_controller_initial` VALUES (145, 16, 'f197bc2e08cde46e252734dba332dfab', '194208', '1945845103026298');
INSERT INTO `desk_controller_initial` VALUES (146, 1, 'a248df291f09a1942ecfbe1a053194bc', '934052', '8622917033567840');
INSERT INTO `desk_controller_initial` VALUES (147, 2, 'a248df291f09a1942ecfbe1a053194bc', '934052', '9955728480716103');
INSERT INTO `desk_controller_initial` VALUES (148, 3, 'a248df291f09a1942ecfbe1a053194bc', '934052', '5279447206896310');
INSERT INTO `desk_controller_initial` VALUES (149, 4, 'a248df291f09a1942ecfbe1a053194bc', '934052', '2187009644533956');
INSERT INTO `desk_controller_initial` VALUES (150, 5, 'a248df291f09a1942ecfbe1a053194bc', '934052', '0947481558103273');
INSERT INTO `desk_controller_initial` VALUES (151, 6, 'a248df291f09a1942ecfbe1a053194bc', '934052', '8356147618043095');
INSERT INTO `desk_controller_initial` VALUES (152, 7, 'a248df291f09a1942ecfbe1a053194bc', '934052', '1473620985783502');
INSERT INTO `desk_controller_initial` VALUES (153, 8, 'a248df291f09a1942ecfbe1a053194bc', '934052', '9475400931375816');
INSERT INTO `desk_controller_initial` VALUES (154, 9, 'a248df291f09a1942ecfbe1a053194bc', '934052', '1146030923792878');
INSERT INTO `desk_controller_initial` VALUES (155, 10, 'a248df291f09a1942ecfbe1a053194bc', '934052', '9981435472256103');
INSERT INTO `desk_controller_initial` VALUES (156, 11, 'a248df291f09a1942ecfbe1a053194bc', '934052', '2051197498367234');
INSERT INTO `desk_controller_initial` VALUES (157, 12, 'a248df291f09a1942ecfbe1a053194bc', '934052', '2809565387104394');
INSERT INTO `desk_controller_initial` VALUES (158, 13, 'a248df291f09a1942ecfbe1a053194bc', '934052', '8450287959113466');
INSERT INTO `desk_controller_initial` VALUES (159, 14, 'a248df291f09a1942ecfbe1a053194bc', '934052', '4866733950901415');
INSERT INTO `desk_controller_initial` VALUES (160, 15, 'a248df291f09a1942ecfbe1a053194bc', '934052', '1185295606844779');
INSERT INTO `desk_controller_initial` VALUES (161, 16, 'a248df291f09a1942ecfbe1a053194bc', '934052', '1533248299687170');
INSERT INTO `desk_controller_initial` VALUES (162, 1, '2186b56ec33101f054e7c106388d0ea8', '194208', '1405217695738640');
INSERT INTO `desk_controller_initial` VALUES (163, 2, '2186b56ec33101f054e7c106388d0ea8', '194208', '1323918657297044');
INSERT INTO `desk_controller_initial` VALUES (164, 3, '2186b56ec33101f054e7c106388d0ea8', '194208', '5062309112443789');
INSERT INTO `desk_controller_initial` VALUES (165, 4, '2186b56ec33101f054e7c106388d0ea8', '194208', '3145707526939848');
INSERT INTO `desk_controller_initial` VALUES (166, 5, '2186b56ec33101f054e7c106388d0ea8', '194208', '8610435701289253');
INSERT INTO `desk_controller_initial` VALUES (167, 6, '2186b56ec33101f054e7c106388d0ea8', '194208', '9726942818004513');
INSERT INTO `desk_controller_initial` VALUES (168, 7, '2186b56ec33101f054e7c106388d0ea8', '194208', '3040897146558927');
INSERT INTO `desk_controller_initial` VALUES (169, 8, '2186b56ec33101f054e7c106388d0ea8', '194208', '6335091251862408');
INSERT INTO `desk_controller_initial` VALUES (170, 9, '2186b56ec33101f054e7c106388d0ea8', '194208', '4739942216870318');
INSERT INTO `desk_controller_initial` VALUES (171, 10, '2186b56ec33101f054e7c106388d0ea8', '194208', '8942955301027746');
INSERT INTO `desk_controller_initial` VALUES (172, 11, '2186b56ec33101f054e7c106388d0ea8', '194208', '4402199767560328');
INSERT INTO `desk_controller_initial` VALUES (173, 12, '2186b56ec33101f054e7c106388d0ea8', '194208', '4350367408516822');
INSERT INTO `desk_controller_initial` VALUES (174, 13, '2186b56ec33101f054e7c106388d0ea8', '194208', '9815706345029163');
INSERT INTO `desk_controller_initial` VALUES (175, 14, '2186b56ec33101f054e7c106388d0ea8', '194208', '6820945812970563');
INSERT INTO `desk_controller_initial` VALUES (176, 15, '2186b56ec33101f054e7c106388d0ea8', '194208', '7035615223476881');
INSERT INTO `desk_controller_initial` VALUES (177, 16, '2186b56ec33101f054e7c106388d0ea8', '194208', '7615018292653984');
INSERT INTO `desk_controller_initial` VALUES (178, 1, '7bb5d4e1eb0c4a0515909074b45d939d', '934052', '9984126854367351');
INSERT INTO `desk_controller_initial` VALUES (179, 2, '7bb5d4e1eb0c4a0515909074b45d939d', '934052', '9706130655492832');
INSERT INTO `desk_controller_initial` VALUES (180, 3, '7bb5d4e1eb0c4a0515909074b45d939d', '934052', '6568754902823904');
INSERT INTO `desk_controller_initial` VALUES (181, 4, '7bb5d4e1eb0c4a0515909074b45d939d', '934052', '1093076379224868');
INSERT INTO `desk_controller_initial` VALUES (182, 5, '7bb5d4e1eb0c4a0515909074b45d939d', '934052', '8957489103537240');
INSERT INTO `desk_controller_initial` VALUES (183, 6, '7bb5d4e1eb0c4a0515909074b45d939d', '934052', '5887043911762492');
INSERT INTO `desk_controller_initial` VALUES (184, 7, '7bb5d4e1eb0c4a0515909074b45d939d', '934052', '5784341380260619');
INSERT INTO `desk_controller_initial` VALUES (185, 8, '7bb5d4e1eb0c4a0515909074b45d939d', '934052', '3180569842071423');
INSERT INTO `desk_controller_initial` VALUES (186, 9, '7bb5d4e1eb0c4a0515909074b45d939d', '934052', '0105472329547861');
INSERT INTO `desk_controller_initial` VALUES (187, 10, '7bb5d4e1eb0c4a0515909074b45d939d', '934052', '4787102330916628');
INSERT INTO `desk_controller_initial` VALUES (188, 11, '7bb5d4e1eb0c4a0515909074b45d939d', '934052', '0714559107283668');
INSERT INTO `desk_controller_initial` VALUES (189, 12, '7bb5d4e1eb0c4a0515909074b45d939d', '934052', '1579230697854813');
INSERT INTO `desk_controller_initial` VALUES (190, 13, '7bb5d4e1eb0c4a0515909074b45d939d', '934052', '6189403275802514');
INSERT INTO `desk_controller_initial` VALUES (191, 14, '7bb5d4e1eb0c4a0515909074b45d939d', '934052', '3596931628807475');
INSERT INTO `desk_controller_initial` VALUES (192, 15, '7bb5d4e1eb0c4a0515909074b45d939d', '934052', '9052871348731964');
INSERT INTO `desk_controller_initial` VALUES (193, 16, '7bb5d4e1eb0c4a0515909074b45d939d', '934052', '0346283515296774');
INSERT INTO `desk_controller_initial` VALUES (194, 1, '3d6d13d7f7e7aa4f308787f830f5e4fe', '934052', '7096243503681582');
INSERT INTO `desk_controller_initial` VALUES (195, 2, '3d6d13d7f7e7aa4f308787f830f5e4fe', '934052', '1923804602379575');
INSERT INTO `desk_controller_initial` VALUES (196, 3, '3d6d13d7f7e7aa4f308787f830f5e4fe', '934052', '2675814783162940');
INSERT INTO `desk_controller_initial` VALUES (197, 4, '3d6d13d7f7e7aa4f308787f830f5e4fe', '934052', '9469883152127054');
INSERT INTO `desk_controller_initial` VALUES (198, 5, '3d6d13d7f7e7aa4f308787f830f5e4fe', '934052', '3342182689155700');
INSERT INTO `desk_controller_initial` VALUES (199, 6, '3d6d13d7f7e7aa4f308787f830f5e4fe', '934052', '9102198375564462');
INSERT INTO `desk_controller_initial` VALUES (200, 7, '3d6d13d7f7e7aa4f308787f830f5e4fe', '934052', '6874425365028307');
INSERT INTO `desk_controller_initial` VALUES (201, 8, '3d6d13d7f7e7aa4f308787f830f5e4fe', '934052', '4895518621323740');
INSERT INTO `desk_controller_initial` VALUES (202, 9, '3d6d13d7f7e7aa4f308787f830f5e4fe', '934052', '8360417285759160');
INSERT INTO `desk_controller_initial` VALUES (203, 10, '3d6d13d7f7e7aa4f308787f830f5e4fe', '934052', '3018763246591795');
INSERT INTO `desk_controller_initial` VALUES (204, 11, '3d6d13d7f7e7aa4f308787f830f5e4fe', '934052', '0832454176392906');
INSERT INTO `desk_controller_initial` VALUES (205, 12, '3d6d13d7f7e7aa4f308787f830f5e4fe', '934052', '6112634503705497');
INSERT INTO `desk_controller_initial` VALUES (206, 13, '3d6d13d7f7e7aa4f308787f830f5e4fe', '934052', '0923681160493752');
INSERT INTO `desk_controller_initial` VALUES (207, 14, '3d6d13d7f7e7aa4f308787f830f5e4fe', '934052', '1529008371435962');
INSERT INTO `desk_controller_initial` VALUES (208, 15, '3d6d13d7f7e7aa4f308787f830f5e4fe', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (209, 16, '3d6d13d7f7e7aa4f308787f830f5e4fe', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (210, 1, '53625adf603ced301da9a8af6360453c', '934052', '9807280136247163');
INSERT INTO `desk_controller_initial` VALUES (211, 2, '53625adf603ced301da9a8af6360453c', '934052', '6429027815836910');
INSERT INTO `desk_controller_initial` VALUES (212, 3, '53625adf603ced301da9a8af6360453c', '934052', '5312179408372094');
INSERT INTO `desk_controller_initial` VALUES (213, 4, '53625adf603ced301da9a8af6360453c', '934052', '2877456133694005');
INSERT INTO `desk_controller_initial` VALUES (214, 5, '53625adf603ced301da9a8af6360453c', '934052', '2802648495753301');
INSERT INTO `desk_controller_initial` VALUES (215, 6, '53625adf603ced301da9a8af6360453c', '934052', '7298240935418576');
INSERT INTO `desk_controller_initial` VALUES (216, 7, '53625adf603ced301da9a8af6360453c', '934052', '8455863942621390');
INSERT INTO `desk_controller_initial` VALUES (217, 8, '53625adf603ced301da9a8af6360453c', '934052', '4725733805821664');
INSERT INTO `desk_controller_initial` VALUES (218, 9, '53625adf603ced301da9a8af6360453c', '934052', '2511947725893043');
INSERT INTO `desk_controller_initial` VALUES (219, 10, '53625adf603ced301da9a8af6360453c', '934052', '7638457108250693');
INSERT INTO `desk_controller_initial` VALUES (220, 11, '53625adf603ced301da9a8af6360453c', '934052', '6456172783520940');
INSERT INTO `desk_controller_initial` VALUES (221, 12, '53625adf603ced301da9a8af6360453c', '934052', '8441265178360097');
INSERT INTO `desk_controller_initial` VALUES (222, 13, '53625adf603ced301da9a8af6360453c', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (223, 14, '53625adf603ced301da9a8af6360453c', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (224, 15, '53625adf603ced301da9a8af6360453c', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (225, 16, '53625adf603ced301da9a8af6360453c', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (226, 1, '66aa44ce69aaf500c851688ca6ec643c', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (227, 2, '66aa44ce69aaf500c851688ca6ec643c', '', '');
INSERT INTO `desk_controller_initial` VALUES (228, 3, '66aa44ce69aaf500c851688ca6ec643c', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (229, 4, '66aa44ce69aaf500c851688ca6ec643c', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (230, 5, '66aa44ce69aaf500c851688ca6ec643c', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (231, 6, '66aa44ce69aaf500c851688ca6ec643c', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (232, 7, '66aa44ce69aaf500c851688ca6ec643c', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (233, 8, '66aa44ce69aaf500c851688ca6ec643c', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (234, 9, '66aa44ce69aaf500c851688ca6ec643c', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (235, 10, '66aa44ce69aaf500c851688ca6ec643c', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (236, 11, '66aa44ce69aaf500c851688ca6ec643c', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (237, 12, '66aa44ce69aaf500c851688ca6ec643c', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (238, 13, '66aa44ce69aaf500c851688ca6ec643c', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (239, 14, '66aa44ce69aaf500c851688ca6ec643c', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (240, 15, '66aa44ce69aaf500c851688ca6ec643c', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (241, 16, '66aa44ce69aaf500c851688ca6ec643c', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (242, 1, '16318ec4a71fc2e8495e51c6285a641f', '194208', '5147399240873606');
INSERT INTO `desk_controller_initial` VALUES (243, 2, '16318ec4a71fc2e8495e51c6285a641f', '194208', '6560125298193803');
INSERT INTO `desk_controller_initial` VALUES (244, 3, '16318ec4a71fc2e8495e51c6285a641f', '194208', '5874716220610583');
INSERT INTO `desk_controller_initial` VALUES (245, 4, '16318ec4a71fc2e8495e51c6285a641f', '194208', '8136054022397476');
INSERT INTO `desk_controller_initial` VALUES (246, 5, '16318ec4a71fc2e8495e51c6285a641f', '194208', '5632474288156001');
INSERT INTO `desk_controller_initial` VALUES (247, 6, '16318ec4a71fc2e8495e51c6285a641f', '194208', '2446250093796715');
INSERT INTO `desk_controller_initial` VALUES (248, 7, '16318ec4a71fc2e8495e51c6285a641f', '194208', '2306985751402841');
INSERT INTO `desk_controller_initial` VALUES (249, 8, '16318ec4a71fc2e8495e51c6285a641f', '194208', '3665112987839405');
INSERT INTO `desk_controller_initial` VALUES (250, 9, '16318ec4a71fc2e8495e51c6285a641f', '194208', '1437136256897025');
INSERT INTO `desk_controller_initial` VALUES (251, 10, '16318ec4a71fc2e8495e51c6285a641f', '194208', '8927704092431816');
INSERT INTO `desk_controller_initial` VALUES (252, 11, '16318ec4a71fc2e8495e51c6285a641f', '194208', '5793203958104761');
INSERT INTO `desk_controller_initial` VALUES (253, 12, '16318ec4a71fc2e8495e51c6285a641f', '194208', '0557621036828941');
INSERT INTO `desk_controller_initial` VALUES (254, 13, '16318ec4a71fc2e8495e51c6285a641f', '194208', '9592647158264107');
INSERT INTO `desk_controller_initial` VALUES (255, 14, '16318ec4a71fc2e8495e51c6285a641f', '194208', '6420523091774316');
INSERT INTO `desk_controller_initial` VALUES (256, 15, '16318ec4a71fc2e8495e51c6285a641f', '194208', '7629376114289345');
INSERT INTO `desk_controller_initial` VALUES (257, 16, '16318ec4a71fc2e8495e51c6285a641f', '194208', '6244837108709515');
INSERT INTO `desk_controller_initial` VALUES (258, 1, '213a6881c1b23331d218f8b076108882', '934052', '2004933795621874');
INSERT INTO `desk_controller_initial` VALUES (259, 2, '213a6881c1b23331d218f8b076108882', '934052', '9147367803981546');
INSERT INTO `desk_controller_initial` VALUES (260, 3, '213a6881c1b23331d218f8b076108882', '934052', '3578629876044311');
INSERT INTO `desk_controller_initial` VALUES (261, 4, '213a6881c1b23331d218f8b076108882', '934052', '6224795836490711');
INSERT INTO `desk_controller_initial` VALUES (262, 5, '213a6881c1b23331d218f8b076108882', '934052', '4512069146388973');
INSERT INTO `desk_controller_initial` VALUES (263, 6, '213a6881c1b23331d218f8b076108882', '934052', '4635900825427867');
INSERT INTO `desk_controller_initial` VALUES (264, 7, '213a6881c1b23331d218f8b076108882', '934052', '4629360158947137');
INSERT INTO `desk_controller_initial` VALUES (265, 8, '213a6881c1b23331d218f8b076108882', '934052', '6195807397264254');
INSERT INTO `desk_controller_initial` VALUES (266, 9, '213a6881c1b23331d218f8b076108882', '934052', '2814061728739056');
INSERT INTO `desk_controller_initial` VALUES (267, 10, '213a6881c1b23331d218f8b076108882', '934052', '0216859043127463');
INSERT INTO `desk_controller_initial` VALUES (268, 11, '213a6881c1b23331d218f8b076108882', '934052', '7210313792405685');
INSERT INTO `desk_controller_initial` VALUES (269, 12, '213a6881c1b23331d218f8b076108882', '934052', '3095822801375614');
INSERT INTO `desk_controller_initial` VALUES (270, 13, '213a6881c1b23331d218f8b076108882', '934052', '5692233048181046');
INSERT INTO `desk_controller_initial` VALUES (271, 14, '213a6881c1b23331d218f8b076108882', '934052', '3752291815876300');
INSERT INTO `desk_controller_initial` VALUES (272, 15, '213a6881c1b23331d218f8b076108882', '934052', '9145634006898712');
INSERT INTO `desk_controller_initial` VALUES (273, 16, '213a6881c1b23331d218f8b076108882', '934052', '7943083246608512');
INSERT INTO `desk_controller_initial` VALUES (274, 1, 'd4d7aee834cc2f14e9891b9336ea818f', '194208', '5892113445026078');
INSERT INTO `desk_controller_initial` VALUES (275, 2, 'd4d7aee834cc2f14e9891b9336ea818f', '194208', '3174456756198082');
INSERT INTO `desk_controller_initial` VALUES (276, 3, 'd4d7aee834cc2f14e9891b9336ea818f', '194208', '2516809278434135');
INSERT INTO `desk_controller_initial` VALUES (277, 4, 'd4d7aee834cc2f14e9891b9336ea818f', '194208', '7079582846042116');
INSERT INTO `desk_controller_initial` VALUES (278, 5, 'd4d7aee834cc2f14e9891b9336ea818f', '194208', '1287632736484159');
INSERT INTO `desk_controller_initial` VALUES (279, 6, 'd4d7aee834cc2f14e9891b9336ea818f', '194208', '6034384067289259');
INSERT INTO `desk_controller_initial` VALUES (280, 7, 'd4d7aee834cc2f14e9891b9336ea818f', '194208', '9631629744325800');
INSERT INTO `desk_controller_initial` VALUES (281, 8, 'd4d7aee834cc2f14e9891b9336ea818f', '194208', '9316284677095142');
INSERT INTO `desk_controller_initial` VALUES (282, 9, 'd4d7aee834cc2f14e9891b9336ea818f', '194208', '9214534778359201');
INSERT INTO `desk_controller_initial` VALUES (283, 10, 'd4d7aee834cc2f14e9891b9336ea818f', '194208', '8298663301457215');
INSERT INTO `desk_controller_initial` VALUES (284, 11, 'd4d7aee834cc2f14e9891b9336ea818f', '194208', '2430651841056827');
INSERT INTO `desk_controller_initial` VALUES (285, 12, 'd4d7aee834cc2f14e9891b9336ea818f', '194208', '4566593407320829');
INSERT INTO `desk_controller_initial` VALUES (286, 13, 'd4d7aee834cc2f14e9891b9336ea818f', '194208', '1319628659207453');
INSERT INTO `desk_controller_initial` VALUES (287, 14, 'd4d7aee834cc2f14e9891b9336ea818f', '194208', '9661750129248703');
INSERT INTO `desk_controller_initial` VALUES (288, 15, 'd4d7aee834cc2f14e9891b9336ea818f', '194208', '6112335748096209');
INSERT INTO `desk_controller_initial` VALUES (289, 16, 'd4d7aee834cc2f14e9891b9336ea818f', '194208', '1223769790848056');
INSERT INTO `desk_controller_initial` VALUES (290, 1, 'f0172d66f2de5780054f4af7d320afa9', '934052', '1829104277550668');
INSERT INTO `desk_controller_initial` VALUES (291, 2, 'f0172d66f2de5780054f4af7d320afa9', '934052', '6892937117404365');
INSERT INTO `desk_controller_initial` VALUES (292, 3, 'f0172d66f2de5780054f4af7d320afa9', '934052', '5329716320098486');
INSERT INTO `desk_controller_initial` VALUES (293, 4, 'f0172d66f2de5780054f4af7d320afa9', '934052', '4893523501768624');
INSERT INTO `desk_controller_initial` VALUES (294, 5, 'f0172d66f2de5780054f4af7d320afa9', '934052', '8483956765479212');
INSERT INTO `desk_controller_initial` VALUES (295, 6, 'f0172d66f2de5780054f4af7d320afa9', '934052', '6459916845371372');
INSERT INTO `desk_controller_initial` VALUES (296, 7, 'f0172d66f2de5780054f4af7d320afa9', '934052', '1754986264735091');
INSERT INTO `desk_controller_initial` VALUES (297, 8, 'f0172d66f2de5780054f4af7d320afa9', '934052', '4532710078923696');
INSERT INTO `desk_controller_initial` VALUES (298, 9, 'f0172d66f2de5780054f4af7d320afa9', '934052', '8634130498521257');
INSERT INTO `desk_controller_initial` VALUES (299, 10, 'f0172d66f2de5780054f4af7d320afa9', '934052', '0669911385427327');
INSERT INTO `desk_controller_initial` VALUES (300, 11, 'f0172d66f2de5780054f4af7d320afa9', '934052', '5931707326698458');
INSERT INTO `desk_controller_initial` VALUES (301, 12, 'f0172d66f2de5780054f4af7d320afa9', '934052', '1532328605081979');
INSERT INTO `desk_controller_initial` VALUES (302, 13, 'f0172d66f2de5780054f4af7d320afa9', '934052', '6950273187962443');
INSERT INTO `desk_controller_initial` VALUES (303, 14, 'f0172d66f2de5780054f4af7d320afa9', '934052', '4804265773601912');
INSERT INTO `desk_controller_initial` VALUES (304, 15, 'f0172d66f2de5780054f4af7d320afa9', '', '');
INSERT INTO `desk_controller_initial` VALUES (305, 16, 'f0172d66f2de5780054f4af7d320afa9', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (306, 1, 'ceb17c4f05ad22d2ab8cd9941a3bbd18', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (307, 2, 'ceb17c4f05ad22d2ab8cd9941a3bbd18', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (308, 3, 'ceb17c4f05ad22d2ab8cd9941a3bbd18', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (309, 4, 'ceb17c4f05ad22d2ab8cd9941a3bbd18', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (310, 5, 'ceb17c4f05ad22d2ab8cd9941a3bbd18', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (311, 6, 'ceb17c4f05ad22d2ab8cd9941a3bbd18', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (312, 7, 'ceb17c4f05ad22d2ab8cd9941a3bbd18', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (313, 8, 'ceb17c4f05ad22d2ab8cd9941a3bbd18', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (314, 9, 'ceb17c4f05ad22d2ab8cd9941a3bbd18', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (315, 10, 'ceb17c4f05ad22d2ab8cd9941a3bbd18', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (316, 11, 'ceb17c4f05ad22d2ab8cd9941a3bbd18', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (317, 12, 'ceb17c4f05ad22d2ab8cd9941a3bbd18', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (318, 13, 'ceb17c4f05ad22d2ab8cd9941a3bbd18', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (319, 14, 'ceb17c4f05ad22d2ab8cd9941a3bbd18', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (320, 15, 'ceb17c4f05ad22d2ab8cd9941a3bbd18', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (321, 16, 'ceb17c4f05ad22d2ab8cd9941a3bbd18', NULL, NULL);
INSERT INTO `desk_controller_initial` VALUES (322, 1, '336d3fc20ce3c9d6fe5b0c31a2640868', '354196', '5183122340677450');
INSERT INTO `desk_controller_initial` VALUES (323, 2, '336d3fc20ce3c9d6fe5b0c31a2640868', '354196', '2453189741275906');
INSERT INTO `desk_controller_initial` VALUES (324, 3, '336d3fc20ce3c9d6fe5b0c31a2640868', '354196', '6704375902211568');
INSERT INTO `desk_controller_initial` VALUES (325, 4, '336d3fc20ce3c9d6fe5b0c31a2640868', '354196', '8295315309820746');
INSERT INTO `desk_controller_initial` VALUES (326, 5, '336d3fc20ce3c9d6fe5b0c31a2640868', '354196', '4616853205392740');
INSERT INTO `desk_controller_initial` VALUES (327, 6, '336d3fc20ce3c9d6fe5b0c31a2640868', '354196', '5712936617840943');
INSERT INTO `desk_controller_initial` VALUES (328, 7, '336d3fc20ce3c9d6fe5b0c31a2640868', '354196', '4395659830112876');
INSERT INTO `desk_controller_initial` VALUES (329, 8, '336d3fc20ce3c9d6fe5b0c31a2640868', '354196', '1790465281450937');
INSERT INTO `desk_controller_initial` VALUES (330, 9, '336d3fc20ce3c9d6fe5b0c31a2640868', '354196', '6709449821813250');
INSERT INTO `desk_controller_initial` VALUES (331, 10, '336d3fc20ce3c9d6fe5b0c31a2640868', '354196', '5017145836428709');
INSERT INTO `desk_controller_initial` VALUES (332, 11, '336d3fc20ce3c9d6fe5b0c31a2640868', '354196', '8875025417363169');
INSERT INTO `desk_controller_initial` VALUES (333, 12, '336d3fc20ce3c9d6fe5b0c31a2640868', '354196', '3206442705899361');
INSERT INTO `desk_controller_initial` VALUES (334, 13, '336d3fc20ce3c9d6fe5b0c31a2640868', '354196', '7752639080345816');
INSERT INTO `desk_controller_initial` VALUES (335, 14, '336d3fc20ce3c9d6fe5b0c31a2640868', '354196', '4096842195207761');
INSERT INTO `desk_controller_initial` VALUES (336, 15, '336d3fc20ce3c9d6fe5b0c31a2640868', '354196', '4679328078131906');
INSERT INTO `desk_controller_initial` VALUES (337, 16, '336d3fc20ce3c9d6fe5b0c31a2640868', '354196', '4758731502634896');

-- ----------------------------
-- Table structure for desk_invitation
-- ----------------------------
DROP TABLE IF EXISTS `desk_invitation`;
CREATE TABLE `desk_invitation`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `booking_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nik` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `internal` int NOT NULL DEFAULT 1,
  `attendance_status` int NOT NULL DEFAULT 0,
  `attendance_reason` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `execute_attendance` int NULL DEFAULT 0,
  `execute_door_access` int NOT NULL,
  `email` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `company` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `position` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `is_pic` tinyint(1) NOT NULL,
  `pin_room` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `created_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `is_deleted` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of desk_invitation
-- ----------------------------

-- ----------------------------
-- Table structure for desk_room
-- ----------------------------
DROP TABLE IF EXISTS `desk_room`;
CREATE TABLE `desk_room`  (
  `_generate` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `building_id` int NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `capacity` int NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `automation_id` int NOT NULL,
  `facility_room` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `work_day` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `work_time` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `work_start` time NOT NULL,
  `work_end` time NOT NULL,
  `google_map` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `image2` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `room_map` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price` bigint NOT NULL,
  `location` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `is_disabled` tinyint(1) NULL DEFAULT NULL,
  `created_by` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `posmap` enum('landscape','potrait') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`_generate`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of desk_room
-- ----------------------------
INSERT INTO `desk_room` VALUES (3, '194208', 1, 'Ruang IT Vendor', 110, '', 0, 'Projector,Light,Screen,LCD TV,Air Conditioner,High speed internet,Power outlet,Whiteboard', 'SUNDAY,MONDAY,TUESDAY,WEDNESDAY,THURSDAY,FRIDAY,SATURDAY', '06:00-23:59', '06:00:00', '23:59:00', 'https://www.google.com/maps', '194208.png', '194208_9418563702_1870562934.jpg', '194208_3708654912.png', 0, '', 0, NULL, '2023-03-10 07:57:28', '2025-01-17 13:06:20', 0, 'potrait');
INSERT INTO `desk_room` VALUES (4, '934052', 1, 'Ruang IT Staff', 100, '', 0, 'Projector,Light,Screen,LCD TV,Air Conditioner,High speed internet,Power outlet,Whiteboard', 'SUNDAY,MONDAY,TUESDAY,WEDNESDAY,THURSDAY,FRIDAY,SATURDAY', '06:00-23:59', '06:00:00', '23:59:00', '', '934052.png', '934052_0137865429_2071856349.jpeg', '934052_3291507468.png', 0, '', 0, NULL, '2023-05-05 09:04:23', '2025-01-17 13:06:04', 0, 'landscape');
INSERT INTO `desk_room` VALUES (10, '054782', 1, 'Test', 10, 'a', 0, 'Projector,Light,Screen,LCD TV,Air Conditioner,High speed internet,Power outlet,Whiteboard,Coffe Machine', 'SUNDAY,MONDAY,TUESDAY,WEDNESDAY,THURSDAY,FRIDAY,SATURDAY', '06:00-23:59', '06:00:00', '23:59:00', 'as', '054782.png', NULL, '054782.png', 0, 'as', 0, NULL, '2025-01-24 08:03:34', '2025-01-24 08:03:34', 1, 'potrait');
INSERT INTO `desk_room` VALUES (11, '354196', 1, 'IRS LT2', 250, '', 0, 'Screen,LCD TV,Air Conditioner,High speed internet', 'SUNDAY,MONDAY,TUESDAY,WEDNESDAY,THURSDAY,FRIDAY,SATURDAY', '00:00-23:59', '00:00:00', '23:59:00', '', '354196.png', NULL, '354196_6253970418.png', 0, '', 0, NULL, '2026-08-28 14:32:14', '2026-08-28 15:00:56', 0, 'landscape');

-- ----------------------------
-- Table structure for desk_room_table
-- ----------------------------
DROP TABLE IF EXISTS `desk_room_table`;
CREATE TABLE `desk_room_table`  (
  `_generate` int NOT NULL AUTO_INCREMENT,
  `desk_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `desk_room_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `zone_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `block_number` int NULL DEFAULT NULL,
  `pointer_desk_x` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `pointer_desk_y` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `datetime` datetime NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` int NULL DEFAULT NULL,
  `is_enable` tinyint(1) NULL DEFAULT NULL,
  `disable_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `device_status` tinyint(1) NULL DEFAULT NULL,
  `last_device_on_at` datetime NULL DEFAULT NULL,
  `last_device_off_at` datetime NULL DEFAULT NULL,
  `last_used_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `last_booking_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`_generate`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 269 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of desk_room_table
-- ----------------------------
INSERT INTO `desk_room_table` VALUES (10, '7884490122516337', '194208', 'darb00', 1, '76', '97', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (11, '6753118798652404', '194208', 'darb00', 3, '569', '431', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (12, '7274338961286554', '194208', 'darb00', 1, '454', '83', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (13, '6693195017234785', '194208', 'jkcwgQ', 1, '742', '4', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (14, '8477602855313429', '934052', 'AMCnSq', 1, '232', '259', '2026-08-26 22:48:39', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (15, '6284537300294715', '934052', 'AMCnSq', 1, '455', '86', '2026-08-26 22:48:39', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (16, '4079660725332145', '934052', 'AMCnSq', 2, '462', '252', '2026-08-26 22:48:39', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (17, '1887952014260473', '194208', 'darb00', 2, '489', '158', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (18, '6302129464975138', '194208', 'jkcwgQ', 2, '190', '83', '2026-08-26 22:48:39', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (19, '9107880261769355', '194208', 'EKtOZc', 3, '334', '264', '2026-08-26 22:48:39', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (20, '6175644998705282', '194208', '5VjcpS', 4, '552', '248', '2026-08-26 22:48:39', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (21, '4719183586750234', '194208', '5VjcpS', 1, '457', '294', '2026-08-26 22:48:39', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (22, '3147071853609824', '194208', '5VjcpS', 6, '502', '313', '2026-08-26 22:48:39', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (23, '6767224931495803', '194208', '1DvjJB', 1, '745', '158', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (24, '1952789681324030', '194208', '1DvjJB', 2, '683', '85', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (25, '8315653074124928', '194208', 'dgycrJ', 1, '799', '264', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (26, '6218785642304305', '194208', 'dgycrJ', 2, '687', '271', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (27, '4108795803413956', '194208', 'dgycrJ', 3, '743', '343', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (28, '3749700168126854', '194208', 'dgycrJ', 3, '748', '344', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (29, '4658167970239048', '194208', 'cUTv77', 1, '810', '430', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (30, '6383095028124157', '194208', 'cUTv77', 2, '688', '431', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (31, '9285243916704713', '194208', 'tLrxpH', 1, '571', '435', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (32, '5136289174235490', '194208', 'tLrxpH', 2, '455', '433', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (33, '4017586326893014', '194208', 'tLrxpH', 3, '516', '360', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (34, '8549205717384360', '934052', 'AMCnSq', 0, '12.968750953674316', '6.9791669845581055', '2026-08-26 22:48:39', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (35, '6610593352780794', '934052', 'AMCnSq', 1, '517.96875', '91.97265625', '2026-08-26 22:48:39', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (36, '7580681940216239', '934052', 'AMCnSq', 0, NULL, NULL, '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (37, '2718907056134263', '934052', 'AMCnSq', 0, NULL, NULL, '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (38, '9689831516030742', '934052', 'AMCnSq', 5, '313', '77', '2026-08-26 22:48:39', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (39, '8152974730606423', '934052', 'AMCnSq', 7, '691', '271', '2026-08-26 22:48:39', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (40, '9202384541803776', '934052', 'AMCnSq', 9, '15.989583969116211', '220.98959350585938', '2026-08-26 22:48:39', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (41, '3578629876044311', '934052', 'AMCnSq', 77, '725.98614501953', '65.986114501953', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (42, '6224795836490711', '934052', 'AMCnSq', 78, '765.98614501953', '67.986114501953', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (43, '4512069146388973', '934052', 'AMCnSq', 79, '764.98614501953', '121.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (44, '4635900825427867', '934052', 'AMCnSq', 80, '764.98614501953', '179', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (45, '0216859043127463', '934052', 'AMCnSq', 84, '726', '253.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (46, '4629360158947137', '934052', 'AMCnSq', 81, '770.98614501953', '363', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (47, '6195807397264254', '934052', 'AMCnSq', 82, '769.98614501953', '309', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (48, '2511947725893043', '934052', 'AMCnSq', 71, '695', '253.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (49, '7638457108250693', '934052', 'AMCnSq', 72, '694.98614501953', '308', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (50, '6456172783520940', '934052', 'AMCnSq', 73, '694.95831298828', '360.97222900391', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (51, '8441265178360097', '934052', 'AMCnSq', 74, '708.98614501953', '400.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (52, '2004933795621874', '934052', 'AMCnSq', 75, '724.98614501953', '176.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (53, '9147367803981546', '934052', 'AMCnSq', 76, '726.98614501953', '121.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (54, '1829104277550668', '934052', 'AMCnSq', 87, '897.97222900391', '192.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (55, '6892937117404365', '934052', 'AMCnSq', 88, '899.97222900391', '247.97222900391', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (56, '5329716320098486', '934052', 'AMCnSq', 89, '897.97222900391', '302.97222900391', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (57, '7210313792405685', '934052', 'AMCnSq', 85, '728', '308.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (58, '4893523501768624', '934052', 'AMCnSq', 90, '796.97222900391', '136.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (59, '3095822801375614', '934052', 'AMCnSq', 86, '728.94445800781', '361.95834350586', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (60, '8483956765479212', '934052', 'AMCnSq', 91, '795.97222900391', '83.972221374512', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (61, '6459916845371372', '934052', 'AMCnSq', 92, '841.97222900391', '84.986114501953', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (62, '1754986264735091', '934052', 'AMCnSq', 93, '839.98614501953', '139.97222900391', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (63, '4532710078923696', '934052', 'AMCnSq', 94, '794.98614501953', '402.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (64, '8736945528947020', '934052', 'AMCnSq', 1, '268.98611450195', '189', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (65, '8634130498521257', '934052', 'AMCnSq', 95, '808.98614501953', '362.97222900391', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (66, '2368547130860491', '934052', 'AMCnSq', 2, '269', '135.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (67, '9103282604554737', '934052', 'AMCnSq', 3, '269', '80.986114501953', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (68, '0669911385427327', '934052', 'AMCnSq', 96, '808.98614501953', '309.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (69, '8107054622733594', '934052', 'AMCnSq', 4, '315.98611450195', '82.986114501953', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (70, '4335624608597809', '934052', 'AMCnSq', 5, '315', '136.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (71, '5931707326698458', '934052', 'AMCnSq', 97, '808.97222900391', '256.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (72, '1532328605081979', '934052', 'AMCnSq', 98, '857.98614501953', '255.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (73, '6950273187962443', '934052', 'AMCnSq', 99, '859.97222900391', '309.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (74, '9239824067508314', '934052', 'AMCnSq', 6, '312.98611450195', '189.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (75, '2470316921057598', '934052', 'AMCnSq', 7, '263', '435.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (76, '4804265773601912', '934052', 'AMCnSq', 100, '860.97222900391', '361.97222900391', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (77, '9229315775083841', '934052', 'AMCnSq', 8, '265.98611450195', '382.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (78, '1396042265485318', '934052', 'AMCnSq', 9, '261.98611450195', '327.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (79, '8089696072435741', '934052', 'AMCnSq', 10, '264.98611450195', '275', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (80, '0608926491318747', '934052', 'AMCnSq', 11, '315.98611450195', '275.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (81, '6146180827053275', '934052', 'AMCnSq', 12, '312', '329.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (82, '9329216488567074', '934052', 'AMCnSq', 13, '313.98611450195', '382.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (83, '5290465241736807', '934052', 'AMCnSq', 14, '314.98611450195', '436.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (84, '9984126854367351', '934052', 'AMCnSq', 33, '454.98611450195', '188.97222900391', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (85, '9706130655492832', '934052', 'AMCnSq', 34, '456.98611450195', '133.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (86, '6568754902823904', '934052', 'AMCnSq', 35, '456.97222900391', '82.972221374512', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (87, '1093076379224868', '934052', 'AMCnSq', 36, '501.98611450195', '82.986114501953', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (88, '8957489103537240', '934052', 'AMCnSq', 37, '501.98611450195', '134.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (89, '5887043911762492', '934052', 'AMCnSq', 38, '500.98611450195', '190.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (90, '8622917033567840', '934052', 'AMCnSq', 17, '350.98611450195', '135.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (91, '5784341380260619', '934052', 'AMCnSq', 39, '455.98611450195', '435.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (92, '9955728480716103', '934052', 'AMCnSq', 18, '349.98611450195', '83.986114501953', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (93, '5279447206896310', '934052', 'AMCnSq', 19, '398.98611450195', '81.986114501953', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (94, '3180569842071423', '934052', 'AMCnSq', 40, '454.97222900391', '384.97222900391', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (95, '0105472329547861', '934052', 'AMCnSq', 41, '455.98611450195', '328.97222900391', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (96, '4787102330916628', '934052', 'AMCnSq', 42, '454.98611450195', '277.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (97, '2187009644533956', '934052', 'AMCnSq', 20, '395.98611450195', '135.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (98, '0714559107283668', '934052', 'AMCnSq', 43, '501.98611450195', '276.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (99, '1579230697854813', '934052', 'AMCnSq', 44, '503.97222900391', '328.97222900391', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (100, '0947481558103273', '934052', 'AMCnSq', 21, '398.98611450195', '190.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (101, '8356147618043095', '934052', 'AMCnSq', 22, '346.98611450195', '477.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (102, '6189403275802514', '934052', 'AMCnSq', 45, '504.97222900391', '383.94445800781', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (103, '1473620985783502', '934052', 'AMCnSq', 23, '346.98611450195', '382', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (104, '9475400931375816', '934052', 'AMCnSq', 24, '346.98611450195', '328.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (105, '1146030923792878', '934052', 'AMCnSq', 25, '349', '275.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (106, '9981435472256103', '934052', 'AMCnSq', 26, '394.98611450195', '276.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (107, '2051197498367234', '934052', 'AMCnSq', 27, '395', '329', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (108, '2809565387104394', '934052', 'AMCnSq', 28, '394.98611450195', '382.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (109, '8450287959113466', '934052', 'AMCnSq', 29, '395', '477', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (110, '4866733950901415', '934052', 'AMCnSq', 30, '434', '476', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (111, '2814061728739056', '934052', 'AMCnSq', 83, '767.98614501953', '254.97222900391', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (112, '6234920515883797', '934052', 'AMCnSq', 15, '231.98611450195', '128.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (113, '7316878139524426', '934052', 'AMCnSq', 16, '232.98611450195', '76.986114501953', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (114, '1185295606844779', '934052', 'AMCnSq', 31, '187.98611450195', '129.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (115, '1533248299687170', '934052', 'AMCnSq', 32, '187.97222900391', '76.986114501953', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (116, '0832454176392906', '934052', 'AMCnSq', 59, '586', '279', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (117, '6112634503705497', '934052', 'AMCnSq', 60, '588', '332', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (118, '0923681160493752', '934052', 'AMCnSq', 61, '585', '385', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (119, '1529008371435962', '934052', 'AMCnSq', 62, '588', '440', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (120, '9807280136247163', '934052', 'AMCnSq', 63, '646', '122', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (121, '6429027815836910', '934052', 'AMCnSq', 64, '645', '70', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (122, '5312179408372094', '934052', 'AMCnSq', 65, '695', '66', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (123, '2877456133694005', '934052', 'AMCnSq', 66, '694', '121', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (124, '2802648495753301', '934052', 'AMCnSq', 67, '694', '177', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (125, '7298240935418576', '934052', 'AMCnSq', 68, '643', '363', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (126, '8455863942621390', '934052', 'AMCnSq', 69, '643', '308', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (127, '4725733805821664', '934052', 'AMCnSq', 70, '643.98614501953', '253.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (128, '9052871348731964', '934052', 'AMCnSq', 47, '154', '124', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (129, '0346283515296774', '934052', 'AMCnSq', 48, '156', '72', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (130, '7096243503681582', '934052', 'AMCnSq', 49, '537', '190', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (131, '1923804602379575', '934052', 'AMCnSq', 50, '538', '135', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (132, '2675814783162940', '934052', 'AMCnSq', 51, '539', '83', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (133, '9469883152127054', '934052', 'AMCnSq', 52, '588', '83', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (134, '3342182689155700', '934052', 'AMCnSq', 53, '587', '135', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (135, '9102198375564462', '934052', 'AMCnSq', 54, '587', '190', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (136, '6874425365028307', '934052', 'AMCnSq', 55, '541', '439', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (137, '4895518621323740', '934052', 'AMCnSq', 56, '540', '386', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (138, '8360417285759160', '934052', 'AMCnSq', 57, '542', '331', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (139, '3018763246591795', '934052', 'AMCnSq', 58, '538', '278', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (140, '9379642051176034', '194208', 'tAs8Q6', 1, '139.9375', '571.953125', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (141, '1560252038497893', '194208', 'tAs8Q6', 2, '172.96875', '572.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (142, '6413528198737495', '194208', 'tAs8Q6', 3, '204.953125', '574.953125', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (143, '8338445727105109', '194208', 'tAs8Q6', 4, '239.96875', '574.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (144, '3981782362604701', '194208', 'tAs8Q6', 5, '238.953125', '541.953125', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (145, '7103292588066759', '194208', 'tAs8Q6', 6, '207.96875', '540.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (146, '2389552047761490', '194208', 'tAs8Q6', 7, '171.953125', '542.9375', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (147, '7055890926211674', '194208', 'tAs8Q6', 8, '139.953125', '542.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (148, '7533646099252170', '194208', 'tAs8Q6', 9, '138.921875', '509.9375', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (149, '5105063492621778', '194208', 'tAs8Q6', 10, '169.984375', '508.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (150, '3694817260589234', '194208', 'tAs8Q6', 11, '203.96875', '506.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (151, '1208687709515439', '194208', 'tAs8Q6', 12, '235.953125', '507.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (152, '4757602360915891', '194208', 'tAs8Q6', 13, '240.953125', '477.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (153, '0595487309142831', '194208', 'tAs8Q6', 14, '205.953125', '475.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (154, '6437306418925120', '194208', 'tAs8Q6', 15, '171.96875', '476.953125', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (155, '1945845103026298', '194208', 'tAs8Q6', 16, '137.96875', '478.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (156, '1405217695738640', '194208', 'tAs8Q6', 17, '138.96875', '445.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (157, '1323918657297044', '194208', 'tAs8Q6', 18, '170.96875', '443.984375', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (158, '5062309112443789', '194208', 'tAs8Q6', 19, '205.96875', '445.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (159, '3145707526939848', '194208', 'tAs8Q6', 20, '239.96875', '446.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (160, '8610435701289253', '194208', 'tAs8Q6', 21, '240.96875', '416.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (161, '9726942818004513', '194208', 'tAs8Q6', 22, '207.953125', '415.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (162, '3040897146558927', '194208', 'tAs8Q6', 23, '172.984375', '414.984375', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (163, '6335091251862408', '194208', 'tAs8Q6', 24, '140.96875', '412.953125', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (164, '4739942216870318', '194208', 'tAs8Q6', 25, '136.984375', '377.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (165, '8942955301027746', '194208', 'tAs8Q6', 26, '170.96875', '378.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (166, '4402199767560328', '194208', 'tAs8Q6', 27, '204.984375', '379.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (167, '4350367408516822', '194208', 'tAs8Q6', 28, '239.96875', '379.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (168, '9815706345029163', '194208', 'tAs8Q6', 29, '239.96875', '352.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (169, '6820945812970563', '194208', 'tAs8Q6', 30, '204.984375', '350.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (170, '7035615223476881', '194208', 'tAs8Q6', 31, '170.984375', '350.984375', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (171, '7615018292653984', '194208', 'tAs8Q6', 32, '139.96875', '347.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (172, '5210067549368973', '194208', 'tAs8Q6', 33, '140.984375', '313.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (173, '1482050789656339', '194208', 'tAs8Q6', 34, '171.984375', '317.984375', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (174, '6839175870942352', '194208', 'tAs8Q6', 35, '207.96875', '317.953125', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (175, '3205836611948707', '194208', 'tAs8Q6', 36, '238.96875', '314.984375', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (176, '4582854207966317', '194208', 'tAs8Q6', 37, '241.96875', '285.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (177, '4508751608246792', '194208', 'tAs8Q6', 38, '205.96875', '283.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (178, '3585398097441262', '194208', 'tAs8Q6', 39, '175.984375', '286.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (179, '4678830591621390', '194208', 'tAs8Q6', 40, '138.96875', '288.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (180, '6848257409321761', '194208', 'tAs8Q6', 41, '138', '247.984375', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (181, '0119536672420853', '194208', 'tAs8Q6', 42, '172.984375', '247.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (182, '5879143412586079', '194208', 'tAs8Q6', 43, '206.96875', '249.984375', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (183, '5918572129743308', '194208', 'tAs8Q6', 44, '239.96875', '248.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (184, '2665485899177302', '194208', 'tAs8Q6', 45, '238.96875', '219.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (185, '1958074695602782', '194208', 'tAs8Q6', 46, '206.96875', '222.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (186, '5386963724214107', '194208', 'tAs8Q6', 47, '174.96875', '219.953125', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (187, '4660897450913257', '194208', 'tAs8Q6', 48, '137.96875', '219.984375', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (188, '1435350679842297', '194208', 'tAs8Q6', 49, '493.96875', '314.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (189, '8319468760352041', '194208', 'tAs8Q6', 50, '458.95834350586', '314.95834350586', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (190, '8656923459432107', '194208', 'tAs8Q6', 51, '425.95834350586', '313.94445800781', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (191, '3065871718450364', '194208', 'tAs8Q6', 52, '427.953125', '280.953125', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (192, '1252363004798176', '194208', 'tAs8Q6', 53, '460.953125', '281.984375', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (193, '2680348512175079', '194208', 'tAs8Q6', 54, '493.953125', '283.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (194, '0181324989056726', '194208', 'tAs8Q6', 55, '295.984375', '328.953125', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (195, '0918826957334654', '194208', 'tAs8Q6', 56, '327.96875', '328.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (196, '0617799154532403', '194208', 'tAs8Q6', 57, '361.96875', '328.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (197, '4457225161903869', '194208', 'tAs8Q6', 58, '361.96875', '296.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (198, '6289047552379168', '194208', 'tAs8Q6', 59, '329.953125', '296.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (199, '9368540820175376', '194208', 'tAs8Q6', 60, '296.984375', '297.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (200, '5249838410017935', '194208', 'tAs8Q6', 61, '294.953125', '257.953125', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (201, '8156453263172084', '194208', 'tAs8Q6', 62, '329.953125', '258.953125', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (202, '4670179850238561', '194208', 'tAs8Q6', 63, '365.96875', '258.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (203, '3146790801643892', '194208', 'tAs8Q6', 64, '363.96875', '224.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (204, '4646385129177905', '194208', 'tAs8Q6', 65, '326.96875', '223.953125', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (205, '9286761854730590', '194208', 'tAs8Q6', 66, '296.96875', '222.984375', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (206, '9105262414873957', '194208', 'tAs8Q6', 67, '294.96875', '187.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (207, '3108922574150846', '194208', 'tAs8Q6', 68, '328.953125', '187.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (208, '1056726483949018', '194208', 'tAs8Q6', 69, '328.96875', '157.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (209, '4076835141590823', '194208', 'tAs8Q6', 70, '294.96875', '156.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (210, '3596931628807475', '934052', 'AMCnSq', 46, '503.98611450195', '437.98611450195', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (211, '5892113445026078', '194208', 'tAs8Q6', 87, '477.96875', '50.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (212, '3174456756198082', '194208', 'tAs8Q6', 88, '444.96875', '55.953125', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (213, '2516809278434135', '194208', 'tAs8Q6', 89, '407.953125', '58.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (214, '7079582846042116', '194208', 'tAs8Q6', 90, '373.96875', '63.953125', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (215, '1287632736484159', '194208', 'tAs8Q6', 91, '337.96875', '66.984375', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (216, '6034384067289259', '194208', 'tAs8Q6', 92, '306.953125', '66.97265625', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (217, '9631629744325800', '194208', 'tAs8Q6', 93, '275.9375', '65.95703125', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (218, '9316284677095142', '194208', 'tAs8Q6', 94, '242.9296875', '69.94140625', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (219, '9214534778359201', '194208', 'tAs8Q6', 95, '206.9140625', '79.921875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (220, '8298663301457215', '194208', 'tAs8Q6', 96, '168.9453125', '71.93359375', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (221, '2430651841056827', '194208', 'tAs8Q6', 97, '238.96875', '146.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (222, '1894922104530753', '194208', 'tAs8Q6', 98, '205.96875', '145.96875', '2026-08-26 22:48:39', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (223, '1319628659207453', '194208', 'tAs8Q6', 99, '175.96875', '149.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (224, '9661750129248703', '194208', 'tAs8Q6', 100, '173.96875', '177.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (225, '6112335748096209', '194208', 'tAs8Q6', 101, '205.96875', '180.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (226, '1223769790848056', '194208', 'tAs8Q6', 102, '238.96875', '180.953125', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (227, '5147399240873606', '194208', 'tAs8Q6', 71, '424.96875', '243.953125', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (228, '6560125298193803', '194208', 'tAs8Q6', 72, '458.96875', '244.984375', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (229, '5874716220610583', '194208', 'tAs8Q6', 73, '493.96875', '242.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (230, '8136054022397476', '194208', 'tAs8Q6', 74, '496.96875', '210.984375', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (231, '5632474288156001', '194208', 'tAs8Q6', 75, '459.96875', '214.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (232, '2446250093796715', '194208', 'tAs8Q6', 76, '424.96875', '211.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (233, '2306985751402841', '194208', 'tAs8Q6', 77, '430.96875', '173.953125', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (234, '3665112987839405', '194208', 'tAs8Q6', 78, '462.96875', '173.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (235, '1437136256897025', '194208', 'tAs8Q6', 79, '497.96875', '174.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (236, '8927704092431816', '194208', 'tAs8Q6', 80, '496.96875', '144.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (237, '5793203958104761', '194208', 'tAs8Q6', 81, '460.96875', '142.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (238, '0733750898924514', '194208', 'tAs8Q6', 12, '351.9965515136719', '240.98959350585938', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (239, '8957409024683116', '194208', 'tAs8Q6', 13, '412.98614501953125', '286.9965515136719', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (240, '6420523091774316', '194208', 'tAs8Q6', 84, '362.953125', '155.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (241, '7629376114289345', '194208', 'tAs8Q6', 85, '362.984375', '115.96875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (242, '6244837108709515', '194208', 'tAs8Q6', 86, '331.953125', '117.94921875', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (243, '4220459810196767', '194208', 'tAs8Q6', 82, '428.96875', '143.953125', '2026-08-26 22:48:39', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (244, '9592647158264107', '194208', 'tAs8Q6', 83, '362.96875', '188.953125', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (247, '8531128367596792', '194208', 'tAs8Q6', 999, '478.9921875', '552.99609375', '2026-08-26 22:48:39', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (248, '7340086965897141', '194208', 'tAs8Q6', 988, '548.98828125', '566.98828125', '2026-08-26 22:48:39', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (249, '9740856724315013', '194208', 'tAs8Q6', 888, '456.99609375', '571.9921875', '2026-08-26 22:48:39', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (250, '4566593407320829', '194208', 'tAs8Q6', 98, '206.9921875', '145', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (251, '0557621036828941', '194208', 'tAs8Q6', 82, '425', '140.9765625', '2026-08-26 22:48:39', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (252, '5183122340677450', '354196', 'nweDYy', 1, '78.9914779663086', '70', '2026-08-28 14:34:02', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (253, '2453189741275906', '354196', 'nweDYy', 2, '95.99431610107422', '210', '2026-08-28 14:34:14', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (254, '6704375902211568', '354196', 'nweDYy', 3, '213.99147033691406', '85.99431610107422', '2026-08-28 14:34:29', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (255, '8295315309820746', '354196', 'nweDYy', 4, '300', '96.98863220214844', '2026-08-28 14:34:46', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (256, '4616853205392740', '354196', 'nweDYy', 5, '336.9886169433594', '393.991455078125', '2026-08-28 14:35:05', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (257, '5712936617840943', '354196', 'nweDYy', 6, '145', '420', '2026-08-28 14:35:16', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (258, '4395659830112876', '354196', 'nweDYy', 7, '838.991455078125', '377.99713134765625', '2026-08-28 14:35:27', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (259, '1790465281450937', '354196', 'nweDYy', 8, '547.9971313476562', '168.99147033691406', '2026-08-28 14:35:42', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (260, '6709449821813250', '354196', 'nweDYy', 9, '706.9885864257812', '239.99998474121094', '2026-08-28 14:35:57', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (261, '5017145836428709', '354196', 'nweDYy', 10, '697.9971313476562', '351.9886169433594', '2026-08-28 14:36:09', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (262, '8875025417363169', '354196', 'nweDYy', 11, '567.9971313476562', '485.9942932128906', '2026-08-28 14:36:23', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (263, '5140912089372678', '354196', 'nweDYy', 12, '760.9942626953125', '470.9942932128906', '2026-08-28 14:36:51', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (264, '7752639080345816', '354196', 'nweDYy', 13, '690.9943237304688', '465.9942932128906', '2026-08-28 14:36:45', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (265, '4096842195207761', '354196', 'nweDYy', 14, '864.9999389648438', '462.99713134765625', '2026-08-28 14:37:17', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (266, '4679328078131906', '354196', 'nweDYy', 15, '625', '167.9971466064453', '2026-08-28 14:37:36', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (267, '4758731502634896', '354196', 'nweDYy', 16, '830.9942626953125', '138.99147033691406', '2026-08-28 14:37:48', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `desk_room_table` VALUES (268, '3206442705899361', '354196', 'nweDYy', 12, '760.9942626953125', '468.991455078125', '2026-08-28 14:38:08', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for desk_room_zone
-- ----------------------------
DROP TABLE IF EXISTS `desk_room_zone`;
CREATE TABLE `desk_room_zone`  (
  `_generate` int NOT NULL AUTO_INCREMENT,
  `desk_room_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `zone_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `pointer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `size` double NULL DEFAULT NULL,
  `color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`_generate`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 48 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of desk_room_zone
-- ----------------------------
INSERT INTO `desk_room_zone` VALUES (9, '934052', 'AMCnSq', 'IT Staff', '', 40, 'black');
INSERT INTO `desk_room_zone` VALUES (39, '194208', 'tAs8Q6', 'IT Vendor', '', 40, 'black');
INSERT INTO `desk_room_zone` VALUES (40, '094182', 'PxdThj', 'Test', '', 40, 'black');
INSERT INTO `desk_room_zone` VALUES (41, '842137', 'bwPxoQ', 'Zona 1', '', 40, 'black');
INSERT INTO `desk_room_zone` VALUES (42, '368419', 'bwPxoQ', 'Zona 1', '', 40, 'black');
INSERT INTO `desk_room_zone` VALUES (43, '178259', 'bwPxoQ', 'Zona 1', '', 40, 'black');
INSERT INTO `desk_room_zone` VALUES (44, '723490', '5WFPth', 'Zona 1', '', 40, 'black');
INSERT INTO `desk_room_zone` VALUES (45, '723490', 'IIf9oI', 'Zona 3', '', 40, 'black');
INSERT INTO `desk_room_zone` VALUES (46, '054782', 'G04TvZ', 'Training', '', 40, 'black');
INSERT INTO `desk_room_zone` VALUES (47, '354196', 'nweDYy', 'Lantai 2', '', 40, 'black');

-- ----------------------------
-- Table structure for device_player_integration
-- ----------------------------
DROP TABLE IF EXISTS `device_player_integration`;
CREATE TABLE `device_player_integration`  (
  `_generate` int NOT NULL AUTO_INCREMENT,
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `serial` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `hardware_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `mac` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `os` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `info` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `version` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `is_deleted` int NULL DEFAULT NULL,
  `is_actived` int NULL DEFAULT 0,
  `created_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`_generate`, `id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of device_player_integration
-- ----------------------------
INSERT INTO `device_player_integration` VALUES (1, 'ADSMRe89f5a', '342088', 'display_smr', 'SKQ1.211006.001', 'd40ce2a1-39ef-45ef-b8bf-5600bb5337e9', '', 'android', 'Xiaomi_M2007J3SG', '', 0, 0, NULL);

-- ----------------------------
-- Table structure for divisi
-- ----------------------------
DROP TABLE IF EXISTS `divisi`;
CREATE TABLE `divisi`  (
  `id_divisi` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_perusahaan` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_department` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `divisi_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `foto` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_by` int NOT NULL,
  `created_at` int NOT NULL,
  `update_at` int NOT NULL,
  `is_deleted` int NOT NULL,
  PRIMARY KEY (`id_divisi`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of divisi
-- ----------------------------

-- ----------------------------
-- Table structure for employee
-- ----------------------------
DROP TABLE IF EXISTS `employee`;
CREATE TABLE `employee`  (
  `_generate` int NOT NULL AUTO_INCREMENT,
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `division_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'jangan isi kolom ini',
  `company_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'alocation_type',
  `department_id` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'alocation',
  `head_employee` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nik` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nik_display` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '100',
  `photo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `no_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT ' ',
  `no_ext` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `birth_date` date NULL DEFAULT NULL,
  `gender` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `card_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `card_number_real` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password_mobile` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `gb_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `fr_id` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `priority` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `is_deleted` int NOT NULL,
  `is_vip` int NULL DEFAULT 0,
  `vip_approve_bypass` int NULL DEFAULT 0,
  `vip_limit_cap_bypass` int NULL DEFAULT 0,
  `vip_lock_room` int NULL DEFAULT 0,
  `status` int NULL DEFAULT NULL COMMENT '1 = aktif ; 0 : non aktif',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `_generate`(`_generate` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 375 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of employee
-- ----------------------------
INSERT INTO `employee` VALUES (357, '20230203130023', '', '1', '10283', NULL, 'as', '20230203130023', 'as', '', 'asas', 'as', 'as', '2023-02-03', 'male', 'ass', 'as', '', '', '', '', 0, '2023-02-03 13:00:23', '2023-02-03 13:00:23', 1, 0, 0, 0, 0, NULL);
INSERT INTO `employee` VALUES (358, '20230203130055', '', '1', '10283', NULL, 'as', '20230203130055', 'as', '', 'asas', 'as', 'as', '2023-02-03', 'male', 'ass', 'as', '', '', '', '', 0, '2023-02-03 13:00:55', '2023-02-03 13:00:55', 1, 0, 0, 0, 0, NULL);
INSERT INTO `employee` VALUES (359, '20230206171324', '', '1', '10283', NULL, 'Test', '20230206171324', 'testuser', 'bc346340-177e-483f-b7f0-1d74f4fafd56.png', 'tmperdana157@gmail.com', '081', '001', '2023-02-06', 'male', 'asas', '029182', '', '', '', '', 0, '2023-02-06 17:13:24', '2023-02-06 17:13:24', 1, 0, 0, 0, 0, NULL);
INSERT INTO `employee` VALUES (360, '20231017110503', '', '1', '10283', NULL, 'Organize Microsoft 365', '20231017110503', 'orgmicrosoft365', '', 'admin@PirantiCerdasIndonesia792.onmicrosoft.com', '0829372883', '332', '1999-10-03', 'male', '', '0003852822', '', '', '', '', 0, '2023-10-17 11:05:03', '2024-06-17 01:56:34', 1, 0, 0, 0, 0, NULL);
INSERT INTO `employee` VALUES (361, '20231107121320', '', '1', '10283', NULL, 'Aditya Juda Manggala', '20231107121320', '0003852880', '', 'adityaworkplay7@gmail.com', '082287676722', '722', '2023-11-07', 'male', '', '0003852880', '', '', '', '', 0, '2023-11-07 12:13:20', '2023-11-07 12:13:53', 1, 0, 0, 0, 0, NULL);
INSERT INTO `employee` VALUES (362, '20231128160531', '', '1', '10283', NULL, 'Pantry', '20231128160531', 'pantry1', '', 'pantry@local.com', '0829372883', '722', '2023-11-28', 'male', '', '3981370387', '', '', '', '', 0, '2023-11-28 16:05:31', '2023-11-28 16:05:31', 1, 0, 0, 0, 0, NULL);
INSERT INTO `employee` VALUES (363, '20241220024501', '', '1', '10283', '', 'Tilis Tiadi', '20241220024501', '1281370387', '87df5130-0871-4f8c-a37f-eae87bdf3678.png', 'tilis.local@mail.com', '08159183157', '332', '2024-12-20', 'male', 'Jl. Buni No.19\r\nMangga Besar, Kec. Taman Sari', '0003852880', '', '', '', '', 0, '2024-12-20 02:45:01', '2024-12-20 02:45:01', 0, 0, 0, 0, 0, NULL);
INSERT INTO `employee` VALUES (1, 'admin', '', '1', '10283', '', 'Administrator', 'admin', 'admin', '', 'admin@adminmail.com', '1224334', '211', '1990-01-01', 'male', 'Alamat', '033313', '0', 'admin', '', '', 0, '2020-09-23 00:00:00', '2025-01-10 13:26:22', 0, 0, 0, 0, 0, NULL);
INSERT INTO `employee` VALUES (364, 'EC20250109163011', '', '1', '10283', 'i32420211115111902', 'iQBAL', 'EC20250109163011', '991027', '651f2dfa-7666-4598-bb0c-4c83987b5a98.png', 'eMAIL@mail.com', '08917222', '1234', '2025-01-09', 'male', '', '088291333', '', '', '', '', 0, '2025-01-09 16:30:11', '2025-01-09 16:30:11', 0, 0, 0, 0, 0, NULL);
INSERT INTO `employee` VALUES (365, 'EC20250109164141', '', '12', '20250109162715k6vK', 'EC20250109163011', 'Bowo', 'EC20250109164141', '54', 'af8fa6d1-84d8-4aa6-a360-35176362885b.png', 'eMAIL@mail.com', '8917222', '1234', '2025-01-09', 'male', '', '4245444', '', '', '', '', 0, '2025-01-09 16:41:41', '2025-01-09 16:41:41', 0, 0, 0, 0, 0, NULL);
INSERT INTO `employee` VALUES (366, 'EC20250110131102', '', '12', '20250109162715k6vK', 'EC20250109163011', 'Handi', 'EC20250110131102', '55', '', 'eMAIL@mail.com', '89172224', '443', '2025-01-10', 'male', '', '123444', '', '', '', '', 0, '2025-01-10 13:11:02', '2025-01-10 13:11:02', 0, 0, 0, 0, 0, NULL);
INSERT INTO `employee` VALUES (369, 'EC20250110132409', '', '12', '20250109162715k6vK', 'EC20250109164141', 'Cahya', 'EC20250110132409', '5589000', '', 'email.local@mail.com', '0823334', '112', '2025-01-10', 'male', '', '445631', '', '', '', '', 0, '2025-01-10 13:24:09', '2025-01-10 13:24:09', 0, 0, 0, 0, 0, NULL);
INSERT INTO `employee` VALUES (370, 'EC20250124150641', '', '12', '20250109162715k6vK', 'EC20250109164141', 'Raka', 'EC20250124150641', '12', '', 'email.local@mail.com', '0823334', '112', '2025-01-24', 'male', 'test trainig ', '12', '', '', '', '', 0, '2025-01-24 15:06:41', '2025-01-24 15:06:41', 0, 0, 0, 0, 0, NULL);
INSERT INTO `employee` VALUES (371, 'EC20251003193126', '', '12', '20250109162715k6vK', 'EC20250109164141', 'riyan', 'EC20251003193126', '123', '', 'asndjsnad@gmail.com', '', '', '2025-10-03', 'male', '', '', '', '', '', '', 0, '2025-10-03 19:31:26', '2025-10-03 19:31:26', 0, 0, 0, 0, 0, NULL);
INSERT INTO `employee` VALUES (372, 'EC20251004081530', '', '12', '20250109162715k6vK', 'EC20250109163011', 'Fenny', 'EC20251004081530', '0091', '', 'tamu@local.com', '', '0091', '2025-10-04', 'female', '', '0091', '', '', '', '', 0, '2025-10-04 08:15:30', '2025-10-04 08:15:30', 0, 0, 0, 0, 0, NULL);
INSERT INTO `employee` VALUES (373, 'EC20251004081605', '', '12', '20250109162715k6vK', 'EC20250109163011', 'Yudist', 'EC20251004081605', '0092', '', 'tamu@local.com', '', '0092', '2025-10-04', 'male', '', '0092', '', '', '', '', 0, '2025-10-04 08:16:05', '2025-10-04 08:16:05', 0, 0, 0, 0, 0, NULL);
INSERT INTO `employee` VALUES (374, 'EC20251004081648', '', '12', '20250109162715k6vK', 'EC20250109163011', 'Juda', 'EC20251004081648', '0093', '', 'tamu@local.com', '', '0093', '2025-10-04', 'male', '', '0093', '', '', '', '', 0, '2025-10-04 08:16:48', '2025-10-04 08:16:48', 0, 0, 0, 0, 0, NULL);
INSERT INTO `employee` VALUES (367, 'EU2564473728613919', '', '1', '10283', '109927', 'Jhon Doe', 'EU2564473728613919', '109928', '', 'example@email.com', '628819276', '`001', '1997-01-01', 'male', 'tempat tinggal', '0016421615', '0016421615', '', '', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 0, 0, 0, 0, NULL);
INSERT INTO `employee` VALUES (368, 'EU9716528051764893', '', '1', '10283', '109927', 'Bagas', 'EU9716528051764893', '109929', '', 'example@email.com', '628819276', '`002', '1997-01-01', 'male', 'tempat tinggal', '0016421616', '0016421616', '', '', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, 0, 0, 0, NULL);
INSERT INTO `employee` VALUES (295, 'i32420211115111902', '', '1', '10283', NULL, 'Alvin', 'i32420211115111902', 'user05', '', 'alvin@gmail.com', '', '', '2000-11-15', 'male', '', '', '', '', '', '', 0, '2021-11-15 11:19:02', '2023-10-27 11:14:01', 0, 1, 1, 1, 1, NULL);

-- ----------------------------
-- Table structure for facility
-- ----------------------------
DROP TABLE IF EXISTS `facility`;
CREATE TABLE `facility`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `google_icon` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_by` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of facility
-- ----------------------------
INSERT INTO `facility` VALUES (3, 'Projector', '', NULL, '2019-09-19 07:47:11', '2019-11-19 03:01:59', 0);
INSERT INTO `facility` VALUES (4, 'Screen', '', NULL, '2019-11-19 03:01:55', '2019-11-19 03:01:55', 1);
INSERT INTO `facility` VALUES (5, 'Light', '', NULL, '2019-12-05 18:43:22', '2019-12-05 18:43:22', 0);
INSERT INTO `facility` VALUES (6, 'Screen', '', NULL, '2019-12-05 18:43:42', '2019-12-05 18:43:42', 0);
INSERT INTO `facility` VALUES (7, 'LCD TV', '', NULL, '2019-12-18 02:47:21', '2020-02-03 16:48:46', 0);
INSERT INTO `facility` VALUES (8, 'Air Conditioner', '', NULL, '2020-01-22 05:12:02', '2021-06-15 04:21:25', 0);
INSERT INTO `facility` VALUES (9, 'High speed internet', '', NULL, '2020-02-03 16:49:11', '2020-02-03 16:49:11', 0);
INSERT INTO `facility` VALUES (10, 'Power outlet', '', NULL, '2020-02-03 16:49:28', '2020-02-03 16:49:28', 0);
INSERT INTO `facility` VALUES (11, 'Whiteboard', 'chat_bubble_outline', NULL, '2022-09-19 09:08:09', '2022-09-19 09:08:09', 0);
INSERT INTO `facility` VALUES (13, 'Coffe Machine', 'av_timer', NULL, '2025-01-09 09:31:12', '2025-01-09 09:31:12', 0);
INSERT INTO `facility` VALUES (14, 'Laptop ROG ', 'laptop_windows', NULL, '2025-01-24 08:11:30', '2025-01-24 08:11:30', 0);

-- ----------------------------
-- Table structure for helpdesk_monitor
-- ----------------------------
DROP TABLE IF EXISTS `helpdesk_monitor`;
CREATE TABLE `helpdesk_monitor`  (
  `_generate` int NOT NULL AUTO_INCREMENT,
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `room_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `datetime` datetime NOT NULL,
  `action` int NOT NULL,
  `response` int NOT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `reason_response` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_deleted` int NOT NULL,
  PRIMARY KEY (`_generate`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of helpdesk_monitor
-- ----------------------------

-- ----------------------------
-- Table structure for integration_365
-- ----------------------------
DROP TABLE IF EXISTS `integration_365`;
CREATE TABLE `integration_365`  (
  `id` int NOT NULL,
  `status` int NULL DEFAULT NULL COMMENT '0; non| 1 : enable | 2 disable',
  `code` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `refresh_token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `access_token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `display_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `userPrincipalName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `account_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `scope` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `refresh_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of integration_365
-- ----------------------------

-- ----------------------------
-- Table structure for kiosk_display
-- ----------------------------
DROP TABLE IF EXISTS `kiosk_display`;
CREATE TABLE `kiosk_display`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `display_serial` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `display_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `display_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `background` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `running_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `title_kiosk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `display_uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `display_hw_serial` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `koordinate` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `is_logged` tinyint(1) NULL DEFAULT 0,
  `last_logged` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of kiosk_display
-- ----------------------------
INSERT INTO `kiosk_display` VALUES (1, '632717', 'display_deskbooking', 'Kiosk Deskbooking Demo', '', 'Demo', 'Deskbooking', 'C02GX0T3Q05D', 'B747B891-04EC-3617-95EC-05E037610520', NULL, 1, '2025-01-17 20:49:05', '2025-01-17 20:49:05', 0);
INSERT INTO `kiosk_display` VALUES (2, '632718', 'display_deskbooking', 'Kiosk Deskbooking IT Vendor Staff', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2024-12-20 01:15:04', 1);

-- ----------------------------
-- Table structure for level
-- ----------------------------
DROP TABLE IF EXISTS `level`;
CREATE TABLE `level`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `default_menu` int NOT NULL,
  `sort_level` int NULL DEFAULT NULL,
  `created_by` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2020 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of level
-- ----------------------------
INSERT INTO `level` VALUES (1, 'Administrator', 7, 2, NULL, '2019-09-19 08:23:17', '2019-09-19 08:23:17', 0);
INSERT INTO `level` VALUES (2, 'Employee', 7, 3, NULL, '2019-09-19 11:38:57', '2019-09-19 11:38:58', 0);
INSERT INTO `level` VALUES (3, 'Employee Old', 7, NULL, NULL, '2019-09-19 11:38:57', '2019-09-19 11:38:57', 1);
INSERT INTO `level` VALUES (4, 'Pantry Display', 7, 4, NULL, '2019-09-19 11:38:57', '2019-09-19 11:38:57', 1);
INSERT INTO `level` VALUES (5, 'Pantry Operator', 7, 5, NULL, '2019-09-19 11:38:57', '2019-09-19 11:38:57', 1);
INSERT INTO `level` VALUES (6, 'Operator Meeting', 7, 6, NULL, '2019-09-19 11:38:57', '2019-09-19 11:38:57', 1);
INSERT INTO `level` VALUES (7, 'Super Admin', 7, 1, NULL, NULL, NULL, 1);

-- ----------------------------
-- Table structure for level_descriptiion
-- ----------------------------
DROP TABLE IF EXISTS `level_descriptiion`;
CREATE TABLE `level_descriptiion`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `level_id` int NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_deleted` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of level_descriptiion
-- ----------------------------
INSERT INTO `level_descriptiion` VALUES (1, 1, '{\"cms\":{\"name\":\"Manage CMS\",\"desc\":\"Allow user to sign in to your CMS using their username and access the following..\",\"detail\":[\"Manage company\",\"Manage employee\",\"Manage room\",\"Manage booking\",\"Manage report\",\"Manage invoice\",\"Manage pantry\",\"Manage user\"]}}', 0);
INSERT INTO `level_descriptiion` VALUES (2, 2, '{\"apps\":{\"name\":\"Mobile Apps\",\"desc\":\"Allow user to sign in to your Apps using their username, nik and access the following.\",\"detail\":[\"Book a meeting\",\"Invite a partisipant\",\"Get notification\",\"Check room\",\"Order Pantry\"]}}', 0);

-- ----------------------------
-- Table structure for level_detail
-- ----------------------------
DROP TABLE IF EXISTS `level_detail`;
CREATE TABLE `level_detail`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `level_id` int NOT NULL,
  `menu_id` int NOT NULL,
  `coment` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 47 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of level_detail
-- ----------------------------
INSERT INTO `level_detail` VALUES (1, 1, 1, 'ADMIN');
INSERT INTO `level_detail` VALUES (2, 1, 2, 'ADMIN');
INSERT INTO `level_detail` VALUES (3, 1, 3, 'ADMIN');
INSERT INTO `level_detail` VALUES (4, 1, 4, 'ADMIN');
INSERT INTO `level_detail` VALUES (5, 1, 5, 'ADMIN');
INSERT INTO `level_detail` VALUES (6, 1, 6, 'ADMIN');
INSERT INTO `level_detail` VALUES (8, 1, 7, 'ADMIN');
INSERT INTO `level_detail` VALUES (9, 1, 8, 'ADMIN');
INSERT INTO `level_detail` VALUES (10, 1, 9, 'ADMIN');
INSERT INTO `level_detail` VALUES (11, 1, 10, 'ADMIN');
INSERT INTO `level_detail` VALUES (12, 1, 11, 'ADMIN');
INSERT INTO `level_detail` VALUES (13, 1, 12, 'ADMIN');
INSERT INTO `level_detail` VALUES (14, 1, 13, 'ADMIN');
INSERT INTO `level_detail` VALUES (15, 1, 14, 'ADMIN');
INSERT INTO `level_detail` VALUES (16, 1, 15, 'ADMIN');
INSERT INTO `level_detail` VALUES (17, 1, 16, 'ADMIN');
INSERT INTO `level_detail` VALUES (18, 1, 17, 'ADMIN');
INSERT INTO `level_detail` VALUES (19, 1, 18, 'ADMIN');
INSERT INTO `level_detail` VALUES (20, 1, 19, 'ADMIN');
INSERT INTO `level_detail` VALUES (21, 1, 20, 'ADMIN');
INSERT INTO `level_detail` VALUES (22, 1, 21, 'ADMIN');
INSERT INTO `level_detail` VALUES (23, 1, 22, 'ADMIN');
INSERT INTO `level_detail` VALUES (24, 1, 23, 'ADMIN');
INSERT INTO `level_detail` VALUES (25, 2, 12, 'user');
INSERT INTO `level_detail` VALUES (26, 2, 7, 'user');
INSERT INTO `level_detail` VALUES (27, 2, 2, 'ADMIN');
INSERT INTO `level_detail` VALUES (28, 1, 24, 'ADMIN');
INSERT INTO `level_detail` VALUES (29, 1, 25, 'ADMIN');
INSERT INTO `level_detail` VALUES (30, 1, 26, 'ADMIN');
INSERT INTO `level_detail` VALUES (31, 1, 27, 'ADMIN');
INSERT INTO `level_detail` VALUES (32, 1, 28, 'ADMIN');
INSERT INTO `level_detail` VALUES (33, 1, 29, 'ADMIN');
INSERT INTO `level_detail` VALUES (34, 1, 30, 'ADMIN');
INSERT INTO `level_detail` VALUES (35, 1, 32, 'ADMIN');
INSERT INTO `level_detail` VALUES (36, 1, 33, 'ADMIN');
INSERT INTO `level_detail` VALUES (37, 1, 34, 'ADMIN');
INSERT INTO `level_detail` VALUES (38, 1, 35, 'ADMIN');
INSERT INTO `level_detail` VALUES (39, 1, 36, 'ADMIN');
INSERT INTO `level_detail` VALUES (40, 1, 37, 'ADMIN');
INSERT INTO `level_detail` VALUES (41, 1, 38, 'ADMIN');
INSERT INTO `level_detail` VALUES (42, 1, 39, 'ADMIN');
INSERT INTO `level_detail` VALUES (43, 2, 42, 'USER');
INSERT INTO `level_detail` VALUES (44, 1, 40, 'ADMIN');
INSERT INTO `level_detail` VALUES (45, 1, 41, 'ADMIN');
INSERT INTO `level_detail` VALUES (46, 2, 41, 'USER');

-- ----------------------------
-- Table structure for level_header_detail
-- ----------------------------
DROP TABLE IF EXISTS `level_header_detail`;
CREATE TABLE `level_header_detail`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `level_id` int NOT NULL,
  `menu_id` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `coment` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 50 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of level_header_detail
-- ----------------------------
INSERT INTO `level_header_detail` VALUES (43, 1, 'MH0001', 'ADMIN');
INSERT INTO `level_header_detail` VALUES (44, 1, 'MH0002', 'ADMIN');
INSERT INTO `level_header_detail` VALUES (45, 1, 'MH0003', 'ADMIN');
INSERT INTO `level_header_detail` VALUES (46, 6, 'MH0001', 'FRONT');
INSERT INTO `level_header_detail` VALUES (47, 6, 'MH0002', 'FRONT');
INSERT INTO `level_header_detail` VALUES (48, 6, 'MH0003', 'FRONT');
INSERT INTO `level_header_detail` VALUES (49, 2, 'MH0003', 'USER');

-- ----------------------------
-- Table structure for license_list
-- ----------------------------
DROP TABLE IF EXISTS `license_list`;
CREATE TABLE `license_list`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `module` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `expired_at` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `is_lifetime` int NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '1 = enable | 2=disable',
  `qty` int NULL DEFAULT NULL,
  `platform_serial` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of license_list
-- ----------------------------
INSERT INTO `license_list` VALUES (9, 'Smart Working Space', 'core', 'module_meeting', '9999-12-30', 1, '1', 1, 'cf7473a5-6192-4688-9051-1051a874c0ad');
INSERT INTO `license_list` VALUES (10, 'Information', 'feature', 'module_informasi', '9999-12-30', 1, '1', 1, 'cf7473a5-6192-4688-9051-1051a874c0ad');
INSERT INTO `license_list` VALUES (11, 'Room', 'feature', 'module_room', '9999-12-30', 1, '1', 2, 'cf7473a5-6192-4688-9051-1051a874c0ad');
INSERT INTO `license_list` VALUES (14, 'KIOSK Client Working Space', 'feature', 'module_display', '9999-12-30', 1, '1', 2, 'cf7473a5-6192-4688-9051-1051a874c0ad');

-- ----------------------------
-- Table structure for license_setting
-- ----------------------------
DROP TABLE IF EXISTS `license_setting`;
CREATE TABLE `license_setting`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `serial` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `platform` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `device_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `checked_at` datetime NULL DEFAULT NULL,
  `status` int NULL DEFAULT NULL COMMENT '0 = unregister | 1 = registered| 2 = disabled',
  `distributor_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `customer_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `ext` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `webhost` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `license_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `pathdownload` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `is_deleted` int NULL DEFAULT 0,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `created_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `updated_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of license_setting
-- ----------------------------
INSERT INTO `license_setting` VALUES (1, 'cf7473a5-6192-4688-9051-1051a874c0ad', 'SMART OFFICE', '', '2023-08-10 04:56:23', 1, 'distri_ivp', 'customer_ivp_001', '.license', 'http://localhost/fileEncCodeIgniter', 'local', 'encryptFile/20230810060521.license', 0, '2023-08-10 04:56:23', '2023-08-10 06:03:52', 'admin', 'admin');

-- ----------------------------
-- Table structure for log
-- ----------------------------
DROP TABLE IF EXISTS `log`;
CREATE TABLE `log`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `datetime` datetime NOT NULL,
  `activity` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_by` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of log
-- ----------------------------

-- ----------------------------
-- Table structure for log_access_room
-- ----------------------------
DROP TABLE IF EXISTS `log_access_room`;
CREATE TABLE `log_access_room`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `room_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `is_default` int NULL DEFAULT 0,
  `pin` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `nik` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `datetime` datetime NULL DEFAULT NULL,
  `msg` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `status` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of log_access_room
-- ----------------------------

-- ----------------------------
-- Table structure for log_activity
-- ----------------------------
DROP TABLE IF EXISTS `log_activity`;
CREATE TABLE `log_activity`  (
  `_generate` bigint NOT NULL AUTO_INCREMENT,
  `nik` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `access_ip` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `access_action` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `access_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `access_time` datetime NOT NULL DEFAULT current_timestamp(),
  `access_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `access_query` int NOT NULL,
  PRIMARY KEY (`_generate`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 75 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of log_activity
-- ----------------------------

-- ----------------------------
-- Table structure for log_services_access_door
-- ----------------------------
DROP TABLE IF EXISTS `log_services_access_door`;
CREATE TABLE `log_services_access_door`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `room_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `pin` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `nik` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `card` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `datetime` datetime NULL DEFAULT NULL,
  `msg` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `status` int NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of log_services_access_door
-- ----------------------------

-- ----------------------------
-- Table structure for menu
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `type_icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `sort` int NOT NULL,
  `is_child` int NOT NULL,
  `menu_group_id` int NOT NULL,
  `module_text` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_by` int NULL DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 43 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of menu
-- ----------------------------
INSERT INTO `menu` VALUES (1, 'User', '/user', 'awesome', 'person', 22, 1, 11, 'module_user', NULL, '2023-07-01 00:55:54', '2023-07-01 00:55:54', 0);
INSERT INTO `menu` VALUES (2, 'Room Schedule', '/booking', 'awesome', 'schedule', 5, 1, 2, 'module_meeting', NULL, '2024-12-20 01:11:00', '2024-12-20 01:11:00', 1);
INSERT INTO `menu` VALUES (3, 'Room Management', '/room', 'awesome', 'apps', 51, 1, 2, 'module_room', NULL, '2024-12-20 01:10:57', '2024-12-20 01:10:57', 1);
INSERT INTO `menu` VALUES (4, 'Master Snack & Pantry', '/pantry', 'awesome', 'room_service', 7, 1, 10, 'module_pantry', NULL, '2024-12-20 01:12:01', '2024-12-20 01:12:01', 1);
INSERT INTO `menu` VALUES (5, 'Facility', '/facility', 'awesome', 'home', 3, 1, 1, 'module_room', NULL, '2024-12-20 01:17:23', '2024-12-20 01:17:23', 0);
INSERT INTO `menu` VALUES (6, 'Attendance', '/attendance', 'awesome', 'people', 100, 1, 1, '', NULL, '2024-01-05 03:27:43', '2024-01-05 03:27:43', 1);
INSERT INTO `menu` VALUES (7, 'Dashboard', '/dashboard', 'awesome', 'home', 0, 0, 1, '', NULL, '2023-07-01 00:44:22', '2023-07-01 00:44:22', 0);
INSERT INTO `menu` VALUES (8, 'Automation', '/automation', 'awsome', 'home', 4, 1, 1, 'module_automation', NULL, '2023-02-02 04:45:31', '2023-02-02 04:45:31', 1);
INSERT INTO `menu` VALUES (9, 'Multimedia', '/Multimedia', 'awesome', 'tv', 9, 1, 1, '', NULL, '2023-02-02 04:45:31', '2023-02-02 04:45:31', 1);
INSERT INTO `menu` VALUES (10, 'Employee', '/employee', 'awesome', 'person', 21, 1, 11, 'module_user', NULL, '2023-07-01 00:55:49', '2023-07-01 00:55:49', 0);
INSERT INTO `menu` VALUES (11, 'Information', '/company', 'awesome', 'card_membership', 1, 0, 2, 'module_informasi', NULL, '2023-07-01 00:44:36', '2023-07-01 00:44:36', 0);
INSERT INTO `menu` VALUES (12, 'Room Usage', '/report-usage', 'awesome', 'description', 52, 1, 2, 'module_report', NULL, '2024-12-20 01:11:04', '2024-12-20 01:11:04', 1);
INSERT INTO `menu` VALUES (13, 'Invoice', '/invoice', 'awesome', 'description', 10, 0, 4, '', NULL, '2023-02-02 04:45:31', '2023-02-02 04:45:31', 1);
INSERT INTO `menu` VALUES (14, 'Department', '/department', 'awesome', 'apps', 2, 1, 11, '', NULL, '2023-07-01 00:48:13', '2023-07-01 00:48:13', 1);
INSERT INTO `menu` VALUES (15, 'Building', '/building', 'awesome', 'room_service', 4, 1, 9, 'module_building', NULL, '2023-07-01 01:00:39', '2023-07-01 01:00:39', 0);
INSERT INTO `menu` VALUES (16, 'Access Door', '/access', 'awsome', 'door', 3, 1, 1, 'module_access_door', NULL, '2024-12-20 01:13:03', '2024-12-20 01:13:03', 1);
INSERT INTO `menu` VALUES (17, 'Company/Department', '/alocation', 'awesome', 'apps', 2, 1, 11, 'module_alocation', NULL, '2023-10-09 13:24:27', '2023-10-09 13:24:27', 0);
INSERT INTO `menu` VALUES (18, 'General', '/setting/general', 'awesome', 'apps', 13, 1, 3, 'module_meeting', NULL, '2025-01-10 08:50:29', '2025-01-10 08:50:29', 1);
INSERT INTO `menu` VALUES (19, 'SMTP & Email', '/setting/smtp-email', 'awesome', 'apps', 14, 1, 3, 'module_meeting', NULL, '2023-03-16 09:24:53', '2023-03-16 09:24:53', 0);
INSERT INTO `menu` VALUES (20, 'Display Signage', '/display', 'awesome', 'apps', 31, 1, 1, 'module_display', NULL, '2024-12-20 01:13:19', '2024-12-20 01:13:19', 1);
INSERT INTO `menu` VALUES (21, 'Cancel Order', '/report-cancel-order', 'awesome', 'description', 10, 1, 5, '', NULL, '2023-10-09 12:24:10', '2023-10-09 12:24:10', 1);
INSERT INTO `menu` VALUES (22, 'Income Rent Room', '/report-income', 'awesome', 'description', 11, 1, 5, '', NULL, '2023-10-09 12:23:49', '2023-10-09 12:23:49', 1);
INSERT INTO `menu` VALUES (23, 'Outstanding Invoice', '/report-outstanding', 'awesome', 'description', 12, 1, 5, '', NULL, '2023-10-09 12:23:53', '2023-10-09 12:23:53', 1);
INSERT INTO `menu` VALUES (24, 'Order Management', '/pantry-transaction', 'awsome', 'schedule', 75, 1, 10, 'module_pantry', NULL, '2024-12-20 01:11:50', '2024-12-20 01:11:50', 1);
INSERT INTO `menu` VALUES (25, 'Menu Package', '/pantry-package', 'awesome', 'room_service', 74, 1, 10, 'module_pantry', NULL, '2024-12-20 01:11:44', '2024-12-20 01:11:44', 1);
INSERT INTO `menu` VALUES (26, 'Locker System', '/locker-system', 'awesome', 'lock', 7, 1, 1, 'module_loker', NULL, '2023-07-01 00:50:29', '2023-07-01 00:50:29', 1);
INSERT INTO `menu` VALUES (27, 'Display Kiosk', '/display-kiosk', 'awesome', 'apps', 32, 1, 1, 'module_kiosk', NULL, '2024-12-20 01:16:36', '2024-12-20 01:16:36', 1);
INSERT INTO `menu` VALUES (28, 'Floor Management', '/beacon-floor', 'awesome', 'apps', 41, 1, 9, 'module_floor', NULL, '2024-12-20 01:18:17', '2024-12-20 01:18:17', 1);
INSERT INTO `menu` VALUES (29, 'Floor Room', '/beacon-floor-room', 'awesome', 'apps', 5, 1, 7, 'module_beacon', NULL, '2023-06-11 02:18:11', '2023-06-11 02:18:11', 1);
INSERT INTO `menu` VALUES (30, 'Beacon Tag', '/beacon-tag', 'awesome', 'apps', 82, 1, 7, 'module_beacon', NULL, '2023-10-09 13:27:02', '2023-10-09 13:27:02', 1);
INSERT INTO `menu` VALUES (32, 'Desk Room', '/deskroom', 'awesome', 'apps', 6, 1, 8, 'module_desk', NULL, '2023-07-01 01:02:35', '2023-07-01 01:02:35', 0);
INSERT INTO `menu` VALUES (33, 'Desk Controller', '/deskcontroller', 'awesome', 'apps', 61, 1, 8, 'module_desk', NULL, '2026-08-27 00:41:02', '2026-08-27 00:41:02', 0);
INSERT INTO `menu` VALUES (34, 'Desk Transaction', '/desktrs', 'awesome', 'apps', 62, 1, 8, 'module_desk', NULL, '2026-08-27 00:41:06', '2026-08-27 00:41:06', 0);
INSERT INTO `menu` VALUES (35, 'Live Transaction', '/beacon-live-monitor', 'awesome', 'apps', 12, 1, 7, 'module_beacon', NULL, '2023-10-09 13:26:59', '2023-10-09 13:26:59', 1);
INSERT INTO `menu` VALUES (36, 'Beacon Gateway', '/beacon-gateway', 'awesome', 'apps', 81, 1, 7, 'module_beacon', NULL, '2023-10-09 13:27:04', '2023-10-09 13:27:04', 1);
INSERT INTO `menu` VALUES (37, 'License', '/setting/license', 'awesome', 'description', 15, 1, 3, 'module_license', NULL, '2025-01-10 10:17:23', '2025-01-10 10:17:23', 0);
INSERT INTO `menu` VALUES (38, 'Integration', '/integration', 'awesome', 'open_with', 12, 0, 1, 'module_integration', NULL, '2024-12-20 01:11:18', '2024-12-20 01:11:18', 1);
INSERT INTO `menu` VALUES (39, 'Menu Order', '/pantry-menu', 'awesome', 'open_with', 71, 1, 10, 'module_pantry', NULL, '2024-12-20 01:11:41', '2024-12-20 01:11:41', 1);
INSERT INTO `menu` VALUES (40, 'Desk Usage', '/report-desk-usage', 'awesome', 'description', 63, 1, 8, 'module_report', NULL, '2026-08-27 00:45:23', '2026-08-27 00:45:23', 0);
INSERT INTO `menu` VALUES (41, 'Monitor', '/deskmonitor', 'awesome', 'tv', 1, 0, 1, 'module_monitor', NULL, '2026-08-27 10:34:05', '2026-08-27 10:34:05', 0);
INSERT INTO `menu` VALUES (42, 'My Desk', '/desktrs', 'awesome', 'event', 1, 0, 1, 'module_desk', NULL, '2026-08-27 15:08:10', '2026-08-27 15:08:10', 0);

-- ----------------------------
-- Table structure for menu_apps
-- ----------------------------
DROP TABLE IF EXISTS `menu_apps`;
CREATE TABLE `menu_apps`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `type_icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `sort` int NOT NULL,
  `is_child` int NOT NULL,
  `menu_group_id` int NOT NULL,
  `module_text` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_by` int NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 32 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of menu_apps
-- ----------------------------
INSERT INTO `menu_apps` VALUES (1, 'Meeting', 'meeting', '', 'meeting.png', 1, 0, 0, 'module_meeting', NULL, NULL, NULL, 1);
INSERT INTO `menu_apps` VALUES (2, 'Desk', 'desk', '', 'desk.png', 2, 0, 0, 'module_desk', NULL, NULL, NULL, 0);
INSERT INTO `menu_apps` VALUES (3, 'Report', 'report', '', 'report.png', 4, 0, 0, 'module_report', NULL, NULL, NULL, 1);
INSERT INTO `menu_apps` VALUES (4, 'Calender', 'calendar', '', 'calendar.png', 5, 0, 0, 'module_calender', NULL, NULL, NULL, 1);
INSERT INTO `menu_apps` VALUES (5, 'Pantry', 'pantry', '', 'pantry.png', 6, 0, 0, 'module_pantry', NULL, NULL, NULL, 1);
INSERT INTO `menu_apps` VALUES (6, 'Approval', 'approval', '', 'approval.png', 7, 0, 0, 'module_room_advance', NULL, NULL, NULL, 1);
INSERT INTO `menu_apps` VALUES (7, 'Website', 'website', '', 'website.png', 8, 0, 0, 'module_meeting', NULL, NULL, NULL, 1);

-- ----------------------------
-- Table structure for menu_group
-- ----------------------------
DROP TABLE IF EXISTS `menu_group`;
CREATE TABLE `menu_group`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of menu_group
-- ----------------------------
INSERT INTO `menu_group` VALUES (1, 'Master/Base', 'storage');
INSERT INTO `menu_group` VALUES (2, 'Meeting Room', 'input');
INSERT INTO `menu_group` VALUES (3, 'Setting', 'lock');
INSERT INTO `menu_group` VALUES (4, 'null', '');
INSERT INTO `menu_group` VALUES (5, 'Report', 'description');
INSERT INTO `menu_group` VALUES (6, 'Invoice', 'description');
INSERT INTO `menu_group` VALUES (7, 'Tracking BLE', 'description');
INSERT INTO `menu_group` VALUES (8, 'Desk Booking', 'event');
INSERT INTO `menu_group` VALUES (9, 'Location', 'my_location');
INSERT INTO `menu_group` VALUES (10, 'Snack & Pantry', 'local_cafe');
INSERT INTO `menu_group` VALUES (11, 'User Management', 'contacts');

-- ----------------------------
-- Table structure for menu_headers
-- ----------------------------
DROP TABLE IF EXISTS `menu_headers`;
CREATE TABLE `menu_headers`  (
  `_generate` int NOT NULL AUTO_INCREMENT,
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `sort` int NULL DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `module_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `created_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `is_deleted` int NULL DEFAULT NULL,
  PRIMARY KEY (`_generate`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of menu_headers
-- ----------------------------
INSERT INTO `menu_headers` VALUES (1, 'MH0001', 'ROOM', 1, 'room-place', '', 'module_room', '2024-05-25 10:35:53', 'admin', '2024-05-25 10:35:53', 1);
INSERT INTO `menu_headers` VALUES (2, 'MH0002', 'DESK', 2, 'desk-place', '', 'module_desk', '2024-05-25 10:35:53', 'admin', '2024-05-25 10:35:53', 1);
INSERT INTO `menu_headers` VALUES (3, 'MH0003', 'DASHBOARD', 3, 'dashboard', '', 'module_core', '2024-05-25 10:35:53', 'admin', '2024-05-25 10:35:53', 0);

-- ----------------------------
-- Table structure for module_backend
-- ----------------------------
DROP TABLE IF EXISTS `module_backend`;
CREATE TABLE `module_backend`  (
  `module_id` int NOT NULL AUTO_INCREMENT,
  `module_text` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `module_serial` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `is_enabled` int NOT NULL,
  PRIMARY KEY (`module_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 35 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of module_backend
-- ----------------------------
INSERT INTO `module_backend` VALUES (1, 'module_company', 'Module Company', NULL, 1);
INSERT INTO `module_backend` VALUES (2, 'module_department', 'Module Department', NULL, 0);
INSERT INTO `module_backend` VALUES (3, 'module_division', 'Module Division', NULL, 0);
INSERT INTO `module_backend` VALUES (4, 'module_facility', 'Module Facility', NULL, 1);
INSERT INTO `module_backend` VALUES (5, 'module_pantry', 'Module Pantry', NULL, 1);
INSERT INTO `module_backend` VALUES (6, 'module_invoice', 'Module Invoice', NULL, 0);
INSERT INTO `module_backend` VALUES (7, 'module_automation', 'Module Automation', NULL, 0);
INSERT INTO `module_backend` VALUES (8, 'module_access_door', 'Module Access Door', NULL, 1);
INSERT INTO `module_backend` VALUES (9, 'module_booking', 'Module booking', NULL, 1);
INSERT INTO `module_backend` VALUES (10, 'module_web', 'Module Web', NULL, 1);
INSERT INTO `module_backend` VALUES (11, 'module_mobile_android', 'Module Mobile Android', NULL, 1);
INSERT INTO `module_backend` VALUES (12, 'module_mobile_ios', 'Module Mobile IOS', NULL, 1);
INSERT INTO `module_backend` VALUES (13, 'module_email', 'Module Email', NULL, 1);
INSERT INTO `module_backend` VALUES (14, 'module_price', 'Module Price', NULL, 1);
INSERT INTO `module_backend` VALUES (15, 'module_alocation', 'Module Alocation', NULL, 1);
INSERT INTO `module_backend` VALUES (16, 'module_display', 'Module Display', NULL, 1);
INSERT INTO `module_backend` VALUES (17, 'module_loker', 'Module Loker', NULL, 0);
INSERT INTO `module_backend` VALUES (18, 'module_beacon', 'Module Beacon', NULL, 1);
INSERT INTO `module_backend` VALUES (19, 'module_kiosk', 'Module KIOSK', NULL, 1);
INSERT INTO `module_backend` VALUES (20, 'module_meeting', 'Module Meeting', NULL, 1);
INSERT INTO `module_backend` VALUES (21, 'module_room', 'Module Room', NULL, 1);
INSERT INTO `module_backend` VALUES (22, 'module_report', 'Module Report', NULL, 1);
INSERT INTO `module_backend` VALUES (23, 'module_desk', 'Module Desk', NULL, 1);
INSERT INTO `module_backend` VALUES (24, 'module_calender', 'Module Calender', NULL, 1);
INSERT INTO `module_backend` VALUES (26, 'module_building', 'Module Building', NULL, 1);
INSERT INTO `module_backend` VALUES (27, 'module_floor', 'Module Building', NULL, 1);
INSERT INTO `module_backend` VALUES (28, 'module_int_alarm', 'Module Integration Alarm', NULL, 1);
INSERT INTO `module_backend` VALUES (29, 'module_int_google', 'Module Integration Google', NULL, 0);
INSERT INTO `module_backend` VALUES (30, 'module_int_365', 'Module Integration 365', NULL, 1);
INSERT INTO `module_backend` VALUES (31, 'module_user_vip', 'Module Employee/User VIP', NULL, 0);
INSERT INTO `module_backend` VALUES (32, 'module_room_advance', 'Module Room', NULL, 1);
INSERT INTO `module_backend` VALUES (34, 'module_core', 'Module Core', NULL, 1);

-- ----------------------------
-- Table structure for notif_booking
-- ----------------------------
DROP TABLE IF EXISTS `notif_booking`;
CREATE TABLE `notif_booking`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `notif_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `booking_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `employee_id` bigint NOT NULL,
  `is_reschedule` int NOT NULL,
  `is_invited` int NOT NULL,
  `is_notifhandler` int NOT NULL,
  `is_notifSend` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of notif_booking
-- ----------------------------

-- ----------------------------
-- Table structure for notification_admin
-- ----------------------------
DROP TABLE IF EXISTS `notification_admin`;
CREATE TABLE `notification_admin`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nik` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `type` int NULL DEFAULT NULL,
  `datetime` datetime NULL DEFAULT NULL,
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `is_read` int NULL DEFAULT 0,
  `is_sending` int NULL DEFAULT 0,
  `is_deleted` int NULL DEFAULT 0,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5519 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of notification_admin
-- ----------------------------
INSERT INTO `notification_admin` VALUES (5508, 'admin', 12, '2026-08-26 23:27:48', 'Create desk', 'Book a Desk in IT Vendor - No.53', 0, 0, 0, '2026-08-26 23:27:48', NULL);
INSERT INTO `notification_admin` VALUES (5509, 'admin', 12, '2026-08-27 09:28:39', 'Create desk', 'Book a Desk in IT Vendor - No.54', 0, 0, 0, '2026-08-27 09:28:39', NULL);
INSERT INTO `notification_admin` VALUES (5510, 'admin', 12, '2026-08-27 14:08:34', 'Create desk', 'Book a Desk in IT Staff - No.2', 0, 0, 0, '2026-08-27 14:08:34', NULL);
INSERT INTO `notification_admin` VALUES (5511, '20241220024501', 12, '2026-08-27 16:42:34', 'Create desk', 'Book a Desk in IT Staff - No.42', 0, 0, 0, '2026-08-27 16:42:34', NULL);
INSERT INTO `notification_admin` VALUES (5512, NULL, 12, '2026-08-27 16:45:49', 'Create desk', 'Book a Desk in IT Staff - Desk No.74', 0, 0, 0, '2026-08-27 16:45:49', NULL);
INSERT INTO `notification_admin` VALUES (5513, NULL, 12, '2026-08-28 14:45:37', 'Create desk', 'Book a Desk in Lantai 2 - No.1', 0, 0, 0, '2026-08-28 14:45:37', NULL);
INSERT INTO `notification_admin` VALUES (5514, NULL, 12, '2026-08-28 14:53:03', 'Create desk', 'Book a Desk in IT Staff - No.1', 0, 0, 0, '2026-08-28 14:53:03', NULL);
INSERT INTO `notification_admin` VALUES (5515, NULL, 12, '2026-08-28 16:18:52', 'Create desk', 'Book a Desk in Lantai 2 - No.1', 0, 0, 0, '2026-08-28 16:18:52', NULL);
INSERT INTO `notification_admin` VALUES (5516, NULL, 12, '2026-08-28 16:46:51', 'Create desk', 'Book a Desk in IT Vendor - No.1', 0, 0, 0, '2026-08-28 16:46:51', NULL);
INSERT INTO `notification_admin` VALUES (5517, NULL, 12, '2026-08-28 17:11:16', 'Create desk', 'Book a Desk in IT Staff - No.1', 0, 0, 0, '2026-08-28 17:11:16', NULL);
INSERT INTO `notification_admin` VALUES (5518, 'admin', 12, '2026-08-28 17:11:38', 'Create desk', 'Book a Desk in IT Staff - No.42', 0, 0, 0, '2026-08-28 17:11:38', NULL);

-- ----------------------------
-- Table structure for notification_config
-- ----------------------------
DROP TABLE IF EXISTS `notification_config`;
CREATE TABLE `notification_config`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `authorization` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `topics` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `active` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of notification_config
-- ----------------------------
INSERT INTO `notification_config` VALUES (1, 'key=AAAAqlzLRWA:APA91bGBlTKd0HEktCO1HClu4kGQdpOu1RVfgUk8dw57PuqlcbpJBn6jsDllDwELPNfQAc_fkZC2xQMVa9FzArwRTGDPwZTiIFSjHuDIBg_W0F23uvz9tb5UOZzIb7KD-DpSnSk7TJis', 'https://fcm.googleapis.com/fcm/send', 'mobile_notif_asdp_', 1);

-- ----------------------------
-- Table structure for notification_data
-- ----------------------------
DROP TABLE IF EXISTS `notification_data`;
CREATE TABLE `notification_data`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nik` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'user_id',
  `type` int NULL DEFAULT NULL COMMENT '1=booking|2=invoice|3=reminder',
  `datetime` datetime NULL DEFAULT NULL,
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `value` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `is_sending` int NULL DEFAULT NULL,
  `is_deleted` int NULL DEFAULT 0,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10764 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of notification_data
-- ----------------------------
INSERT INTO `notification_data` VALUES (10742, 'EC20250110131102', 1, '2026-08-26 23:27:48', 'Notification desk schedule', 'Book a Desk in IT Vendor - No.53 - 27 Agustus 2026', '3015764982', 0, 0, '2026-08-26 23:27:48', NULL);
INSERT INTO `notification_data` VALUES (10743, 'EC20250110131102', 1, '2026-08-26 23:27:48', 'Create a desk schedule', 'Book a Desk in IT Vendor - No.53 - 27 Agustus 2026', '3015764982', 0, 0, '2026-08-26 23:27:48', NULL);
INSERT INTO `notification_data` VALUES (10744, 'admin', 1, '2026-08-27 09:28:39', 'Notification desk schedule', 'Book a Desk in IT Vendor - No.54 - 27 Agustus 2026', '9087546123', 0, 0, '2026-08-27 09:28:39', NULL);
INSERT INTO `notification_data` VALUES (10745, 'admin', 1, '2026-08-27 09:28:39', 'Create a desk schedule', 'Book a Desk in IT Vendor - No.54 - 27 Agustus 2026', '9087546123', 0, 0, '2026-08-27 09:28:39', NULL);
INSERT INTO `notification_data` VALUES (10746, 'admin', 1, '2026-08-27 14:08:34', 'Notification desk schedule', 'Book a Desk in IT Staff - No.2 - 27 Agustus 2026', '2590876143', 0, 0, '2026-08-27 14:08:34', NULL);
INSERT INTO `notification_data` VALUES (10747, 'admin', 1, '2026-08-27 14:08:34', 'Create a desk schedule', 'Book a Desk in IT Staff - No.2 - 27 Agustus 2026', '2590876143', 0, 0, '2026-08-27 14:08:34', NULL);
INSERT INTO `notification_data` VALUES (10748, '20241220024501', 1, '2026-08-27 16:42:34', 'Notification desk schedule', 'Book a Desk in IT Staff - No.42 - 27 Agustus 2026', '6451927038', 0, 0, '2026-08-27 16:42:34', NULL);
INSERT INTO `notification_data` VALUES (10749, '20241220024501', 1, '2026-08-27 16:42:34', 'Create a desk schedule', 'Book a Desk in IT Staff - No.42 - 27 Agustus 2026', '6451927038', 0, 0, '2026-08-27 16:42:34', NULL);
INSERT INTO `notification_data` VALUES (10750, '20241220024501', 1, '2026-08-27 16:45:49', 'Notification desk schedule', 'Book a Desk in IT Staff - Desk No.74 - 27 Agustus 2026', '1968537204', 0, 0, '2026-08-27 16:45:49', NULL);
INSERT INTO `notification_data` VALUES (10751, '20241220024501', 1, '2026-08-27 16:45:49', 'Create a desk schedule', 'Book a Desk in IT Staff - Desk No.74 - 27 Agustus 2026', '1968537204', 0, 0, '2026-08-27 16:45:49', NULL);
INSERT INTO `notification_data` VALUES (10752, '20241220024501', 1, '2026-08-28 14:45:37', 'Notification desk schedule', 'Book a Desk in Lantai 2 - No.1 - 28 Agustus 2026', '6735204891', 0, 0, '2026-08-28 14:45:37', NULL);
INSERT INTO `notification_data` VALUES (10753, '20241220024501', 1, '2026-08-28 14:45:37', 'Create a desk schedule', 'Book a Desk in Lantai 2 - No.1 - 28 Agustus 2026', '6735204891', 0, 0, '2026-08-28 14:45:37', NULL);
INSERT INTO `notification_data` VALUES (10754, '20241220024501', 1, '2026-08-28 14:53:03', 'Notification desk schedule', 'Book a Desk in IT Staff - No.1 - 28 Agustus 2026', '3785961420', 0, 0, '2026-08-28 14:53:03', NULL);
INSERT INTO `notification_data` VALUES (10755, '20241220024501', 1, '2026-08-28 14:53:03', 'Create a desk schedule', 'Book a Desk in IT Staff - No.1 - 28 Agustus 2026', '3785961420', 0, 0, '2026-08-28 14:53:03', NULL);
INSERT INTO `notification_data` VALUES (10756, '20241220024501', 1, '2026-08-28 16:18:52', 'Notification desk schedule', 'Book a Desk in Lantai 2 - No.1 - 28 Agustus 2026', '2637049518', 0, 0, '2026-08-28 16:18:52', NULL);
INSERT INTO `notification_data` VALUES (10757, '20241220024501', 1, '2026-08-28 16:18:52', 'Create a desk schedule', 'Book a Desk in Lantai 2 - No.1 - 28 Agustus 2026', '2637049518', 0, 0, '2026-08-28 16:18:52', NULL);
INSERT INTO `notification_data` VALUES (10758, '20241220024501', 1, '2026-08-28 16:46:50', 'Notification desk schedule', 'Book a Desk in IT Vendor - No.1 - 28 Agustus 2026', '9182476053', 0, 0, '2026-08-28 16:46:50', NULL);
INSERT INTO `notification_data` VALUES (10759, '20241220024501', 1, '2026-08-28 16:46:50', 'Create a desk schedule', 'Book a Desk in IT Vendor - No.1 - 28 Agustus 2026', '9182476053', 0, 0, '2026-08-28 16:46:50', NULL);
INSERT INTO `notification_data` VALUES (10760, '20241220024501', 1, '2026-08-28 17:11:16', 'Notification desk schedule', 'Book a Desk in IT Staff - No.1 - 28 Agustus 2026', '7926043815', 0, 0, '2026-08-28 17:11:16', NULL);
INSERT INTO `notification_data` VALUES (10761, '20241220024501', 1, '2026-08-28 17:11:16', 'Create a desk schedule', 'Book a Desk in IT Staff - No.1 - 28 Agustus 2026', '7926043815', 0, 0, '2026-08-28 17:11:16', NULL);
INSERT INTO `notification_data` VALUES (10762, 'admin', 1, '2026-08-28 17:11:38', 'Notification desk schedule', 'Book a Desk in IT Staff - No.42 - 28 Agustus 2026', '3908417526', 0, 0, '2026-08-28 17:11:38', NULL);
INSERT INTO `notification_data` VALUES (10763, 'admin', 1, '2026-08-28 17:11:38', 'Create a desk schedule', 'Book a Desk in IT Staff - No.42 - 28 Agustus 2026', '3908417526', 0, 0, '2026-08-28 17:11:38', NULL);

-- ----------------------------
-- Table structure for notification_type
-- ----------------------------
DROP TABLE IF EXISTS `notification_type`;
CREATE TABLE `notification_type`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `cololr` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `route` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `table` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `where` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `topics` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of notification_type
-- ----------------------------
INSERT INTO `notification_type` VALUES (1, '1', NULL, 'notification', NULL, NULL, NULL, NULL);
INSERT INTO `notification_type` VALUES (2, '2', NULL, 'meeting', NULL, NULL, NULL, NULL);
INSERT INTO `notification_type` VALUES (3, '3', NULL, 'desk', NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for notification_type_admin
-- ----------------------------
DROP TABLE IF EXISTS `notification_type_admin`;
CREATE TABLE `notification_type_admin`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `element` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `route` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of notification_type_admin
-- ----------------------------

-- ----------------------------
-- Table structure for pantry
-- ----------------------------
DROP TABLE IF EXISTS `pantry`;
CREATE TABLE `pantry`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `building_id` int NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `detail` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `location` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `days` int NOT NULL DEFAULT 0,
  `opening_hours_start` time NOT NULL,
  `opening_hours_end` time NOT NULL,
  `is_show_price` int NULL DEFAULT 0,
  `pic` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `employee_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_by` int NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` datetime NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `is_approval` int NULL DEFAULT 0,
  `is_internal` int NULL DEFAULT 1,
  `owner_pantry` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pantry
-- ----------------------------

-- ----------------------------
-- Table structure for pantry_detail
-- ----------------------------
DROP TABLE IF EXISTS `pantry_detail`;
CREATE TABLE `pantry_detail`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `pantry_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pic` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `prefix_id` int NOT NULL,
  `rasio` int NOT NULL DEFAULT 0,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price` int NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pantry_detail
-- ----------------------------

-- ----------------------------
-- Table structure for pantry_detail_menu_variant
-- ----------------------------
DROP TABLE IF EXISTS `pantry_detail_menu_variant`;
CREATE TABLE `pantry_detail_menu_variant`  (
  `id` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `menu_id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `multiple` int NOT NULL,
  `min` int NOT NULL,
  `max` int NOT NULL,
  `is_deleted` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pantry_detail_menu_variant
-- ----------------------------

-- ----------------------------
-- Table structure for pantry_detail_menu_variant_detail
-- ----------------------------
DROP TABLE IF EXISTS `pantry_detail_menu_variant_detail`;
CREATE TABLE `pantry_detail_menu_variant_detail`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `variant_id` varchar(33) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price` int NULL DEFAULT 0,
  `is_deleted` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pantry_detail_menu_variant_detail
-- ----------------------------

-- ----------------------------
-- Table structure for pantry_display
-- ----------------------------
DROP TABLE IF EXISTS `pantry_display`;
CREATE TABLE `pantry_display`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `display_serial` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'general' COMMENT 'general',
  `background` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `background_update` int NULL DEFAULT NULL,
  `color_occupied` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `color_available` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `created_by` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `updated_by` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `is_deleted` int NULL DEFAULT 0,
  `status_sync` int NULL DEFAULT 0 COMMENT '0 : bleum sync | 1 : sync | 2 : process update',
  `enabled` int NULL DEFAULT 1,
  `hardware_uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT 'hadware uuid/mac',
  `hardware_info` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `hardware_lastsync` datetime NULL DEFAULT NULL,
  `room_select` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `disable_msg` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pantry_display
-- ----------------------------

-- ----------------------------
-- Table structure for pantry_menu_paket
-- ----------------------------
DROP TABLE IF EXISTS `pantry_menu_paket`;
CREATE TABLE `pantry_menu_paket`  (
  `_generate` int NOT NULL AUTO_INCREMENT,
  `id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pantry_id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_by` int NOT NULL,
  `updated_by` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `_generate`(`_generate` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pantry_menu_paket
-- ----------------------------

-- ----------------------------
-- Table structure for pantry_menu_paket_d
-- ----------------------------
DROP TABLE IF EXISTS `pantry_menu_paket_d`;
CREATE TABLE `pantry_menu_paket_d`  (
  `_generate` int NOT NULL AUTO_INCREMENT,
  `menu_id` int NOT NULL COMMENT 'pantry_detail id',
  `package_id` int NOT NULL,
  `is_deleted` int NOT NULL,
  PRIMARY KEY (`_generate`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 23 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pantry_menu_paket_d
-- ----------------------------

-- ----------------------------
-- Table structure for pantry_satuan
-- ----------------------------
DROP TABLE IF EXISTS `pantry_satuan`;
CREATE TABLE `pantry_satuan`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_deleted` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pantry_satuan
-- ----------------------------

-- ----------------------------
-- Table structure for pantry_transaksi
-- ----------------------------
DROP TABLE IF EXISTS `pantry_transaksi`;
CREATE TABLE `pantry_transaksi`  (
  `_generate` int NOT NULL AUTO_INCREMENT,
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pantry_id` int NOT NULL,
  `order_no` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `employee_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'nik',
  `booking_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_blive` int NOT NULL DEFAULT 0,
  `room_id` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `via` enum('mobile','web','tablet_induksi') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'mobile',
  `datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `order_datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `order_datetime_before` datetime NOT NULL DEFAULT current_timestamp(),
  `order_st` int NOT NULL,
  `order_st_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `process` int NOT NULL,
  `complete` int NOT NULL,
  `failed` int NOT NULL,
  `done` int NOT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `note_reject` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `note_canceled` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `is_rejected_pantry` int NOT NULL DEFAULT 0,
  `rejected_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `rejected_at` datetime NOT NULL DEFAULT current_timestamp(),
  `is_trashpantry` int NOT NULL DEFAULT 0,
  `is_canceled` int NOT NULL DEFAULT 0,
  `is_expired` int NOT NULL DEFAULT 0,
  `expired_at` datetime NOT NULL DEFAULT current_timestamp(),
  `canceled_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `canceled_at` datetime NOT NULL DEFAULT current_timestamp(),
  `completed_at` datetime NOT NULL DEFAULT current_timestamp(),
  `completed_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `process_at` datetime NULL DEFAULT NULL,
  `process_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `canceled_pantry_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `rejected_pantry_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `completed_pantry_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `process_pantry_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `is_deleted` int NOT NULL DEFAULT 0,
  `timezone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `from_pantry` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `to_pantry` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `pending` int NULL DEFAULT 0,
  `pending_at` datetime NULL DEFAULT NULL,
  `paket_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `is_approved` int NULL DEFAULT NULL COMMENT '0 : pending, 1 : approve, 2:rejected, 3:by pass approve',
  `approved_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'nik employee',
  `approved_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `_generate`(`_generate` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pantry_transaksi
-- ----------------------------

-- ----------------------------
-- Table structure for pantry_transaksi_d
-- ----------------------------
DROP TABLE IF EXISTS `pantry_transaksi_d`;
CREATE TABLE `pantry_transaksi_d`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `transaksi_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `menu_id` int NOT NULL,
  `qty` int NOT NULL,
  `note_order` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `note_reject` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `detailorder` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` int NOT NULL,
  `is_rejected` int NOT NULL DEFAULT 0,
  `rejected_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `rejected_at` datetime NOT NULL DEFAULT current_timestamp(),
  `is_deleted` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pantry_transaksi_d
-- ----------------------------

-- ----------------------------
-- Table structure for pantry_transaksi_status
-- ----------------------------
DROP TABLE IF EXISTS `pantry_transaksi_status`;
CREATE TABLE `pantry_transaksi_status`  (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pantry_transaksi_status
-- ----------------------------

-- ----------------------------
-- Table structure for room
-- ----------------------------
DROP TABLE IF EXISTS `room`;
CREATE TABLE `room`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `radid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `building_id` int NULL DEFAULT NULL,
  `floor_id` int NULL DEFAULT NULL,
  `type_room` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'single',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `capacity` int NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `google_map` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `is_automation` tinyint(1) NOT NULL,
  `automation_id` int NOT NULL,
  `facility_room` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `work_day` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `work_time` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `work_start` time NOT NULL,
  `work_end` time NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'default.jpeg',
  `image2` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `multiple_image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price` bigint NOT NULL,
  `location` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `is_disabled` tinyint(1) NULL DEFAULT NULL,
  `is_beacon` int NULL DEFAULT 0,
  `created_by` int NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `is_config_setting_enable` int NULL DEFAULT 0,
  `config_room_for_usage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `is_enable_approval` int NULL DEFAULT 0,
  `config_approval_user` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `is_enable_permission` int NULL DEFAULT 0,
  `config_permission_user` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `config_permission_checkin` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'pic',
  `config_permission_end` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'pic',
  `config_min_duration` int NULL DEFAULT 0,
  `config_max_duration` int NULL DEFAULT 240,
  `config_advance_booking` int NULL DEFAULT 7 COMMENT 'Bisa booking untuk 7 hari kedepan',
  `is_enable_recurring` int NULL DEFAULT 0,
  `is_enable_checkin` int NULL DEFAULT 0,
  `config_advance_checkin` int NULL DEFAULT 5 COMMENT 'check in 5 menit sebelum meeting',
  `is_realease_checkin_timeout` int NULL DEFAULT 0 COMMENT 'Release the Meeting Room Upon Check-in Timeout',
  `config_release_room_checkin_timeout` int NULL DEFAULT 10 COMMENT 'Release di 10 menit jika tidak ada checkin',
  `config_participant_checkin_count` int NOT NULL DEFAULT 1 COMMENT 'Release the Meeting Room Upon Check-in Timeout',
  `is_enable_checkin_count` int NULL DEFAULT 0,
  `config_google` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `config_microsoft` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `kind_room` enum('room','trainingroom','noroom','specialroom') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'room',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 29 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of room
-- ----------------------------
INSERT INTO `room` VALUES (20, '4718532960', 1, 0, 'single', 'Room 1', 20, 'Room 1', '', 0, 0, 'Projector,Light,Screen,LCD TV,Air Conditioner,High speed internet,Power outlet,Whiteboard,', 'SUNDAY,MONDAY,TUESDAY,WEDNESDAY,THURSDAY,FRIDAY,SATURDAY', '00:00-23:59', '00:00:00', '23:59:00', '4718532960.jpeg', '4718532960_9384527160.jpeg##4718532960_6103295847.jpeg##4718532960_4198352067.jpeg', '', 0, 'Room 1', 0, 1, NULL, '2023-01-26 23:26:55', '2023-10-31 16:42:13', 0, 1, '1,2', 1, 'i32420211115111902', 1, 'i32420211115111902', 'pic', 'pic', 15, 120, 3, 1, 1, 5, 1, 10, 1, 0, '', '1b7568b5-5231-459c-9949-3640520d6141', 'room');
INSERT INTO `room` VALUES (22, '406215', 1, 0, 'merge', 'Merge Room 1', 20, 'Description', '', 0, 0, 'Projector,Light,Screen,LCD TV,Air Conditioner,High speed internet,Power outlet,Whiteboard,', 'SUNDAY,MONDAY,TUESDAY,WEDNESDAY,THURSDAY,FRIDAY,SATURDAY', '00:00-23:59', '00:00:00', '23:59:00', 'default.jpeg', '', '', 0, 'Detail Location', 1, 1, NULL, '2023-02-01 15:28:50', '2023-10-24 10:21:22', 0, 0, '', 0, '', 0, '', 'pic', 'pic', 15, 240, 7, 0, 0, 5, 0, 10, 1, 0, '', '', 'room');
INSERT INTO `room` VALUES (23, '943571', 1, 0, 'single', 'Room 2', 6, '', '', 0, 0, 'Projector,Light,Screen,LCD TV,Air Conditioner,High speed internet,Power outlet,Whiteboard', 'SUNDAY,MONDAY,TUESDAY,WEDNESDAY,THURSDAY,FRIDAY,SATURDAY', '00:00-23:59', '00:00:00', '23:59:00', '943571.jpg', '', '', 0, 'Room 2', 0, 0, NULL, '2023-02-02 04:36:53', '2023-11-27 13:21:35', 0, 1, '1', 0, '', 0, '', 'pic', 'pic', 15, 240, 7, 1, 0, 5, 0, 10, 1, 0, '', '364e3d9b-304b-43aa-8769-72bf885ee21b', 'room');
INSERT INTO `room` VALUES (25, '014937', 1, 0, 'single', 'Room 3', 10, '', '', 0, 0, 'Projector,Light,Screen,LCD TV,Air Conditioner,High speed internet,Power outlet,Whiteboard', 'SUNDAY,MONDAY,TUESDAY,WEDNESDAY,THURSDAY,FRIDAY,SATURDAY', '06:00-21:00', '06:00:00', '21:00:00', '014937.jpeg', '014937_4276035198.jpeg', '', 0, '', 0, 0, NULL, '2023-02-13 14:21:11', '2023-11-14 11:50:21', 0, 1, '', 1, 'i32420211115111902', 0, '', 'pic', 'pic', 15, 240, 7, 1, 1, 5, 0, 10, 1, 0, '', NULL, 'room');
INSERT INTO `room` VALUES (26, '517294', 1, 0, 'single', 'Room 4', 10, '', '', 0, 0, 'Projector,Light,Screen,LCD TV,Air Conditioner,High speed internet,Power outlet,Whiteboard,', 'SUNDAY,MONDAY,TUESDAY,WEDNESDAY,THURSDAY,FRIDAY,SATURDAY', '06:00-21:00', '06:00:00', '21:00:00', '517294.jpeg', '', '', 0, '', 0, 0, NULL, '2023-02-13 14:21:45', '2024-05-15 09:27:31', 1, 0, '', 0, '', 0, '', 'pic', 'pic', 15, 240, 7, 0, 0, 5, 0, 10, 1, 0, '', NULL, 'room');
INSERT INTO `room` VALUES (27, '912638', 1, 0, 'single', 'Room 5', 10, '', '', 0, 0, 'Light,LCD TV,Air Conditioner', 'MONDAY,TUESDAY,WEDNESDAY,WEDNESDAY,FRIDAY', '06:00-21:00', '06:00:00', '21:00:00', '912638.jpeg', '', '', 0, '', 0, 0, NULL, '2023-02-13 14:22:16', '2023-02-13 14:22:16', 1, 0, '', 0, '', 0, '', 'pic', 'pic', 15, 240, 7, NULL, 1, 5, 0, 10, 1, 0, '', NULL, 'room');
INSERT INTO `room` VALUES (28, '420759', 1, 0, 'merge', 'Merge Room 3', 10, '', '', 0, 0, 'Projector,Light,Screen,LCD TV,Air Conditioner,High speed internet,Power outlet,Whiteboard,', 'SUNDAY,MONDAY,TUESDAY,WEDNESDAY,THURSDAY,FRIDAY,SATURDAY', '06:00-21:00', '06:00:00', '21:00:00', '420759.jpeg', '420759_6583107492.jpeg', '', 0, '', 0, 0, NULL, '2023-02-13 14:24:18', '2024-05-15 09:08:28', 1, 0, '', 0, '', 0, '', 'pic', 'pic', 15, 240, 7, 0, 0, 5, 0, 10, 1, 0, '', NULL, 'room');

-- ----------------------------
-- Table structure for room_365
-- ----------------------------
DROP TABLE IF EXISTS `room_365`;
CREATE TABLE `room_365`  (
  `_generate` int NOT NULL AUTO_INCREMENT,
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `emailAddress` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `displayName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `geoCoordinates` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `nickname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `building` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `floorNumber` int NULL DEFAULT NULL,
  `floorLabel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `capacity` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `bookingType` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `audioDeviceName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `videoDeviceName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `displayDeviceName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `isWheelChairAccessible` tinyint(1) NULL DEFAULT NULL,
  `tags` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `initial` int NULL DEFAULT 0,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `is_deleted` int NULL DEFAULT 0,
  PRIMARY KEY (`_generate`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 22 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of room_365
-- ----------------------------

-- ----------------------------
-- Table structure for room_automation
-- ----------------------------
DROP TABLE IF EXISTS `room_automation`;
CREATE TABLE `room_automation`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ip_address` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `serial` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `room` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `devices` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `is_deleted` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of room_automation
-- ----------------------------

-- ----------------------------
-- Table structure for room_detail
-- ----------------------------
DROP TABLE IF EXISTS `room_detail`;
CREATE TABLE `room_detail`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `room_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `facility_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `datetime` datetime NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1157 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of room_detail
-- ----------------------------
INSERT INTO `room_detail` VALUES (1153, '354196', '6', NULL);
INSERT INTO `room_detail` VALUES (1154, '354196', '7', NULL);
INSERT INTO `room_detail` VALUES (1155, '354196', '8', NULL);
INSERT INTO `room_detail` VALUES (1156, '354196', '9', NULL);

-- ----------------------------
-- Table structure for room_display
-- ----------------------------
DROP TABLE IF EXISTS `room_display`;
CREATE TABLE `room_display`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `room_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `display_serial` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'general' COMMENT 'general | allroom | receptionist',
  `background` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `background_update` int NULL DEFAULT NULL,
  `color_occupied` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `color_available` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `enable_signage` int NULL DEFAULT 0,
  `signage_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `signage_media` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `signage_update` int NULL DEFAULT 0,
  `created_by` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `updated_by` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `is_deleted` int NULL DEFAULT 0,
  `status_sync` int NULL DEFAULT 0 COMMENT '0 : bleum sync | 1 : sync | 2 : process update',
  `enabled` int NULL DEFAULT 1,
  `hardware_uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT 'hadware uuid/mac',
  `hardware_info` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `hardware_lastsync` datetime NULL DEFAULT NULL,
  `room_select` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `disable_msg` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of room_display
-- ----------------------------

-- ----------------------------
-- Table structure for room_for_usage
-- ----------------------------
DROP TABLE IF EXISTS `room_for_usage`;
CREATE TABLE `room_for_usage`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `is_deleted` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of room_for_usage
-- ----------------------------
INSERT INTO `room_for_usage` VALUES (1, 'Internal', 0);
INSERT INTO `room_for_usage` VALUES (2, 'External', 0);

-- ----------------------------
-- Table structure for room_for_usage_detail
-- ----------------------------
DROP TABLE IF EXISTS `room_for_usage_detail`;
CREATE TABLE `room_for_usage_detail`  (
  `room_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `room_usage_id` int NULL DEFAULT NULL,
  `min_cap` int NULL DEFAULT NULL,
  `internal` int NULL DEFAULT 0,
  `external` int NULL DEFAULT 0
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of room_for_usage_detail
-- ----------------------------
INSERT INTO `room_for_usage_detail` VALUES ('4718532960', 1, 2, 1, 1);
INSERT INTO `room_for_usage_detail` VALUES ('4718532960', 2, 3, 0, 1);
INSERT INTO `room_for_usage_detail` VALUES ('943571', 1, 2, 0, 0);

-- ----------------------------
-- Table structure for room_google
-- ----------------------------
DROP TABLE IF EXISTS `room_google`;
CREATE TABLE `room_google`  (
  `_generate` int NOT NULL AUTO_INCREMENT,
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `emailAddress` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `displayName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `geoCoordinates` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `nickname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `building` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `floorNumber` int NULL DEFAULT NULL,
  `floorLabel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `capacity` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `bookingType` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `audioDeviceName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `videoDeviceName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `displayDeviceName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `isWheelChairAccessible` tinyint(1) NULL DEFAULT NULL,
  `tags` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `initial` int NULL DEFAULT 0,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `is_deleted` int NULL DEFAULT 0,
  PRIMARY KEY (`_generate`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of room_google
-- ----------------------------

-- ----------------------------
-- Table structure for room_merge_detail
-- ----------------------------
DROP TABLE IF EXISTS `room_merge_detail`;
CREATE TABLE `room_merge_detail`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `merge_room_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'merge_room_id',
  `room_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 237 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of room_merge_detail
-- ----------------------------

-- ----------------------------
-- Table structure for room_user_checkin
-- ----------------------------
DROP TABLE IF EXISTS `room_user_checkin`;
CREATE TABLE `room_user_checkin`  (
  `id` int NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `is_deleted` int NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of room_user_checkin
-- ----------------------------
INSERT INTO `room_user_checkin` VALUES (1, 'pic', 'PIC/Host/Organize Only', 0);
INSERT INTO `room_user_checkin` VALUES (2, 'all', 'PIC/Host/Organize  OR Attendee/Audience/Partisipant', 0);

-- ----------------------------
-- Table structure for sending_email
-- ----------------------------
DROP TABLE IF EXISTS `sending_email`;
CREATE TABLE `sending_email`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `batch` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `type` int NULL DEFAULT NULL,
  `booking_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pending` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `error_sending` int NOT NULL,
  `success` int NOT NULL,
  `is_status` int NULL DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5199 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sending_email
-- ----------------------------
INSERT INTO `sending_email` VALUES (5188, '{\"internal\":[{\"card_number\":\"123444\",\"nik\":\"EC20250110131102\",\"name\":\"Handi\",\"division_id\":0,\"is_pic\":1,\"email\":\"eMAIL@mail.com\",\"pin_room\":\"802697\"}],\"eksternal\":[]}', 1, '3015764982', '0', 0, 0, 0, '2026-08-26 23:27:48', '2026-08-26 23:27:48', 0);
INSERT INTO `sending_email` VALUES (5189, '{\"internal\":[{\"card_number\":\"033313\",\"nik\":\"admin\",\"name\":\"Administrator\",\"division_id\":0,\"is_pic\":1,\"email\":\"admin@adminmail.com\",\"pin_room\":\"039748\"}],\"eksternal\":[]}', 1, '9087546123', '0', 0, 0, 0, '2026-08-27 09:28:39', '2026-08-27 09:28:39', 0);
INSERT INTO `sending_email` VALUES (5190, '{\"internal\":[{\"card_number\":\"033313\",\"nik\":\"admin\",\"name\":\"Administrator\",\"division_id\":0,\"is_pic\":1,\"email\":\"admin@adminmail.com\",\"pin_room\":\"860234\"}],\"eksternal\":[]}', 1, '2590876143', '0', 0, 0, 0, '2026-08-27 14:08:34', '2026-08-27 14:08:34', 0);
INSERT INTO `sending_email` VALUES (5191, '{\"internal\":[{\"card_number\":\"0003852880\",\"nik\":\"20241220024501\",\"name\":\"Tilis Tiadi\",\"division_id\":0,\"is_pic\":1,\"email\":\"tilis.local@mail.com\",\"pin_room\":\"063547\"}],\"eksternal\":[]}', 1, '6451927038', '0', 0, 0, 0, '2026-08-27 16:42:34', '2026-08-27 16:42:34', 0);
INSERT INTO `sending_email` VALUES (5192, '{\"internal\":[{\"card_number\":\"0003852880\",\"nik\":\"20241220024501\",\"name\":\"Tilis Tiadi\",\"division_id\":0,\"is_pic\":1,\"email\":\"tilis.local@mail.com\",\"pin_room\":\"018936\"}],\"eksternal\":[]}', 1, '1968537204', '0', 0, 0, 0, '2026-08-27 16:45:49', '2026-08-27 16:45:49', 0);
INSERT INTO `sending_email` VALUES (5193, '{\"internal\":[{\"card_number\":\"0003852880\",\"nik\":\"20241220024501\",\"name\":\"Tilis Tiadi\",\"division_id\":0,\"is_pic\":1,\"email\":\"tilis.local@mail.com\",\"pin_room\":\"736914\"}],\"eksternal\":[]}', 1, '6735204891', '0', 0, 0, 0, '2026-08-28 14:45:37', '2026-08-28 14:45:37', 0);
INSERT INTO `sending_email` VALUES (5194, '{\"internal\":[{\"card_number\":\"0003852880\",\"nik\":\"20241220024501\",\"name\":\"Tilis Tiadi\",\"division_id\":0,\"is_pic\":1,\"email\":\"tilis.local@mail.com\",\"pin_room\":\"715206\"}],\"eksternal\":[]}', 1, '3785961420', '0', 0, 0, 0, '2026-08-28 14:53:03', '2026-08-28 14:53:03', 0);
INSERT INTO `sending_email` VALUES (5195, '{\"internal\":[{\"card_number\":\"0003852880\",\"nik\":\"20241220024501\",\"name\":\"Tilis Tiadi\",\"division_id\":0,\"is_pic\":1,\"email\":\"tilis.local@mail.com\",\"pin_room\":\"210369\"}],\"eksternal\":[]}', 1, '2637049518', '0', 0, 0, 0, '2026-08-28 16:18:52', '2026-08-28 16:18:52', 0);
INSERT INTO `sending_email` VALUES (5196, '{\"internal\":[{\"card_number\":\"0003852880\",\"nik\":\"20241220024501\",\"name\":\"Tilis Tiadi\",\"division_id\":0,\"is_pic\":1,\"email\":\"tilis.local@mail.com\",\"pin_room\":\"756034\"}],\"eksternal\":[]}', 1, '9182476053', '0', 0, 0, 0, '2026-08-28 16:46:50', '2026-08-28 16:46:50', 0);
INSERT INTO `sending_email` VALUES (5197, '{\"internal\":[{\"card_number\":\"0003852880\",\"nik\":\"20241220024501\",\"name\":\"Tilis Tiadi\",\"division_id\":0,\"is_pic\":1,\"email\":\"tilis.local@mail.com\",\"pin_room\":\"372085\"}],\"eksternal\":[]}', 1, '7926043815', '0', 0, 0, 0, '2026-08-28 17:11:16', '2026-08-28 17:11:16', 0);
INSERT INTO `sending_email` VALUES (5198, '{\"internal\":[{\"card_number\":\"033313\",\"nik\":\"admin\",\"name\":\"Administrator\",\"division_id\":0,\"is_pic\":1,\"email\":\"admin@adminmail.com\",\"pin_room\":\"916035\"}],\"eksternal\":[]}', 1, '3908417526', '0', 0, 0, 0, '2026-08-28 17:11:38', '2026-08-28 17:11:38', 0);

-- ----------------------------
-- Table structure for sending_notif
-- ----------------------------
DROP TABLE IF EXISTS `sending_notif`;
CREATE TABLE `sending_notif`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `batch` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `type` int NULL DEFAULT NULL,
  `booking_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pending` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `error_sending` int NOT NULL,
  `success` int NOT NULL,
  `is_status` int NULL DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5199 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sending_notif
-- ----------------------------
INSERT INTO `sending_notif` VALUES (5188, '[{\"card_number\":\"123444\",\"nik\":\"EC20250110131102\",\"name\":\"Handi\",\"division_id\":0,\"is_pic\":1,\"email\":\"eMAIL@mail.com\",\"pin_room\":\"802697\"}]', 1, '3015764982', '0', 0, 0, 1, '2026-08-26 23:27:48', '2026-08-26 23:27:48', 0);
INSERT INTO `sending_notif` VALUES (5189, '[{\"card_number\":\"033313\",\"nik\":\"admin\",\"name\":\"Administrator\",\"division_id\":0,\"is_pic\":1,\"email\":\"admin@adminmail.com\",\"pin_room\":\"039748\"}]', 1, '9087546123', '0', 0, 0, 1, '2026-08-27 09:28:39', '2026-08-27 09:28:39', 0);
INSERT INTO `sending_notif` VALUES (5190, '[{\"card_number\":\"033313\",\"nik\":\"admin\",\"name\":\"Administrator\",\"division_id\":0,\"is_pic\":1,\"email\":\"admin@adminmail.com\",\"pin_room\":\"860234\"}]', 1, '2590876143', '0', 0, 0, 1, '2026-08-27 14:08:34', '2026-08-27 14:08:34', 0);
INSERT INTO `sending_notif` VALUES (5191, '[{\"card_number\":\"0003852880\",\"nik\":\"20241220024501\",\"name\":\"Tilis Tiadi\",\"division_id\":0,\"is_pic\":1,\"email\":\"tilis.local@mail.com\",\"pin_room\":\"063547\"}]', 1, '6451927038', '0', 0, 0, 1, '2026-08-27 16:42:34', '2026-08-27 16:42:34', 0);
INSERT INTO `sending_notif` VALUES (5192, '[{\"card_number\":\"0003852880\",\"nik\":\"20241220024501\",\"name\":\"Tilis Tiadi\",\"division_id\":0,\"is_pic\":1,\"email\":\"tilis.local@mail.com\",\"pin_room\":\"018936\"}]', 1, '1968537204', '0', 0, 0, 1, '2026-08-27 16:45:49', '2026-08-27 16:45:49', 0);
INSERT INTO `sending_notif` VALUES (5193, '[{\"card_number\":\"0003852880\",\"nik\":\"20241220024501\",\"name\":\"Tilis Tiadi\",\"division_id\":0,\"is_pic\":1,\"email\":\"tilis.local@mail.com\",\"pin_room\":\"736914\"}]', 1, '6735204891', '0', 0, 0, 1, '2026-08-28 14:45:37', '2026-08-28 14:45:37', 0);
INSERT INTO `sending_notif` VALUES (5194, '[{\"card_number\":\"0003852880\",\"nik\":\"20241220024501\",\"name\":\"Tilis Tiadi\",\"division_id\":0,\"is_pic\":1,\"email\":\"tilis.local@mail.com\",\"pin_room\":\"715206\"}]', 1, '3785961420', '0', 0, 0, 1, '2026-08-28 14:53:03', '2026-08-28 14:53:03', 0);
INSERT INTO `sending_notif` VALUES (5195, '[{\"card_number\":\"0003852880\",\"nik\":\"20241220024501\",\"name\":\"Tilis Tiadi\",\"division_id\":0,\"is_pic\":1,\"email\":\"tilis.local@mail.com\",\"pin_room\":\"210369\"}]', 1, '2637049518', '0', 0, 0, 1, '2026-08-28 16:18:52', '2026-08-28 16:18:52', 0);
INSERT INTO `sending_notif` VALUES (5196, '[{\"card_number\":\"0003852880\",\"nik\":\"20241220024501\",\"name\":\"Tilis Tiadi\",\"division_id\":0,\"is_pic\":1,\"email\":\"tilis.local@mail.com\",\"pin_room\":\"756034\"}]', 1, '9182476053', '0', 0, 0, 1, '2026-08-28 16:46:50', '2026-08-28 16:46:50', 0);
INSERT INTO `sending_notif` VALUES (5197, '[{\"card_number\":\"0003852880\",\"nik\":\"20241220024501\",\"name\":\"Tilis Tiadi\",\"division_id\":0,\"is_pic\":1,\"email\":\"tilis.local@mail.com\",\"pin_room\":\"372085\"}]', 1, '7926043815', '0', 0, 0, 1, '2026-08-28 17:11:16', '2026-08-28 17:11:16', 0);
INSERT INTO `sending_notif` VALUES (5198, '[{\"card_number\":\"033313\",\"nik\":\"admin\",\"name\":\"Administrator\",\"division_id\":0,\"is_pic\":1,\"email\":\"admin@adminmail.com\",\"pin_room\":\"916035\"}]', 1, '3908417526', '0', 0, 0, 1, '2026-08-28 17:11:38', '2026-08-28 17:11:38', 0);

-- ----------------------------
-- Table structure for sending_text_status
-- ----------------------------
DROP TABLE IF EXISTS `sending_text_status`;
CREATE TABLE `sending_text_status`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `text` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sending_text_status
-- ----------------------------
INSERT INTO `sending_text_status` VALUES (1, 'iNVITATION');
INSERT INTO `sending_text_status` VALUES (2, 'RESCHEDULE');
INSERT INTO `sending_text_status` VALUES (3, 'CANCEL');

-- ----------------------------
-- Table structure for setting_email_template
-- ----------------------------
DROP TABLE IF EXISTS `setting_email_template`;
CREATE TABLE `setting_email_template`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `is_enabled` int NULL DEFAULT 0,
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `title_of_text` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `to_text` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `title_agenda_text` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `date_text` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `room` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `detail_location` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `greeting_text` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `content_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `attendance_text` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `attendance_no_text` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `close_text` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `support_text` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `foot_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `link` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `map_link_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'Direction map',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of setting_email_template
-- ----------------------------
INSERT INTO `setting_email_template` VALUES (1, 1, 'invitation', 'Invitation Meeting - ', 'Kepada', 'Agenda', 'Tanggal', 'Ruangan', 'Lokasi', 'Dengan Hormat,', 'Dari email ini kami mengundang bapak/ibu sebagai partisipan dalam acara rapat kali ini yang akan didakan pada tempat dan waktu yang tertera.Kami berharap kedatangan bapak/ibu dalam rapat kali ini, terimakasih.', 'Attendance', 'Not Attendance', 'Terima kasih,', 'Support by ', 'Bio Experience', 'http://bio-experience.com/', 'Direction map');
INSERT INTO `setting_email_template` VALUES (2, 1, 'reschedule', 'Reschedule Meeting - ', 'Kepada', 'Agenda', 'Tanggal', 'Ruangan', 'Lokasi', 'Dengan Hormat,', 'Dari email ini kami mengundang bapak/ibu sebagai partisipan dalam acara rapat kali ini yang akan didakan pada tempat dan waktu yang tertera.Kami berharap kedatangan bapak/ibu dalam rapat kali ini, terimakasih.', 'Attendance', 'Not Attendance', 'Terima kasih,', 'Support by ', 'Bio Experience', 'http://bio-experience.com/', 'Direction map');
INSERT INTO `setting_email_template` VALUES (3, 1, 'cancel', 'Cancel Meeting - ', 'Kepada', 'Agenda', 'Tanggal', 'Ruangan', 'Lokasi', 'Dengan Hormat,', 'Dari email ini kami memberitahukan bahwa rapat/meeting ini telah dibatalkan,terimakasih.', 'Attendance', 'Not Attendance', 'Terima kasih,', 'Support by ', 'Bio Experience', 'http://bio-experience.com/', 'Direction map');
INSERT INTO `setting_email_template` VALUES (4, 1, 'desk_invitation', 'Desk Reservation - ', 'Kepada', 'Agenda', 'Tanggal', 'Ruangan', 'Lokasi', 'Dengan Hormat,', 'Dari email ini kami memberitahukan bapak/ibu telah memesan desk booking', 'Attendance', 'Not Attendance', 'Terima kasih,', 'Support by ', 'Bio Experience', 'http://bio-experience.com/', 'Direction map');
INSERT INTO `setting_email_template` VALUES (5, 1, 'desk_reschedule', 'Desk Reservation - ', 'Kepada', 'Agenda', 'Tanggal', 'Ruangan', 'Lokasi', 'Dengan Hormat,', 'Dari email ini kami memberitahukan bapak/ibu telah memesan desk booking', 'Attendance', 'Not Attendance', 'Terima kasih,', 'Support by ', 'Bio Experience', 'http://bio-experience.com/', 'Direction map');
INSERT INTO `setting_email_template` VALUES (6, 1, 'desk_ cancel', 'Desk Reservation Canceled - ', 'Kepada', 'Agenda', 'Tanggal', 'Ruangan', 'Lokasi', 'Dengan Hormat,', 'Dari email ini kami mengundang bapak/ibu sebagai partisipan dalam acara rapat kali ini yang akan didakan pada tempat dan waktu yang tertera.Kami berharap kedatangan bapak/ibu dalam rapat kali ini, terimakasih.', 'Attendance', 'Not Attendance', 'Terima kasih,', 'Support by ', 'Bio Experience', 'http://bio-experience.com/', 'Direction map');

-- ----------------------------
-- Table structure for setting_invoice_config
-- ----------------------------
DROP TABLE IF EXISTS `setting_invoice_config`;
CREATE TABLE `setting_invoice_config`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `date_format` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `date_text` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `to_text` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `up_text` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `no_inv_text` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `no_profit_text` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `description_text` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `amount_text` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `content_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `amount_bill_text` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `tax_text` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `tax_amount` int NULL DEFAULT NULL,
  `total_text` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `footer_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `footer2_text` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `footer3_text` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `is_deleted` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of setting_invoice_config
-- ----------------------------
INSERT INTO `setting_invoice_config` VALUES (1, 'd/m/Y', 'Tanggal', 'Kpd.', 'Up.', 'No. Invoice', 'No. Profit Center', 'Uraian', 'Jumlah', '<p>Pembayaran atas pemakaian ruang meeting, &nbsp;&nbsp; &nbsp;<br />\r\nSesuai dengan Kontrak/ Perjanjian No.....<br />\r\nRincian terlampir.&nbsp;&nbsp; &nbsp;</p>\r\n\r\n<p>Periode Bulan&nbsp; %bln1%&nbsp; s/d %bln2%&nbsp; Tahun :&nbsp; &nbsp; %tahun%&nbsp;&nbsp;</p>\r\n', 'Nilai Tagihan', 'PPN', 10, 'TOTAL', '<p>Terbilang:&nbsp;&nbsp; &nbsp;<br />\r\nMohon Tagihan tersebut dikirim pada : PT. Bank Negara Indonesia - Cabang Jatinegara<br />\r\nRekening No. 0008912317 ( Rekening Rupiah )<br />\r\nSwift Code No. : BNINIDJA<br />\r\nAtas Nama : PT. Rekayasa Industri</p>\r\n', 'PT. Rekayasa Industri', 'Dedy Rinaldi', 0);

-- ----------------------------
-- Table structure for setting_invoice_text
-- ----------------------------
DROP TABLE IF EXISTS `setting_invoice_text`;
CREATE TABLE `setting_invoice_text`  (
  `id` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `created_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 's',
  `created_at` datetime NULL DEFAULT current_timestamp(),
  `updated_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT ' s',
  `updated_at` datetime NULL DEFAULT current_timestamp(),
  `is_deleted` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of setting_invoice_text
-- ----------------------------
INSERT INTO `setting_invoice_text` VALUES ('0', 'Belum dikirim ke finance', '', '2020-07-16 00:00:00', '2020-07-16 00:00:00', '2020-07-16 00:00:00', 0);
INSERT INTO `setting_invoice_text` VALUES ('1', 'Sudah dikirim ke finance', '', '2020-07-16 00:00:00', '2020-07-16 00:00:00', '2020-07-16 00:00:00', 0);
INSERT INTO `setting_invoice_text` VALUES ('2', 'Sudah dibayar', '', '2020-07-16 00:00:00', '2020-07-16 00:00:00', '2020-07-16 00:00:00', 0);
INSERT INTO `setting_invoice_text` VALUES ('N/A', 'N/A', '', '2020-07-16 00:00:00', '2020-07-16 00:00:00', '2020-07-16 00:00:00', 0);

-- ----------------------------
-- Table structure for setting_log_config
-- ----------------------------
DROP TABLE IF EXISTS `setting_log_config`;
CREATE TABLE `setting_log_config`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `text` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of setting_log_config
-- ----------------------------
INSERT INTO `setting_log_config` VALUES (1, 'Pin not have permission');
INSERT INTO `setting_log_config` VALUES (2, 'Access door not connected');
INSERT INTO `setting_log_config` VALUES (3, 'Pin have permission');
INSERT INTO `setting_log_config` VALUES (4, 'You have permission');
INSERT INTO `setting_log_config` VALUES (5, 'You not have permission');

-- ----------------------------
-- Table structure for setting_pantry_config
-- ----------------------------
DROP TABLE IF EXISTS `setting_pantry_config`;
CREATE TABLE `setting_pantry_config`  (
  `id` int NOT NULL,
  `status` int NOT NULL,
  `pantry_expired` int NOT NULL,
  `max_order_qty` int NOT NULL COMMENT 'pilih 0 to infinity',
  `before_order_meeting` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of setting_pantry_config
-- ----------------------------
INSERT INTO `setting_pantry_config` VALUES (1, 1, 30, 50, 30);

-- ----------------------------
-- Table structure for setting_rule_booking
-- ----------------------------
DROP TABLE IF EXISTS `setting_rule_booking`;
CREATE TABLE `setting_rule_booking`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `duration` int NULL DEFAULT 0,
  `if_unused_room` int NOT NULL,
  `max_end_meeting` int NULL DEFAULT NULL,
  `notif_unused_meeting` int NULL DEFAULT NULL COMMENT 'Cancel a meeting when no participant is present',
  `notif_unuse_before_meeting` int NOT NULL COMMENT 'Notifickasi sebelum meeting digunakan',
  `unuse_cancel_fee` int NOT NULL,
  `max_display_duration` int NULL DEFAULT NULL,
  `room_pin` tinyint(1) NULL DEFAULT NULL,
  `room_pin_number` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `room_pin_refresh` time NULL DEFAULT NULL,
  `extend_meeting` int NULL DEFAULT 30,
  `extend_meeting_max` int NULL DEFAULT 60,
  `extend_count_time` int NULL DEFAULT 0,
  `extend_meeting_notification` int NULL DEFAULT 1,
  `end_early_meeting` int NULL DEFAULT NULL,
  `limit_time_booking` int NULL DEFAULT 0 COMMENT '0 = no limit',
  `created_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `updated_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `is_config_setting_enable` int NULL DEFAULT 0,
  `config_room_for_usage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `is_enable_approval` int NULL DEFAULT 0,
  `config_approval_user` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `is_enable_permission` int NULL DEFAULT 0,
  `config_permission_user` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `config_permission_checkin` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'pic' COMMENT '1 only host | 2 can participant & host',
  `config_permission_end` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'pic' COMMENT '1 only host | 2 can participant & host',
  `config_min_duration` int NULL DEFAULT 15,
  `config_max_duration` int NULL DEFAULT 240,
  `config_advance_booking` int NULL DEFAULT 7,
  `is_enable_recurring` int NULL DEFAULT 0,
  `is_enable_checkin` int NULL DEFAULT 0,
  `config_advance_checkin` int NULL DEFAULT 5 COMMENT 'check in 5 menit sebelum meeting',
  `is_realease_checkin_timeout` int NULL DEFAULT 0,
  `config_release_room_checkin_timeout` int NULL DEFAULT 10,
  `config_participant_checkin_count` int NULL DEFAULT 0,
  `is_enable_checkin_count` int NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of setting_rule_booking
-- ----------------------------
INSERT INTO `setting_rule_booking` VALUES (1, 15, 0, 30, 30, 5, 60, 240, 1, '123456', '05:00:00', 1, 30, 30, 10, 1, 0, 'admin', 'admin', '2023-06-30 12:54:43', 0, '', 0, NULL, 0, NULL, '1', '1', 15, 240, 7, 0, 0, 0, 1, NULL, 0, 0);

-- ----------------------------
-- Table structure for setting_rule_deskbooking
-- ----------------------------
DROP TABLE IF EXISTS `setting_rule_deskbooking`;
CREATE TABLE `setting_rule_deskbooking`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `duration` int NULL DEFAULT 0,
  `max_end_meeting` int NULL DEFAULT NULL,
  `notification_reminder` int NULL DEFAULT 0 COMMENT 'Notifickasi sebelum meeting digunakan',
  `max_display_duration` int NULL DEFAULT NULL,
  `extend_meeting` int NULL DEFAULT 30,
  `extend_meeting_max` int NULL DEFAULT 60,
  `extend_count_time` int NULL DEFAULT 0,
  `extend_meeting_notification` int NULL DEFAULT 1,
  `end_early_meeting` int NULL DEFAULT NULL,
  `limit_time_booking` int NULL DEFAULT 0 COMMENT '0 = no limit',
  `created_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `updated_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `is_config_setting_enable` int NULL DEFAULT 1,
  `config_min_duration` int NULL DEFAULT 15,
  `config_max_duration` int NULL DEFAULT 240,
  `config_advance_booking` int NULL DEFAULT 7,
  `is_config_checkin_enable` int NULL DEFAULT 0,
  `config_enable_checkin` int NULL DEFAULT 1,
  `config_permission_checkin` int NULL DEFAULT 1 COMMENT '1 only host | 2 can participant & host',
  `config_permission_end` int NULL DEFAULT 1 COMMENT '1 only host | 2 can participant & host',
  `config_advance_checkin` int NULL DEFAULT 7,
  `config_release_room_checkin_enable` int NULL DEFAULT 1,
  `config_release_room_checkin_time` int NULL DEFAULT 10,
  `config_participant_checkin_count` int NULL DEFAULT 0,
  `enable_security_captcha` int NULL DEFAULT 1 COMMENT 'Enable captha',
  `config_book_duration` int NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of setting_rule_deskbooking
-- ----------------------------
INSERT INTO `setting_rule_deskbooking` VALUES (1, 1, 30, 5, 240, 1, 30, 30, 10, 1, 0, 'admin', 'admin', '2023-06-30 12:54:43', 1, 5, 240, 7, 0, 1, 1, 1, 7, 1, 5, 0, 1, 1);

-- ----------------------------
-- Table structure for setting_smtp
-- ----------------------------
DROP TABLE IF EXISTS `setting_smtp`;
CREATE TABLE `setting_smtp`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `selected_email` int NULL DEFAULT 0,
  `is_enabled` int NULL DEFAULT 0,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `title_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `host` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `port` int NOT NULL,
  `secure` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of setting_smtp
-- ----------------------------
INSERT INTO `setting_smtp` VALUES (1, 1, 1, 'BIO SMTP Server', 'Email SMR', 'mail.bio-experience.com', 'team-noreply@bio-experience.com', 'bestsolution123!@#', 465, 1, '2020-01-16 00:00:00', '2020-01-16 00:00:00', 0);
INSERT INTO `setting_smtp` VALUES (2, 0, 0, 'Custom SMTP Server', 'Email SMR', 'mail.server.com', 'email@server.com', 'pass123456', 465, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0);
INSERT INTO `setting_smtp` VALUES (3, 0, 0, 'Disabled', 'Disabled', '', '', '', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0);

-- ----------------------------
-- Table structure for time_am_meeting
-- ----------------------------
DROP TABLE IF EXISTS `time_am_meeting`;
CREATE TABLE `time_am_meeting`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `time` int NULL DEFAULT 0,
  `day` int NULL DEFAULT 0,
  `desc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `created_by` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `updated_by` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `is_disactivated` int NULL DEFAULT 0,
  `is_deleted` int NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of time_am_meeting
-- ----------------------------
INSERT INTO `time_am_meeting` VALUES (1, 30, 0, '30 MIN', NULL, NULL, NULL, NULL, 0, 0);
INSERT INTO `time_am_meeting` VALUES (2, 60, 0, '60 MIN', NULL, NULL, NULL, NULL, 0, 0);
INSERT INTO `time_am_meeting` VALUES (3, 90, 0, '90 MIN', NULL, NULL, NULL, NULL, 0, 0);
INSERT INTO `time_am_meeting` VALUES (4, 120, 0, '120 MIN\n', NULL, NULL, NULL, NULL, 0, 0);
INSERT INTO `time_am_meeting` VALUES (5, 0, 1, 'ALL DAY', NULL, NULL, NULL, NULL, 0, 0);

-- ----------------------------
-- Table structure for time_schedule_1
-- ----------------------------
DROP TABLE IF EXISTS `time_schedule_1`;
CREATE TABLE `time_schedule_1`  (
  `timeid` int NOT NULL AUTO_INCREMENT,
  `time` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_deleted` int NOT NULL,
  PRIMARY KEY (`timeid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1441 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of time_schedule_1
-- ----------------------------
INSERT INTO `time_schedule_1` VALUES (1, '00:00', 0);
INSERT INTO `time_schedule_1` VALUES (2, '00:01', 0);
INSERT INTO `time_schedule_1` VALUES (3, '00:02', 0);
INSERT INTO `time_schedule_1` VALUES (4, '00:03', 0);
INSERT INTO `time_schedule_1` VALUES (5, '00:04', 0);
INSERT INTO `time_schedule_1` VALUES (6, '00:05', 0);
INSERT INTO `time_schedule_1` VALUES (7, '00:06', 0);
INSERT INTO `time_schedule_1` VALUES (8, '00:07', 0);
INSERT INTO `time_schedule_1` VALUES (9, '00:08', 0);
INSERT INTO `time_schedule_1` VALUES (10, '00:09', 0);
INSERT INTO `time_schedule_1` VALUES (11, '00:10', 0);
INSERT INTO `time_schedule_1` VALUES (12, '00:11', 0);
INSERT INTO `time_schedule_1` VALUES (13, '00:12', 0);
INSERT INTO `time_schedule_1` VALUES (14, '00:13', 0);
INSERT INTO `time_schedule_1` VALUES (15, '00:14', 0);
INSERT INTO `time_schedule_1` VALUES (16, '00:15', 0);
INSERT INTO `time_schedule_1` VALUES (17, '00:16', 0);
INSERT INTO `time_schedule_1` VALUES (18, '00:17', 0);
INSERT INTO `time_schedule_1` VALUES (19, '00:18', 0);
INSERT INTO `time_schedule_1` VALUES (20, '00:19', 0);
INSERT INTO `time_schedule_1` VALUES (21, '00:20', 0);
INSERT INTO `time_schedule_1` VALUES (22, '00:21', 0);
INSERT INTO `time_schedule_1` VALUES (23, '00:22', 0);
INSERT INTO `time_schedule_1` VALUES (24, '00:23', 0);
INSERT INTO `time_schedule_1` VALUES (25, '00:24', 0);
INSERT INTO `time_schedule_1` VALUES (26, '00:25', 0);
INSERT INTO `time_schedule_1` VALUES (27, '00:26', 0);
INSERT INTO `time_schedule_1` VALUES (28, '00:27', 0);
INSERT INTO `time_schedule_1` VALUES (29, '00:28', 0);
INSERT INTO `time_schedule_1` VALUES (30, '00:29', 0);
INSERT INTO `time_schedule_1` VALUES (31, '00:30', 0);
INSERT INTO `time_schedule_1` VALUES (32, '00:31', 0);
INSERT INTO `time_schedule_1` VALUES (33, '00:32', 0);
INSERT INTO `time_schedule_1` VALUES (34, '00:33', 0);
INSERT INTO `time_schedule_1` VALUES (35, '00:34', 0);
INSERT INTO `time_schedule_1` VALUES (36, '00:35', 0);
INSERT INTO `time_schedule_1` VALUES (37, '00:36', 0);
INSERT INTO `time_schedule_1` VALUES (38, '00:37', 0);
INSERT INTO `time_schedule_1` VALUES (39, '00:38', 0);
INSERT INTO `time_schedule_1` VALUES (40, '00:39', 0);
INSERT INTO `time_schedule_1` VALUES (41, '00:40', 0);
INSERT INTO `time_schedule_1` VALUES (42, '00:41', 0);
INSERT INTO `time_schedule_1` VALUES (43, '00:42', 0);
INSERT INTO `time_schedule_1` VALUES (44, '00:43', 0);
INSERT INTO `time_schedule_1` VALUES (45, '00:44', 0);
INSERT INTO `time_schedule_1` VALUES (46, '00:45', 0);
INSERT INTO `time_schedule_1` VALUES (47, '00:46', 0);
INSERT INTO `time_schedule_1` VALUES (48, '00:47', 0);
INSERT INTO `time_schedule_1` VALUES (49, '00:48', 0);
INSERT INTO `time_schedule_1` VALUES (50, '00:49', 0);
INSERT INTO `time_schedule_1` VALUES (51, '00:50', 0);
INSERT INTO `time_schedule_1` VALUES (52, '00:51', 0);
INSERT INTO `time_schedule_1` VALUES (53, '00:52', 0);
INSERT INTO `time_schedule_1` VALUES (54, '00:53', 0);
INSERT INTO `time_schedule_1` VALUES (55, '00:54', 0);
INSERT INTO `time_schedule_1` VALUES (56, '00:55', 0);
INSERT INTO `time_schedule_1` VALUES (57, '00:56', 0);
INSERT INTO `time_schedule_1` VALUES (58, '00:57', 0);
INSERT INTO `time_schedule_1` VALUES (59, '00:58', 0);
INSERT INTO `time_schedule_1` VALUES (60, '00:59', 0);
INSERT INTO `time_schedule_1` VALUES (61, '01:00', 0);
INSERT INTO `time_schedule_1` VALUES (62, '01:01', 0);
INSERT INTO `time_schedule_1` VALUES (63, '01:02', 0);
INSERT INTO `time_schedule_1` VALUES (64, '01:03', 0);
INSERT INTO `time_schedule_1` VALUES (65, '01:04', 0);
INSERT INTO `time_schedule_1` VALUES (66, '01:05', 0);
INSERT INTO `time_schedule_1` VALUES (67, '01:06', 0);
INSERT INTO `time_schedule_1` VALUES (68, '01:07', 0);
INSERT INTO `time_schedule_1` VALUES (69, '01:08', 0);
INSERT INTO `time_schedule_1` VALUES (70, '01:09', 0);
INSERT INTO `time_schedule_1` VALUES (71, '01:10', 0);
INSERT INTO `time_schedule_1` VALUES (72, '01:11', 0);
INSERT INTO `time_schedule_1` VALUES (73, '01:12', 0);
INSERT INTO `time_schedule_1` VALUES (74, '01:13', 0);
INSERT INTO `time_schedule_1` VALUES (75, '01:14', 0);
INSERT INTO `time_schedule_1` VALUES (76, '01:15', 0);
INSERT INTO `time_schedule_1` VALUES (77, '01:16', 0);
INSERT INTO `time_schedule_1` VALUES (78, '01:17', 0);
INSERT INTO `time_schedule_1` VALUES (79, '01:18', 0);
INSERT INTO `time_schedule_1` VALUES (80, '01:19', 0);
INSERT INTO `time_schedule_1` VALUES (81, '01:20', 0);
INSERT INTO `time_schedule_1` VALUES (82, '01:21', 0);
INSERT INTO `time_schedule_1` VALUES (83, '01:22', 0);
INSERT INTO `time_schedule_1` VALUES (84, '01:23', 0);
INSERT INTO `time_schedule_1` VALUES (85, '01:24', 0);
INSERT INTO `time_schedule_1` VALUES (86, '01:25', 0);
INSERT INTO `time_schedule_1` VALUES (87, '01:26', 0);
INSERT INTO `time_schedule_1` VALUES (88, '01:27', 0);
INSERT INTO `time_schedule_1` VALUES (89, '01:28', 0);
INSERT INTO `time_schedule_1` VALUES (90, '01:29', 0);
INSERT INTO `time_schedule_1` VALUES (91, '01:30', 0);
INSERT INTO `time_schedule_1` VALUES (92, '01:31', 0);
INSERT INTO `time_schedule_1` VALUES (93, '01:32', 0);
INSERT INTO `time_schedule_1` VALUES (94, '01:33', 0);
INSERT INTO `time_schedule_1` VALUES (95, '01:34', 0);
INSERT INTO `time_schedule_1` VALUES (96, '01:35', 0);
INSERT INTO `time_schedule_1` VALUES (97, '01:36', 0);
INSERT INTO `time_schedule_1` VALUES (98, '01:37', 0);
INSERT INTO `time_schedule_1` VALUES (99, '01:38', 0);
INSERT INTO `time_schedule_1` VALUES (100, '01:39', 0);
INSERT INTO `time_schedule_1` VALUES (101, '01:40', 0);
INSERT INTO `time_schedule_1` VALUES (102, '01:41', 0);
INSERT INTO `time_schedule_1` VALUES (103, '01:42', 0);
INSERT INTO `time_schedule_1` VALUES (104, '01:43', 0);
INSERT INTO `time_schedule_1` VALUES (105, '01:44', 0);
INSERT INTO `time_schedule_1` VALUES (106, '01:45', 0);
INSERT INTO `time_schedule_1` VALUES (107, '01:46', 0);
INSERT INTO `time_schedule_1` VALUES (108, '01:47', 0);
INSERT INTO `time_schedule_1` VALUES (109, '01:48', 0);
INSERT INTO `time_schedule_1` VALUES (110, '01:49', 0);
INSERT INTO `time_schedule_1` VALUES (111, '01:50', 0);
INSERT INTO `time_schedule_1` VALUES (112, '01:51', 0);
INSERT INTO `time_schedule_1` VALUES (113, '01:52', 0);
INSERT INTO `time_schedule_1` VALUES (114, '01:53', 0);
INSERT INTO `time_schedule_1` VALUES (115, '01:54', 0);
INSERT INTO `time_schedule_1` VALUES (116, '01:55', 0);
INSERT INTO `time_schedule_1` VALUES (117, '01:56', 0);
INSERT INTO `time_schedule_1` VALUES (118, '01:57', 0);
INSERT INTO `time_schedule_1` VALUES (119, '01:58', 0);
INSERT INTO `time_schedule_1` VALUES (120, '01:59', 0);
INSERT INTO `time_schedule_1` VALUES (121, '02:00', 0);
INSERT INTO `time_schedule_1` VALUES (122, '02:01', 0);
INSERT INTO `time_schedule_1` VALUES (123, '02:02', 0);
INSERT INTO `time_schedule_1` VALUES (124, '02:03', 0);
INSERT INTO `time_schedule_1` VALUES (125, '02:04', 0);
INSERT INTO `time_schedule_1` VALUES (126, '02:05', 0);
INSERT INTO `time_schedule_1` VALUES (127, '02:06', 0);
INSERT INTO `time_schedule_1` VALUES (128, '02:07', 0);
INSERT INTO `time_schedule_1` VALUES (129, '02:08', 0);
INSERT INTO `time_schedule_1` VALUES (130, '02:09', 0);
INSERT INTO `time_schedule_1` VALUES (131, '02:10', 0);
INSERT INTO `time_schedule_1` VALUES (132, '02:11', 0);
INSERT INTO `time_schedule_1` VALUES (133, '02:12', 0);
INSERT INTO `time_schedule_1` VALUES (134, '02:13', 0);
INSERT INTO `time_schedule_1` VALUES (135, '02:14', 0);
INSERT INTO `time_schedule_1` VALUES (136, '02:15', 0);
INSERT INTO `time_schedule_1` VALUES (137, '02:16', 0);
INSERT INTO `time_schedule_1` VALUES (138, '02:17', 0);
INSERT INTO `time_schedule_1` VALUES (139, '02:18', 0);
INSERT INTO `time_schedule_1` VALUES (140, '02:19', 0);
INSERT INTO `time_schedule_1` VALUES (141, '02:20', 0);
INSERT INTO `time_schedule_1` VALUES (142, '02:21', 0);
INSERT INTO `time_schedule_1` VALUES (143, '02:22', 0);
INSERT INTO `time_schedule_1` VALUES (144, '02:23', 0);
INSERT INTO `time_schedule_1` VALUES (145, '02:24', 0);
INSERT INTO `time_schedule_1` VALUES (146, '02:25', 0);
INSERT INTO `time_schedule_1` VALUES (147, '02:26', 0);
INSERT INTO `time_schedule_1` VALUES (148, '02:27', 0);
INSERT INTO `time_schedule_1` VALUES (149, '02:28', 0);
INSERT INTO `time_schedule_1` VALUES (150, '02:29', 0);
INSERT INTO `time_schedule_1` VALUES (151, '02:30', 0);
INSERT INTO `time_schedule_1` VALUES (152, '02:31', 0);
INSERT INTO `time_schedule_1` VALUES (153, '02:32', 0);
INSERT INTO `time_schedule_1` VALUES (154, '02:33', 0);
INSERT INTO `time_schedule_1` VALUES (155, '02:34', 0);
INSERT INTO `time_schedule_1` VALUES (156, '02:35', 0);
INSERT INTO `time_schedule_1` VALUES (157, '02:36', 0);
INSERT INTO `time_schedule_1` VALUES (158, '02:37', 0);
INSERT INTO `time_schedule_1` VALUES (159, '02:38', 0);
INSERT INTO `time_schedule_1` VALUES (160, '02:39', 0);
INSERT INTO `time_schedule_1` VALUES (161, '02:40', 0);
INSERT INTO `time_schedule_1` VALUES (162, '02:41', 0);
INSERT INTO `time_schedule_1` VALUES (163, '02:42', 0);
INSERT INTO `time_schedule_1` VALUES (164, '02:43', 0);
INSERT INTO `time_schedule_1` VALUES (165, '02:44', 0);
INSERT INTO `time_schedule_1` VALUES (166, '02:45', 0);
INSERT INTO `time_schedule_1` VALUES (167, '02:46', 0);
INSERT INTO `time_schedule_1` VALUES (168, '02:47', 0);
INSERT INTO `time_schedule_1` VALUES (169, '02:48', 0);
INSERT INTO `time_schedule_1` VALUES (170, '02:49', 0);
INSERT INTO `time_schedule_1` VALUES (171, '02:50', 0);
INSERT INTO `time_schedule_1` VALUES (172, '02:51', 0);
INSERT INTO `time_schedule_1` VALUES (173, '02:52', 0);
INSERT INTO `time_schedule_1` VALUES (174, '02:53', 0);
INSERT INTO `time_schedule_1` VALUES (175, '02:54', 0);
INSERT INTO `time_schedule_1` VALUES (176, '02:55', 0);
INSERT INTO `time_schedule_1` VALUES (177, '02:56', 0);
INSERT INTO `time_schedule_1` VALUES (178, '02:57', 0);
INSERT INTO `time_schedule_1` VALUES (179, '02:58', 0);
INSERT INTO `time_schedule_1` VALUES (180, '02:59', 0);
INSERT INTO `time_schedule_1` VALUES (181, '03:00', 0);
INSERT INTO `time_schedule_1` VALUES (182, '03:01', 0);
INSERT INTO `time_schedule_1` VALUES (183, '03:02', 0);
INSERT INTO `time_schedule_1` VALUES (184, '03:03', 0);
INSERT INTO `time_schedule_1` VALUES (185, '03:04', 0);
INSERT INTO `time_schedule_1` VALUES (186, '03:05', 0);
INSERT INTO `time_schedule_1` VALUES (187, '03:06', 0);
INSERT INTO `time_schedule_1` VALUES (188, '03:07', 0);
INSERT INTO `time_schedule_1` VALUES (189, '03:08', 0);
INSERT INTO `time_schedule_1` VALUES (190, '03:09', 0);
INSERT INTO `time_schedule_1` VALUES (191, '03:10', 0);
INSERT INTO `time_schedule_1` VALUES (192, '03:11', 0);
INSERT INTO `time_schedule_1` VALUES (193, '03:12', 0);
INSERT INTO `time_schedule_1` VALUES (194, '03:13', 0);
INSERT INTO `time_schedule_1` VALUES (195, '03:14', 0);
INSERT INTO `time_schedule_1` VALUES (196, '03:15', 0);
INSERT INTO `time_schedule_1` VALUES (197, '03:16', 0);
INSERT INTO `time_schedule_1` VALUES (198, '03:17', 0);
INSERT INTO `time_schedule_1` VALUES (199, '03:18', 0);
INSERT INTO `time_schedule_1` VALUES (200, '03:19', 0);
INSERT INTO `time_schedule_1` VALUES (201, '03:20', 0);
INSERT INTO `time_schedule_1` VALUES (202, '03:21', 0);
INSERT INTO `time_schedule_1` VALUES (203, '03:22', 0);
INSERT INTO `time_schedule_1` VALUES (204, '03:23', 0);
INSERT INTO `time_schedule_1` VALUES (205, '03:24', 0);
INSERT INTO `time_schedule_1` VALUES (206, '03:25', 0);
INSERT INTO `time_schedule_1` VALUES (207, '03:26', 0);
INSERT INTO `time_schedule_1` VALUES (208, '03:27', 0);
INSERT INTO `time_schedule_1` VALUES (209, '03:28', 0);
INSERT INTO `time_schedule_1` VALUES (210, '03:29', 0);
INSERT INTO `time_schedule_1` VALUES (211, '03:30', 0);
INSERT INTO `time_schedule_1` VALUES (212, '03:31', 0);
INSERT INTO `time_schedule_1` VALUES (213, '03:32', 0);
INSERT INTO `time_schedule_1` VALUES (214, '03:33', 0);
INSERT INTO `time_schedule_1` VALUES (215, '03:34', 0);
INSERT INTO `time_schedule_1` VALUES (216, '03:35', 0);
INSERT INTO `time_schedule_1` VALUES (217, '03:36', 0);
INSERT INTO `time_schedule_1` VALUES (218, '03:37', 0);
INSERT INTO `time_schedule_1` VALUES (219, '03:38', 0);
INSERT INTO `time_schedule_1` VALUES (220, '03:39', 0);
INSERT INTO `time_schedule_1` VALUES (221, '03:40', 0);
INSERT INTO `time_schedule_1` VALUES (222, '03:41', 0);
INSERT INTO `time_schedule_1` VALUES (223, '03:42', 0);
INSERT INTO `time_schedule_1` VALUES (224, '03:43', 0);
INSERT INTO `time_schedule_1` VALUES (225, '03:44', 0);
INSERT INTO `time_schedule_1` VALUES (226, '03:45', 0);
INSERT INTO `time_schedule_1` VALUES (227, '03:46', 0);
INSERT INTO `time_schedule_1` VALUES (228, '03:47', 0);
INSERT INTO `time_schedule_1` VALUES (229, '03:48', 0);
INSERT INTO `time_schedule_1` VALUES (230, '03:49', 0);
INSERT INTO `time_schedule_1` VALUES (231, '03:50', 0);
INSERT INTO `time_schedule_1` VALUES (232, '03:51', 0);
INSERT INTO `time_schedule_1` VALUES (233, '03:52', 0);
INSERT INTO `time_schedule_1` VALUES (234, '03:53', 0);
INSERT INTO `time_schedule_1` VALUES (235, '03:54', 0);
INSERT INTO `time_schedule_1` VALUES (236, '03:55', 0);
INSERT INTO `time_schedule_1` VALUES (237, '03:56', 0);
INSERT INTO `time_schedule_1` VALUES (238, '03:57', 0);
INSERT INTO `time_schedule_1` VALUES (239, '03:58', 0);
INSERT INTO `time_schedule_1` VALUES (240, '03:59', 0);
INSERT INTO `time_schedule_1` VALUES (241, '04:00', 0);
INSERT INTO `time_schedule_1` VALUES (242, '04:01', 0);
INSERT INTO `time_schedule_1` VALUES (243, '04:02', 0);
INSERT INTO `time_schedule_1` VALUES (244, '04:03', 0);
INSERT INTO `time_schedule_1` VALUES (245, '04:04', 0);
INSERT INTO `time_schedule_1` VALUES (246, '04:05', 0);
INSERT INTO `time_schedule_1` VALUES (247, '04:06', 0);
INSERT INTO `time_schedule_1` VALUES (248, '04:07', 0);
INSERT INTO `time_schedule_1` VALUES (249, '04:08', 0);
INSERT INTO `time_schedule_1` VALUES (250, '04:09', 0);
INSERT INTO `time_schedule_1` VALUES (251, '04:10', 0);
INSERT INTO `time_schedule_1` VALUES (252, '04:11', 0);
INSERT INTO `time_schedule_1` VALUES (253, '04:12', 0);
INSERT INTO `time_schedule_1` VALUES (254, '04:13', 0);
INSERT INTO `time_schedule_1` VALUES (255, '04:14', 0);
INSERT INTO `time_schedule_1` VALUES (256, '04:15', 0);
INSERT INTO `time_schedule_1` VALUES (257, '04:16', 0);
INSERT INTO `time_schedule_1` VALUES (258, '04:17', 0);
INSERT INTO `time_schedule_1` VALUES (259, '04:18', 0);
INSERT INTO `time_schedule_1` VALUES (260, '04:19', 0);
INSERT INTO `time_schedule_1` VALUES (261, '04:20', 0);
INSERT INTO `time_schedule_1` VALUES (262, '04:21', 0);
INSERT INTO `time_schedule_1` VALUES (263, '04:22', 0);
INSERT INTO `time_schedule_1` VALUES (264, '04:23', 0);
INSERT INTO `time_schedule_1` VALUES (265, '04:24', 0);
INSERT INTO `time_schedule_1` VALUES (266, '04:25', 0);
INSERT INTO `time_schedule_1` VALUES (267, '04:26', 0);
INSERT INTO `time_schedule_1` VALUES (268, '04:27', 0);
INSERT INTO `time_schedule_1` VALUES (269, '04:28', 0);
INSERT INTO `time_schedule_1` VALUES (270, '04:29', 0);
INSERT INTO `time_schedule_1` VALUES (271, '04:30', 0);
INSERT INTO `time_schedule_1` VALUES (272, '04:31', 0);
INSERT INTO `time_schedule_1` VALUES (273, '04:32', 0);
INSERT INTO `time_schedule_1` VALUES (274, '04:33', 0);
INSERT INTO `time_schedule_1` VALUES (275, '04:34', 0);
INSERT INTO `time_schedule_1` VALUES (276, '04:35', 0);
INSERT INTO `time_schedule_1` VALUES (277, '04:36', 0);
INSERT INTO `time_schedule_1` VALUES (278, '04:37', 0);
INSERT INTO `time_schedule_1` VALUES (279, '04:38', 0);
INSERT INTO `time_schedule_1` VALUES (280, '04:39', 0);
INSERT INTO `time_schedule_1` VALUES (281, '04:40', 0);
INSERT INTO `time_schedule_1` VALUES (282, '04:41', 0);
INSERT INTO `time_schedule_1` VALUES (283, '04:42', 0);
INSERT INTO `time_schedule_1` VALUES (284, '04:43', 0);
INSERT INTO `time_schedule_1` VALUES (285, '04:44', 0);
INSERT INTO `time_schedule_1` VALUES (286, '04:45', 0);
INSERT INTO `time_schedule_1` VALUES (287, '04:46', 0);
INSERT INTO `time_schedule_1` VALUES (288, '04:47', 0);
INSERT INTO `time_schedule_1` VALUES (289, '04:48', 0);
INSERT INTO `time_schedule_1` VALUES (290, '04:49', 0);
INSERT INTO `time_schedule_1` VALUES (291, '04:50', 0);
INSERT INTO `time_schedule_1` VALUES (292, '04:51', 0);
INSERT INTO `time_schedule_1` VALUES (293, '04:52', 0);
INSERT INTO `time_schedule_1` VALUES (294, '04:53', 0);
INSERT INTO `time_schedule_1` VALUES (295, '04:54', 0);
INSERT INTO `time_schedule_1` VALUES (296, '04:55', 0);
INSERT INTO `time_schedule_1` VALUES (297, '04:56', 0);
INSERT INTO `time_schedule_1` VALUES (298, '04:57', 0);
INSERT INTO `time_schedule_1` VALUES (299, '04:58', 0);
INSERT INTO `time_schedule_1` VALUES (300, '04:59', 0);
INSERT INTO `time_schedule_1` VALUES (301, '05:00', 0);
INSERT INTO `time_schedule_1` VALUES (302, '05:01', 0);
INSERT INTO `time_schedule_1` VALUES (303, '05:02', 0);
INSERT INTO `time_schedule_1` VALUES (304, '05:03', 0);
INSERT INTO `time_schedule_1` VALUES (305, '05:04', 0);
INSERT INTO `time_schedule_1` VALUES (306, '05:05', 0);
INSERT INTO `time_schedule_1` VALUES (307, '05:06', 0);
INSERT INTO `time_schedule_1` VALUES (308, '05:07', 0);
INSERT INTO `time_schedule_1` VALUES (309, '05:08', 0);
INSERT INTO `time_schedule_1` VALUES (310, '05:09', 0);
INSERT INTO `time_schedule_1` VALUES (311, '05:10', 0);
INSERT INTO `time_schedule_1` VALUES (312, '05:11', 0);
INSERT INTO `time_schedule_1` VALUES (313, '05:12', 0);
INSERT INTO `time_schedule_1` VALUES (314, '05:13', 0);
INSERT INTO `time_schedule_1` VALUES (315, '05:14', 0);
INSERT INTO `time_schedule_1` VALUES (316, '05:15', 0);
INSERT INTO `time_schedule_1` VALUES (317, '05:16', 0);
INSERT INTO `time_schedule_1` VALUES (318, '05:17', 0);
INSERT INTO `time_schedule_1` VALUES (319, '05:18', 0);
INSERT INTO `time_schedule_1` VALUES (320, '05:19', 0);
INSERT INTO `time_schedule_1` VALUES (321, '05:20', 0);
INSERT INTO `time_schedule_1` VALUES (322, '05:21', 0);
INSERT INTO `time_schedule_1` VALUES (323, '05:22', 0);
INSERT INTO `time_schedule_1` VALUES (324, '05:23', 0);
INSERT INTO `time_schedule_1` VALUES (325, '05:24', 0);
INSERT INTO `time_schedule_1` VALUES (326, '05:25', 0);
INSERT INTO `time_schedule_1` VALUES (327, '05:26', 0);
INSERT INTO `time_schedule_1` VALUES (328, '05:27', 0);
INSERT INTO `time_schedule_1` VALUES (329, '05:28', 0);
INSERT INTO `time_schedule_1` VALUES (330, '05:29', 0);
INSERT INTO `time_schedule_1` VALUES (331, '05:30', 0);
INSERT INTO `time_schedule_1` VALUES (332, '05:31', 0);
INSERT INTO `time_schedule_1` VALUES (333, '05:32', 0);
INSERT INTO `time_schedule_1` VALUES (334, '05:33', 0);
INSERT INTO `time_schedule_1` VALUES (335, '05:34', 0);
INSERT INTO `time_schedule_1` VALUES (336, '05:35', 0);
INSERT INTO `time_schedule_1` VALUES (337, '05:36', 0);
INSERT INTO `time_schedule_1` VALUES (338, '05:37', 0);
INSERT INTO `time_schedule_1` VALUES (339, '05:38', 0);
INSERT INTO `time_schedule_1` VALUES (340, '05:39', 0);
INSERT INTO `time_schedule_1` VALUES (341, '05:40', 0);
INSERT INTO `time_schedule_1` VALUES (342, '05:41', 0);
INSERT INTO `time_schedule_1` VALUES (343, '05:42', 0);
INSERT INTO `time_schedule_1` VALUES (344, '05:43', 0);
INSERT INTO `time_schedule_1` VALUES (345, '05:44', 0);
INSERT INTO `time_schedule_1` VALUES (346, '05:45', 0);
INSERT INTO `time_schedule_1` VALUES (347, '05:46', 0);
INSERT INTO `time_schedule_1` VALUES (348, '05:47', 0);
INSERT INTO `time_schedule_1` VALUES (349, '05:48', 0);
INSERT INTO `time_schedule_1` VALUES (350, '05:49', 0);
INSERT INTO `time_schedule_1` VALUES (351, '05:50', 0);
INSERT INTO `time_schedule_1` VALUES (352, '05:51', 0);
INSERT INTO `time_schedule_1` VALUES (353, '05:52', 0);
INSERT INTO `time_schedule_1` VALUES (354, '05:53', 0);
INSERT INTO `time_schedule_1` VALUES (355, '05:54', 0);
INSERT INTO `time_schedule_1` VALUES (356, '05:55', 0);
INSERT INTO `time_schedule_1` VALUES (357, '05:56', 0);
INSERT INTO `time_schedule_1` VALUES (358, '05:57', 0);
INSERT INTO `time_schedule_1` VALUES (359, '05:58', 0);
INSERT INTO `time_schedule_1` VALUES (360, '05:59', 0);
INSERT INTO `time_schedule_1` VALUES (361, '06:00', 0);
INSERT INTO `time_schedule_1` VALUES (362, '06:01', 0);
INSERT INTO `time_schedule_1` VALUES (363, '06:02', 0);
INSERT INTO `time_schedule_1` VALUES (364, '06:03', 0);
INSERT INTO `time_schedule_1` VALUES (365, '06:04', 0);
INSERT INTO `time_schedule_1` VALUES (366, '06:05', 0);
INSERT INTO `time_schedule_1` VALUES (367, '06:06', 0);
INSERT INTO `time_schedule_1` VALUES (368, '06:07', 0);
INSERT INTO `time_schedule_1` VALUES (369, '06:08', 0);
INSERT INTO `time_schedule_1` VALUES (370, '06:09', 0);
INSERT INTO `time_schedule_1` VALUES (371, '06:10', 0);
INSERT INTO `time_schedule_1` VALUES (372, '06:11', 0);
INSERT INTO `time_schedule_1` VALUES (373, '06:12', 0);
INSERT INTO `time_schedule_1` VALUES (374, '06:13', 0);
INSERT INTO `time_schedule_1` VALUES (375, '06:14', 0);
INSERT INTO `time_schedule_1` VALUES (376, '06:15', 0);
INSERT INTO `time_schedule_1` VALUES (377, '06:16', 0);
INSERT INTO `time_schedule_1` VALUES (378, '06:17', 0);
INSERT INTO `time_schedule_1` VALUES (379, '06:18', 0);
INSERT INTO `time_schedule_1` VALUES (380, '06:19', 0);
INSERT INTO `time_schedule_1` VALUES (381, '06:20', 0);
INSERT INTO `time_schedule_1` VALUES (382, '06:21', 0);
INSERT INTO `time_schedule_1` VALUES (383, '06:22', 0);
INSERT INTO `time_schedule_1` VALUES (384, '06:23', 0);
INSERT INTO `time_schedule_1` VALUES (385, '06:24', 0);
INSERT INTO `time_schedule_1` VALUES (386, '06:25', 0);
INSERT INTO `time_schedule_1` VALUES (387, '06:26', 0);
INSERT INTO `time_schedule_1` VALUES (388, '06:27', 0);
INSERT INTO `time_schedule_1` VALUES (389, '06:28', 0);
INSERT INTO `time_schedule_1` VALUES (390, '06:29', 0);
INSERT INTO `time_schedule_1` VALUES (391, '06:30', 0);
INSERT INTO `time_schedule_1` VALUES (392, '06:31', 0);
INSERT INTO `time_schedule_1` VALUES (393, '06:32', 0);
INSERT INTO `time_schedule_1` VALUES (394, '06:33', 0);
INSERT INTO `time_schedule_1` VALUES (395, '06:34', 0);
INSERT INTO `time_schedule_1` VALUES (396, '06:35', 0);
INSERT INTO `time_schedule_1` VALUES (397, '06:36', 0);
INSERT INTO `time_schedule_1` VALUES (398, '06:37', 0);
INSERT INTO `time_schedule_1` VALUES (399, '06:38', 0);
INSERT INTO `time_schedule_1` VALUES (400, '06:39', 0);
INSERT INTO `time_schedule_1` VALUES (401, '06:40', 0);
INSERT INTO `time_schedule_1` VALUES (402, '06:41', 0);
INSERT INTO `time_schedule_1` VALUES (403, '06:42', 0);
INSERT INTO `time_schedule_1` VALUES (404, '06:43', 0);
INSERT INTO `time_schedule_1` VALUES (405, '06:44', 0);
INSERT INTO `time_schedule_1` VALUES (406, '06:45', 0);
INSERT INTO `time_schedule_1` VALUES (407, '06:46', 0);
INSERT INTO `time_schedule_1` VALUES (408, '06:47', 0);
INSERT INTO `time_schedule_1` VALUES (409, '06:48', 0);
INSERT INTO `time_schedule_1` VALUES (410, '06:49', 0);
INSERT INTO `time_schedule_1` VALUES (411, '06:50', 0);
INSERT INTO `time_schedule_1` VALUES (412, '06:51', 0);
INSERT INTO `time_schedule_1` VALUES (413, '06:52', 0);
INSERT INTO `time_schedule_1` VALUES (414, '06:53', 0);
INSERT INTO `time_schedule_1` VALUES (415, '06:54', 0);
INSERT INTO `time_schedule_1` VALUES (416, '06:55', 0);
INSERT INTO `time_schedule_1` VALUES (417, '06:56', 0);
INSERT INTO `time_schedule_1` VALUES (418, '06:57', 0);
INSERT INTO `time_schedule_1` VALUES (419, '06:58', 0);
INSERT INTO `time_schedule_1` VALUES (420, '06:59', 0);
INSERT INTO `time_schedule_1` VALUES (421, '07:00', 0);
INSERT INTO `time_schedule_1` VALUES (422, '07:01', 0);
INSERT INTO `time_schedule_1` VALUES (423, '07:02', 0);
INSERT INTO `time_schedule_1` VALUES (424, '07:03', 0);
INSERT INTO `time_schedule_1` VALUES (425, '07:04', 0);
INSERT INTO `time_schedule_1` VALUES (426, '07:05', 0);
INSERT INTO `time_schedule_1` VALUES (427, '07:06', 0);
INSERT INTO `time_schedule_1` VALUES (428, '07:07', 0);
INSERT INTO `time_schedule_1` VALUES (429, '07:08', 0);
INSERT INTO `time_schedule_1` VALUES (430, '07:09', 0);
INSERT INTO `time_schedule_1` VALUES (431, '07:10', 0);
INSERT INTO `time_schedule_1` VALUES (432, '07:11', 0);
INSERT INTO `time_schedule_1` VALUES (433, '07:12', 0);
INSERT INTO `time_schedule_1` VALUES (434, '07:13', 0);
INSERT INTO `time_schedule_1` VALUES (435, '07:14', 0);
INSERT INTO `time_schedule_1` VALUES (436, '07:15', 0);
INSERT INTO `time_schedule_1` VALUES (437, '07:16', 0);
INSERT INTO `time_schedule_1` VALUES (438, '07:17', 0);
INSERT INTO `time_schedule_1` VALUES (439, '07:18', 0);
INSERT INTO `time_schedule_1` VALUES (440, '07:19', 0);
INSERT INTO `time_schedule_1` VALUES (441, '07:20', 0);
INSERT INTO `time_schedule_1` VALUES (442, '07:21', 0);
INSERT INTO `time_schedule_1` VALUES (443, '07:22', 0);
INSERT INTO `time_schedule_1` VALUES (444, '07:23', 0);
INSERT INTO `time_schedule_1` VALUES (445, '07:24', 0);
INSERT INTO `time_schedule_1` VALUES (446, '07:25', 0);
INSERT INTO `time_schedule_1` VALUES (447, '07:26', 0);
INSERT INTO `time_schedule_1` VALUES (448, '07:27', 0);
INSERT INTO `time_schedule_1` VALUES (449, '07:28', 0);
INSERT INTO `time_schedule_1` VALUES (450, '07:29', 0);
INSERT INTO `time_schedule_1` VALUES (451, '07:30', 0);
INSERT INTO `time_schedule_1` VALUES (452, '07:31', 0);
INSERT INTO `time_schedule_1` VALUES (453, '07:32', 0);
INSERT INTO `time_schedule_1` VALUES (454, '07:33', 0);
INSERT INTO `time_schedule_1` VALUES (455, '07:34', 0);
INSERT INTO `time_schedule_1` VALUES (456, '07:35', 0);
INSERT INTO `time_schedule_1` VALUES (457, '07:36', 0);
INSERT INTO `time_schedule_1` VALUES (458, '07:37', 0);
INSERT INTO `time_schedule_1` VALUES (459, '07:38', 0);
INSERT INTO `time_schedule_1` VALUES (460, '07:39', 0);
INSERT INTO `time_schedule_1` VALUES (461, '07:40', 0);
INSERT INTO `time_schedule_1` VALUES (462, '07:41', 0);
INSERT INTO `time_schedule_1` VALUES (463, '07:42', 0);
INSERT INTO `time_schedule_1` VALUES (464, '07:43', 0);
INSERT INTO `time_schedule_1` VALUES (465, '07:44', 0);
INSERT INTO `time_schedule_1` VALUES (466, '07:45', 0);
INSERT INTO `time_schedule_1` VALUES (467, '07:46', 0);
INSERT INTO `time_schedule_1` VALUES (468, '07:47', 0);
INSERT INTO `time_schedule_1` VALUES (469, '07:48', 0);
INSERT INTO `time_schedule_1` VALUES (470, '07:49', 0);
INSERT INTO `time_schedule_1` VALUES (471, '07:50', 0);
INSERT INTO `time_schedule_1` VALUES (472, '07:51', 0);
INSERT INTO `time_schedule_1` VALUES (473, '07:52', 0);
INSERT INTO `time_schedule_1` VALUES (474, '07:53', 0);
INSERT INTO `time_schedule_1` VALUES (475, '07:54', 0);
INSERT INTO `time_schedule_1` VALUES (476, '07:55', 0);
INSERT INTO `time_schedule_1` VALUES (477, '07:56', 0);
INSERT INTO `time_schedule_1` VALUES (478, '07:57', 0);
INSERT INTO `time_schedule_1` VALUES (479, '07:58', 0);
INSERT INTO `time_schedule_1` VALUES (480, '07:59', 0);
INSERT INTO `time_schedule_1` VALUES (481, '08:00', 0);
INSERT INTO `time_schedule_1` VALUES (482, '08:01', 0);
INSERT INTO `time_schedule_1` VALUES (483, '08:02', 0);
INSERT INTO `time_schedule_1` VALUES (484, '08:03', 0);
INSERT INTO `time_schedule_1` VALUES (485, '08:04', 0);
INSERT INTO `time_schedule_1` VALUES (486, '08:05', 0);
INSERT INTO `time_schedule_1` VALUES (487, '08:06', 0);
INSERT INTO `time_schedule_1` VALUES (488, '08:07', 0);
INSERT INTO `time_schedule_1` VALUES (489, '08:08', 0);
INSERT INTO `time_schedule_1` VALUES (490, '08:09', 0);
INSERT INTO `time_schedule_1` VALUES (491, '08:10', 0);
INSERT INTO `time_schedule_1` VALUES (492, '08:11', 0);
INSERT INTO `time_schedule_1` VALUES (493, '08:12', 0);
INSERT INTO `time_schedule_1` VALUES (494, '08:13', 0);
INSERT INTO `time_schedule_1` VALUES (495, '08:14', 0);
INSERT INTO `time_schedule_1` VALUES (496, '08:15', 0);
INSERT INTO `time_schedule_1` VALUES (497, '08:16', 0);
INSERT INTO `time_schedule_1` VALUES (498, '08:17', 0);
INSERT INTO `time_schedule_1` VALUES (499, '08:18', 0);
INSERT INTO `time_schedule_1` VALUES (500, '08:19', 0);
INSERT INTO `time_schedule_1` VALUES (501, '08:20', 0);
INSERT INTO `time_schedule_1` VALUES (502, '08:21', 0);
INSERT INTO `time_schedule_1` VALUES (503, '08:22', 0);
INSERT INTO `time_schedule_1` VALUES (504, '08:23', 0);
INSERT INTO `time_schedule_1` VALUES (505, '08:24', 0);
INSERT INTO `time_schedule_1` VALUES (506, '08:25', 0);
INSERT INTO `time_schedule_1` VALUES (507, '08:26', 0);
INSERT INTO `time_schedule_1` VALUES (508, '08:27', 0);
INSERT INTO `time_schedule_1` VALUES (509, '08:28', 0);
INSERT INTO `time_schedule_1` VALUES (510, '08:29', 0);
INSERT INTO `time_schedule_1` VALUES (511, '08:30', 0);
INSERT INTO `time_schedule_1` VALUES (512, '08:31', 0);
INSERT INTO `time_schedule_1` VALUES (513, '08:32', 0);
INSERT INTO `time_schedule_1` VALUES (514, '08:33', 0);
INSERT INTO `time_schedule_1` VALUES (515, '08:34', 0);
INSERT INTO `time_schedule_1` VALUES (516, '08:35', 0);
INSERT INTO `time_schedule_1` VALUES (517, '08:36', 0);
INSERT INTO `time_schedule_1` VALUES (518, '08:37', 0);
INSERT INTO `time_schedule_1` VALUES (519, '08:38', 0);
INSERT INTO `time_schedule_1` VALUES (520, '08:39', 0);
INSERT INTO `time_schedule_1` VALUES (521, '08:40', 0);
INSERT INTO `time_schedule_1` VALUES (522, '08:41', 0);
INSERT INTO `time_schedule_1` VALUES (523, '08:42', 0);
INSERT INTO `time_schedule_1` VALUES (524, '08:43', 0);
INSERT INTO `time_schedule_1` VALUES (525, '08:44', 0);
INSERT INTO `time_schedule_1` VALUES (526, '08:45', 0);
INSERT INTO `time_schedule_1` VALUES (527, '08:46', 0);
INSERT INTO `time_schedule_1` VALUES (528, '08:47', 0);
INSERT INTO `time_schedule_1` VALUES (529, '08:48', 0);
INSERT INTO `time_schedule_1` VALUES (530, '08:49', 0);
INSERT INTO `time_schedule_1` VALUES (531, '08:50', 0);
INSERT INTO `time_schedule_1` VALUES (532, '08:51', 0);
INSERT INTO `time_schedule_1` VALUES (533, '08:52', 0);
INSERT INTO `time_schedule_1` VALUES (534, '08:53', 0);
INSERT INTO `time_schedule_1` VALUES (535, '08:54', 0);
INSERT INTO `time_schedule_1` VALUES (536, '08:55', 0);
INSERT INTO `time_schedule_1` VALUES (537, '08:56', 0);
INSERT INTO `time_schedule_1` VALUES (538, '08:57', 0);
INSERT INTO `time_schedule_1` VALUES (539, '08:58', 0);
INSERT INTO `time_schedule_1` VALUES (540, '08:59', 0);
INSERT INTO `time_schedule_1` VALUES (541, '09:00', 0);
INSERT INTO `time_schedule_1` VALUES (542, '09:01', 0);
INSERT INTO `time_schedule_1` VALUES (543, '09:02', 0);
INSERT INTO `time_schedule_1` VALUES (544, '09:03', 0);
INSERT INTO `time_schedule_1` VALUES (545, '09:04', 0);
INSERT INTO `time_schedule_1` VALUES (546, '09:05', 0);
INSERT INTO `time_schedule_1` VALUES (547, '09:06', 0);
INSERT INTO `time_schedule_1` VALUES (548, '09:07', 0);
INSERT INTO `time_schedule_1` VALUES (549, '09:08', 0);
INSERT INTO `time_schedule_1` VALUES (550, '09:09', 0);
INSERT INTO `time_schedule_1` VALUES (551, '09:10', 0);
INSERT INTO `time_schedule_1` VALUES (552, '09:11', 0);
INSERT INTO `time_schedule_1` VALUES (553, '09:12', 0);
INSERT INTO `time_schedule_1` VALUES (554, '09:13', 0);
INSERT INTO `time_schedule_1` VALUES (555, '09:14', 0);
INSERT INTO `time_schedule_1` VALUES (556, '09:15', 0);
INSERT INTO `time_schedule_1` VALUES (557, '09:16', 0);
INSERT INTO `time_schedule_1` VALUES (558, '09:17', 0);
INSERT INTO `time_schedule_1` VALUES (559, '09:18', 0);
INSERT INTO `time_schedule_1` VALUES (560, '09:19', 0);
INSERT INTO `time_schedule_1` VALUES (561, '09:20', 0);
INSERT INTO `time_schedule_1` VALUES (562, '09:21', 0);
INSERT INTO `time_schedule_1` VALUES (563, '09:22', 0);
INSERT INTO `time_schedule_1` VALUES (564, '09:23', 0);
INSERT INTO `time_schedule_1` VALUES (565, '09:24', 0);
INSERT INTO `time_schedule_1` VALUES (566, '09:25', 0);
INSERT INTO `time_schedule_1` VALUES (567, '09:26', 0);
INSERT INTO `time_schedule_1` VALUES (568, '09:27', 0);
INSERT INTO `time_schedule_1` VALUES (569, '09:28', 0);
INSERT INTO `time_schedule_1` VALUES (570, '09:29', 0);
INSERT INTO `time_schedule_1` VALUES (571, '09:30', 0);
INSERT INTO `time_schedule_1` VALUES (572, '09:31', 0);
INSERT INTO `time_schedule_1` VALUES (573, '09:32', 0);
INSERT INTO `time_schedule_1` VALUES (574, '09:33', 0);
INSERT INTO `time_schedule_1` VALUES (575, '09:34', 0);
INSERT INTO `time_schedule_1` VALUES (576, '09:35', 0);
INSERT INTO `time_schedule_1` VALUES (577, '09:36', 0);
INSERT INTO `time_schedule_1` VALUES (578, '09:37', 0);
INSERT INTO `time_schedule_1` VALUES (579, '09:38', 0);
INSERT INTO `time_schedule_1` VALUES (580, '09:39', 0);
INSERT INTO `time_schedule_1` VALUES (581, '09:40', 0);
INSERT INTO `time_schedule_1` VALUES (582, '09:41', 0);
INSERT INTO `time_schedule_1` VALUES (583, '09:42', 0);
INSERT INTO `time_schedule_1` VALUES (584, '09:43', 0);
INSERT INTO `time_schedule_1` VALUES (585, '09:44', 0);
INSERT INTO `time_schedule_1` VALUES (586, '09:45', 0);
INSERT INTO `time_schedule_1` VALUES (587, '09:46', 0);
INSERT INTO `time_schedule_1` VALUES (588, '09:47', 0);
INSERT INTO `time_schedule_1` VALUES (589, '09:48', 0);
INSERT INTO `time_schedule_1` VALUES (590, '09:49', 0);
INSERT INTO `time_schedule_1` VALUES (591, '09:50', 0);
INSERT INTO `time_schedule_1` VALUES (592, '09:51', 0);
INSERT INTO `time_schedule_1` VALUES (593, '09:52', 0);
INSERT INTO `time_schedule_1` VALUES (594, '09:53', 0);
INSERT INTO `time_schedule_1` VALUES (595, '09:54', 0);
INSERT INTO `time_schedule_1` VALUES (596, '09:55', 0);
INSERT INTO `time_schedule_1` VALUES (597, '09:56', 0);
INSERT INTO `time_schedule_1` VALUES (598, '09:57', 0);
INSERT INTO `time_schedule_1` VALUES (599, '09:58', 0);
INSERT INTO `time_schedule_1` VALUES (600, '09:59', 0);
INSERT INTO `time_schedule_1` VALUES (601, '10:00', 0);
INSERT INTO `time_schedule_1` VALUES (602, '10:01', 0);
INSERT INTO `time_schedule_1` VALUES (603, '10:02', 0);
INSERT INTO `time_schedule_1` VALUES (604, '10:03', 0);
INSERT INTO `time_schedule_1` VALUES (605, '10:04', 0);
INSERT INTO `time_schedule_1` VALUES (606, '10:05', 0);
INSERT INTO `time_schedule_1` VALUES (607, '10:06', 0);
INSERT INTO `time_schedule_1` VALUES (608, '10:07', 0);
INSERT INTO `time_schedule_1` VALUES (609, '10:08', 0);
INSERT INTO `time_schedule_1` VALUES (610, '10:09', 0);
INSERT INTO `time_schedule_1` VALUES (611, '10:10', 0);
INSERT INTO `time_schedule_1` VALUES (612, '10:11', 0);
INSERT INTO `time_schedule_1` VALUES (613, '10:12', 0);
INSERT INTO `time_schedule_1` VALUES (614, '10:13', 0);
INSERT INTO `time_schedule_1` VALUES (615, '10:14', 0);
INSERT INTO `time_schedule_1` VALUES (616, '10:15', 0);
INSERT INTO `time_schedule_1` VALUES (617, '10:16', 0);
INSERT INTO `time_schedule_1` VALUES (618, '10:17', 0);
INSERT INTO `time_schedule_1` VALUES (619, '10:18', 0);
INSERT INTO `time_schedule_1` VALUES (620, '10:19', 0);
INSERT INTO `time_schedule_1` VALUES (621, '10:20', 0);
INSERT INTO `time_schedule_1` VALUES (622, '10:21', 0);
INSERT INTO `time_schedule_1` VALUES (623, '10:22', 0);
INSERT INTO `time_schedule_1` VALUES (624, '10:23', 0);
INSERT INTO `time_schedule_1` VALUES (625, '10:24', 0);
INSERT INTO `time_schedule_1` VALUES (626, '10:25', 0);
INSERT INTO `time_schedule_1` VALUES (627, '10:26', 0);
INSERT INTO `time_schedule_1` VALUES (628, '10:27', 0);
INSERT INTO `time_schedule_1` VALUES (629, '10:28', 0);
INSERT INTO `time_schedule_1` VALUES (630, '10:29', 0);
INSERT INTO `time_schedule_1` VALUES (631, '10:30', 0);
INSERT INTO `time_schedule_1` VALUES (632, '10:31', 0);
INSERT INTO `time_schedule_1` VALUES (633, '10:32', 0);
INSERT INTO `time_schedule_1` VALUES (634, '10:33', 0);
INSERT INTO `time_schedule_1` VALUES (635, '10:34', 0);
INSERT INTO `time_schedule_1` VALUES (636, '10:35', 0);
INSERT INTO `time_schedule_1` VALUES (637, '10:36', 0);
INSERT INTO `time_schedule_1` VALUES (638, '10:37', 0);
INSERT INTO `time_schedule_1` VALUES (639, '10:38', 0);
INSERT INTO `time_schedule_1` VALUES (640, '10:39', 0);
INSERT INTO `time_schedule_1` VALUES (641, '10:40', 0);
INSERT INTO `time_schedule_1` VALUES (642, '10:41', 0);
INSERT INTO `time_schedule_1` VALUES (643, '10:42', 0);
INSERT INTO `time_schedule_1` VALUES (644, '10:43', 0);
INSERT INTO `time_schedule_1` VALUES (645, '10:44', 0);
INSERT INTO `time_schedule_1` VALUES (646, '10:45', 0);
INSERT INTO `time_schedule_1` VALUES (647, '10:46', 0);
INSERT INTO `time_schedule_1` VALUES (648, '10:47', 0);
INSERT INTO `time_schedule_1` VALUES (649, '10:48', 0);
INSERT INTO `time_schedule_1` VALUES (650, '10:49', 0);
INSERT INTO `time_schedule_1` VALUES (651, '10:50', 0);
INSERT INTO `time_schedule_1` VALUES (652, '10:51', 0);
INSERT INTO `time_schedule_1` VALUES (653, '10:52', 0);
INSERT INTO `time_schedule_1` VALUES (654, '10:53', 0);
INSERT INTO `time_schedule_1` VALUES (655, '10:54', 0);
INSERT INTO `time_schedule_1` VALUES (656, '10:55', 0);
INSERT INTO `time_schedule_1` VALUES (657, '10:56', 0);
INSERT INTO `time_schedule_1` VALUES (658, '10:57', 0);
INSERT INTO `time_schedule_1` VALUES (659, '10:58', 0);
INSERT INTO `time_schedule_1` VALUES (660, '10:59', 0);
INSERT INTO `time_schedule_1` VALUES (661, '11:00', 0);
INSERT INTO `time_schedule_1` VALUES (662, '11:01', 0);
INSERT INTO `time_schedule_1` VALUES (663, '11:02', 0);
INSERT INTO `time_schedule_1` VALUES (664, '11:03', 0);
INSERT INTO `time_schedule_1` VALUES (665, '11:04', 0);
INSERT INTO `time_schedule_1` VALUES (666, '11:05', 0);
INSERT INTO `time_schedule_1` VALUES (667, '11:06', 0);
INSERT INTO `time_schedule_1` VALUES (668, '11:07', 0);
INSERT INTO `time_schedule_1` VALUES (669, '11:08', 0);
INSERT INTO `time_schedule_1` VALUES (670, '11:09', 0);
INSERT INTO `time_schedule_1` VALUES (671, '11:10', 0);
INSERT INTO `time_schedule_1` VALUES (672, '11:11', 0);
INSERT INTO `time_schedule_1` VALUES (673, '11:12', 0);
INSERT INTO `time_schedule_1` VALUES (674, '11:13', 0);
INSERT INTO `time_schedule_1` VALUES (675, '11:14', 0);
INSERT INTO `time_schedule_1` VALUES (676, '11:15', 0);
INSERT INTO `time_schedule_1` VALUES (677, '11:16', 0);
INSERT INTO `time_schedule_1` VALUES (678, '11:17', 0);
INSERT INTO `time_schedule_1` VALUES (679, '11:18', 0);
INSERT INTO `time_schedule_1` VALUES (680, '11:19', 0);
INSERT INTO `time_schedule_1` VALUES (681, '11:20', 0);
INSERT INTO `time_schedule_1` VALUES (682, '11:21', 0);
INSERT INTO `time_schedule_1` VALUES (683, '11:22', 0);
INSERT INTO `time_schedule_1` VALUES (684, '11:23', 0);
INSERT INTO `time_schedule_1` VALUES (685, '11:24', 0);
INSERT INTO `time_schedule_1` VALUES (686, '11:25', 0);
INSERT INTO `time_schedule_1` VALUES (687, '11:26', 0);
INSERT INTO `time_schedule_1` VALUES (688, '11:27', 0);
INSERT INTO `time_schedule_1` VALUES (689, '11:28', 0);
INSERT INTO `time_schedule_1` VALUES (690, '11:29', 0);
INSERT INTO `time_schedule_1` VALUES (691, '11:30', 0);
INSERT INTO `time_schedule_1` VALUES (692, '11:31', 0);
INSERT INTO `time_schedule_1` VALUES (693, '11:32', 0);
INSERT INTO `time_schedule_1` VALUES (694, '11:33', 0);
INSERT INTO `time_schedule_1` VALUES (695, '11:34', 0);
INSERT INTO `time_schedule_1` VALUES (696, '11:35', 0);
INSERT INTO `time_schedule_1` VALUES (697, '11:36', 0);
INSERT INTO `time_schedule_1` VALUES (698, '11:37', 0);
INSERT INTO `time_schedule_1` VALUES (699, '11:38', 0);
INSERT INTO `time_schedule_1` VALUES (700, '11:39', 0);
INSERT INTO `time_schedule_1` VALUES (701, '11:40', 0);
INSERT INTO `time_schedule_1` VALUES (702, '11:41', 0);
INSERT INTO `time_schedule_1` VALUES (703, '11:42', 0);
INSERT INTO `time_schedule_1` VALUES (704, '11:43', 0);
INSERT INTO `time_schedule_1` VALUES (705, '11:44', 0);
INSERT INTO `time_schedule_1` VALUES (706, '11:45', 0);
INSERT INTO `time_schedule_1` VALUES (707, '11:46', 0);
INSERT INTO `time_schedule_1` VALUES (708, '11:47', 0);
INSERT INTO `time_schedule_1` VALUES (709, '11:48', 0);
INSERT INTO `time_schedule_1` VALUES (710, '11:49', 0);
INSERT INTO `time_schedule_1` VALUES (711, '11:50', 0);
INSERT INTO `time_schedule_1` VALUES (712, '11:51', 0);
INSERT INTO `time_schedule_1` VALUES (713, '11:52', 0);
INSERT INTO `time_schedule_1` VALUES (714, '11:53', 0);
INSERT INTO `time_schedule_1` VALUES (715, '11:54', 0);
INSERT INTO `time_schedule_1` VALUES (716, '11:55', 0);
INSERT INTO `time_schedule_1` VALUES (717, '11:56', 0);
INSERT INTO `time_schedule_1` VALUES (718, '11:57', 0);
INSERT INTO `time_schedule_1` VALUES (719, '11:58', 0);
INSERT INTO `time_schedule_1` VALUES (720, '11:59', 0);
INSERT INTO `time_schedule_1` VALUES (721, '12:00', 0);
INSERT INTO `time_schedule_1` VALUES (722, '12:01', 0);
INSERT INTO `time_schedule_1` VALUES (723, '12:02', 0);
INSERT INTO `time_schedule_1` VALUES (724, '12:03', 0);
INSERT INTO `time_schedule_1` VALUES (725, '12:04', 0);
INSERT INTO `time_schedule_1` VALUES (726, '12:05', 0);
INSERT INTO `time_schedule_1` VALUES (727, '12:06', 0);
INSERT INTO `time_schedule_1` VALUES (728, '12:07', 0);
INSERT INTO `time_schedule_1` VALUES (729, '12:08', 0);
INSERT INTO `time_schedule_1` VALUES (730, '12:09', 0);
INSERT INTO `time_schedule_1` VALUES (731, '12:10', 0);
INSERT INTO `time_schedule_1` VALUES (732, '12:11', 0);
INSERT INTO `time_schedule_1` VALUES (733, '12:12', 0);
INSERT INTO `time_schedule_1` VALUES (734, '12:13', 0);
INSERT INTO `time_schedule_1` VALUES (735, '12:14', 0);
INSERT INTO `time_schedule_1` VALUES (736, '12:15', 0);
INSERT INTO `time_schedule_1` VALUES (737, '12:16', 0);
INSERT INTO `time_schedule_1` VALUES (738, '12:17', 0);
INSERT INTO `time_schedule_1` VALUES (739, '12:18', 0);
INSERT INTO `time_schedule_1` VALUES (740, '12:19', 0);
INSERT INTO `time_schedule_1` VALUES (741, '12:20', 0);
INSERT INTO `time_schedule_1` VALUES (742, '12:21', 0);
INSERT INTO `time_schedule_1` VALUES (743, '12:22', 0);
INSERT INTO `time_schedule_1` VALUES (744, '12:23', 0);
INSERT INTO `time_schedule_1` VALUES (745, '12:24', 0);
INSERT INTO `time_schedule_1` VALUES (746, '12:25', 0);
INSERT INTO `time_schedule_1` VALUES (747, '12:26', 0);
INSERT INTO `time_schedule_1` VALUES (748, '12:27', 0);
INSERT INTO `time_schedule_1` VALUES (749, '12:28', 0);
INSERT INTO `time_schedule_1` VALUES (750, '12:29', 0);
INSERT INTO `time_schedule_1` VALUES (751, '12:30', 0);
INSERT INTO `time_schedule_1` VALUES (752, '12:31', 0);
INSERT INTO `time_schedule_1` VALUES (753, '12:32', 0);
INSERT INTO `time_schedule_1` VALUES (754, '12:33', 0);
INSERT INTO `time_schedule_1` VALUES (755, '12:34', 0);
INSERT INTO `time_schedule_1` VALUES (756, '12:35', 0);
INSERT INTO `time_schedule_1` VALUES (757, '12:36', 0);
INSERT INTO `time_schedule_1` VALUES (758, '12:37', 0);
INSERT INTO `time_schedule_1` VALUES (759, '12:38', 0);
INSERT INTO `time_schedule_1` VALUES (760, '12:39', 0);
INSERT INTO `time_schedule_1` VALUES (761, '12:40', 0);
INSERT INTO `time_schedule_1` VALUES (762, '12:41', 0);
INSERT INTO `time_schedule_1` VALUES (763, '12:42', 0);
INSERT INTO `time_schedule_1` VALUES (764, '12:43', 0);
INSERT INTO `time_schedule_1` VALUES (765, '12:44', 0);
INSERT INTO `time_schedule_1` VALUES (766, '12:45', 0);
INSERT INTO `time_schedule_1` VALUES (767, '12:46', 0);
INSERT INTO `time_schedule_1` VALUES (768, '12:47', 0);
INSERT INTO `time_schedule_1` VALUES (769, '12:48', 0);
INSERT INTO `time_schedule_1` VALUES (770, '12:49', 0);
INSERT INTO `time_schedule_1` VALUES (771, '12:50', 0);
INSERT INTO `time_schedule_1` VALUES (772, '12:51', 0);
INSERT INTO `time_schedule_1` VALUES (773, '12:52', 0);
INSERT INTO `time_schedule_1` VALUES (774, '12:53', 0);
INSERT INTO `time_schedule_1` VALUES (775, '12:54', 0);
INSERT INTO `time_schedule_1` VALUES (776, '12:55', 0);
INSERT INTO `time_schedule_1` VALUES (777, '12:56', 0);
INSERT INTO `time_schedule_1` VALUES (778, '12:57', 0);
INSERT INTO `time_schedule_1` VALUES (779, '12:58', 0);
INSERT INTO `time_schedule_1` VALUES (780, '12:59', 0);
INSERT INTO `time_schedule_1` VALUES (781, '13:00', 0);
INSERT INTO `time_schedule_1` VALUES (782, '13:01', 0);
INSERT INTO `time_schedule_1` VALUES (783, '13:02', 0);
INSERT INTO `time_schedule_1` VALUES (784, '13:03', 0);
INSERT INTO `time_schedule_1` VALUES (785, '13:04', 0);
INSERT INTO `time_schedule_1` VALUES (786, '13:05', 0);
INSERT INTO `time_schedule_1` VALUES (787, '13:06', 0);
INSERT INTO `time_schedule_1` VALUES (788, '13:07', 0);
INSERT INTO `time_schedule_1` VALUES (789, '13:08', 0);
INSERT INTO `time_schedule_1` VALUES (790, '13:09', 0);
INSERT INTO `time_schedule_1` VALUES (791, '13:10', 0);
INSERT INTO `time_schedule_1` VALUES (792, '13:11', 0);
INSERT INTO `time_schedule_1` VALUES (793, '13:12', 0);
INSERT INTO `time_schedule_1` VALUES (794, '13:13', 0);
INSERT INTO `time_schedule_1` VALUES (795, '13:14', 0);
INSERT INTO `time_schedule_1` VALUES (796, '13:15', 0);
INSERT INTO `time_schedule_1` VALUES (797, '13:16', 0);
INSERT INTO `time_schedule_1` VALUES (798, '13:17', 0);
INSERT INTO `time_schedule_1` VALUES (799, '13:18', 0);
INSERT INTO `time_schedule_1` VALUES (800, '13:19', 0);
INSERT INTO `time_schedule_1` VALUES (801, '13:20', 0);
INSERT INTO `time_schedule_1` VALUES (802, '13:21', 0);
INSERT INTO `time_schedule_1` VALUES (803, '13:22', 0);
INSERT INTO `time_schedule_1` VALUES (804, '13:23', 0);
INSERT INTO `time_schedule_1` VALUES (805, '13:24', 0);
INSERT INTO `time_schedule_1` VALUES (806, '13:25', 0);
INSERT INTO `time_schedule_1` VALUES (807, '13:26', 0);
INSERT INTO `time_schedule_1` VALUES (808, '13:27', 0);
INSERT INTO `time_schedule_1` VALUES (809, '13:28', 0);
INSERT INTO `time_schedule_1` VALUES (810, '13:29', 0);
INSERT INTO `time_schedule_1` VALUES (811, '13:30', 0);
INSERT INTO `time_schedule_1` VALUES (812, '13:31', 0);
INSERT INTO `time_schedule_1` VALUES (813, '13:32', 0);
INSERT INTO `time_schedule_1` VALUES (814, '13:33', 0);
INSERT INTO `time_schedule_1` VALUES (815, '13:34', 0);
INSERT INTO `time_schedule_1` VALUES (816, '13:35', 0);
INSERT INTO `time_schedule_1` VALUES (817, '13:36', 0);
INSERT INTO `time_schedule_1` VALUES (818, '13:37', 0);
INSERT INTO `time_schedule_1` VALUES (819, '13:38', 0);
INSERT INTO `time_schedule_1` VALUES (820, '13:39', 0);
INSERT INTO `time_schedule_1` VALUES (821, '13:40', 0);
INSERT INTO `time_schedule_1` VALUES (822, '13:41', 0);
INSERT INTO `time_schedule_1` VALUES (823, '13:42', 0);
INSERT INTO `time_schedule_1` VALUES (824, '13:43', 0);
INSERT INTO `time_schedule_1` VALUES (825, '13:44', 0);
INSERT INTO `time_schedule_1` VALUES (826, '13:45', 0);
INSERT INTO `time_schedule_1` VALUES (827, '13:46', 0);
INSERT INTO `time_schedule_1` VALUES (828, '13:47', 0);
INSERT INTO `time_schedule_1` VALUES (829, '13:48', 0);
INSERT INTO `time_schedule_1` VALUES (830, '13:49', 0);
INSERT INTO `time_schedule_1` VALUES (831, '13:50', 0);
INSERT INTO `time_schedule_1` VALUES (832, '13:51', 0);
INSERT INTO `time_schedule_1` VALUES (833, '13:52', 0);
INSERT INTO `time_schedule_1` VALUES (834, '13:53', 0);
INSERT INTO `time_schedule_1` VALUES (835, '13:54', 0);
INSERT INTO `time_schedule_1` VALUES (836, '13:55', 0);
INSERT INTO `time_schedule_1` VALUES (837, '13:56', 0);
INSERT INTO `time_schedule_1` VALUES (838, '13:57', 0);
INSERT INTO `time_schedule_1` VALUES (839, '13:58', 0);
INSERT INTO `time_schedule_1` VALUES (840, '13:59', 0);
INSERT INTO `time_schedule_1` VALUES (841, '14:00', 0);
INSERT INTO `time_schedule_1` VALUES (842, '14:01', 0);
INSERT INTO `time_schedule_1` VALUES (843, '14:02', 0);
INSERT INTO `time_schedule_1` VALUES (844, '14:03', 0);
INSERT INTO `time_schedule_1` VALUES (845, '14:04', 0);
INSERT INTO `time_schedule_1` VALUES (846, '14:05', 0);
INSERT INTO `time_schedule_1` VALUES (847, '14:06', 0);
INSERT INTO `time_schedule_1` VALUES (848, '14:07', 0);
INSERT INTO `time_schedule_1` VALUES (849, '14:08', 0);
INSERT INTO `time_schedule_1` VALUES (850, '14:09', 0);
INSERT INTO `time_schedule_1` VALUES (851, '14:10', 0);
INSERT INTO `time_schedule_1` VALUES (852, '14:11', 0);
INSERT INTO `time_schedule_1` VALUES (853, '14:12', 0);
INSERT INTO `time_schedule_1` VALUES (854, '14:13', 0);
INSERT INTO `time_schedule_1` VALUES (855, '14:14', 0);
INSERT INTO `time_schedule_1` VALUES (856, '14:15', 0);
INSERT INTO `time_schedule_1` VALUES (857, '14:16', 0);
INSERT INTO `time_schedule_1` VALUES (858, '14:17', 0);
INSERT INTO `time_schedule_1` VALUES (859, '14:18', 0);
INSERT INTO `time_schedule_1` VALUES (860, '14:19', 0);
INSERT INTO `time_schedule_1` VALUES (861, '14:20', 0);
INSERT INTO `time_schedule_1` VALUES (862, '14:21', 0);
INSERT INTO `time_schedule_1` VALUES (863, '14:22', 0);
INSERT INTO `time_schedule_1` VALUES (864, '14:23', 0);
INSERT INTO `time_schedule_1` VALUES (865, '14:24', 0);
INSERT INTO `time_schedule_1` VALUES (866, '14:25', 0);
INSERT INTO `time_schedule_1` VALUES (867, '14:26', 0);
INSERT INTO `time_schedule_1` VALUES (868, '14:27', 0);
INSERT INTO `time_schedule_1` VALUES (869, '14:28', 0);
INSERT INTO `time_schedule_1` VALUES (870, '14:29', 0);
INSERT INTO `time_schedule_1` VALUES (871, '14:30', 0);
INSERT INTO `time_schedule_1` VALUES (872, '14:31', 0);
INSERT INTO `time_schedule_1` VALUES (873, '14:32', 0);
INSERT INTO `time_schedule_1` VALUES (874, '14:33', 0);
INSERT INTO `time_schedule_1` VALUES (875, '14:34', 0);
INSERT INTO `time_schedule_1` VALUES (876, '14:35', 0);
INSERT INTO `time_schedule_1` VALUES (877, '14:36', 0);
INSERT INTO `time_schedule_1` VALUES (878, '14:37', 0);
INSERT INTO `time_schedule_1` VALUES (879, '14:38', 0);
INSERT INTO `time_schedule_1` VALUES (880, '14:39', 0);
INSERT INTO `time_schedule_1` VALUES (881, '14:40', 0);
INSERT INTO `time_schedule_1` VALUES (882, '14:41', 0);
INSERT INTO `time_schedule_1` VALUES (883, '14:42', 0);
INSERT INTO `time_schedule_1` VALUES (884, '14:43', 0);
INSERT INTO `time_schedule_1` VALUES (885, '14:44', 0);
INSERT INTO `time_schedule_1` VALUES (886, '14:45', 0);
INSERT INTO `time_schedule_1` VALUES (887, '14:46', 0);
INSERT INTO `time_schedule_1` VALUES (888, '14:47', 0);
INSERT INTO `time_schedule_1` VALUES (889, '14:48', 0);
INSERT INTO `time_schedule_1` VALUES (890, '14:49', 0);
INSERT INTO `time_schedule_1` VALUES (891, '14:50', 0);
INSERT INTO `time_schedule_1` VALUES (892, '14:51', 0);
INSERT INTO `time_schedule_1` VALUES (893, '14:52', 0);
INSERT INTO `time_schedule_1` VALUES (894, '14:53', 0);
INSERT INTO `time_schedule_1` VALUES (895, '14:54', 0);
INSERT INTO `time_schedule_1` VALUES (896, '14:55', 0);
INSERT INTO `time_schedule_1` VALUES (897, '14:56', 0);
INSERT INTO `time_schedule_1` VALUES (898, '14:57', 0);
INSERT INTO `time_schedule_1` VALUES (899, '14:58', 0);
INSERT INTO `time_schedule_1` VALUES (900, '14:59', 0);
INSERT INTO `time_schedule_1` VALUES (901, '15:00', 0);
INSERT INTO `time_schedule_1` VALUES (902, '15:01', 0);
INSERT INTO `time_schedule_1` VALUES (903, '15:02', 0);
INSERT INTO `time_schedule_1` VALUES (904, '15:03', 0);
INSERT INTO `time_schedule_1` VALUES (905, '15:04', 0);
INSERT INTO `time_schedule_1` VALUES (906, '15:05', 0);
INSERT INTO `time_schedule_1` VALUES (907, '15:06', 0);
INSERT INTO `time_schedule_1` VALUES (908, '15:07', 0);
INSERT INTO `time_schedule_1` VALUES (909, '15:08', 0);
INSERT INTO `time_schedule_1` VALUES (910, '15:09', 0);
INSERT INTO `time_schedule_1` VALUES (911, '15:10', 0);
INSERT INTO `time_schedule_1` VALUES (912, '15:11', 0);
INSERT INTO `time_schedule_1` VALUES (913, '15:12', 0);
INSERT INTO `time_schedule_1` VALUES (914, '15:13', 0);
INSERT INTO `time_schedule_1` VALUES (915, '15:14', 0);
INSERT INTO `time_schedule_1` VALUES (916, '15:15', 0);
INSERT INTO `time_schedule_1` VALUES (917, '15:16', 0);
INSERT INTO `time_schedule_1` VALUES (918, '15:17', 0);
INSERT INTO `time_schedule_1` VALUES (919, '15:18', 0);
INSERT INTO `time_schedule_1` VALUES (920, '15:19', 0);
INSERT INTO `time_schedule_1` VALUES (921, '15:20', 0);
INSERT INTO `time_schedule_1` VALUES (922, '15:21', 0);
INSERT INTO `time_schedule_1` VALUES (923, '15:22', 0);
INSERT INTO `time_schedule_1` VALUES (924, '15:23', 0);
INSERT INTO `time_schedule_1` VALUES (925, '15:24', 0);
INSERT INTO `time_schedule_1` VALUES (926, '15:25', 0);
INSERT INTO `time_schedule_1` VALUES (927, '15:26', 0);
INSERT INTO `time_schedule_1` VALUES (928, '15:27', 0);
INSERT INTO `time_schedule_1` VALUES (929, '15:28', 0);
INSERT INTO `time_schedule_1` VALUES (930, '15:29', 0);
INSERT INTO `time_schedule_1` VALUES (931, '15:30', 0);
INSERT INTO `time_schedule_1` VALUES (932, '15:31', 0);
INSERT INTO `time_schedule_1` VALUES (933, '15:32', 0);
INSERT INTO `time_schedule_1` VALUES (934, '15:33', 0);
INSERT INTO `time_schedule_1` VALUES (935, '15:34', 0);
INSERT INTO `time_schedule_1` VALUES (936, '15:35', 0);
INSERT INTO `time_schedule_1` VALUES (937, '15:36', 0);
INSERT INTO `time_schedule_1` VALUES (938, '15:37', 0);
INSERT INTO `time_schedule_1` VALUES (939, '15:38', 0);
INSERT INTO `time_schedule_1` VALUES (940, '15:39', 0);
INSERT INTO `time_schedule_1` VALUES (941, '15:40', 0);
INSERT INTO `time_schedule_1` VALUES (942, '15:41', 0);
INSERT INTO `time_schedule_1` VALUES (943, '15:42', 0);
INSERT INTO `time_schedule_1` VALUES (944, '15:43', 0);
INSERT INTO `time_schedule_1` VALUES (945, '15:44', 0);
INSERT INTO `time_schedule_1` VALUES (946, '15:45', 0);
INSERT INTO `time_schedule_1` VALUES (947, '15:46', 0);
INSERT INTO `time_schedule_1` VALUES (948, '15:47', 0);
INSERT INTO `time_schedule_1` VALUES (949, '15:48', 0);
INSERT INTO `time_schedule_1` VALUES (950, '15:49', 0);
INSERT INTO `time_schedule_1` VALUES (951, '15:50', 0);
INSERT INTO `time_schedule_1` VALUES (952, '15:51', 0);
INSERT INTO `time_schedule_1` VALUES (953, '15:52', 0);
INSERT INTO `time_schedule_1` VALUES (954, '15:53', 0);
INSERT INTO `time_schedule_1` VALUES (955, '15:54', 0);
INSERT INTO `time_schedule_1` VALUES (956, '15:55', 0);
INSERT INTO `time_schedule_1` VALUES (957, '15:56', 0);
INSERT INTO `time_schedule_1` VALUES (958, '15:57', 0);
INSERT INTO `time_schedule_1` VALUES (959, '15:58', 0);
INSERT INTO `time_schedule_1` VALUES (960, '15:59', 0);
INSERT INTO `time_schedule_1` VALUES (961, '16:00', 0);
INSERT INTO `time_schedule_1` VALUES (962, '16:01', 0);
INSERT INTO `time_schedule_1` VALUES (963, '16:02', 0);
INSERT INTO `time_schedule_1` VALUES (964, '16:03', 0);
INSERT INTO `time_schedule_1` VALUES (965, '16:04', 0);
INSERT INTO `time_schedule_1` VALUES (966, '16:05', 0);
INSERT INTO `time_schedule_1` VALUES (967, '16:06', 0);
INSERT INTO `time_schedule_1` VALUES (968, '16:07', 0);
INSERT INTO `time_schedule_1` VALUES (969, '16:08', 0);
INSERT INTO `time_schedule_1` VALUES (970, '16:09', 0);
INSERT INTO `time_schedule_1` VALUES (971, '16:10', 0);
INSERT INTO `time_schedule_1` VALUES (972, '16:11', 0);
INSERT INTO `time_schedule_1` VALUES (973, '16:12', 0);
INSERT INTO `time_schedule_1` VALUES (974, '16:13', 0);
INSERT INTO `time_schedule_1` VALUES (975, '16:14', 0);
INSERT INTO `time_schedule_1` VALUES (976, '16:15', 0);
INSERT INTO `time_schedule_1` VALUES (977, '16:16', 0);
INSERT INTO `time_schedule_1` VALUES (978, '16:17', 0);
INSERT INTO `time_schedule_1` VALUES (979, '16:18', 0);
INSERT INTO `time_schedule_1` VALUES (980, '16:19', 0);
INSERT INTO `time_schedule_1` VALUES (981, '16:20', 0);
INSERT INTO `time_schedule_1` VALUES (982, '16:21', 0);
INSERT INTO `time_schedule_1` VALUES (983, '16:22', 0);
INSERT INTO `time_schedule_1` VALUES (984, '16:23', 0);
INSERT INTO `time_schedule_1` VALUES (985, '16:24', 0);
INSERT INTO `time_schedule_1` VALUES (986, '16:25', 0);
INSERT INTO `time_schedule_1` VALUES (987, '16:26', 0);
INSERT INTO `time_schedule_1` VALUES (988, '16:27', 0);
INSERT INTO `time_schedule_1` VALUES (989, '16:28', 0);
INSERT INTO `time_schedule_1` VALUES (990, '16:29', 0);
INSERT INTO `time_schedule_1` VALUES (991, '16:30', 0);
INSERT INTO `time_schedule_1` VALUES (992, '16:31', 0);
INSERT INTO `time_schedule_1` VALUES (993, '16:32', 0);
INSERT INTO `time_schedule_1` VALUES (994, '16:33', 0);
INSERT INTO `time_schedule_1` VALUES (995, '16:34', 0);
INSERT INTO `time_schedule_1` VALUES (996, '16:35', 0);
INSERT INTO `time_schedule_1` VALUES (997, '16:36', 0);
INSERT INTO `time_schedule_1` VALUES (998, '16:37', 0);
INSERT INTO `time_schedule_1` VALUES (999, '16:38', 0);
INSERT INTO `time_schedule_1` VALUES (1000, '16:39', 0);
INSERT INTO `time_schedule_1` VALUES (1001, '16:40', 0);
INSERT INTO `time_schedule_1` VALUES (1002, '16:41', 0);
INSERT INTO `time_schedule_1` VALUES (1003, '16:42', 0);
INSERT INTO `time_schedule_1` VALUES (1004, '16:43', 0);
INSERT INTO `time_schedule_1` VALUES (1005, '16:44', 0);
INSERT INTO `time_schedule_1` VALUES (1006, '16:45', 0);
INSERT INTO `time_schedule_1` VALUES (1007, '16:46', 0);
INSERT INTO `time_schedule_1` VALUES (1008, '16:47', 0);
INSERT INTO `time_schedule_1` VALUES (1009, '16:48', 0);
INSERT INTO `time_schedule_1` VALUES (1010, '16:49', 0);
INSERT INTO `time_schedule_1` VALUES (1011, '16:50', 0);
INSERT INTO `time_schedule_1` VALUES (1012, '16:51', 0);
INSERT INTO `time_schedule_1` VALUES (1013, '16:52', 0);
INSERT INTO `time_schedule_1` VALUES (1014, '16:53', 0);
INSERT INTO `time_schedule_1` VALUES (1015, '16:54', 0);
INSERT INTO `time_schedule_1` VALUES (1016, '16:55', 0);
INSERT INTO `time_schedule_1` VALUES (1017, '16:56', 0);
INSERT INTO `time_schedule_1` VALUES (1018, '16:57', 0);
INSERT INTO `time_schedule_1` VALUES (1019, '16:58', 0);
INSERT INTO `time_schedule_1` VALUES (1020, '16:59', 0);
INSERT INTO `time_schedule_1` VALUES (1021, '17:00', 0);
INSERT INTO `time_schedule_1` VALUES (1022, '17:01', 0);
INSERT INTO `time_schedule_1` VALUES (1023, '17:02', 0);
INSERT INTO `time_schedule_1` VALUES (1024, '17:03', 0);
INSERT INTO `time_schedule_1` VALUES (1025, '17:04', 0);
INSERT INTO `time_schedule_1` VALUES (1026, '17:05', 0);
INSERT INTO `time_schedule_1` VALUES (1027, '17:06', 0);
INSERT INTO `time_schedule_1` VALUES (1028, '17:07', 0);
INSERT INTO `time_schedule_1` VALUES (1029, '17:08', 0);
INSERT INTO `time_schedule_1` VALUES (1030, '17:09', 0);
INSERT INTO `time_schedule_1` VALUES (1031, '17:10', 0);
INSERT INTO `time_schedule_1` VALUES (1032, '17:11', 0);
INSERT INTO `time_schedule_1` VALUES (1033, '17:12', 0);
INSERT INTO `time_schedule_1` VALUES (1034, '17:13', 0);
INSERT INTO `time_schedule_1` VALUES (1035, '17:14', 0);
INSERT INTO `time_schedule_1` VALUES (1036, '17:15', 0);
INSERT INTO `time_schedule_1` VALUES (1037, '17:16', 0);
INSERT INTO `time_schedule_1` VALUES (1038, '17:17', 0);
INSERT INTO `time_schedule_1` VALUES (1039, '17:18', 0);
INSERT INTO `time_schedule_1` VALUES (1040, '17:19', 0);
INSERT INTO `time_schedule_1` VALUES (1041, '17:20', 0);
INSERT INTO `time_schedule_1` VALUES (1042, '17:21', 0);
INSERT INTO `time_schedule_1` VALUES (1043, '17:22', 0);
INSERT INTO `time_schedule_1` VALUES (1044, '17:23', 0);
INSERT INTO `time_schedule_1` VALUES (1045, '17:24', 0);
INSERT INTO `time_schedule_1` VALUES (1046, '17:25', 0);
INSERT INTO `time_schedule_1` VALUES (1047, '17:26', 0);
INSERT INTO `time_schedule_1` VALUES (1048, '17:27', 0);
INSERT INTO `time_schedule_1` VALUES (1049, '17:28', 0);
INSERT INTO `time_schedule_1` VALUES (1050, '17:29', 0);
INSERT INTO `time_schedule_1` VALUES (1051, '17:30', 0);
INSERT INTO `time_schedule_1` VALUES (1052, '17:31', 0);
INSERT INTO `time_schedule_1` VALUES (1053, '17:32', 0);
INSERT INTO `time_schedule_1` VALUES (1054, '17:33', 0);
INSERT INTO `time_schedule_1` VALUES (1055, '17:34', 0);
INSERT INTO `time_schedule_1` VALUES (1056, '17:35', 0);
INSERT INTO `time_schedule_1` VALUES (1057, '17:36', 0);
INSERT INTO `time_schedule_1` VALUES (1058, '17:37', 0);
INSERT INTO `time_schedule_1` VALUES (1059, '17:38', 0);
INSERT INTO `time_schedule_1` VALUES (1060, '17:39', 0);
INSERT INTO `time_schedule_1` VALUES (1061, '17:40', 0);
INSERT INTO `time_schedule_1` VALUES (1062, '17:41', 0);
INSERT INTO `time_schedule_1` VALUES (1063, '17:42', 0);
INSERT INTO `time_schedule_1` VALUES (1064, '17:43', 0);
INSERT INTO `time_schedule_1` VALUES (1065, '17:44', 0);
INSERT INTO `time_schedule_1` VALUES (1066, '17:45', 0);
INSERT INTO `time_schedule_1` VALUES (1067, '17:46', 0);
INSERT INTO `time_schedule_1` VALUES (1068, '17:47', 0);
INSERT INTO `time_schedule_1` VALUES (1069, '17:48', 0);
INSERT INTO `time_schedule_1` VALUES (1070, '17:49', 0);
INSERT INTO `time_schedule_1` VALUES (1071, '17:50', 0);
INSERT INTO `time_schedule_1` VALUES (1072, '17:51', 0);
INSERT INTO `time_schedule_1` VALUES (1073, '17:52', 0);
INSERT INTO `time_schedule_1` VALUES (1074, '17:53', 0);
INSERT INTO `time_schedule_1` VALUES (1075, '17:54', 0);
INSERT INTO `time_schedule_1` VALUES (1076, '17:55', 0);
INSERT INTO `time_schedule_1` VALUES (1077, '17:56', 0);
INSERT INTO `time_schedule_1` VALUES (1078, '17:57', 0);
INSERT INTO `time_schedule_1` VALUES (1079, '17:58', 0);
INSERT INTO `time_schedule_1` VALUES (1080, '17:59', 0);
INSERT INTO `time_schedule_1` VALUES (1081, '18:00', 0);
INSERT INTO `time_schedule_1` VALUES (1082, '18:01', 0);
INSERT INTO `time_schedule_1` VALUES (1083, '18:02', 0);
INSERT INTO `time_schedule_1` VALUES (1084, '18:03', 0);
INSERT INTO `time_schedule_1` VALUES (1085, '18:04', 0);
INSERT INTO `time_schedule_1` VALUES (1086, '18:05', 0);
INSERT INTO `time_schedule_1` VALUES (1087, '18:06', 0);
INSERT INTO `time_schedule_1` VALUES (1088, '18:07', 0);
INSERT INTO `time_schedule_1` VALUES (1089, '18:08', 0);
INSERT INTO `time_schedule_1` VALUES (1090, '18:09', 0);
INSERT INTO `time_schedule_1` VALUES (1091, '18:10', 0);
INSERT INTO `time_schedule_1` VALUES (1092, '18:11', 0);
INSERT INTO `time_schedule_1` VALUES (1093, '18:12', 0);
INSERT INTO `time_schedule_1` VALUES (1094, '18:13', 0);
INSERT INTO `time_schedule_1` VALUES (1095, '18:14', 0);
INSERT INTO `time_schedule_1` VALUES (1096, '18:15', 0);
INSERT INTO `time_schedule_1` VALUES (1097, '18:16', 0);
INSERT INTO `time_schedule_1` VALUES (1098, '18:17', 0);
INSERT INTO `time_schedule_1` VALUES (1099, '18:18', 0);
INSERT INTO `time_schedule_1` VALUES (1100, '18:19', 0);
INSERT INTO `time_schedule_1` VALUES (1101, '18:20', 0);
INSERT INTO `time_schedule_1` VALUES (1102, '18:21', 0);
INSERT INTO `time_schedule_1` VALUES (1103, '18:22', 0);
INSERT INTO `time_schedule_1` VALUES (1104, '18:23', 0);
INSERT INTO `time_schedule_1` VALUES (1105, '18:24', 0);
INSERT INTO `time_schedule_1` VALUES (1106, '18:25', 0);
INSERT INTO `time_schedule_1` VALUES (1107, '18:26', 0);
INSERT INTO `time_schedule_1` VALUES (1108, '18:27', 0);
INSERT INTO `time_schedule_1` VALUES (1109, '18:28', 0);
INSERT INTO `time_schedule_1` VALUES (1110, '18:29', 0);
INSERT INTO `time_schedule_1` VALUES (1111, '18:30', 0);
INSERT INTO `time_schedule_1` VALUES (1112, '18:31', 0);
INSERT INTO `time_schedule_1` VALUES (1113, '18:32', 0);
INSERT INTO `time_schedule_1` VALUES (1114, '18:33', 0);
INSERT INTO `time_schedule_1` VALUES (1115, '18:34', 0);
INSERT INTO `time_schedule_1` VALUES (1116, '18:35', 0);
INSERT INTO `time_schedule_1` VALUES (1117, '18:36', 0);
INSERT INTO `time_schedule_1` VALUES (1118, '18:37', 0);
INSERT INTO `time_schedule_1` VALUES (1119, '18:38', 0);
INSERT INTO `time_schedule_1` VALUES (1120, '18:39', 0);
INSERT INTO `time_schedule_1` VALUES (1121, '18:40', 0);
INSERT INTO `time_schedule_1` VALUES (1122, '18:41', 0);
INSERT INTO `time_schedule_1` VALUES (1123, '18:42', 0);
INSERT INTO `time_schedule_1` VALUES (1124, '18:43', 0);
INSERT INTO `time_schedule_1` VALUES (1125, '18:44', 0);
INSERT INTO `time_schedule_1` VALUES (1126, '18:45', 0);
INSERT INTO `time_schedule_1` VALUES (1127, '18:46', 0);
INSERT INTO `time_schedule_1` VALUES (1128, '18:47', 0);
INSERT INTO `time_schedule_1` VALUES (1129, '18:48', 0);
INSERT INTO `time_schedule_1` VALUES (1130, '18:49', 0);
INSERT INTO `time_schedule_1` VALUES (1131, '18:50', 0);
INSERT INTO `time_schedule_1` VALUES (1132, '18:51', 0);
INSERT INTO `time_schedule_1` VALUES (1133, '18:52', 0);
INSERT INTO `time_schedule_1` VALUES (1134, '18:53', 0);
INSERT INTO `time_schedule_1` VALUES (1135, '18:54', 0);
INSERT INTO `time_schedule_1` VALUES (1136, '18:55', 0);
INSERT INTO `time_schedule_1` VALUES (1137, '18:56', 0);
INSERT INTO `time_schedule_1` VALUES (1138, '18:57', 0);
INSERT INTO `time_schedule_1` VALUES (1139, '18:58', 0);
INSERT INTO `time_schedule_1` VALUES (1140, '18:59', 0);
INSERT INTO `time_schedule_1` VALUES (1141, '19:00', 0);
INSERT INTO `time_schedule_1` VALUES (1142, '19:01', 0);
INSERT INTO `time_schedule_1` VALUES (1143, '19:02', 0);
INSERT INTO `time_schedule_1` VALUES (1144, '19:03', 0);
INSERT INTO `time_schedule_1` VALUES (1145, '19:04', 0);
INSERT INTO `time_schedule_1` VALUES (1146, '19:05', 0);
INSERT INTO `time_schedule_1` VALUES (1147, '19:06', 0);
INSERT INTO `time_schedule_1` VALUES (1148, '19:07', 0);
INSERT INTO `time_schedule_1` VALUES (1149, '19:08', 0);
INSERT INTO `time_schedule_1` VALUES (1150, '19:09', 0);
INSERT INTO `time_schedule_1` VALUES (1151, '19:10', 0);
INSERT INTO `time_schedule_1` VALUES (1152, '19:11', 0);
INSERT INTO `time_schedule_1` VALUES (1153, '19:12', 0);
INSERT INTO `time_schedule_1` VALUES (1154, '19:13', 0);
INSERT INTO `time_schedule_1` VALUES (1155, '19:14', 0);
INSERT INTO `time_schedule_1` VALUES (1156, '19:15', 0);
INSERT INTO `time_schedule_1` VALUES (1157, '19:16', 0);
INSERT INTO `time_schedule_1` VALUES (1158, '19:17', 0);
INSERT INTO `time_schedule_1` VALUES (1159, '19:18', 0);
INSERT INTO `time_schedule_1` VALUES (1160, '19:19', 0);
INSERT INTO `time_schedule_1` VALUES (1161, '19:20', 0);
INSERT INTO `time_schedule_1` VALUES (1162, '19:21', 0);
INSERT INTO `time_schedule_1` VALUES (1163, '19:22', 0);
INSERT INTO `time_schedule_1` VALUES (1164, '19:23', 0);
INSERT INTO `time_schedule_1` VALUES (1165, '19:24', 0);
INSERT INTO `time_schedule_1` VALUES (1166, '19:25', 0);
INSERT INTO `time_schedule_1` VALUES (1167, '19:26', 0);
INSERT INTO `time_schedule_1` VALUES (1168, '19:27', 0);
INSERT INTO `time_schedule_1` VALUES (1169, '19:28', 0);
INSERT INTO `time_schedule_1` VALUES (1170, '19:29', 0);
INSERT INTO `time_schedule_1` VALUES (1171, '19:30', 0);
INSERT INTO `time_schedule_1` VALUES (1172, '19:31', 0);
INSERT INTO `time_schedule_1` VALUES (1173, '19:32', 0);
INSERT INTO `time_schedule_1` VALUES (1174, '19:33', 0);
INSERT INTO `time_schedule_1` VALUES (1175, '19:34', 0);
INSERT INTO `time_schedule_1` VALUES (1176, '19:35', 0);
INSERT INTO `time_schedule_1` VALUES (1177, '19:36', 0);
INSERT INTO `time_schedule_1` VALUES (1178, '19:37', 0);
INSERT INTO `time_schedule_1` VALUES (1179, '19:38', 0);
INSERT INTO `time_schedule_1` VALUES (1180, '19:39', 0);
INSERT INTO `time_schedule_1` VALUES (1181, '19:40', 0);
INSERT INTO `time_schedule_1` VALUES (1182, '19:41', 0);
INSERT INTO `time_schedule_1` VALUES (1183, '19:42', 0);
INSERT INTO `time_schedule_1` VALUES (1184, '19:43', 0);
INSERT INTO `time_schedule_1` VALUES (1185, '19:44', 0);
INSERT INTO `time_schedule_1` VALUES (1186, '19:45', 0);
INSERT INTO `time_schedule_1` VALUES (1187, '19:46', 0);
INSERT INTO `time_schedule_1` VALUES (1188, '19:47', 0);
INSERT INTO `time_schedule_1` VALUES (1189, '19:48', 0);
INSERT INTO `time_schedule_1` VALUES (1190, '19:49', 0);
INSERT INTO `time_schedule_1` VALUES (1191, '19:50', 0);
INSERT INTO `time_schedule_1` VALUES (1192, '19:51', 0);
INSERT INTO `time_schedule_1` VALUES (1193, '19:52', 0);
INSERT INTO `time_schedule_1` VALUES (1194, '19:53', 0);
INSERT INTO `time_schedule_1` VALUES (1195, '19:54', 0);
INSERT INTO `time_schedule_1` VALUES (1196, '19:55', 0);
INSERT INTO `time_schedule_1` VALUES (1197, '19:56', 0);
INSERT INTO `time_schedule_1` VALUES (1198, '19:57', 0);
INSERT INTO `time_schedule_1` VALUES (1199, '19:58', 0);
INSERT INTO `time_schedule_1` VALUES (1200, '19:59', 0);
INSERT INTO `time_schedule_1` VALUES (1201, '20:00', 0);
INSERT INTO `time_schedule_1` VALUES (1202, '20:01', 0);
INSERT INTO `time_schedule_1` VALUES (1203, '20:02', 0);
INSERT INTO `time_schedule_1` VALUES (1204, '20:03', 0);
INSERT INTO `time_schedule_1` VALUES (1205, '20:04', 0);
INSERT INTO `time_schedule_1` VALUES (1206, '20:05', 0);
INSERT INTO `time_schedule_1` VALUES (1207, '20:06', 0);
INSERT INTO `time_schedule_1` VALUES (1208, '20:07', 0);
INSERT INTO `time_schedule_1` VALUES (1209, '20:08', 0);
INSERT INTO `time_schedule_1` VALUES (1210, '20:09', 0);
INSERT INTO `time_schedule_1` VALUES (1211, '20:10', 0);
INSERT INTO `time_schedule_1` VALUES (1212, '20:11', 0);
INSERT INTO `time_schedule_1` VALUES (1213, '20:12', 0);
INSERT INTO `time_schedule_1` VALUES (1214, '20:13', 0);
INSERT INTO `time_schedule_1` VALUES (1215, '20:14', 0);
INSERT INTO `time_schedule_1` VALUES (1216, '20:15', 0);
INSERT INTO `time_schedule_1` VALUES (1217, '20:16', 0);
INSERT INTO `time_schedule_1` VALUES (1218, '20:17', 0);
INSERT INTO `time_schedule_1` VALUES (1219, '20:18', 0);
INSERT INTO `time_schedule_1` VALUES (1220, '20:19', 0);
INSERT INTO `time_schedule_1` VALUES (1221, '20:20', 0);
INSERT INTO `time_schedule_1` VALUES (1222, '20:21', 0);
INSERT INTO `time_schedule_1` VALUES (1223, '20:22', 0);
INSERT INTO `time_schedule_1` VALUES (1224, '20:23', 0);
INSERT INTO `time_schedule_1` VALUES (1225, '20:24', 0);
INSERT INTO `time_schedule_1` VALUES (1226, '20:25', 0);
INSERT INTO `time_schedule_1` VALUES (1227, '20:26', 0);
INSERT INTO `time_schedule_1` VALUES (1228, '20:27', 0);
INSERT INTO `time_schedule_1` VALUES (1229, '20:28', 0);
INSERT INTO `time_schedule_1` VALUES (1230, '20:29', 0);
INSERT INTO `time_schedule_1` VALUES (1231, '20:30', 0);
INSERT INTO `time_schedule_1` VALUES (1232, '20:31', 0);
INSERT INTO `time_schedule_1` VALUES (1233, '20:32', 0);
INSERT INTO `time_schedule_1` VALUES (1234, '20:33', 0);
INSERT INTO `time_schedule_1` VALUES (1235, '20:34', 0);
INSERT INTO `time_schedule_1` VALUES (1236, '20:35', 0);
INSERT INTO `time_schedule_1` VALUES (1237, '20:36', 0);
INSERT INTO `time_schedule_1` VALUES (1238, '20:37', 0);
INSERT INTO `time_schedule_1` VALUES (1239, '20:38', 0);
INSERT INTO `time_schedule_1` VALUES (1240, '20:39', 0);
INSERT INTO `time_schedule_1` VALUES (1241, '20:40', 0);
INSERT INTO `time_schedule_1` VALUES (1242, '20:41', 0);
INSERT INTO `time_schedule_1` VALUES (1243, '20:42', 0);
INSERT INTO `time_schedule_1` VALUES (1244, '20:43', 0);
INSERT INTO `time_schedule_1` VALUES (1245, '20:44', 0);
INSERT INTO `time_schedule_1` VALUES (1246, '20:45', 0);
INSERT INTO `time_schedule_1` VALUES (1247, '20:46', 0);
INSERT INTO `time_schedule_1` VALUES (1248, '20:47', 0);
INSERT INTO `time_schedule_1` VALUES (1249, '20:48', 0);
INSERT INTO `time_schedule_1` VALUES (1250, '20:49', 0);
INSERT INTO `time_schedule_1` VALUES (1251, '20:50', 0);
INSERT INTO `time_schedule_1` VALUES (1252, '20:51', 0);
INSERT INTO `time_schedule_1` VALUES (1253, '20:52', 0);
INSERT INTO `time_schedule_1` VALUES (1254, '20:53', 0);
INSERT INTO `time_schedule_1` VALUES (1255, '20:54', 0);
INSERT INTO `time_schedule_1` VALUES (1256, '20:55', 0);
INSERT INTO `time_schedule_1` VALUES (1257, '20:56', 0);
INSERT INTO `time_schedule_1` VALUES (1258, '20:57', 0);
INSERT INTO `time_schedule_1` VALUES (1259, '20:58', 0);
INSERT INTO `time_schedule_1` VALUES (1260, '20:59', 0);
INSERT INTO `time_schedule_1` VALUES (1261, '21:00', 0);
INSERT INTO `time_schedule_1` VALUES (1262, '21:01', 0);
INSERT INTO `time_schedule_1` VALUES (1263, '21:02', 0);
INSERT INTO `time_schedule_1` VALUES (1264, '21:03', 0);
INSERT INTO `time_schedule_1` VALUES (1265, '21:04', 0);
INSERT INTO `time_schedule_1` VALUES (1266, '21:05', 0);
INSERT INTO `time_schedule_1` VALUES (1267, '21:06', 0);
INSERT INTO `time_schedule_1` VALUES (1268, '21:07', 0);
INSERT INTO `time_schedule_1` VALUES (1269, '21:08', 0);
INSERT INTO `time_schedule_1` VALUES (1270, '21:09', 0);
INSERT INTO `time_schedule_1` VALUES (1271, '21:10', 0);
INSERT INTO `time_schedule_1` VALUES (1272, '21:11', 0);
INSERT INTO `time_schedule_1` VALUES (1273, '21:12', 0);
INSERT INTO `time_schedule_1` VALUES (1274, '21:13', 0);
INSERT INTO `time_schedule_1` VALUES (1275, '21:14', 0);
INSERT INTO `time_schedule_1` VALUES (1276, '21:15', 0);
INSERT INTO `time_schedule_1` VALUES (1277, '21:16', 0);
INSERT INTO `time_schedule_1` VALUES (1278, '21:17', 0);
INSERT INTO `time_schedule_1` VALUES (1279, '21:18', 0);
INSERT INTO `time_schedule_1` VALUES (1280, '21:19', 0);
INSERT INTO `time_schedule_1` VALUES (1281, '21:20', 0);
INSERT INTO `time_schedule_1` VALUES (1282, '21:21', 0);
INSERT INTO `time_schedule_1` VALUES (1283, '21:22', 0);
INSERT INTO `time_schedule_1` VALUES (1284, '21:23', 0);
INSERT INTO `time_schedule_1` VALUES (1285, '21:24', 0);
INSERT INTO `time_schedule_1` VALUES (1286, '21:25', 0);
INSERT INTO `time_schedule_1` VALUES (1287, '21:26', 0);
INSERT INTO `time_schedule_1` VALUES (1288, '21:27', 0);
INSERT INTO `time_schedule_1` VALUES (1289, '21:28', 0);
INSERT INTO `time_schedule_1` VALUES (1290, '21:29', 0);
INSERT INTO `time_schedule_1` VALUES (1291, '21:30', 0);
INSERT INTO `time_schedule_1` VALUES (1292, '21:31', 0);
INSERT INTO `time_schedule_1` VALUES (1293, '21:32', 0);
INSERT INTO `time_schedule_1` VALUES (1294, '21:33', 0);
INSERT INTO `time_schedule_1` VALUES (1295, '21:34', 0);
INSERT INTO `time_schedule_1` VALUES (1296, '21:35', 0);
INSERT INTO `time_schedule_1` VALUES (1297, '21:36', 0);
INSERT INTO `time_schedule_1` VALUES (1298, '21:37', 0);
INSERT INTO `time_schedule_1` VALUES (1299, '21:38', 0);
INSERT INTO `time_schedule_1` VALUES (1300, '21:39', 0);
INSERT INTO `time_schedule_1` VALUES (1301, '21:40', 0);
INSERT INTO `time_schedule_1` VALUES (1302, '21:41', 0);
INSERT INTO `time_schedule_1` VALUES (1303, '21:42', 0);
INSERT INTO `time_schedule_1` VALUES (1304, '21:43', 0);
INSERT INTO `time_schedule_1` VALUES (1305, '21:44', 0);
INSERT INTO `time_schedule_1` VALUES (1306, '21:45', 0);
INSERT INTO `time_schedule_1` VALUES (1307, '21:46', 0);
INSERT INTO `time_schedule_1` VALUES (1308, '21:47', 0);
INSERT INTO `time_schedule_1` VALUES (1309, '21:48', 0);
INSERT INTO `time_schedule_1` VALUES (1310, '21:49', 0);
INSERT INTO `time_schedule_1` VALUES (1311, '21:50', 0);
INSERT INTO `time_schedule_1` VALUES (1312, '21:51', 0);
INSERT INTO `time_schedule_1` VALUES (1313, '21:52', 0);
INSERT INTO `time_schedule_1` VALUES (1314, '21:53', 0);
INSERT INTO `time_schedule_1` VALUES (1315, '21:54', 0);
INSERT INTO `time_schedule_1` VALUES (1316, '21:55', 0);
INSERT INTO `time_schedule_1` VALUES (1317, '21:56', 0);
INSERT INTO `time_schedule_1` VALUES (1318, '21:57', 0);
INSERT INTO `time_schedule_1` VALUES (1319, '21:58', 0);
INSERT INTO `time_schedule_1` VALUES (1320, '21:59', 0);
INSERT INTO `time_schedule_1` VALUES (1321, '22:00', 0);
INSERT INTO `time_schedule_1` VALUES (1322, '22:01', 0);
INSERT INTO `time_schedule_1` VALUES (1323, '22:02', 0);
INSERT INTO `time_schedule_1` VALUES (1324, '22:03', 0);
INSERT INTO `time_schedule_1` VALUES (1325, '22:04', 0);
INSERT INTO `time_schedule_1` VALUES (1326, '22:05', 0);
INSERT INTO `time_schedule_1` VALUES (1327, '22:06', 0);
INSERT INTO `time_schedule_1` VALUES (1328, '22:07', 0);
INSERT INTO `time_schedule_1` VALUES (1329, '22:08', 0);
INSERT INTO `time_schedule_1` VALUES (1330, '22:09', 0);
INSERT INTO `time_schedule_1` VALUES (1331, '22:10', 0);
INSERT INTO `time_schedule_1` VALUES (1332, '22:11', 0);
INSERT INTO `time_schedule_1` VALUES (1333, '22:12', 0);
INSERT INTO `time_schedule_1` VALUES (1334, '22:13', 0);
INSERT INTO `time_schedule_1` VALUES (1335, '22:14', 0);
INSERT INTO `time_schedule_1` VALUES (1336, '22:15', 0);
INSERT INTO `time_schedule_1` VALUES (1337, '22:16', 0);
INSERT INTO `time_schedule_1` VALUES (1338, '22:17', 0);
INSERT INTO `time_schedule_1` VALUES (1339, '22:18', 0);
INSERT INTO `time_schedule_1` VALUES (1340, '22:19', 0);
INSERT INTO `time_schedule_1` VALUES (1341, '22:20', 0);
INSERT INTO `time_schedule_1` VALUES (1342, '22:21', 0);
INSERT INTO `time_schedule_1` VALUES (1343, '22:22', 0);
INSERT INTO `time_schedule_1` VALUES (1344, '22:23', 0);
INSERT INTO `time_schedule_1` VALUES (1345, '22:24', 0);
INSERT INTO `time_schedule_1` VALUES (1346, '22:25', 0);
INSERT INTO `time_schedule_1` VALUES (1347, '22:26', 0);
INSERT INTO `time_schedule_1` VALUES (1348, '22:27', 0);
INSERT INTO `time_schedule_1` VALUES (1349, '22:28', 0);
INSERT INTO `time_schedule_1` VALUES (1350, '22:29', 0);
INSERT INTO `time_schedule_1` VALUES (1351, '22:30', 0);
INSERT INTO `time_schedule_1` VALUES (1352, '22:31', 0);
INSERT INTO `time_schedule_1` VALUES (1353, '22:32', 0);
INSERT INTO `time_schedule_1` VALUES (1354, '22:33', 0);
INSERT INTO `time_schedule_1` VALUES (1355, '22:34', 0);
INSERT INTO `time_schedule_1` VALUES (1356, '22:35', 0);
INSERT INTO `time_schedule_1` VALUES (1357, '22:36', 0);
INSERT INTO `time_schedule_1` VALUES (1358, '22:37', 0);
INSERT INTO `time_schedule_1` VALUES (1359, '22:38', 0);
INSERT INTO `time_schedule_1` VALUES (1360, '22:39', 0);
INSERT INTO `time_schedule_1` VALUES (1361, '22:40', 0);
INSERT INTO `time_schedule_1` VALUES (1362, '22:41', 0);
INSERT INTO `time_schedule_1` VALUES (1363, '22:42', 0);
INSERT INTO `time_schedule_1` VALUES (1364, '22:43', 0);
INSERT INTO `time_schedule_1` VALUES (1365, '22:44', 0);
INSERT INTO `time_schedule_1` VALUES (1366, '22:45', 0);
INSERT INTO `time_schedule_1` VALUES (1367, '22:46', 0);
INSERT INTO `time_schedule_1` VALUES (1368, '22:47', 0);
INSERT INTO `time_schedule_1` VALUES (1369, '22:48', 0);
INSERT INTO `time_schedule_1` VALUES (1370, '22:49', 0);
INSERT INTO `time_schedule_1` VALUES (1371, '22:50', 0);
INSERT INTO `time_schedule_1` VALUES (1372, '22:51', 0);
INSERT INTO `time_schedule_1` VALUES (1373, '22:52', 0);
INSERT INTO `time_schedule_1` VALUES (1374, '22:53', 0);
INSERT INTO `time_schedule_1` VALUES (1375, '22:54', 0);
INSERT INTO `time_schedule_1` VALUES (1376, '22:55', 0);
INSERT INTO `time_schedule_1` VALUES (1377, '22:56', 0);
INSERT INTO `time_schedule_1` VALUES (1378, '22:57', 0);
INSERT INTO `time_schedule_1` VALUES (1379, '22:58', 0);
INSERT INTO `time_schedule_1` VALUES (1380, '22:59', 0);
INSERT INTO `time_schedule_1` VALUES (1381, '23:00', 0);
INSERT INTO `time_schedule_1` VALUES (1382, '23:01', 0);
INSERT INTO `time_schedule_1` VALUES (1383, '23:02', 0);
INSERT INTO `time_schedule_1` VALUES (1384, '23:03', 0);
INSERT INTO `time_schedule_1` VALUES (1385, '23:04', 0);
INSERT INTO `time_schedule_1` VALUES (1386, '23:05', 0);
INSERT INTO `time_schedule_1` VALUES (1387, '23:06', 0);
INSERT INTO `time_schedule_1` VALUES (1388, '23:07', 0);
INSERT INTO `time_schedule_1` VALUES (1389, '23:08', 0);
INSERT INTO `time_schedule_1` VALUES (1390, '23:09', 0);
INSERT INTO `time_schedule_1` VALUES (1391, '23:10', 0);
INSERT INTO `time_schedule_1` VALUES (1392, '23:11', 0);
INSERT INTO `time_schedule_1` VALUES (1393, '23:12', 0);
INSERT INTO `time_schedule_1` VALUES (1394, '23:13', 0);
INSERT INTO `time_schedule_1` VALUES (1395, '23:14', 0);
INSERT INTO `time_schedule_1` VALUES (1396, '23:15', 0);
INSERT INTO `time_schedule_1` VALUES (1397, '23:16', 0);
INSERT INTO `time_schedule_1` VALUES (1398, '23:17', 0);
INSERT INTO `time_schedule_1` VALUES (1399, '23:18', 0);
INSERT INTO `time_schedule_1` VALUES (1400, '23:19', 0);
INSERT INTO `time_schedule_1` VALUES (1401, '23:20', 0);
INSERT INTO `time_schedule_1` VALUES (1402, '23:21', 0);
INSERT INTO `time_schedule_1` VALUES (1403, '23:22', 0);
INSERT INTO `time_schedule_1` VALUES (1404, '23:23', 0);
INSERT INTO `time_schedule_1` VALUES (1405, '23:24', 0);
INSERT INTO `time_schedule_1` VALUES (1406, '23:25', 0);
INSERT INTO `time_schedule_1` VALUES (1407, '23:26', 0);
INSERT INTO `time_schedule_1` VALUES (1408, '23:27', 0);
INSERT INTO `time_schedule_1` VALUES (1409, '23:28', 0);
INSERT INTO `time_schedule_1` VALUES (1410, '23:29', 0);
INSERT INTO `time_schedule_1` VALUES (1411, '23:30', 0);
INSERT INTO `time_schedule_1` VALUES (1412, '23:31', 0);
INSERT INTO `time_schedule_1` VALUES (1413, '23:32', 0);
INSERT INTO `time_schedule_1` VALUES (1414, '23:33', 0);
INSERT INTO `time_schedule_1` VALUES (1415, '23:34', 0);
INSERT INTO `time_schedule_1` VALUES (1416, '23:35', 0);
INSERT INTO `time_schedule_1` VALUES (1417, '23:36', 0);
INSERT INTO `time_schedule_1` VALUES (1418, '23:37', 0);
INSERT INTO `time_schedule_1` VALUES (1419, '23:38', 0);
INSERT INTO `time_schedule_1` VALUES (1420, '23:39', 0);
INSERT INTO `time_schedule_1` VALUES (1421, '23:40', 0);
INSERT INTO `time_schedule_1` VALUES (1422, '23:41', 0);
INSERT INTO `time_schedule_1` VALUES (1423, '23:42', 0);
INSERT INTO `time_schedule_1` VALUES (1424, '23:43', 0);
INSERT INTO `time_schedule_1` VALUES (1425, '23:44', 0);
INSERT INTO `time_schedule_1` VALUES (1426, '23:45', 0);
INSERT INTO `time_schedule_1` VALUES (1427, '23:46', 0);
INSERT INTO `time_schedule_1` VALUES (1428, '23:47', 0);
INSERT INTO `time_schedule_1` VALUES (1429, '23:48', 0);
INSERT INTO `time_schedule_1` VALUES (1430, '23:49', 0);
INSERT INTO `time_schedule_1` VALUES (1431, '23:50', 0);
INSERT INTO `time_schedule_1` VALUES (1432, '23:51', 0);
INSERT INTO `time_schedule_1` VALUES (1433, '23:52', 0);
INSERT INTO `time_schedule_1` VALUES (1434, '23:53', 0);
INSERT INTO `time_schedule_1` VALUES (1435, '23:54', 0);
INSERT INTO `time_schedule_1` VALUES (1436, '23:55', 0);
INSERT INTO `time_schedule_1` VALUES (1437, '23:56', 0);
INSERT INTO `time_schedule_1` VALUES (1438, '23:57', 0);
INSERT INTO `time_schedule_1` VALUES (1439, '23:58', 0);
INSERT INTO `time_schedule_1` VALUES (1440, '23:59', 0);

-- ----------------------------
-- Table structure for time_schedule_15
-- ----------------------------
DROP TABLE IF EXISTS `time_schedule_15`;
CREATE TABLE `time_schedule_15`  (
  `timeid` int NOT NULL AUTO_INCREMENT,
  `time` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_deleted` int NOT NULL,
  PRIMARY KEY (`timeid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 97 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of time_schedule_15
-- ----------------------------
INSERT INTO `time_schedule_15` VALUES (1, '00:00', 0);
INSERT INTO `time_schedule_15` VALUES (2, '00:15', 0);
INSERT INTO `time_schedule_15` VALUES (3, '00:30', 0);
INSERT INTO `time_schedule_15` VALUES (4, '00:45', 0);
INSERT INTO `time_schedule_15` VALUES (5, '01:00', 0);
INSERT INTO `time_schedule_15` VALUES (6, '01:15', 0);
INSERT INTO `time_schedule_15` VALUES (7, '01:30', 0);
INSERT INTO `time_schedule_15` VALUES (8, '01:45', 0);
INSERT INTO `time_schedule_15` VALUES (9, '02:00', 0);
INSERT INTO `time_schedule_15` VALUES (10, '02:15', 0);
INSERT INTO `time_schedule_15` VALUES (11, '02:30', 0);
INSERT INTO `time_schedule_15` VALUES (12, '02:45', 0);
INSERT INTO `time_schedule_15` VALUES (13, '03:00', 0);
INSERT INTO `time_schedule_15` VALUES (14, '03:15', 0);
INSERT INTO `time_schedule_15` VALUES (15, '03:30', 0);
INSERT INTO `time_schedule_15` VALUES (16, '03:45', 0);
INSERT INTO `time_schedule_15` VALUES (17, '04:00', 0);
INSERT INTO `time_schedule_15` VALUES (18, '04:15', 0);
INSERT INTO `time_schedule_15` VALUES (19, '04:30', 0);
INSERT INTO `time_schedule_15` VALUES (20, '04:45', 0);
INSERT INTO `time_schedule_15` VALUES (21, '05:00', 0);
INSERT INTO `time_schedule_15` VALUES (22, '05:15', 0);
INSERT INTO `time_schedule_15` VALUES (23, '05:30', 0);
INSERT INTO `time_schedule_15` VALUES (24, '05:45', 0);
INSERT INTO `time_schedule_15` VALUES (25, '06:00', 0);
INSERT INTO `time_schedule_15` VALUES (26, '06:15', 0);
INSERT INTO `time_schedule_15` VALUES (27, '06:30', 0);
INSERT INTO `time_schedule_15` VALUES (28, '06:45', 0);
INSERT INTO `time_schedule_15` VALUES (29, '07:00', 0);
INSERT INTO `time_schedule_15` VALUES (30, '07:15', 0);
INSERT INTO `time_schedule_15` VALUES (31, '07:30', 0);
INSERT INTO `time_schedule_15` VALUES (32, '07:45', 0);
INSERT INTO `time_schedule_15` VALUES (33, '08:00', 0);
INSERT INTO `time_schedule_15` VALUES (34, '08:15', 0);
INSERT INTO `time_schedule_15` VALUES (35, '08:30', 0);
INSERT INTO `time_schedule_15` VALUES (36, '08:45', 0);
INSERT INTO `time_schedule_15` VALUES (37, '09:00', 0);
INSERT INTO `time_schedule_15` VALUES (38, '09:15', 0);
INSERT INTO `time_schedule_15` VALUES (39, '09:30', 0);
INSERT INTO `time_schedule_15` VALUES (40, '09:45', 0);
INSERT INTO `time_schedule_15` VALUES (41, '10:00', 0);
INSERT INTO `time_schedule_15` VALUES (42, '10:15', 0);
INSERT INTO `time_schedule_15` VALUES (43, '10:30', 0);
INSERT INTO `time_schedule_15` VALUES (44, '10:45', 0);
INSERT INTO `time_schedule_15` VALUES (45, '11:00', 0);
INSERT INTO `time_schedule_15` VALUES (46, '11:15', 0);
INSERT INTO `time_schedule_15` VALUES (47, '11:30', 0);
INSERT INTO `time_schedule_15` VALUES (48, '11:45', 0);
INSERT INTO `time_schedule_15` VALUES (49, '12:00', 0);
INSERT INTO `time_schedule_15` VALUES (50, '12:15', 0);
INSERT INTO `time_schedule_15` VALUES (51, '12:30', 0);
INSERT INTO `time_schedule_15` VALUES (52, '12:45', 0);
INSERT INTO `time_schedule_15` VALUES (53, '13:00', 0);
INSERT INTO `time_schedule_15` VALUES (54, '13:15', 0);
INSERT INTO `time_schedule_15` VALUES (55, '13:30', 0);
INSERT INTO `time_schedule_15` VALUES (56, '13:45', 0);
INSERT INTO `time_schedule_15` VALUES (57, '14:00', 0);
INSERT INTO `time_schedule_15` VALUES (58, '14:15', 0);
INSERT INTO `time_schedule_15` VALUES (59, '14:30', 0);
INSERT INTO `time_schedule_15` VALUES (60, '14:45', 0);
INSERT INTO `time_schedule_15` VALUES (61, '15:00', 0);
INSERT INTO `time_schedule_15` VALUES (62, '15:15', 0);
INSERT INTO `time_schedule_15` VALUES (63, '15:30', 0);
INSERT INTO `time_schedule_15` VALUES (64, '15:45', 0);
INSERT INTO `time_schedule_15` VALUES (65, '16:00', 0);
INSERT INTO `time_schedule_15` VALUES (66, '16:15', 0);
INSERT INTO `time_schedule_15` VALUES (67, '16:30', 0);
INSERT INTO `time_schedule_15` VALUES (68, '16:45', 0);
INSERT INTO `time_schedule_15` VALUES (69, '17:00', 0);
INSERT INTO `time_schedule_15` VALUES (70, '17:15', 0);
INSERT INTO `time_schedule_15` VALUES (71, '17:30', 0);
INSERT INTO `time_schedule_15` VALUES (72, '17:45', 0);
INSERT INTO `time_schedule_15` VALUES (73, '18:00', 0);
INSERT INTO `time_schedule_15` VALUES (74, '18:15', 0);
INSERT INTO `time_schedule_15` VALUES (75, '18:30', 0);
INSERT INTO `time_schedule_15` VALUES (76, '18:45', 0);
INSERT INTO `time_schedule_15` VALUES (77, '19:00', 0);
INSERT INTO `time_schedule_15` VALUES (78, '19:15', 0);
INSERT INTO `time_schedule_15` VALUES (79, '19:30', 0);
INSERT INTO `time_schedule_15` VALUES (80, '19:45', 0);
INSERT INTO `time_schedule_15` VALUES (81, '20:00', 0);
INSERT INTO `time_schedule_15` VALUES (82, '20:15', 0);
INSERT INTO `time_schedule_15` VALUES (83, '20:30', 0);
INSERT INTO `time_schedule_15` VALUES (84, '20:45', 0);
INSERT INTO `time_schedule_15` VALUES (85, '21:00', 0);
INSERT INTO `time_schedule_15` VALUES (86, '21:15', 0);
INSERT INTO `time_schedule_15` VALUES (87, '21:30', 0);
INSERT INTO `time_schedule_15` VALUES (88, '21:45', 0);
INSERT INTO `time_schedule_15` VALUES (89, '22:00', 0);
INSERT INTO `time_schedule_15` VALUES (90, '22:15', 0);
INSERT INTO `time_schedule_15` VALUES (91, '22:30', 0);
INSERT INTO `time_schedule_15` VALUES (92, '22:45', 0);
INSERT INTO `time_schedule_15` VALUES (93, '23:00', 0);
INSERT INTO `time_schedule_15` VALUES (94, '23:15', 0);
INSERT INTO `time_schedule_15` VALUES (95, '23:30', 0);
INSERT INTO `time_schedule_15` VALUES (96, '23:45', 0);

-- ----------------------------
-- Table structure for time_schedule_30
-- ----------------------------
DROP TABLE IF EXISTS `time_schedule_30`;
CREATE TABLE `time_schedule_30`  (
  `timeid` int NOT NULL AUTO_INCREMENT,
  `time` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_deleted` int NOT NULL,
  PRIMARY KEY (`timeid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 49 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of time_schedule_30
-- ----------------------------
INSERT INTO `time_schedule_30` VALUES (1, '00:00', 0);
INSERT INTO `time_schedule_30` VALUES (2, '00:30', 0);
INSERT INTO `time_schedule_30` VALUES (3, '01:00', 0);
INSERT INTO `time_schedule_30` VALUES (4, '01:30', 0);
INSERT INTO `time_schedule_30` VALUES (5, '02:00', 0);
INSERT INTO `time_schedule_30` VALUES (6, '02:30', 0);
INSERT INTO `time_schedule_30` VALUES (7, '03:00', 0);
INSERT INTO `time_schedule_30` VALUES (8, '03:30', 0);
INSERT INTO `time_schedule_30` VALUES (9, '04:00', 0);
INSERT INTO `time_schedule_30` VALUES (10, '04:30', 0);
INSERT INTO `time_schedule_30` VALUES (11, '05:00', 0);
INSERT INTO `time_schedule_30` VALUES (12, '05:30', 0);
INSERT INTO `time_schedule_30` VALUES (13, '06:00', 0);
INSERT INTO `time_schedule_30` VALUES (14, '06:30', 0);
INSERT INTO `time_schedule_30` VALUES (15, '07:00', 0);
INSERT INTO `time_schedule_30` VALUES (16, '07:30', 0);
INSERT INTO `time_schedule_30` VALUES (17, '08:00', 0);
INSERT INTO `time_schedule_30` VALUES (18, '08:30', 0);
INSERT INTO `time_schedule_30` VALUES (19, '09:00', 0);
INSERT INTO `time_schedule_30` VALUES (20, '09:30', 0);
INSERT INTO `time_schedule_30` VALUES (21, '10:00', 0);
INSERT INTO `time_schedule_30` VALUES (22, '10:30', 0);
INSERT INTO `time_schedule_30` VALUES (23, '11:00', 0);
INSERT INTO `time_schedule_30` VALUES (24, '11:30', 0);
INSERT INTO `time_schedule_30` VALUES (25, '12:00', 0);
INSERT INTO `time_schedule_30` VALUES (26, '12:30', 0);
INSERT INTO `time_schedule_30` VALUES (27, '13:00', 0);
INSERT INTO `time_schedule_30` VALUES (28, '13:30', 0);
INSERT INTO `time_schedule_30` VALUES (29, '14:00', 0);
INSERT INTO `time_schedule_30` VALUES (30, '14:30', 0);
INSERT INTO `time_schedule_30` VALUES (31, '15:00', 0);
INSERT INTO `time_schedule_30` VALUES (32, '15:30', 0);
INSERT INTO `time_schedule_30` VALUES (33, '16:00', 0);
INSERT INTO `time_schedule_30` VALUES (34, '16:30', 0);
INSERT INTO `time_schedule_30` VALUES (35, '17:00', 0);
INSERT INTO `time_schedule_30` VALUES (36, '17:30', 0);
INSERT INTO `time_schedule_30` VALUES (37, '18:00', 0);
INSERT INTO `time_schedule_30` VALUES (38, '18:30', 0);
INSERT INTO `time_schedule_30` VALUES (39, '19:00', 0);
INSERT INTO `time_schedule_30` VALUES (40, '19:30', 0);
INSERT INTO `time_schedule_30` VALUES (41, '20:00', 0);
INSERT INTO `time_schedule_30` VALUES (42, '20:30', 0);
INSERT INTO `time_schedule_30` VALUES (43, '21:00', 0);
INSERT INTO `time_schedule_30` VALUES (44, '21:30', 0);
INSERT INTO `time_schedule_30` VALUES (45, '22:00', 0);
INSERT INTO `time_schedule_30` VALUES (46, '22:30', 0);
INSERT INTO `time_schedule_30` VALUES (47, '23:00', 0);
INSERT INTO `time_schedule_30` VALUES (48, '23:30', 0);

-- ----------------------------
-- Table structure for time_schedule_60
-- ----------------------------
DROP TABLE IF EXISTS `time_schedule_60`;
CREATE TABLE `time_schedule_60`  (
  `timeid` int NOT NULL AUTO_INCREMENT,
  `time` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_deleted` int NOT NULL,
  PRIMARY KEY (`timeid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 25 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of time_schedule_60
-- ----------------------------
INSERT INTO `time_schedule_60` VALUES (1, '00:00', 0);
INSERT INTO `time_schedule_60` VALUES (2, '01:00', 0);
INSERT INTO `time_schedule_60` VALUES (3, '02:00', 0);
INSERT INTO `time_schedule_60` VALUES (4, '03:00', 0);
INSERT INTO `time_schedule_60` VALUES (5, '04:00', 0);
INSERT INTO `time_schedule_60` VALUES (6, '05:00', 0);
INSERT INTO `time_schedule_60` VALUES (7, '06:00', 0);
INSERT INTO `time_schedule_60` VALUES (8, '07:00', 0);
INSERT INTO `time_schedule_60` VALUES (9, '08:00', 0);
INSERT INTO `time_schedule_60` VALUES (10, '09:00', 0);
INSERT INTO `time_schedule_60` VALUES (11, '10:00', 0);
INSERT INTO `time_schedule_60` VALUES (12, '11:00', 0);
INSERT INTO `time_schedule_60` VALUES (13, '12:00', 0);
INSERT INTO `time_schedule_60` VALUES (14, '13:00', 0);
INSERT INTO `time_schedule_60` VALUES (15, '14:00', 0);
INSERT INTO `time_schedule_60` VALUES (16, '15:00', 0);
INSERT INTO `time_schedule_60` VALUES (17, '16:00', 0);
INSERT INTO `time_schedule_60` VALUES (18, '17:00', 0);
INSERT INTO `time_schedule_60` VALUES (19, '18:00', 0);
INSERT INTO `time_schedule_60` VALUES (20, '19:00', 0);
INSERT INTO `time_schedule_60` VALUES (21, '20:00', 0);
INSERT INTO `time_schedule_60` VALUES (22, '21:00', 0);
INSERT INTO `time_schedule_60` VALUES (23, '22:00', 0);
INSERT INTO `time_schedule_60` VALUES (24, '23:00', 0);

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `secure_qr` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `employee_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'nik di employee',
  `password` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `real_password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `level_id` int NOT NULL,
  `access_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1',
  `created_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_disactived` int NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `updated_by` int NULL DEFAULT NULL,
  `is_vip` int NULL DEFAULT 0,
  `vip_approve_bypass` int NULL DEFAULT 0,
  `vip_limit_cap_bypass` int NULL DEFAULT 0,
  `vip_shifted_bypass` int NULL DEFAULT 0,
  `is_approval` int NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 260 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES (3, 'e99cfb1f790d1b4ee36e9ac3c0a25f0c4ae4d3f5f59f984b5f1b238e9b2f25874bad2351ab7d577a5a005ee3a33e439f88bc7033de2742414e7b3c1482224323tpdD/q2klWoZS1Mj2OYhcHToGjY0', 'Administrator', 'admin', 'admin', 'bnF6dmE=', 'admin', 1, '1#2#3', 'admin', '2019-09-19 07:43:23', '2020-03-20 08:17:37', 0, 0, NULL, 0, 0, 0, 0, 0);
INSERT INTO `user` VALUES (174, NULL, 'Tommy G', 'tommy', 'i31820201210134715', 'MTIzNA==', '1234', 2, '1#2#3', 'admin', '2020-12-10 06:47:15', '2023-02-09 20:50:34', 0, 1, NULL, 0, 0, 0, 0, 0);
INSERT INTO `user` VALUES (238, NULL, 'as', 'as', '20230203130023', 'MTIzNA==', '1234', 2, '1#2#3', 'admin', '2023-02-03 06:00:23', '2023-02-09 20:50:26', 0, 1, NULL, 0, 0, 0, 0, 0);
INSERT INTO `user` VALUES (239, NULL, 'as', 'as', '20230203130055', 'MTIzNA==', '1234', 2, '1#2#3', 'admin', '2023-02-03 06:00:55', '2023-02-09 09:08:27', 0, 1, NULL, 0, 0, 0, 0, 0);
INSERT INTO `user` VALUES (240, NULL, 'Test', 'testuser', '20230206171324', 'MTIzNA==', '1234', 2, '1#2#3', 'admin', '2023-02-06 10:13:24', '2023-02-09 20:50:18', 0, 1, NULL, 0, 0, 0, 0, 0);
INSERT INTO `user` VALUES (243, '03fc8d4f76565e4a13c29e1e870df45ed2f6cd7736a2a7b8b104a64b484d0af43699134ad049347e744825b81cdc024a534ba9257989518de1a0ecd264b0dca2FqVLjq/eKz03obV6nVYQy9V2KPtudQ==', 'Alvin', 'user05', 'i32420211115111902', 'MTIzNA==', '1234', 2, '1#2#3#4', 'admin', '2023-02-09 21:40:20', '2023-10-19 09:06:41', 0, 0, NULL, 0, 0, 0, 0, 0);
INSERT INTO `user` VALUES (244, '', 'Organize Microsoft 365', 'orgmicrosoft365', '20231017110503', 'MTIzNA==', '1234', 2, '1', 'admin', '2023-10-17 04:05:03', '2024-11-06 07:52:57', 0, 1, NULL, 0, 0, 0, 0, 0);
INSERT INTO `user` VALUES (245, '4e4757813ec0c501d9ed1e6c6efa81984e6e5c0b4bd5e739d393f5158aed487e0e3aea2547febb5ec4595f34ffaf6e2cead9d74ed1d64069e3e0b6b8b88e3661K1O+8HDEXBmPKTzIuUU7u9mi5mA=', 'Farhan', 'adit', '20231107121320', 'MTIzNA==', '1234', 2, '1', 'admin', '2023-11-07 05:13:20', '2023-11-07 05:14:33', 0, 1, NULL, 0, 0, 0, 0, 0);
INSERT INTO `user` VALUES (246, '4e4757813ec0c501d9ed1e6c6efa81984e6e5c0b4bd5e739d393f5158aed487e0e3aea2547febb5ec4595f34ffaf6e2cead9d74ed1d64069e3e0b6b8b88e3661K1O+8HDEXBmPKTzIuUU7u9mi5mA=', 'Aditya Juda Manggala', 'adit', '20231107121320', 'bnF2Zw==', '', 2, '1#2#3#4', 'admin', '2023-11-07 05:14:49', '2025-01-10 06:24:41', 0, 1, NULL, 0, 0, 0, 0, 0);
INSERT INTO `user` VALUES (247, '', 'Pantry', 'pantry1', '20231128160531', 'MTIzNA==', '1234', 2, '1#2', 'admin', '2023-11-28 09:05:31', '2025-01-10 06:24:49', 0, 1, NULL, 0, 0, 0, 0, 0);
INSERT INTO `user` VALUES (248, '945f00b000aa12b1400f0a9a8b4eae19f925a1bff25dd63b52572037193eec2344d3b8372fef36f9be40758edc412755aa80b186f3a8888e184773b66593d87cinayk3PHc42PC3lc32P9gkNkgMs/', 'Tilis Tiadi', 'tilis', '20241220024501', 'MTIzNA==', '1234', 2, '1#2#3#4', 'admin', '2024-12-19 19:45:01', '2024-12-19 21:42:53', 0, 0, NULL, 0, 0, 0, 0, 0);
INSERT INTO `user` VALUES (249, '36c850ec3268afe982edcb06646100dabc8e96e100f953f99301f398a23b0bb3d22ff1a5142f571447a3ca668691aec8e1002bc9ded04770e530e1b16a059639ZyFxXt0EoSb3vO4CA6eonGgXo4pgRr3Qp7CUEv+DwoY=', 'iQBAL', '991027', 'EC20250109163011', 'MTIzNA==', '1234', 2, '1#2#3#4', 'admin', '2025-01-09 09:30:11', NULL, 0, 0, NULL, 0, 0, 0, 0, 0);
INSERT INTO `user` VALUES (250, 'db894d14a04d87608c8927939cf1fe26061d47c024d2fdb31103567c75334e1e7f4d057c7f5be1411aff0a40fd8ca2030cf0310cbcc37e17d3c431a0010947aeOhHYI8hiX4HdLhDpjnT83r1vTCsgru4msy1EANS9jg4=', 'Bowo', '54', 'EC20250109164141', 'MTIzNA==', '1234', 2, '1#2#3#4', 'admin', '2025-01-09 09:41:41', NULL, 0, 0, NULL, 0, 0, 0, 0, 0);
INSERT INTO `user` VALUES (251, '02f7f7f1cb82c9b1550081b6025da388814b70d2fb161106a76877fd1a78cd96b900329c214531dd046bf874f89e9ec6fd7dd9ba55b82e1eaa2c4cf2b45442e3IudwI0sWAQF3Sgukg8AFJ1AqI2kZgIRDSmDva1G79oc=', 'Handi', '55', 'EC20250110131102', 'MTIzNA==', '1234', 2, '1#2#3#4', 'admin', '2025-01-10 06:11:02', NULL, 0, 0, NULL, 0, 0, 0, 0, 0);
INSERT INTO `user` VALUES (252, '599270860c5f5b85f40428957935e541e628d2e6873822688a309819f866fbdaf1460838b24c29637eaf49267fda8e4d1a3d20c22496f23e3089cd1c97ed9f82LovCNGAICsNHsJBdbVbkSuJc+k07DB3q120RSs4zHKc7Ww==', 'Jhon Doe', '109928', 'EU2564473728613919', 'MTIzNA==', '1234', 2, '1#2#3#4', 'admin', '2025-01-10 06:16:41', '2025-01-10 06:24:29', 0, 1, NULL, 0, 0, 0, 0, 0);
INSERT INTO `user` VALUES (253, '34e398fe727c7d5e04119da8569217d3e33815ab6f50c22bb518f6f007ca80a64ef558c1cbce91a811c7930ca4aa6789542e99b3561c44184a4457c3f1ab19ccaVAwdCE65bGSocXD+TKjsUeUb54pRRUIqvUZPGe8qf18Lg==', 'Bagas', '109929', 'EU9716528051764893', 'MTIzNA==', '1234', 2, '1#2#3#4', 'admin', '2025-01-10 06:20:21', NULL, 0, 0, NULL, 0, 0, 0, 0, 0);
INSERT INTO `user` VALUES (254, 'adeeae01cc003e14c4b22518fd1a84c07e948c7bbd88ec1dc1d5e867185e569c94cdb3ae199662e4a0fe796b2a1ac98ab6a6fa1ae0dc44d825f05d1e53d8d6a7vF/qyzWPBBP9sftoY3Ji72AOwwpM9sXajumLPme/kAU=', 'Cahya', '5589000', 'EC20250110132409', 'MTIzNA==', '1234', 2, '1#2#3#4', 'admin', '2025-01-10 06:24:09', NULL, 0, 0, NULL, 0, 0, 0, 0, 0);
INSERT INTO `user` VALUES (255, 'cd84cba58d53e1401a92146250a308a8f3b6bb9e30b8c5bc03c4c2e5dcd65db10bd5b21708c29174e1fb8c2cecbd923138d3ff304264e91a657f38e2672ca5fc9O8CBWPlQ0f/N8Mg/W/yN30vq0L5xEBB9GcDB/0DayU=', 'Raka', '12', 'EC20250124150641', 'MTIzNA==', '1234', 2, '1#2#3#4', 'admin', '2025-01-24 08:06:41', NULL, 0, 0, NULL, 0, 0, 0, 0, 0);
INSERT INTO `user` VALUES (256, 'c725258caef078b8614597cd70ec2f034a562140006ac27a388e9bf456f0fd5c683df07a2863a2854d98afb7e975d7984b63dc07d4ca11515c18633acd5f55d0cd8ysgfm5PqiAF9blpyTB6noF1jAebSV/3DgBSFDjeQ=', 'riyan', 'riyan', 'EC20251003193126', 'bnF6dmE=', '1234', 2, '1#3', 'admin', '2025-10-03 12:31:26', '2025-10-04 01:17:27', 0, 0, NULL, 0, 0, 0, 0, 0);
INSERT INTO `user` VALUES (257, 'f39d9d17b6a90ffd2277488598af1b9399dc5d664bba2e09d0599b12a86a4c1497827c180c93c797cc0d64e1872131323b0826967fd64467349911f7717dcf7cB6NKqpFfQ1Qvi/dth7L1rhLKaaHr4Qf6XkcORa9i9rY=', 'Fenny', 'fenny', 'EC20251004081530', 'bnF6dmE=', '1234', 2, '1#3', 'admin', '2025-10-04 01:15:30', '2025-10-04 01:18:49', 0, 0, NULL, 0, 0, 0, 0, 0);
INSERT INTO `user` VALUES (258, 'ca9f7499201738e6b7496e0b95eca31212732da6c9421b703139308e41c997534e390d56310a6795afbd7a6d04ad82868d6063be649108605953897d5846eb261EwIxopMov35N0b4OnAvh54QZM8qrTeyBqBsqrXD6uw=', 'Yudist', 'yudist', 'EC20251004081605', 'bnF6dmE=', '1234', 2, '1#3', 'admin', '2025-10-04 01:16:05', '2025-10-04 01:18:15', 0, 0, NULL, 0, 0, 0, 0, 0);
INSERT INTO `user` VALUES (259, '3758375cb40931a877e91b2976711c09b36c25333c561d9353008089296f2a9f463190bb7d9a0e11c84f18e2d90a8e3786bd8d0ef661afe9ec206454fd4fde6aw9lZ4MYmyrrKlu9H4y6s+W76JOp2tnOpQcERNDIjhz0=', 'Juda', 'juda', 'EC20251004081648', 'bnF6dmE=', '1234', 2, '1#3', 'admin', '2025-10-04 01:16:48', '2025-10-04 01:18:28', 0, 0, NULL, 0, 0, 0, 0, 0);

-- ----------------------------
-- Table structure for user_access
-- ----------------------------
DROP TABLE IF EXISTS `user_access`;
CREATE TABLE `user_access`  (
  `access_id` int NOT NULL,
  `access_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `default_access` int NULL DEFAULT 0,
  `is_active` int NULL DEFAULT NULL,
  PRIMARY KEY (`access_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user_access
-- ----------------------------
INSERT INTO `user_access` VALUES (1, 'Booking', 1, 1);
INSERT INTO `user_access` VALUES (2, 'Order Pantry', 0, 0);
INSERT INTO `user_access` VALUES (3, 'Booking Desk', 1, 1);
INSERT INTO `user_access` VALUES (4, 'Approval', 0, 0);

-- ----------------------------
-- Table structure for user_config
-- ----------------------------
DROP TABLE IF EXISTS `user_config`;
CREATE TABLE `user_config`  (
  `id` int NOT NULL,
  `default_password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password_length` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user_config
-- ----------------------------
INSERT INTO `user_config` VALUES (1, '1234', 4);

-- ----------------------------
-- Table structure for variable_time_duration
-- ----------------------------
DROP TABLE IF EXISTS `variable_time_duration`;
CREATE TABLE `variable_time_duration`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `time` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of variable_time_duration
-- ----------------------------
INSERT INTO `variable_time_duration` VALUES (1, 30);
INSERT INTO `variable_time_duration` VALUES (2, 60);

-- ----------------------------
-- Table structure for variable_time_extend
-- ----------------------------
DROP TABLE IF EXISTS `variable_time_extend`;
CREATE TABLE `variable_time_extend`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `time` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of variable_time_extend
-- ----------------------------
INSERT INTO `variable_time_extend` VALUES (1, 30);
INSERT INTO `variable_time_extend` VALUES (2, 60);
INSERT INTO `variable_time_extend` VALUES (3, 90);
INSERT INTO `variable_time_extend` VALUES (4, 120);

SET FOREIGN_KEY_CHECKS = 1;
