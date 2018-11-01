<?php
if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$server_url='action=plugins&operation=config&do=' . $pluginid . '&identifier=csdn123com_toutiao&pmod=tophead';
require './source/plugin/csdn123com_toutiao/function_common.php';
if($_GET['formhash'] == FORMHASH && !empty($_GET['csdn123_agrs']) && $_GET['csdn123_agrs']=='yes')
{
	if(empty($_GET['keyword'])){
		cpmsg('csdn123com_toutiao:keywords_empty', '', 'error');
	}
	$postdata = serialize($_GET);
	$keyword = $_GET['keyword'];
	$keyword = diconv($keyword,CHARSET,'UTF-8');	
	$specUrl = "https://www.toutiao.com/search_content/?offset=0&format=json&keyword={$keyword}&autoload=true&count=20&cur_tab=4";
	$htmlcode = dfsockopen($specUrl);
	if (strlen($htmlcode) < 200) {
        $htmlcode = dfsockopen($specUrl, 0, '', '', FALSE, '', 15, TRUE, 'URLENCODE', FALSE);
    }
	$htmlcode = base64_encode($htmlcode);
	$htmlcode = dfsockopen('http://discuz.csdn123.net/catch/toutiao201711/tophead.php', 0, array('htmlcode' => $htmlcode));
	$htmlcode = preg_replace('/^\s+|\s+$/', '', $htmlcode);
	$htmlcode = base64_decode($htmlcode);
	$specLinkArr = dunserialize($htmlcode);	
	include template('csdn123com_toutiao:tophead_search');

} elseif($_GET['formhash'] == FORMHASH && !empty($_GET['step_one']) && $_GET['step_one']=='yes') {
	
	$specUrl = $_GET['specUrl'];
	$specUrl = "https://www.toutiao.com/c/user/article/?page_type=1&user_id={$specUrl}&count=20";
	$htmlcode = dfsockopen($specUrl);		
	if (strlen($htmlcode) < 200) {
		$htmlcode = dfsockopen($specUrl, 0, '', '', FALSE, '', 15, TRUE, 'URLENCODE', FALSE);
	}
	$htmlcode = base64_encode($htmlcode);
	$htmlcode = dfsockopen('http://discuz.csdn123.net/catch/toutiao201711/tophead_links.php',0, array('htmlcode' => $htmlcode));
	$htmlcode = preg_replace('/^\s+|\s+$/', '', $htmlcode);
	$htmlcode = base64_decode($htmlcode);
	$resultLink = dunserialize($htmlcode);	
	if(is_array($resultLink)==FALSE)
	{
		cpmsg("csdn123com_toutiao:collection_result_null","","error");
	}
	$_GET = dunserialize($_GET['postdata']);
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

	require_once libfile('function/forumlist');
	require_once libfile('function/portalcp');
	require_once libfile('function/group');
	$grouplistArr=grouplist('displayorder',false,100);
	$release_time=rand(1,3600);
	$release_time=time() - $release_time;
	$release_time=date('Y-m-d H:i:s',$release_time);	
	include template('csdn123com_toutiao:tophead');
	
}
?>