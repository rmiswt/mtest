/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50717
Source Host           : localhost:3306
Source Database       : new_fjhhjr

Target Server Type    : MYSQL
Target Server Version : 50717
File Encoding         : 65001

Date: 2018-08-01 11:48:58
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for wt_account
-- ----------------------------
DROP TABLE IF EXISTS `wt_account`;
CREATE TABLE `wt_account` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL COMMENT '对应角色',
  `username` varchar(255) NOT NULL COMMENT '登录用户名',
  `pwd` varchar(255) NOT NULL COMMENT '登录密码',
  `created_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1正常0禁用',
  `last_login_time` int(11) NOT NULL DEFAULT '0' COMMENT '上次登录时间',
  `isadmin` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否超级管理员',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wt_account
-- ----------------------------
INSERT INTO `wt_account` VALUES ('1', '1', 'admin', '0d47876f4609b668947a6469e650c06b', '0', '1', '1533029088', '1');
INSERT INTO `wt_account` VALUES ('2', '2', 'test', '0d47876f4609b668947a6469e650c06b', '1532942485', '1', '1533023332', '0');
INSERT INTO `wt_account` VALUES ('3', '1', 'chen', '0d47876f4609b668947a6469e650c06b', '1532943423', '1', '0', '0');
INSERT INTO `wt_account` VALUES ('4', '1', '1234', '0d47876f4609b668947a6469e650c06b', '1532943324', '1', '0', '0');
INSERT INTO `wt_account` VALUES ('7', '1', 'h123', '0d47876f4609b668947a6469e650c06b', '1532943539', '1', '0', '0');
INSERT INTO `wt_account` VALUES ('11', '1', 'qqq', '0d47876f4609b668947a6469e650c06b', '1532943596', '1', '0', '0');
INSERT INTO `wt_account` VALUES ('13', '1', 'admin1', '0d47876f4609b668947a6469e650c06b', '1532943686', '1', '0', '0');
INSERT INTO `wt_account` VALUES ('14', '2', 'admin2', '0d47876f4609b668947a6469e650c06b', '1532943730', '1', '0', '0');

-- ----------------------------
-- Table structure for wt_module
-- ----------------------------
DROP TABLE IF EXISTS `wt_module`;
CREATE TABLE `wt_module` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '模块名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wt_module
-- ----------------------------
INSERT INTO `wt_module` VALUES ('16', '会员管理');
INSERT INTO `wt_module` VALUES ('17', '订单管理');
INSERT INTO `wt_module` VALUES ('18', '产品管理');

-- ----------------------------
-- Table structure for wt_role
-- ----------------------------
DROP TABLE IF EXISTS `wt_role`;
CREATE TABLE `wt_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '角色名称',
  `rules` varchar(255) NOT NULL COMMENT '权限列表(ruleid逗号隔开)',
  `created_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wt_role
-- ----------------------------
INSERT INTO `wt_role` VALUES ('2', '普通管理员', '13,14', '0');

-- ----------------------------
-- Table structure for wt_rules
-- ----------------------------
DROP TABLE IF EXISTS `wt_rules`;
CREATE TABLE `wt_rules` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rule` varchar(255) NOT NULL COMMENT '控制器行为',
  `name` varchar(50) NOT NULL COMMENT '描述',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '1菜单并权限列表0只权限控制',
  `module_id` int(11) NOT NULL COMMENT '所属模块',
  `display_order` int(11) NOT NULL DEFAULT '0' COMMENT '显示顺序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk` (`rule`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wt_rules
-- ----------------------------
INSERT INTO `wt_rules` VALUES ('8', 'admin/ruleop', '操作权限', '0', '15', '0');
INSERT INTO `wt_rules` VALUES ('9', '1', '2', '0', '15', '10');
INSERT INTO `wt_rules` VALUES ('12', 'member/memberop', '会员操作（增删改）', '1', '16', '12');
INSERT INTO `wt_rules` VALUES ('13', 'member/memberlist', '会员列表', '0', '16', '0');
INSERT INTO `wt_rules` VALUES ('14', 'order/orderlist', '订单列表', '0', '17', '0');
INSERT INTO `wt_rules` VALUES ('15', 'order/orderop', '订单操作(增删改)', '1', '17', '0');
