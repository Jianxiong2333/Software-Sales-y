SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `user_log` (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `y_ip` char(15) NOT NULL COMMENT 'IP地址',
  `y_order` char(22) NOT NULL COMMENT '支付宝付款+标准版+邮箱32位MD5前四位+带前导四位年月日时分秒+置入两位随机数',
  `y_email` varchar(39) NOT NULL COMMENT '凭证邮箱',
  `y_code` char(128) NOT NULL COMMENT '机器码',
  `y_state` int(2) NOT NULL COMMENT '购买状态：0为下单未购买1为已购买成功',
  PRIMARY KEY (`id`),
  KEY `y_order` (`y_order`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='日志表' AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `user_trust` (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `y_ip` char(15) NOT NULL COMMENT 'IP地址',
  `y_trade_no` varchar(28) NOT NULL COMMENT '支付宝交易订单号',
  `y_order` char(22) NOT NULL COMMENT '支付宝付款+标准版+邮箱32位MD5前四位+带前导四位年月日时分秒+置入两位随机数',
  `y_email` varchar(39) NOT NULL COMMENT '凭证邮箱',
  `y_code` char(128) NOT NULL COMMENT '机器码',
  `y_key` char(19) NOT NULL COMMENT '激活码',
  `y_price` int(5) NOT NULL COMMENT '购买价格',
  `y_state` int(2) NOT NULL COMMENT '状态：0购买未激活1已激活-1已禁用',
  PRIMARY KEY (`id`),
  UNIQUE KEY `y_trade_no` (`y_trade_no`),
  KEY `y_email` (`y_email`),
  KEY `y_key` (`y_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='正版用户表' AUTO_INCREMENT=1 ;

