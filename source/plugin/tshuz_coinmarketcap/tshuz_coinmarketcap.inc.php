<?php

/**
 *      (C)2001-2099 DiscuzLab.com.
 *      This is NOT a freeware, use is subject to license terms
 *      $Id: tshuz_coinmarketcap.inc.php 2018-01-09 13:45:43Z Todd $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

/* 插件代码开始 */
$pvars = $_G['cache']['plugin']['tshuz_coinmarketcap'];
if(!$pvars['guest'] && !$_G['uid']) showmessage('to_login', null, array(), array('showmsg' => true, 'login' => 1));
$pvars['covert'] = strtolower($pvars['covert']);
if($_GET['mod'] == 'view' && $_GET['coin']){
	$_GET['coin'] = str_replace(' ','-',urldecode($_GET['coin']));
	$extend = DISCUZ_ROOT.'./source/plugin/tshuz_coinmarketcap/extend/view.php';
	if(!file_exists($extend)){
		showmessage(lang('plugin/tshuz_coinmarketcap','nlvB6e'));
	}
	$jqSrc = file_exists(DISCUZ_ROOT.'./static/js/mobile/jquery-1.8.3.min.js') ? '-1.8.3' :'';
	include $extend;
}elseif($_GET['mod'] == 'chart'){
	$extend = DISCUZ_ROOT.'./source/plugin/tshuz_coinmarketcap/extend/chart.php';
	if(!file_exists($extend)){
		showmessage(lang('plugin/tshuz_coinmarketcap','nlvB6e'));
	}
	include $extend;
}else{
	$navtitle = lang('plugin/tshuz_coinmarketcap','QJp3Cj');
	$jqSrc = file_exists(DISCUZ_ROOT.'./static/js/mobile/jquery-1.8.3.min.js') ? '-1.8.3' :'';
	include template("tshuz_coinmarketcap:list");
}
?>