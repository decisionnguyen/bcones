<?php
if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
require './source/plugin/csdn123com_toutiao/common.fun.php';
if ($_GET['formhash'] == FORMHASH && empty($_GET['catch']) == false && $_GET['catch'] == 'yes') {
	
	$fromurl = daddslashes($_GET['fromurl']);
	$fromurl = preg_replace('/\?.+$/','',$fromurl);
	$chk = DB::fetch_first("SELECT ID,tid_aid,forum_portal FROM " . DB::table('csdn123toutiao_news') . " WHERE fromurl='" . $fromurl . "'");
	if (count($chk) > 0 && $chk['tid_aid'] > 0) {
		
		if($chk['forum_portal']=='forum')
		{
			$threadUrl = preview_url($chk['forum_portal'],$chk['tid_aid']);
			echo '<div style="line-height:30px;font-size:22px;">' . lang('plugin/csdn123com_toutiao', 'catch_ok') . '<br><a href="' . $threadUrl . '" target="_blank">' . $threadUrl . '</a></div>';
		} else {
			$portalUrl = preview_url($chk['forum_portal'],$chk['tid_aid']);
			echo '<div style="line-height:30px;font-size:22px;">' . lang('plugin/csdn123com_toutiao', 'catch_ok') . '<br><a href="' . $portalUrl . '" target="_blank">' . $portalUrl . '</a></div>';
		}
		exit;
	}	
	$first_uid = daddslashes($_GET['first_uid']);
	$reply_uid = daddslashes($_GET['reply_uid']);
	if (preg_match('/[a-z]/i', $first_uid) == 1 || preg_match('/[a-z]/i', $reply_uid) == 1) {
		cpmsg('csdn123com_toutiao:uid_err', '', 'error');
		exit;
	}
	if (strpos($fromurl, 'toutiao.com') === false) {
		cpmsg('csdn123com_toutiao:url_err', '', 'error');
		exit;
	}
	if (count($chk) == 0) {
		
		$threadValue['first_uid'] = $first_uid;
		$threadValue['reply_uid'] = $reply_uid;
		$threadValue['fid'] = intval($_GET['fid']);
		$threadValue['typeid'] = intval($_GET['threadtypeid']);
		$threadValue['replaynum'] = intval($_GET['replaynum']);
		$threadValue['fromurl'] = $fromurl;
		$threadValue['forum_portal'] = daddslashes($_GET['forum_portal']);
		$threadValue['portal'] = intval($_GET['tocatid']);
		$threadValue['intval_time'] = intval($_GET['intval_time']);
		$threadValue['views'] = intval($_GET['views']);
		$threadValue['simplified'] = daddslashes($_GET['simplified']);
		$threadValue['weiyanchang'] = daddslashes($_GET['weiyanchang']);
		$ID = DB::insert('csdn123toutiao_news', $threadValue, true);
		
	} else {
		
		$ID = $chk["ID"];		
	}
	$recode = send_thread($ID);
	if ($recode == 'ok') {
		DB::update('csdn123toutiao_news',array('catch_way'=>'shougong'),array('ID'=>$ID));
		$success_Url = 'action=plugins&operation=config&do=' . $pluginid . '&identifier=csdn123com_toutiao&pmod=success';
		cpmsg('csdn123com_toutiao:catch_ok', $success_Url, 'succeed');
	} else {
		cpmsg('csdn123com_toutiao:catch_err', '', 'error');
	}

} else {
	
	$server_url = 'action=plugins&operation=config&do=' . $pluginid . '&identifier=csdn123com_toutiao&pmod=catch';
	$regUser = 'action=plugins&operation=config&do=' . $pluginid . '&identifier=csdn123com_toutiao&pmod=reg_user';
	$weiyanchang = 'action=plugins&operation=config&do=' . $pluginid . '&identifier=csdn123com_toutiao&pmod=weiyanchang';	
	require_once libfile('function/forumlist');
	include_once libfile('function/portalcp');
	include template("csdn123com_toutiao:catch");
	
}
