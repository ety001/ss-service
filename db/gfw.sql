-- phpMyAdmin SQL Dump
-- version 3.4.8
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2015 年 02 月 26 日 15:07
-- 服务器版本: 5.5.37
-- PHP 版本: 5.6.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- 数据库: `gfw`
--

-- --------------------------------------------------------

--
-- 表的结构 `gfw_order`
--

CREATE TABLE IF NOT EXISTS `gfw_order` (
  `id` int(11) NOT NULL,
  `order_id` text NOT NULL,
  `userid` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `gfw_users`
--

CREATE TABLE IF NOT EXISTS `gfw_users` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(32) NOT NULL,
  `vpnname` varchar(20) NOT NULL,
  `vpnpass` varchar(20) NOT NULL,
  `start_time` int(11) NOT NULL,
  `end_time` int(11) NOT NULL,
  `user_type` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;



ALTER TABLE  `gfw_users` ADD  `email` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE  `gfw_users` CHANGE  `start_time`  `start_time` INT( 11 ) NOT NULL DEFAULT  '0',
CHANGE  `end_time`  `end_time` INT( 11 ) NOT NULL DEFAULT  '0';


ALTER TABLE  `gfw_order` ADD PRIMARY KEY (  `id` );
ALTER TABLE  `gfw_order` CHANGE  `id`  `id` INT( 11 ) NOT NULL AUTO_INCREMENT;
ALTER TABLE  `gfw_users` ADD  `port` INT NOT NULL DEFAULT  '0' AFTER  `userid`;

