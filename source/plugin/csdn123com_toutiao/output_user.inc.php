<?php
if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$user_list = DB::fetch_all('select * from ' . DB::table('csdn123toutiao_reguser') . ' order by uid desc');
foreach ($user_list as $uidvalue) {
	$uidstr = $uidvalue['uid'] . ',' . $uidstr;
}
$uidstr = substr($uidstr, 0, -1);
include template('csdn123com_toutiao:output_user');
