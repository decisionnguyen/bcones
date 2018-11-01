<?php

if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

runquery('DROP TABLE IF EXISTS `pre_csdn123toutiao_news`');
runquery('DROP TABLE IF EXISTS `pre_csdn123toutiao_cron`');
runquery('DROP TABLE IF EXISTS `pre_csdn123toutiao_reguser`');
runquery('DROP TABLE IF EXISTS `pre_csdn123toutiao_weiyanchang`');
runquery('DROP TABLE IF EXISTS `pre_csdn123toutiao_words`');

$sql = <<<EOF

CREATE TABLE IF NOT EXISTS `pre_csdn123toutiao_news` (

  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) DEFAULT NULL,
  `forum_portal` varchar(50) DEFAULT NULL,
  `fid` int(11) DEFAULT NULL,
  `threadtypeid` int(11) DEFAULT NULL,
  `portal_catid` int(11) DEFAULT NULL,
  `first_uid` varchar(500) DEFAULT NULL,
  `reply_uid` varchar(500) DEFAULT NULL,
  `display_link` int(11) DEFAULT NULL,
  `replaynum` int(11) DEFAULT '0',
  `intval_time` int(11) DEFAULT NULL,
  `image_localized` int(11) DEFAULT NULL,
  `image_center` int(11) DEFAULT NULL,
  `filter_image` int(11) DEFAULT NULL,
  `pseudo_original` int(11) DEFAULT NULL,
  `chinese_encoding` int(11) DEFAULT NULL,
  `views` int(11) DEFAULT NULL,
  `source_link` varchar(500) DEFAULT NULL,
  `release_time` int(11) DEFAULT NULL,
  `group_fid` int(11) DEFAULT NULL,
  `tid_aid` int(11) DEFAULT '0',
  `del` int(11) DEFAULT '0',
  `send_datetime` int(11) DEFAULT '0',
  `catch_way` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `title` (`title`(250)),
  UNIQUE KEY `source_link` (`source_link`(250))

) ENGINE=MyISAM;

EOF;

runquery($sql);



$sql2 = <<<EOF

CREATE TABLE IF NOT EXISTS `pre_csdn123toutiao_cron` (

  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(500) DEFAULT NULL,
  `forum_portal` varchar(50) DEFAULT NULL,
  `fid` int(11) DEFAULT NULL,
  `threadtypeid` int(11) DEFAULT NULL,
  `portal_catid` int(11) DEFAULT NULL,
  `first_uid` varchar(500) DEFAULT NULL,
  `reply_uid` varchar(500) DEFAULT NULL,
  `replaynum` int(11) DEFAULT '0',
  `intval_time` int(11) DEFAULT NULL,
  `display_link` int(11) DEFAULT NULL,
  `image_localized` int(11) DEFAULT NULL,
  `image_center` int(11) DEFAULT NULL,
  `filter_image` int(11) DEFAULT NULL,
  `pseudo_original` int(11) DEFAULT NULL,
  `chinese_encoding` int(11) DEFAULT NULL,
  `views` int(11) DEFAULT NULL,
  `group_fid` int(11) DEFAULT NULL,
  `catchnum` int(11) DEFAULT '0',
  `catchtime` int(11) DEFAULT '0',
  PRIMARY KEY (`ID`)

) ENGINE=MyISAM;

EOF;

runquery($sql2);

$sql3 = <<<EOF

CREATE TABLE IF NOT EXISTS `pre_csdn123toutiao_reguser` (

  `uid` int(11) NOT NULL DEFAULT '0',
  `username` varchar(200) DEFAULT NULL,
  `username_pwd` varchar(50) DEFAULT NULL,
  `username_mail` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`uid`)

) ENGINE=MyISAM;

EOF;

runquery($sql3);

$sql4 = <<<EOF

CREATE TABLE IF NOT EXISTS `pre_csdn123toutiao_weiyanchang` (

  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `word1` varchar(255) DEFAULT NULL,
  `word2` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `word1` (`word1`)

) ENGINE=MyISAM;

EOF;

runquery($sql4);

$sql5 = <<<EOF

CREATE TABLE IF NOT EXISTS `pre_csdn123toutiao_words` (

  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `word_str` varchar(200) DEFAULT NULL,
  `orderby_num` int(11) DEFAULT '0',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `keyword` (`word_str`)

) ENGINE=MyISAM;

EOF;

runquery($sql5);

$finish = TRUE;

