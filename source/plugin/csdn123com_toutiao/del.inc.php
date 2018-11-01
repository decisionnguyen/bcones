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
require './source/plugin/csdn123com_toutiao/common.fun.php';
$server_url = 'action=plugins&operation=config&do=' . $pluginid . '&identifier=csdn123com_toutiao&pmod=del';
if ($_GET['formhash'] == FORMHASH && empty($_GET['canceldel']) == false) {
	
	$delid = intval($_GET['canceldel']);
	DB::update('csdn123toutiao_news', array('del' => 0), 'ID=' . $delid);
	$canceldel_url = $server_url . '&page=' . $page;
	cpmsg('csdn123com_toutiao:caceldel_success', $canceldel_url, 'succeed');
	
} elseif ($_GET['formhash'] == FORMHASH && empty($_GET['del']) == false) {
	
	$delid = intval($_GET['del']);
	DB::delete('csdn123toutiao_news', 'ID=' . $delid);
	$del_backurl = $server_url . '&page=' . $page;
	cpmsg('csdn123com_toutiao:del2_success', $del_backurl, 'succeed');
	
} elseif ($_GET['formhash'] == FORMHASH && empty($_GET['seldelsmt']) == false) {
	
	if (empty($_GET['clearall']) == false) {
		
		DB::delete('csdn123toutiao_news','del=1');
		cpmsg('csdn123com_toutiao:cleardelok', $server_url, 'succeed');
		
	}
	if (empty($_GET['idarray'])) {
		cpmsg('csdn123com_toutiao:select_empty', '', 'error');
	}
	if (empty($_GET['seldelete']) == false) {
		$idstr = implode(',', $_GET['idarray']);
		$idstr = daddslashes($idstr);
		DB::delete('csdn123toutiao_news', 'ID in (' . $idstr . ')');
		cpmsg('csdn123com_toutiao:del_send', $server_url . '&page=' . $_GET['page'], 'succeed');
	}
	if (empty($_GET['selcancel']) == false) {
		$idstr = implode(',', $_GET['idarray']);
		$idstr = daddslashes($idstr);
		DB::update('csdn123toutiao_news', array('del' => 0), 'ID in (' . $idstr . ')');
		cpmsg('csdn123com_toutiao:caceldel_success', $server_url . '&page=' . $_GET['page'], 'succeed');
	}
	
} else {
	
	$startNum = ($page - 1) * 20;
	$postRs = DB::fetch_all("SELECT * FROM " . DB::table("csdn123toutiao_news") . " WHERE tid_aid=0 and del=1 ORDER BY ID DESC LIMIT " . $startNum . ",20");
	$nextPage = $server_url . '&page=' . ($page + 1);
	$prePage = $server_url . '&page=' . ($page - 1);
	include template("csdn123com_toutiao:del");
	
}
