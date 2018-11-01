<?php
if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
if (empty($_GET['page']) || is_numeric($_GET['page']) === false) {
	$page = 1;
} else {
	$page = intval($_GET['page']);
	$page = max(1, $page);
}
$server_url = 'action=plugins&operation=config&do=' . $pluginid . '&identifier=csdn123com_toutiao&pmod=admin_user';
if ($_GET['formhash'] == FORMHASH && empty($_GET['deluser']) == false && $_GET['deluser'] == 'yes') {
	
	if(empty($_GET['uidarray']))
	{
		cpmsg('csdn123com_toutiao:select_empty', '' , 'error');
	}
	$uidarray = $_GET['uidarray'];
	foreach ($uidarray as $uid) {
		DB::delete('csdn123toutiao_reguser', 'uid=' . intval($uid));
	}
	include template('csdn123com_toutiao:delete_user_list');
	
} elseif ($_GET['formhash'] == FORMHASH && empty($_GET['modifypic']) == false && is_numeric($_GET['modifypic']) == true) {
	
	loaducenter();
	$avatarhtml = uc_avatar($_GET['modifypic']);
	include template('csdn123com_toutiao:uc_avatar');
	
} elseif ($_GET['formhash'] == FORMHASH && empty($_GET['output']) == false && $_GET['output'] == 'yes') {
	
	$uidarray = DB::fetch_all('select uid from ' . DB::table('csdn123toutiao_reguser') . ' order by uid desc');
	foreach ($uidarray as $uidvalue) {
		$uidstr = $uidvalue['uid'] . ',' . $uidstr;
	}
	$uidstr = substr($uidstr, 0, -1);
	include template('csdn123com_toutiao:output_user_list');
	
} else {
	
	$startNum = ($page - 1) * 20;
	$user_list = DB::fetch_all('SELECT * FROM ' . DB::table('csdn123toutiao_reguser') . ' ORDER BY uid DESC LIMIT ' . $startNum . ',20');
	$nextPage = $server_url . '&page=' . ($page + 1);
	$prePage = $server_url . '&page=' . ($page - 1);
	include template('csdn123com_toutiao:admin_user');
}
