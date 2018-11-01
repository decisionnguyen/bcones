<?php

//cronname:www_csdn123_net_toutiao
//week:
//day:
//hour:
//minute:0,10,20,30,40,50

if(!defined('IN_DISCUZ')) {

	exit('Access Denied');
}

$cronUrl = $_G['siteurl'] . 'plugin.php?id=csdn123com_toutiao';
dfsockopen($cronUrl);


?>