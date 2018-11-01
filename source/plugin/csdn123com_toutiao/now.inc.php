<?php
if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
require './source/plugin/csdn123com_toutiao/common.fun.php';
$server_url = 'action=plugins&operation=config&do=' . $pluginid . '&identifier=csdn123com_toutiao&pmod=now';
if ($_GET['formhash'] == FORMHASH && empty($_GET['search']) == false) {
	
	if (empty($_GET['keyword'])) {
		cpmsg('csdn123com_toutiao:keyword_empty', '', 'error');
	}
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
	$rndpage = intval($_GET['rndpage']);
	$keyword = diconv($keyword, CHARSET, 'UTF-8');
	$keyword = urlencode($keyword);
	if ($rndpage == 1) {
		$pn = rand(1, 10);
		$pn = 20 * $pn;
		$baiduCatch = "http://www.toutiao.com/search_content/?offset={$pn}&format=json&keyword={$keyword}&autoload=true&count=20&cur_tab=1";
	} else {
		$baiduCatch = "http://www.toutiao.com/search_content/?offset=0&format=json&keyword={$keyword}&autoload=true&count=20&cur_tab=1";
	}
	$htmlcode = dfsockopen($baiduCatch);
	if (strlen($htmlcode) < 200) {
		$htmlcode = dfsockopen($baiduCatch, 0, '', '', FALSE, '', 15, TRUE, 'URLENCODE', FALSE);
	}
	$htmlcode = base64_encode($htmlcode);
	$htmlcode = dfsockopen('http://discuz.csdn123.net/catch/toutiao_news/now.catch.php', 0, array('htmlcode' => $htmlcode));
	$htmlcode = preg_replace('/^\s+|\s+$/', '', $htmlcode);
	$htmlcode = base64_decode($htmlcode);
	$linkArr = dunserialize($htmlcode);
	if (is_array($linkArr) == true && count($linkArr) > 0) {
		foreach ($linkArr as $linkValue) {
			$chk = DB::fetch_first("SELECT ID FROM " . DB::table('csdn123toutiao_news') . " WHERE fromurl='" . daddslashes($linkValue['href']) . "'");
			if (empty($chk)) {
				$linkValue['text'] = diconv($linkValue['text'], 'UTF-8');
				$insertData = array();
				$insertData['fromurl'] = $linkValue['href'];
				$insertData['subject'] = $linkValue['text'];
				$insertData['forum_portal'] = $forum_portal;
				$insertData['fid'] = $fid;
				$insertData['typeid'] = $threadtypeid;
				$insertData['replaynum'] = $replaynum;
				$insertData['portal'] = $portal;
				$insertData['first_uid'] = $first_uid;
				$insertData['reply_uid'] = $reply_uid;
				$insertData['intval_time'] = $intval_time;
				$insertData['views'] = rand(1,$views);
				$insertData['simplified'] = $simplified;
				$insertData['weiyanchang'] = $weiyanchang;				
				DB::insert('csdn123toutiao_news', $insertData);
			}
		}
		$now_catch_ok_url = 'action=plugins&operation=config&do=' . $pluginid . '&identifier=csdn123com_toutiao&pmod=send';
		cpmsg('csdn123com_toutiao:now_catch_ok', $now_catch_ok_url, 'succeed');
	} else {
		cpmsg('csdn123com_toutiao:linkarr_err', '', 'error');
	}
	
} else {
	
	$regUser = 'action=plugins&operation=config&do=' . $pluginid . '&identifier=csdn123com_toutiao&pmod=reg_user';
	$weiyanchang = 'action=plugins&operation=config&do=' . $pluginid . '&identifier=csdn123com_toutiao&pmod=weiyanchang';	
	require_once libfile('function/forumlist');
	include_once libfile('function/portalcp');
	include template("csdn123com_toutiao:now");
	
}
