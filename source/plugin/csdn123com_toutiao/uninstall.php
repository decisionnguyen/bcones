<?php

if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

runquery('DROP TABLE IF EXISTS `pre_csdn123toutiao_news`');
runquery('DROP TABLE IF EXISTS `pre_csdn123toutiao_cron`');
runquery('DROP TABLE IF EXISTS `pre_csdn123toutiao_reguser`');
runquery('DROP TABLE IF EXISTS `pre_csdn123toutiao_weiyanchang`');
runquery('DROP TABLE IF EXISTS `pre_csdn123toutiao_words`');

$finish = true;

