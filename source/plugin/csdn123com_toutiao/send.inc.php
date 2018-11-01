<?php
if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
require './source/plugin/csdn123com_toutiao/common.fun.php';
if (empty($_GET['page']) || is_numeric($_GET['page']) === false) {
	$page = 1;
} else {
	$page = intval($_GET['page']);
	$page = max(1, $page);
}
$server_url = 'action=plugins&operation=config&do=' . $pluginid . '&identifier=csdn123com_toutiao&pmod=send';
if ($_GET['formhash'] == FORMHASH && $_GET['getrecordform'] == 'yes') {
	

	$forum_portal = daddslashes($_GET['forum_portal']);
	$fid = intval($_GET['fid']);
	$typeid = intval($_GET['threadtypeid']);
	$portal = intval($_GET['tocatid']);
	$replaynum = intval($_GET['replaynum']);
	$first_uid = daddslashes($_GET['first_uid']);
	$reply_uid = daddslashes($_GET['reply_uid']);
	$intval_time = intval($_GET['intval_time']);
	$views = intval($_GET['views']);
	$simplified = daddslashes($_GET['simplified']);
	$weiyanchang = daddslashes($_GET['weiyanchang']);
	$thread = dfsockopen("http://discuz.csdn123.net/catch/toutiao_news/new2.php");
	$thread = unserialize($thread);
	foreach ($thread as $threadValue) {
		$chk = DB::fetch_first("SELECT ID FROM " . DB::table('csdn123toutiao_news') . " WHERE fromurl='" . daddslashes($threadValue['fromurl']) . "'");
		if (empty($chk)) {
			$subject = diconv($threadValue['subject'], 'UTF-8');
			$threadValue['subject'] = daddslashes($subject);
			$threadValue['first_uid'] = $first_uid;
			$threadValue['reply_uid'] = $reply_uid;
			$threadValue['fid'] = $fid;
			$threadValue['typeid'] = $typeid;
			$threadValue['replaynum'] = $replaynum;
			$threadValue['fromurl'] = daddslashes($threadValue['fromurl']);
			$threadValue['portal'] = $portal;
			$threadValue['forum_portal'] = $forum_portal;
			$threadValue['intval_time'] = $intval_time;
			$threadValue['views'] = rand(1,$views);
			$threadValue['simplified'] = $simplified;
			$threadValue['weiyanchang'] = $weiyanchang;
			DB::insert('csdn123toutiao_news', $threadValue);
		}
	}
	cpmsg('csdn123com_toutiao:rnd_tieba_num', $server_url, 'succeed');
	
} elseif ($_GET['formhash'] == FORMHASH && $_GET['selall'] == 'yes') {
	
	if (empty($_GET['idarray'])) {
		cpmsg('csdn123com_toutiao:select_empty', '' , 'error');
	}
	if (empty($_GET['seldelete']) == false) {
		$idstr = implode(',', $_GET['idarray']);
		$idstr = daddslashes($idstr);
		DB::update('csdn123toutiao_news', array('del' => 1), 'ID in (' . $idstr . ')');
		cpmsg('csdn123com_toutiao:del_success', $server_url . '&page=' . $_GET['page'], 'succeed');
	}
	if (empty($_GET['selimport']) == false) {
		if (empty($_GET['num'])) {
			$num = 0;
		} else {
			$num = $_GET['num'];
			$num = intval($num);
		}
		if (is_array($_GET['idarray'])) {
			$idarray = $_GET['idarray'];
			$count_num = count($idarray);
			$idstr = implode(',', $_GET['idarray']);
		} else {
			$idstr = $_GET['idarray'];
			$idarray = explode(',', $idstr);
			$count_num = count($idarray);
		}
		$ID = $idarray[$num];
		if (is_numeric($ID) == false) {
			$success_Url = 'action=plugins&operation=config&do=' . $pluginid . '&identifier=csdn123com_toutiao&pmod=success';
			cpmsg('csdn123com_toutiao:catch_ok', $success_Url, 'succeed');
		}
		$recode = send_thread($ID);
		if ($recode == 'ok') {
			$num++;
			$ID = $idarray[$num];
			if (is_numeric($ID) == false) {
				$success_Url = 'action=plugins&operation=config&do=' . $pluginid . '&identifier=csdn123com_toutiao&pmod=success';
				cpmsg('csdn123com_toutiao:catch_ok', $success_Url, 'succeed');
			}
			$nextCatchUrl = '?' . $server_url . '&selimport=yes&selall=yes&formhash=' . FORMHASH . '&idarray=' . $idstr . '&num=' . $num;
			$statusStr = lang('plugin/csdn123com_toutiao', 'all_import');
			$statusStr = str_replace('count', $count_num, $statusStr);
			$statusStr = str_replace('num', $num, $statusStr);
			echo '<div style="font-size:20px;margin-top:64px;text-align:center;color:red">' . $statusStr . '</div>';
			echo '<script>setTimeout(function(){ window.location.href="' . $nextCatchUrl . '" },8000);</script>';
		} else {
			$num++;
			$ID = $idarray[$num];
			if (is_numeric($ID) == false) {
				$success_Url = 'action=plugins&operation=config&do=' . $pluginid . '&identifier=csdn123com_toutiao&pmod=success';
				cpmsg('csdn123com_toutiao:catch_ok', $success_Url, 'succeed');
			}
			$nextCatchUrl = '?' . $server_url . '&selimport=yes&selall=yes&formhash=' . FORMHASH . '&idarray=' . $idstr . '&num=' . $num;
			$statusStr = lang('plugin/csdn123com_toutiao', 'all_import_err');
			$statusStr = str_replace('num', $num, $statusStr);
			echo '<div style="font-size:20px;margin-top:64px;text-align:center;color:green">' . dhtmlspecialchars($statusStr) . '</div>';
			echo '<script>setTimeout(function(){ window.location.href="' . $nextCatchUrl . '" },5000);</script>';
		}
	}
	
} elseif ($_GET['formhash'] == FORMHASH && $_GET['getrecord'] == 'yes') {
	
	$regUser = 'action=plugins&operation=config&do=' . $pluginid . '&identifier=csdn123com_toutiao&pmod=reg_user';
	$weiyanchang = 'action=plugins&operation=config&do=' . $pluginid . '&identifier=csdn123com_toutiao&pmod=weiyanchang';	
	require_once libfile('function/forumlist');
	include_once libfile('function/portalcp');
	include template("csdn123com_toutiao:send_getrecord");
	
} elseif ($_GET['formhash'] == FORMHASH && empty($_GET['del']) == false) {
	
	$delid = intval($_GET['del']);
	DB::update('csdn123toutiao_news', array('del' => 1), 'ID=' . $delid);
	$del_backUrl = $server_url . '&page=' . $page;
	cpmsg('csdn123com_toutiao:del_success', $del_backUrl, 'succeed');
	
} elseif ($_GET['formhash'] == FORMHASH && empty($_GET['import_id']) == false && is_numeric($_GET['import_id'])) {
	
	$recode = send_thread($_GET['import_id']);
	if ($recode == 'ok') {
		
		$success_Url = 'action=plugins&operation=config&do=' . $pluginid . '&identifier=csdn123com_toutiao&pmod=success';
		cpmsg('csdn123com_toutiao:catch_ok', $success_Url, 'succeed');
		
	} else {
		
		cpmsg('csdn123com_toutiao:catch_err', '', 'error');
		
	}
	
} elseif ($_GET['formhash'] == FORMHASH && empty($_GET['update_id']) == false && is_numeric($_GET['update_id'])) {
	
	$update_id = intval($_GET['update_id']);
	$postRs = DB::fetch_first("SELECT * FROM " . DB::table("csdn123toutiao_news") . " WHERE ID=" . $update_id);
	$typeclassArr = C::t('forum_threadclass')->fetch_all_by_fid($postRs['fid']);
	$regUser = 'action=plugins&operation=config&do=' . $pluginid . '&identifier=csdn123com_toutiao&pmod=reg_user';
	$weiyanchang = 'action=plugins&operation=config&do=' . $pluginid . '&identifier=csdn123com_toutiao&pmod=weiyanchang';	
	require_once libfile('function/forumlist');
	include_once libfile('function/portalcp');
	include template("csdn123com_toutiao:send_update");
	
} elseif ($_GET['formhash'] == FORMHASH && empty($_GET['update']) == false && $_GET['update'] == 'yes') {
	
	$newsArr = array();
	$newsArr['subject'] = daddslashes($_GET['subject']);
	$newsArr['fid'] = intval($_GET['fid']);
	$newsArr['typeid'] = intval($_GET['threadtypeid']);
	$newsArr['replaynum'] = intval($_GET['replaynum']);
	$newsArr['forum_portal'] = daddslashes($_GET['forum_portal']);
	$newsArr['portal'] = intval($_GET['tocatid']);
	$newsArr['first_uid'] = daddslashes($_GET['first_uid']);
	$newsArr['reply_uid'] = daddslashes($_GET['reply_uid']);
	$newsArr['intval_time'] = intval($_GET['intval_time']);
	$newsArr['views'] = intval($_GET['views']);
	$newsArr['simplified'] = daddslashes($_GET['simplified']);
	$newsArr['weiyanchang'] = daddslashes($_GET['weiyanchang']);	
	DB::update('csdn123toutiao_news', $newsArr, 'ID=' . intval($_GET['news_id']));
	$update_backUrl = $server_url . '&page=' . $page;
	cpmsg('csdn123com_toutiao:modify_success', $update_backUrl, 'succeed');

} elseif ($_GET['formhash'] == FORMHASH && empty($_GET['sendfail']) == false && $_GET['sendfail'] == 'yes') {
	
	DB::delete('csdn123toutiao_news','tid_aid=-1');
	cpmsg('csdn123com_toutiao:sendfailok', $server_url, 'succeed');
	
} else {
	
	$startNum = ($page - 1) * 20;
	$postRs = DB::fetch_all("SELECT * FROM " . DB::table("csdn123toutiao_news") . " WHERE tid_aid<=0 and del=0 ORDER BY ID DESC LIMIT " . $startNum . ",20");
	$nextPage = $server_url . '&page=' . ($page + 1);
	$prePage = $server_url . '&page=' . ($page - 1);
	include template("csdn123com_toutiao:send");
	
}
