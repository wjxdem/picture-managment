--
-- Host: localhost    Database: photogallery
-- ------------------------------------------------------
-- Server version	5.6.17
--
CREATE database if not exists photogallery;
USE photogllery;

DROP TABLE IF EXISTS `pg_admin`;
CREATE TABLE `pg_admin` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` char(32) NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `pg_pcate`;
CREATE TABLE `pg_pcate` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `pcatename` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`pcatename`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `pg_scate`;
CREATE TABLE `pg_scate` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `sname` varchar(20) NOT NULL,
  `snum` int(10) unsigned DEFAULT '0',
  `stime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`sname`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `pg_cate`;
CREATE TABLE `pg_cate` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `pid` smallint(5) unsigned NOT NULL,
  `cname` varchar(20) DEFAULT NULL,
  `pnum` int(10) unsigned DEFAULT '0',
  `ptime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`cname`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `pg_logophoto`;
CREATE TABLE `pg_logophoto` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pname` varchar(255) NOT NULL,
  `ppath` varchar(100) NOT NULL,
  `pubtime` int(10) unsigned NOT NULL,
  `cid` smallint(5) unsigned NOT NULL,
  `parentname` varchar(50) NOT NULL,
  `pstate` varchar(10) NOT NULL,
  `pstyle` tinyint(1) NOT NULL,
  `psize` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`pname`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `pg_materialphoto`;
CREATE TABLE `pg_materialphoto` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pname` varchar(255) NOT NULL,
  `ppath` varchar(100) NOT NULL,
  `pubtime` int(10) unsigned NOT NULL,
  `cid` smallint(5) unsigned NOT NULL,
  `parentname` varchar(50) NOT NULL,
  `pversion` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`pname`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `pg_log`;
CREATE TABLE `pg_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ptime` int(10) unsigned NOT NULL,
  `pdesc` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;