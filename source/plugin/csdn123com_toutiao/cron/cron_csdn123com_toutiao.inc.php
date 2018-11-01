<?php

//cronname:csdn123com_toutiao
//week:
//day:
//hour:
//minute:0,5,10,15,20,25,30,35,40,45,50,55

if(!defined('IN_DISCUZ')) {

	exit('Access Denied');
}

$cronUrl = $_G['siteurl'] . 'plugin.php?id=csdn123com_toutiao';
dfsockopen($cronUrl);


?>