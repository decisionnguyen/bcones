<?php
if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$server_url = 'action=plugins&operation=config&do=' . $pluginid . '&identifier=csdn123com_toutiao&pmod=keyword';
if ($_GET['formhash'] == FORMHASH && empty($_GET['keyword_add']) == false && $_GET['keyword_add'] == 'yes') {
	
	if(empty($_GET['keyword']) || trim($_GET['keyword'])=='')
	{
		cpmsg('csdn123com_toutiao:keywords_empty', '', 'error');
	} else {
		$keyword=trim($keyword);
		$keyword=daddslashes($_GET['keyword']);	
	}
	if(empty($_GET['orderby_num']) || is_numeric($_GET['orderby_num'])==false)
	{
		$orderby_num=0;
	} else {
		$orderby_num=intval($_GET['orderby_num']);
	}
	$keywordSql="REPLACE INTO " . DB::table('csdn123toutiao_words') . "(word_str,orderby_num)  VALUES('" . $keyword . "'," . $orderby_num . ")";
	DB::query($keywordSql);
	cpmsg('csdn123com_toutiao:succeed', $server_url, 'succeed');

} elseif($_GET['formhash'] == FORMHASH && empty($_GET['clears_all']) == false && $_GET['clears_all'] == 'yes') {
	
	DB::delete('csdn123toutiao_words','ID>0');
	cpmsg('csdn123com_toutiao:succeed', $server_url, 'succeed');

} elseif($_GET['formhash'] == FORMHASH && empty($_GET['hot_keyword']) == false && $_GET['hot_keyword'] == 'yes') {
	
	$keywordUrl="http://top.baidu.com/";
	$htmlcode = dfsockopen($keywordUrl);
	if (strlen($htmlcode) < 100) {
		$htmlcode = dfsockopen($keywordUrl, 0, '', '', FALSE, '', 15, TRUE, 'URLENCODE', FALSE);
	}
	if (strlen($htmlcode) < 100) {
		cpmsg("csdn123com_toutiao:hot_keyword_error","","error");
	}
	$remoteUrl = array();
	$remoteUrl['SN'] = '2017110810hVvSm91ynM';
	$remoteUrl['RevisionID'] = '72572';
	$remoteUrl['RevisionDateline'] = '1510020002';
	$remoteUrl['SiteUrl'] = 'http://www.xxyd.com/';
	$remoteUrl['ClientUrl'] = 'http://www.xxyd.com/';
	$remoteUrl['SiteID'] = '60800A68-6CB1-C77F-812B-F6B102544472';
	$remoteUrl['QQID'] = 'F054243E-3FCB-F389-B960-24A25627C504';
	$remoteUrl['safecode'] = '767b9a5a4488aa2acc7f147b4354dd2b';
	$remoteUrl['SiteUrl2'] = $_G['siteurl'];
	$remoteUrl['ip'] = $_SERVER['REMOTE_ADDR'];
	$remoteUrl['url'] = $source_link;
	$remoteUrl['siteur1'] = $_SERVER['HTTP_HOST'];
	$fetchUrl = "http://www.csdn123.net/zd_version/zd9/hot_keyword.php";
	$htmlcode = base64_encode($htmlcode);
	$remoteUrl['htmlcode'] = $htmlcode;
	$htmlcode = dfsockopen($fetchUrl, 0, $remoteUrl);
	if (strlen($htmlcode) < 100) {
		cpmsg("csdn123com_toutiao:hot_keyword_error","","error");
	}
	$htmlcode = preg_replace('/^\s+|\s+$/', '', $htmlcode);
	$htmlcode = dunserialize($htmlcode);
	if (is_array($htmlcode) === false) {
		cpmsg("csdn123com_toutiao:hot_keyword_error","","error");
	}
	foreach($htmlcode as $keyword)
	{
		$keyword = diconv($keyword,'UTF-8');
		$keywordSql="REPLACE INTO " . DB::table('csdn123toutiao_words') . "(word_str)  VALUES('" . $keyword . "')";
		DB::query($keywordSql);
	}
	cpmsg('csdn123com_toutiao:succeed', $server_url, 'succeed');		
	
} elseif($_GET['formhash'] == FORMHASH && empty($_GET['delid']) == false && is_numeric($_GET['delid']) == true) {
	
	$id=intval($_GET['delid']);
	DB::delete('csdn123toutiao_words','ID=' . $id);
	cpmsg('csdn123com_toutiao:succeed', $server_url, 'succeed');

} elseif($_GET['formhash'] == FORMHASH && empty($_GET['keyword_update']) == false && $_GET['keyword_update'] == 'yes') {
	
	foreach($_GET['ids'] as $id)
	{
		$word_str='keyword' . $id;
		$word_str=$_GET[$word_str];
		$orderby_num='orderby_num' . $id;
		$orderby_num=$_GET[$orderby_num];
		$keywordArr=array();
		$keywordArr['word_str']=daddslashes($word_str);
		$keywordArr['orderby_num']=daddslashes($orderby_num);
		DB::update('csdn123toutiao_words',$keywordArr,'ID=' . $id);		
	}
	cpmsg('csdn123com_toutiao:succeed', $server_url, 'succeed');
	
} else {
	
	$keyword_list = DB::fetch_all('SELECT * FROM ' . DB::table('csdn123toutiao_words') . ' ORDER BY orderby_num ASC,ID DESC');
	include template('csdn123com_toutiao:keyword');
	
}
