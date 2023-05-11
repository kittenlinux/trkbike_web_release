/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 80021
 Source Host           : localhost:3306
 Source Schema         : trackmycars

 Target Server Type    : MySQL
 Target Server Version : 80021
 File Encoding         : 65001

 Date: 09/11/2020 09:03:53
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for trkbike_bike
-- ----------------------------
DROP TABLE IF EXISTS `trkbike_bike`;
CREATE TABLE `trkbike_bike`  (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `user` int UNSIGNED NULL DEFAULT NULL,
  `key` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `plate` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `model` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `color` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `reg_date` datetime(0) NULL DEFAULT NULL,
  `status` smallint NULL DEFAULT NULL,
  `device_mac` varchar(18) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `image_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `phone_id`(`id`) USING BTREE,
  INDEX `user`(`user`) USING BTREE,
  INDEX `status`(`status`) USING BTREE,
  UNIQUE INDEX `uc_bike`(`key`, `plate`) USING BTREE,
  CONSTRAINT `trkbike_bike_ibfk_1` FOREIGN KEY (`user`) REFERENCES `trkbike_users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of trkbike_bike
-- ----------------------------

-- ----------------------------
-- Table structure for trkbike_ci_sessions
-- ----------------------------
DROP TABLE IF EXISTS `trkbike_ci_sessions`;
CREATE TABLE `trkbike_ci_sessions`  (
  `id` varchar(40) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `ip_address` varchar(45) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `timestamp` int UNSIGNED NOT NULL DEFAULT 0,
  `data` blob NOT NULL,
  INDEX `ci_sessions_timestamp`(`timestamp`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of trkbike_ci_sessions
-- ----------------------------

-- ----------------------------
-- Table structure for trkbike_event
-- ----------------------------
DROP TABLE IF EXISTS `trkbike_event`;
CREATE TABLE `trkbike_event`  (
  `id` smallint NOT NULL,
  `name` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `alert` tinyint(1) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of trkbike_event
-- ----------------------------
INSERT INTO `trkbike_event` VALUES (1, 'รับข้อมูลการติดตามปกติ', 0);
INSERT INTO `trkbike_event` VALUES (11, 'รับข้อมูลการติดตามโหมดโจรกรรม', 0);
INSERT INTO `trkbike_event` VALUES (100, 'ลงทะเบียนเสร็จสิ้น', 1);
INSERT INTO `trkbike_event` VALUES (201, 'เริ่มการโจรกรรมและกำลังติดตาม', 1);
INSERT INTO `trkbike_event` VALUES (202, 'โหมดติดตามรถตามปกติ', 1);
INSERT INTO `trkbike_event` VALUES (301, 'เริ่มการเฝ้าระวัง', 1);
INSERT INTO `trkbike_event` VALUES (302, 'สิ้นสุดการเฝ้าระวัง', 1);

-- ----------------------------
-- Table structure for trkbike_groups
-- ----------------------------
DROP TABLE IF EXISTS `trkbike_groups`;
CREATE TABLE `trkbike_groups`  (
  `id` mediumint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `description` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of trkbike_groups
-- ----------------------------
INSERT INTO `trkbike_groups` VALUES (1, 'admin', 'ผู้ดูแลระบบ');
INSERT INTO `trkbike_groups` VALUES (2, 'members', 'ผู้ใช้งาน');

-- ----------------------------
-- Table structure for trkbike_track
-- ----------------------------
DROP TABLE IF EXISTS `trkbike_track`;
CREATE TABLE `trkbike_track`  (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `time` datetime(0) NULL DEFAULT NULL,
  `bike` bigint NULL DEFAULT NULL,
  `lat` decimal(16, 8) NULL DEFAULT NULL,
  `lng` decimal(16, 8) NULL DEFAULT NULL,
  `event` smallint NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `status`(`event`) USING BTREE,
  INDEX `bike`(`bike`) USING BTREE,
  CONSTRAINT `trkbike_track_ibfk_1` FOREIGN KEY (`event`) REFERENCES `trkbike_event` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `trkbike_track_ibfk_4` FOREIGN KEY (`bike`) REFERENCES `trkbike_bike` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of trkbike_track
-- ----------------------------

-- ----------------------------
-- Table structure for trkbike_users
-- ----------------------------
DROP TABLE IF EXISTS `trkbike_users`;
CREATE TABLE `trkbike_users`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `username` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `salt` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `remember_code` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `created_on` int UNSIGNED NOT NULL,
  `last_login` int UNSIGNED NULL DEFAULT NULL,
  `active` tinyint UNSIGNED NULL DEFAULT NULL,
  `first_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `last_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `key` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `lineapi_key` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id`(`id`) USING BTREE,
  UNIQUE INDEX `uc_users`(`username`, `email`, `key`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of trkbike_users
-- ----------------------------
INSERT INTO `trkbike_users` VALUES (1, '127.0.0.1', 'administrator', '$2y$08$LsCARWZsqbVveC5NJvyI/uL/LlJP9XcWXVqzNXfkNroE8lqeOuGfW', NULL, 'admin@admin.com', 'Hc5TuVyWYOVNUkmS5qB/Ie', 1268889823, 1604887099, 1, 'Admin', 'istrator', 'd84b24028d64aa128ed799d298f1aa6a', NULL);

-- ----------------------------
-- Table structure for trkbike_users_groups
-- ----------------------------
DROP TABLE IF EXISTS `trkbike_users_groups`;
CREATE TABLE `trkbike_users_groups`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `group_id` mediumint UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uc_users_groups`(`user_id`, `group_id`) USING BTREE,
  INDEX `fk_users_groups_users1_idx`(`user_id`) USING BTREE,
  INDEX `fk_users_groups_groups1_idx`(`group_id`) USING BTREE,
  CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `trkbike_groups` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `trkbike_users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of trkbike_users_groups
-- ----------------------------
INSERT INTO `trkbike_users_groups` VALUES (1, 1, 1);
INSERT INTO `trkbike_users_groups` VALUES (2, 1, 2);

SET FOREIGN_KEY_CHECKS = 1;
