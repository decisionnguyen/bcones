<?php
if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$server_url = 'action=plugins&operation=config&do=' . $pluginid . '&identifier=csdn123com_toutiao&pmod=weiyanchang';
if ($_GET['formhash'] == FORMHASH && empty($_GET['deluser']) == false && $_GET['deluser'] == 'yes') {
	
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

} elseif ($_GET['formhash'] == FORMHASH && empty($_GET['weiyanchang_add']) == false && $_GET['weiyanchang_add'] == 'show') {
	
	include template('csdn123com_toutiao:weiyanchang_add');
	
} elseif ($_GET['formhash'] == FORMHASH && empty($_GET['weiyanchang_import']) == false && $_GET['weiyanchang_import'] == 'show') {
	
	include template('csdn123com_toutiao:weiyanchang_import');

} elseif ($_GET['formhash'] == FORMHASH && empty($_GET['import_word']) == false && $_GET['import_word'] == 'yes') {
	
	$word_data=$_GET['word_data'];
	$word_data=base64_decode($word_data);
	$word_arr=dunserialize($word_data);
	if(is_array($word_arr)==false)
	{
		cpmsg('csdn123com_toutiao:word_data_err', '', 'error');
	}
	$word_charset=$word_arr['charset'];
	foreach($word_arr['word'] as $word_value)
	{
		$word1=diconv($word_value['word1'],$word_charset,CHARSET);
		$word2=diconv($word_value['word2'],$word_charset,CHARSET);
		$wordChk=DB::fetch_first("SELECT * FROM " . DB::table('csdn123toutiao_weiyanchang') . " WHERE word1='" . $word1 . "'");
		if(count($wordChk)==0)
		{
			$wordArr=array();
			$wordArr['word1']=$word1;
			$wordArr['word2']=$word2;
			DB::insert('csdn123toutiao_weiyanchang',$wordArr);
		
		}		
	}
	cpmsg('csdn123com_toutiao:word_add', $server_url, 'succeed');
	
	
} elseif ($_GET['formhash'] == FORMHASH && empty($_GET['weiyanchang_output']) == false && $_GET['weiyanchang_output'] == 'yes') {
	
	$weiyanchang_Data=array();
	$weiyanchang_Data['charset']=CHARSET;
	$weiyanchang_Rs=DB::fetch_all("SELECT word1,word2 FROM " . DB::table('csdn123toutiao_weiyanchang'));
	$weiyanchang_Data['word']=$weiyanchang_Rs;
	$weiyanchang_output=serialize($weiyanchang_Data);
	$weiyanchang_output=base64_encode($weiyanchang_output);
	include template('csdn123com_toutiao:weiyanchang_output');

} elseif ($_GET['formhash'] == FORMHASH && empty($_GET['delword']) == false && $_GET['delword'] == 'yes') {
	
	if(empty($_GET['idarray']))
	{	
		cpmsg('csdn123com_toutiao:select_empty', '' , 'error');		
	}
	$idstr = implode(',', $_GET['idarray']);
	$idstr = daddslashes($idstr);
	DB::delete('csdn123toutiao_weiyanchang','ID in (' . $idstr . ')');	
	cpmsg('csdn123com_toutiao:word_del_success',$server_url . '&page=' . $_GET['page'], 'succeed');

} elseif ($_GET['formhash'] == FORMHASH && empty($_GET['addword']) == false && $_GET['addword'] == 'yes') {
	
	if(empty($_GET['word1']) || empty($_GET['word2']))
	{
		cpmsg('csdn123com_toutiao:word_add_err', '', 'error');
	}
	$word1=daddslashes($_GET['word1']);
	$word2=daddslashes($_GET['word2']);	
	$wordChk=DB::fetch_first("SELECT * FROM " . DB::table('csdn123toutiao_weiyanchang') . " WHERE word1='" . $word1 . "'");
	if(count($wordChk)==0)
	{
		$wordArr=array();
		$wordArr['word1']=$word1;
		$wordArr['word2']=$word2;
		DB::insert('csdn123toutiao_weiyanchang',$wordArr);
		cpmsg('csdn123com_toutiao:word_add', $server_url, 'succeed');
		
	} else {
		
		cpmsg('csdn123com_toutiao:word_add_err', '', 'error');
		
	}	

} elseif ($_GET['formhash'] == FORMHASH && empty($_GET['modify_id']) == false && is_numeric($_GET['modify_id']) == true) {
	
	$modify_rs=DB::fetch_first("SELECT * FROM " . DB::table('csdn123toutiao_weiyanchang') . " WHERE ID=" . $_GET['modify_id']);
	include template('csdn123com_toutiao:weiyanchang_modify');

} elseif ($_GET['formhash'] == FORMHASH && empty($_GET['modify_submit']) == false && $_GET['modify_submit'] == 'yes') {
	
	if(empty($_GET['word1']) || empty($_GET['word2']))
	{
		cpmsg('csdn123com_toutiao:word_add_err', '', 'error');
	}
	$ID=intval($_GET['ID']);
	$word1=daddslashes($_GET['word1']);
	$word2=daddslashes($_GET['word2']);
	$wordArr=array();
	$wordArr['word1']=$word1;
	$wordArr['word2']=$word2;
	DB::update('csdn123toutiao_weiyanchang',$wordArr,'ID=' . $ID);
	cpmsg('csdn123com_toutiao:word_modify_success', $server_url . '&page=' . $page, 'succeed');

} elseif ($_GET['formhash'] == FORMHASH && empty($_GET['weiyanchang_clear']) == false && $_GET['weiyanchang_clear'] == 'yes') {
	
	DB::delete('csdn123toutiao_weiyanchang','ID>0');
	cpmsg('csdn123com_toutiao:weiyanchang_clear',$server_url , 'succeed');
		
} else {
	
	if(empty($_GET['page']) || is_numeric($_GET['page'])==false)
	{
		$page=1;
	} else {
		$page=$_GET['page'];
	}
	$page=max(1,$page);
	$nextPage=$server_url . '&page=' . ($page+1);
	$prePage=$server_url . '&page=' . ($page-1);
	$startNum=($page - 1) * 30;
	$weiyanchang_list = DB::fetch_all('SELECT * FROM ' . DB::table('csdn123toutiao_weiyanchang') . ' ORDER BY ID DESC limit ' . $startNum . ' ,30');
	include template('csdn123com_toutiao:weiyanchang_list');
	
}
