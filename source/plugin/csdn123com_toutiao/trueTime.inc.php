<?php
if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$server_url='action=plugins&operation=config&do=' . $pluginid . '&identifier=csdn123com_toutiao&pmod=trueTime';
require './source/plugin/csdn123com_toutiao/function_common.php';
if($_GET['formhash'] == FORMHASH && !empty($_GET['csdn123_agrs']) && $_GET['csdn123_agrs']=='yes')
{
	if(empty($_GET['keyword']))
	{
		cpmsg("csdn123com_toutiao:keywords_empty","","error");
	} else {
		$keyword=$_GET['keyword'];
		$keyword=diconv($keyword,CHARSET,"UTF-8");
		$keyword=urlencode($keyword);
	}
	if(empty($_GET['first_uid']))
	{
		cpmsg("csdn123com_toutiao:uid_error","","error");
	} else {
		$first_uid=$_GET['first_uid'];
	}
	if(empty($_GET['reply_uid']))
	{
		cpmsg("csdn123com_toutiao:uid_error","","error");
	} else {
		$reply_uid=$_GET['reply_uid'];
	}	
	if(preg_match('/[a-z]/i',$first_uid)==1 || preg_match('/[a-z]/i',$reply_uid)==1)
	{
		cpmsg("csdn123com_toutiao:uid_error","","error");
	}
	if(empty($_GET['page_number']) || !is_numeric($_GET['page_number']))
	{
		$page_number=1;
	} else {
		$page_number=$_GET['page_number'];
	}
	$release_time=$_GET['release_time'];
	$release_time=strtotime($release_time);
	if(is_numeric($release_time)==FALSE || $release_time<10000)
	{
		$release_time_start=time() - 3600;
		$release_time=rand($release_time_start,time());
	}
	$page_number--;
	$page_number = 20 * $page_number;
	$toutiaoUrl = "https://www.toutiao.com/search_content/?offset={$page_number}&format=json&keyword={$keyword}&autoload=true&count=20&cur_tab=1";	
	$htmlcode = dfsockopen($toutiaoUrl);
    if (strlen($htmlcode) < 200) {
        $htmlcode = dfsockopen($toutiaoUrl, 0, '', '', false, '', 15, true, 'URLENCODE', false);
    }
    $htmlcode = base64_encode($htmlcode);
	$api_server="http://discuz.csdn123.net/catch/toutiao201711/trueTime.php";
	$api_server_parameter=array();
	$api_server_parameter['SN'] = '2017110810hVvSm91ynM';
	$api_server_parameter['RevisionID'] = '72572';
	$api_server_parameter['RevisionDateline'] = '1510020002';
	$api_server_parameter['SiteUrl'] = 'http://www.xxyd.com/';
	$api_server_parameter['ClientUrl'] = 'http://www.xxyd.com/';
	$api_server_parameter['SiteID'] = '60800A68-6CB1-C77F-812B-F6B102544472';
	$api_server_parameter['siteuri'] = $_SERVER['HTTP_REFERER'];
	$api_server_parameter['QQID'] = 'F054243E-3FCB-F389-B960-24A25627C504';
	$api_server_parameter['S1teurl'] = $_SERVER['SERVER_NAME'];
	$api_server_parameter['safecode'] = '767b9a5a4488aa2acc7f147b4354dd2b';
	$api_server_parameter['SlteUrl'] = $_G['siteurl'];
	$api_server_parameter['ip'] = $_SERVER['REMOTE_ADDR'];
	$api_server_parameter['siteur1'] = 'http://' . $_SERVER['HTTP_HOST'];
	$api_server_parameter['htmlcode'] = $htmlcode;
	$htmlcode = dfsockopen($api_server,0,$api_server_parameter);
	if (strlen($htmlcode) < 50) {
		$htmlcode = dfsockopen($api_server, 0, $api_server_parameter, '', FALSE, '', 15, TRUE, 'URLENCODE', FALSE);
	}
	if (strlen($htmlcode) < 50) {
		cpmsg("csdn123com_toutiao:collection_result_null","","error");
	}
	$htmlcode = preg_replace('/^\s+|\s+$/', '', $htmlcode);
	$htmlcode = base64_decode($htmlcode);
	$resultLink = dunserialize($htmlcode);
	if(is_array($resultLink)==FALSE)
	{
		cpmsg("csdn123com_toutiao:collection_result_null","","error");
	}
	$forum_portal=daddslashes($_GET['forum_portal']);
	$fid=intval($_GET['fid']);
	$threadtypeid=intval($_GET['threadtypeid']);
	$portal_catid=intval($_GET['portal_catid']);
	$display_link=intval($_GET['display_link']);
	$image_localized=intval($_GET['image_localized']);
	$pseudo_original=intval($_GET['pseudo_original']);
	$chinese_encoding=intval($_GET['chinese_encoding']);
	$group_fid=intval($_GET['group_fid']);
	$replaynum=intval($_GET['replaynum']);
	$first_uid=daddslashes($_GET['first_uid']);
	$reply_uid=daddslashes($_GET['reply_uid']);
	$intval_time=intval($_GET['intval_time']);
	$filter_image=intval($_GET['filter_image']);
	$image_center=intval($_GET['image_center']);
	$views=intval($_GET['views']);
	foreach($resultLink as $resultLinkItem)
	{
		$newsArr=array();
		$title=diconv($resultLinkItem['title'],'UTF-8',CHARSET);
		$newsArr['title']=daddslashes($title);
		$source_link=$resultLinkItem['url'];
		$newsArr['source_link']=daddslashes($source_link);
		$newsArr['forum_portal']=$forum_portal;
		$newsArr['fid']=$fid;
		$newsArr['threadtypeid']=$threadtypeid;
		$newsArr['portal_catid']=$portal_catid;
		$newsArr['first_uid']=$first_uid;
		$newsArr['reply_uid']=$reply_uid;
		$newsArr['display_link']=$display_link;
		$newsArr['image_localized']=$image_localized;
		$newsArr['pseudo_original']=$pseudo_original;
		$newsArr['chinese_encoding']=$chinese_encoding;
		$newsArr['views']=rand(1,$views);
		$newsArr['group_fid']=$group_fid;
		$newsArr['intval_time']=$intval_time;
		$newsArr['filter_image']=$filter_image;
		$newsArr['image_center']=$image_center;
		$newsArr['replaynum']=$replaynum;
		$newsArr['release_time']=$release_time-rand(-1800,1800);
		$chk = DB::fetch_first("SELECT * FROM " . DB::table('csdn123toutiao_news') . " WHERE source_link='" . daddslashes($source_link) . "' LIMIT 1");
		$chk2 = DB::fetch_first("SELECT * FROM " . DB::table('csdn123toutiao_news') . " WHERE title='" . daddslashes($title) . "' LIMIT 1");		
		if (count($chk) == 0 && count($chk2) == 0) {
			DB::insert('csdn123toutiao_news', $newsArr);
		}
		
	}
	$succeed_url='action=plugins&operation=config&do=' . $pluginid . '&identifier=csdn123com_toutiao&pmod=pending';
	cpmsg("csdn123com_toutiao:collection_success",$succeed_url,"succeed");
	
} else {
	
	$regvest_url='?action=plugins&operation=config&do=' . $pluginid . '&identifier=csdn123com_toutiao&pmod=adminVest&subpmod=regvest&formhash=' . FORMHASH;
	require_once libfile('function/forumlist');
	require_once libfile('function/portalcp');
	require_once libfile('function/group');
	$grouplistArr=grouplist('displayorder',false,100);
	$release_time=rand(1,3600);
	$release_time=time() - $release_time;
	$release_time=date('Y-m-d H:i:s',$release_time);
	if(empty($_GET['keyword_page']) || is_numeric($_GET['keyword_page'])==false)
	{
		$keyword_page=1;
	} else {
		$keyword_page=$_GET['keyword_page'];
	}
	$keywordArr=show_keywords($keyword_page);
	$preKeywordPage=$server_url . "&keyword_page=" . ($keyword_page - 1);
	$nextKeywordPage=$server_url . "&keyword_page=" . ($keyword_page + 1);
	include template('csdn123com_toutiao:trueTime');
	
}
?>