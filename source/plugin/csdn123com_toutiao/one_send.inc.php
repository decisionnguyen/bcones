<?php
if (!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
if (empty($_GET['hzw_fromurl'])) {
	echo 'no';
	exit;
}
if (!isset($_G['cache']['plugin'])) {
	loadcache('plugin');
}
$args = $_G['cache']['plugin']['csdn123com_toutiao']['hzw_useuid'];
if (strpos($args, ',') === false) {
	$argsArr = array($args);
} else {
	$argsArr = explode(',', $args);
}
if (in_array($_G['uid'], $argsArr)) {
	require './source/plugin/csdn123com_toutiao/common.fun.php';
	$fromurl = urldecode($_GET['hzw_fromurl']);
	$replaynum = $_GET['hzw_replaynum'];
	if(empty($_GET['hzw_fid']))
	{
		$fid = 0;
	} else {
		$fid = $_GET['hzw_fid'];
	}
	if(empty($_GET['hzw_typeid']))
	{
		$typeid = 0;
	} else {
		$typeid = $_GET['hzw_typeid'];
	}
	if(empty($_GET['hzw_catid']))
	{
		$portal = 0;
	} else {
		$portal = $_GET['hzw_catid'];
	}	
	$first_uid = $_GET['hzw_first_uid'];
	$reply_uid = $_GET['hzw_reply_uid'];
	$weiyanchang = $_GET['hzw_weiyanchang'];
	$intval_time = $_GET['hzw_intval_time'];
	$simplified = $_GET['hzw_simplified'];
	$views = $_GET['hzw_views'];
	$forum_portal = $_GET['hzw_forum_portal'];	
	if (strpos($fromurl, 'toutiao.com') === false) {
		echo 'no1';
		exit;
	}
	$chk = DB::fetch_first("SELECT ID,tid_aid FROM " . DB::table('csdn123toutiao_news') . " WHERE fromurl='" . $fromurl . "'");
	if (count($chk) > 0 && $chk['tid_aid'] > 0) {
		echo $chk['tid_aid'];
		exit;
	}
	if (count($chk) == 0) {
		$threadValue = array();
		$threadValue['fromurl'] = daddslashes($fromurl);
		$threadValue['replaynum'] = intval($replaynum);
		$threadValue['fid'] = intval($fid);
		$threadValue['typeid'] = intval($typeid);
		$threadValue['portal'] = intval($portal);
		$threadValue['replaynum'] = intval($replaynum);
		$threadValue['first_uid'] = intval($first_uid);
		$threadValue['reply_uid'] = daddslashes($reply_uid);
		$threadValue['weiyanchang'] = daddslashes($weiyanchang);
		$threadValue['intval_time'] = intval($intval_time);
		$threadValue['simplified'] = daddslashes($simplified);
		$threadValue['views'] = intval($views);
		$threadValue['forum_portal'] = daddslashes($forum_portal);		
		$ID = DB::insert('csdn123toutiao_news', $threadValue, true);
	} else {
		$ID = $chk["ID"];
	}
	require_once './source/function/function_forum.php';
	$recode = send_thread($ID);
	if ($recode == 'ok') {
		$chk = DB::fetch_first("SELECT tid_aid FROM " . DB::table('csdn123toutiao_news') . " WHERE ID=" . intval($ID));
		if ($chk['tid_aid'] > 0) {
			echo $chk['tid_aid'];
		} else {
			echo "no2";
		}
	}
}
