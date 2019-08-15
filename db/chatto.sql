/*
Navicat MySQL Data Transfer

Source Server         : Projects
Source Server Version : 100129
Source Host           : localhost:3306
Source Database       : chatto

Target Server Type    : MYSQL
Target Server Version : 100129
File Encoding         : 65001

Date: 2019-08-15 00:10:26
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for messages
-- ----------------------------
DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sender` varchar(50) DEFAULT NULL,
  `text` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of messages
-- ----------------------------
INSERT INTO `messages` VALUES ('13', 'Jane', 'loliti.com is the shit', '2019-08-14 20:52:42', '2019-08-14 20:52:42');
INSERT INTO `messages` VALUES ('14', 'anonymous1565649556269', 'naaa kinda like xvideos more', '2019-08-14 20:53:08', '2019-08-14 20:53:08');
INSERT INTO `messages` VALUES ('15', 'Michael', 'sorry my name is michael', '2019-08-14 20:53:36', '2019-08-14 20:53:36');
INSERT INTO `messages` VALUES ('16', 'Jane', 'Crime rate is rising ', '2019-08-14 23:00:26', '2019-08-14 23:00:26');
INSERT INTO `messages` VALUES ('17', 'Jameson', '@jane I know right', '2019-08-14 23:03:11', '2019-08-14 23:03:11');
