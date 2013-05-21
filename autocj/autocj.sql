-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2013 年 04 月 12 日 16:58
-- 服务器版本: 5.5.16
-- PHP 版本: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `autocj`
--

-- --------------------------------------------------------

--
-- 表的结构 `rulesinfo`
--

CREATE TABLE IF NOT EXISTS `rulesinfo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rulename` varchar(300) CHARACTER SET utf8 NOT NULL,
  `rulefile` varchar(300) CHARACTER SET utf8 NOT NULL,
  `part` varchar(300) CHARACTER SET utf8 NOT NULL,
  `addtime` int(11) NOT NULL,
  `autotime` int(11) NOT NULL,
  `geturl` varchar(300) CHARACTER SET utf8 NOT NULL,
  `getlist` varchar(600) CHARACTER SET utf8 NOT NULL,
  `getlink` varchar(600) CHARACTER SET utf8 NOT NULL,
  `autocj` int(11) NOT NULL,
  `gethost` varchar(600) CHARACTER SET utf8 NOT NULL,
  `gettitle` varchar(600) CHARACTER SET utf8 NOT NULL,
  `patterns` varchar(600) CHARACTER SET utf8 NOT NULL,
  `replacements` varchar(600) CHARACTER SET utf8 NOT NULL,
  `universal` int(11) NOT NULL,
  `getdetailurl` varchar(600) CHARACTER SET utf8 NOT NULL,
  `article` varchar(600) CHARACTER SET utf8 NOT NULL,
  `ispage` int(11) NOT NULL,
  `articlepageurl` varchar(600) CHARACTER SET utf8 NOT NULL,
  `articlepage` varchar(600) CHARACTER SET utf8 NOT NULL,
  `getrange` varchar(600) CHARACTER SET utf8 NOT NULL,
  `dpatterns` varchar(600) CHARACTER SET utf8 NOT NULL,
  `dreplacements` varchar(600) CHARACTER SET utf8 NOT NULL,
  `uses` int(11) NOT NULL,
  `approval` int(11) NOT NULL,
  `compatible` int(11) NOT NULL,
  `code` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `rulesinfo`
--

INSERT INTO `rulesinfo` (`id`, `rulename`, `rulefile`, `part`, `addtime`, `autotime`, `geturl`, `getlist`, `getlink`, `autocj`, `gethost`, `gettitle`, `patterns`, `replacements`, `universal`, `getdetailurl`, `article`, `ispage`, `articlepageurl`, `articlepage`, `getrange`, `dpatterns`, `dreplacements`, `uses`, `approval`, `compatible`, `code`) VALUES
(1, '爱美网服饰', '爱美网服饰', '　┝潮流女性', 1364991852, 2013031216, 'http://clothing.lady8844.com/clothing/school/', '<div class="ArtList">[list]<div class="blank1">', 'href="[link]"', 0, 'null', '', '爱美网,lady8844.com,流行搭配-,服饰-,-', ',,,,', 0, 'http://clothing.lady8844.com/school/2013-03-18/1363596664d1192845.html', '<div class="endtext">[article]<span class="span">', 0, 'http://clothing.lady8844.com/school/2013-03-18/1363596664d1192845_[num].html', '<span id="TEXT_CONTENT" style="margin-top:3px;display:block;">[pagelist]<div class="endpage" align="right">', '<div class="pages" id="content_pagelist">[getrange]</div>', '\\/upimg', 'http://www.admin5.com/upimg', 0, 1, 0, 0),
(2, '中国男装品牌网', '中国男装品牌网', '　┝时尚男人', 1365597154, 2013031216, 'http://man.efpp.com.cn/News/NewsList-433.html', '<div class="b08">[list]class="pagination"', 'href="[link]"', 0, 'null', '<title>[article]</title>', '_,中国服装品牌网', ',', 0, 'http://www.efpp.com.cn/html/news/2013-4-3/271887.html', '<div id="fontzoom" class="newContent">[article]<div class="fenye clear">', 0, 'null', 'null', 'null', '/uploadfiles,', 'http://www.efpp.com.cn/uploadfiles,', 0, 1, 1, 1),
(3, '腾讯厦门新闻', '腾讯厦门新闻', '　┝时尚男人', 1364726128, 2013030817, 'http://fj.qq.com/news/xmxw/index.htm', 'PageSet[list]cntR', '', 0, 'http://fj.qq.com', '', '', '', 1, 'http://fj.qq.com/a/20121202/000054.htm', '', 0, '', '', '<div id="Cnt-Main-Article-QQ" bossZone="content">[detail]<div class="ft">', 'width:300px;height:250px;', '', 0, 0, 1, 0),
(4, 'QQ国际新闻', 'QQ国际新闻', '　┝潮流女性', 1364726135, 2013030817, 'http://news.qq.com/newsgj/rss_newswj.xml', '</generator>[list]</channel>', '<link>[link]</link>', 0, '', '<title>[title]</title>', '', '', 0, 'http://news.qq.com/a/20130111/000372.htm', '', 0, '', '', '<div id="Cnt-Main-Article-QQ" bossZone="content">[detail]<span style="width:0;height:0;overflow:hidden;display:block;font:0/0 Arial">', 'div,bossZone="content">', ',', 0, 0, 1, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
