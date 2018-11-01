<?php
if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
require './source/plugin/csdn123com_toutiao/common.fun.php';
$server_url = 'action=plugins&operation=config&do=' . $pluginid . '&identifier=csdn123com_toutiao&pmod=cron';
if ($_GET['formhash'] == FORMHASH && empty($_GET['cron']) == false && $_GET['cron'] == 'yes') {
	
	$keyword = daddslashes($_GET['keyword']);
	$forum_portal = daddslashes($_GET['forum_portal']);
	$fid = intval($_GET['fid']);
	$threadtypeid = intval($_GET['threadtypeid']);
	$portal = intval($_GET['tocatid']);
	$replaynum = intval($_GET['replaynum']);
	$first_uid = daddslashes($_GET['first_uid']);
	$reply_uid = daddslashes($_GET['reply_uid']);
	$intval_time = intval($_GET['intval_time']);
	$views = intval($_GET['views']);
	$simplified = daddslashes($_GET['simplified']);
	$weiyanchang = daddslashes($_GET['weiyanchang']);
	if (preg_match('/[a-z]/i', $first_uid) == 1 || preg_match('/[a-z]/i', $reply_uid) == 1) {
		cpmsg('csdn123com_toutiao:uid_err', '', 'error');
		exit;
	}
	if (strlen($keyword) < 2) {
		cpmsg('csdn123com_toutiao:keyword_empty', '', 'error');
		exit;
	}
	$cronArr = array();
	$cronArr['keyword'] = $keyword;
	$cronArr['forum_portal'] = $forum_portal;
	$cronArr['fid'] = $fid;
	$cronArr['typeid'] = $threadtypeid;
	$cronArr['portal'] = $portal;
	$cronArr['replaynum'] = $replaynum;
	$cronArr['first_uid'] = $first_uid;
	$cronArr['reply_uid'] = $reply_uid;
	$cronArr['intval_time'] = $intval_time;
	$cronArr['views'] = $views;
	$cronArr['simplified'] = $simplified;
	$cronArr['weiyanchang'] = $weiyanchang;
	DB::insert('csdn123toutiao_cron', $cronArr);
	cpmsg('csdn123com_toutiao:cron_ok', $server_url, 'succeed');
	
} elseif ($_GET['formhash'] == FORMHASH && empty($_GET['cron_add']) == false && $_GET['cron_add'] == 'yes') {
	
	$regUser = 'action=plugins&operation=config&do=' . $pluginid . '&identifier=csdn123com_toutiao&pmod=reg_user';
	$weiyanchang = 'action=plugins&operation=config&do=' . $pluginid . '&identifier=csdn123com_toutiao&pmod=weiyanchang';	
	require_once libfile('function/forumlist');
	include_once libfile('function/portalcp');
	include template("csdn123com_toutiao:cron_add");
	
} elseif ($_GET['formhash'] == FORMHASH && empty($_GET['modify']) == false && is_numeric($_GET['modify']) == true) {
	
	$modify_id = intval($_GET['modify']);
	$postRs = DB::fetch_first("SELECT * FROM " . DB::table("csdn123toutiao_cron") . " WHERE ID=" . $modify_id);
	$typeclassArr = C::t('forum_threadclass')->fetch_all_by_fid($postRs['fid']);
	$regUser = 'action=plugins&operation=config&do=' . $pluginid . '&identifier=csdn123com_toutiao&pmod=reg_user';
	$weiyanchang = 'action=plugins&operation=config&do=' . $pluginid . '&identifier=csdn123com_toutiao&pmod=weiyanchang';	
	require_once libfile('function/forumlist');
	include_once libfile('function/portalcp');
	include template("csdn123com_toutiao:cron_modify");
	
} elseif ($_GET['formhash'] == FORMHASH && empty($_GET['modifysmt']) == false && $_GET['modifysmt'] == 'yes') {
	
	$cronArr = array();
	$cronArr['keyword'] = daddslashes($_GET['keyword']);
	$cronArr['fid'] = intval($_GET['fid']);
	$cronArr['typeid'] = intval($_GET['threadtypeid']);
	$cronArr['replaynum'] = intval($_GET['replaynum']);	
	$cronArr['forum_portal'] = daddslashes($_GET['forum_portal']);
	$cronArr['portal'] = intval($_GET['tocatid']);
	$cronArr['first_uid'] = daddslashes($_GET['first_uid']);
	$cronArr['reply_uid'] = daddslashes($_GET['reply_uid']);
	$cronArr['intval_time'] = intval($_GET['intval_time']);
	$cronArr['views'] = intval($_GET['views']);
	$cronArr['simplified'] = daddslashes($_GET['simplified']);
	$cronArr['weiyanchang'] = daddslashes($_GET['weiyanchang']);	
	DB::update('csdn123toutiao_cron', $cronArr, 'ID=' . intval($_GET['modify_update_id']));
	cpmsg('csdn123com_toutiao:modify_success', $server_url, 'succeed');
	
} elseif ($_GET['formhash'] == FORMHASH && empty($_GET['del']) == false && is_numeric($_GET['del']) == true) {
	
	DB::delete('csdn123toutiao_cron', 'ID=' . intval($_GET['del']));
	cpmsg('csdn123com_toutiao:cron_del_ok', $server_url, 'succeed');
	
} else {
	
	if (!isset($_G['cache']['plugin'])) {
		loadcache('plugin');
	}
	$hzw_startcron = $_G['cache']['plugin']['csdn123com_toutiao']['hzw_startcron'];
	if($hzw_startcron != 1)
	{
		echo '<div style="text-align:center;margin:64px;"><a href="?action=plugins&operation=config&do=' . $pluginid . '" style="font-size:24px;color:red;">' . lang('plugin/csdn123com_toutiao', 'open_cron') . '</a></div>';
		
	} else {
		
		$csdn123_cronid=DB::fetch_first("select * from " . DB::table('common_cron') . " where filename='csdn123com_toutiao:cron_toutiao.inc.php'");
		$csdn123_cronUrl=$_G['siteurl'] . ADMINSCRIPT . '?action=misc&operation=cron&edit=' . $csdn123_cronid['cronid'];
		$cronUrl = $_G['siteurl'] . 'plugin.php?id=csdn123com_toutiao';
		$postRs = DB::fetch_all("SELECT * FROM " . DB::table("csdn123toutiao_cron") . " ORDER BY  catchnum DESC");
		include template("csdn123com_toutiao:cron_list");
	
	}
}
