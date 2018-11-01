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
$server_url = 'action=plugins&operation=config&do=' . $pluginid . '&identifier=csdn123com_toutiao&pmod=success';
function return_seo($data,$url)
{
	$data_arr=json_decode($data,true);
	$restr=lang('plugin/csdn123com_toutiao', 'baiduseo_url') . $url . '<br>';
	$restr=$restr . lang('plugin/csdn123com_toutiao', 'baiduseo_success') . $data_arr['success'] . '<br>';
	$restr=$restr . lang('plugin/csdn123com_toutiao', 'baiduseo_remain') . $data_arr['remain'] . '<br>';
	$restr=$restr . '<span onClick="document.getElementById(\'showbaiduseoinfo\').style.display=\'block\'" style="cursor:pointer;color:red">' . lang('plugin/csdn123com_toutiao', 'baiduseo_info') . '</span><br>';
	$restr=$restr . '<textarea style="display:none;width:550px;height:300px;" id="showbaiduseoinfo">' . $data . '</textarea>';
	return $restr;
	
}
if ($_GET['formhash'] == FORMHASH && empty($_GET['del']) == false && is_numeric($_GET['del']) == true) {
	
	$delid = intval($_GET['del']);
	DB::delete('csdn123toutiao_news', 'ID=' . $delid);
	cpmsg('csdn123com_toutiao:del_send', $server_url . '&page=' . $_GET['page'], 'succeed');
	
} elseif ($_GET['formhash'] == FORMHASH && is_numeric($_GET['seo_id']) == true) {

	if (!isset($_G['cache']['plugin'])) {
		loadcache('plugin');
	}
	if(empty($_G['cache']['plugin']['csdn123com_toutiao']['hzw_seo']))
	{
		cpmsg('csdn123com_toutiao:zhanzhang_err', '', 'error');
	} else {
		$hzw_seo=$_G['cache']['plugin']['csdn123com_toutiao']['hzw_seo'];
	}
	$hzw_seo=trim($hzw_seo);
	$seo_id=intval($_GET['seo_id']);
	$news_rs=DB::fetch_first("SELECT * FROM " . DB::table("csdn123toutiao_news") . " WHERE tid_aid>0 AND ID=" . $seo_id);
	$seo_url = preview_url($news_rs['forum_portal'],$news_rs['tid_aid']);
	$return_status=dfsockopen($hzw_seo,0,array($seo_url));	
	echo '<div style="margin:32px;text-align:left;line-height:36px;font-size:18px;">';
	echo '<a href="javascript:history.go(-1);" style="font-size:24px;">' . lang('plugin/csdn123com_toutiao', 'zhanzhang_back') . '</a><br><br>';
	echo  return_seo($return_status,$seo_url);
	echo '</div>';

} elseif ($_GET['formhash'] == FORMHASH && $_GET['selall'] == 'yes') {
	
	if (empty($_GET['idarray'])) {
		cpmsg('csdn123com_toutiao:select_empty', '' , 'error');
	}
	if(empty($_GET['seldelete'])==false)
	{
	
		$idstr = implode(',', $_GET['idarray']);
		$idstr = daddslashes($idstr);
		DB::delete('csdn123toutiao_news', 'ID in (' . $idstr . ')');
		cpmsg('csdn123com_toutiao:del_send', $server_url . '&page=' . $_GET['page'], 'succeed');
	
	}
	if(empty($_GET['selpostbaidu'])==false)
	{
		
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
			cpmsg('csdn123com_toutiao:baiduseo_succeed', $server_url , 'succeed');
		}
		if (!isset($_G['cache']['plugin'])) {
			loadcache('plugin');
		}
		if(empty($_G['cache']['plugin']['csdn123com_toutiao']['hzw_seo']))
		{
			cpmsg('csdn123com_toutiao:zhanzhang_err', '', 'error');
		} else {
			$hzw_seo=$_G['cache']['plugin']['csdn123com_toutiao']['hzw_seo'];
		}
		$hzw_seo=trim($hzw_seo);		
		$post_seo_url_RS=DB::fetch_first("SELECT * FROM " . DB::table('csdn123toutiao_news') . " WHERE tid_aid>0 AND ID=" . $ID);
		$post_seo_url=preview_url($post_seo_url_RS['forum_portal'],$post_seo_url_RS['tid_aid']);
		$return_status=dfsockopen($hzw_seo,0,array($post_seo_url));
		$statusStr = lang('plugin/csdn123com_toutiao', 'baiduseo_status');
		$statusStr = str_replace('count', $count_num, $statusStr);
		$statusStr = str_replace('num', $num+1, $statusStr);
		echo '<div style="margin:32px;text-align:left;line-height:36px;font-size:18px;">';
		echo '<br><span style="font-size:20px;color:red">' . $statusStr . '</span><br>';
		echo  return_seo($return_status,$post_seo_url);
		echo '</div>';
		$num++;
		$nextSeoUrl = '?' . $server_url . '&selpostbaidu=yes&selall=yes&formhash=' . FORMHASH . '&idarray=' . $idstr . '&num=' . $num;
		echo '<script>setTimeout(function(){ window.location.href="' . $nextSeoUrl . '" },8000);</script>';
		
	}
	
} else {
	
	$startNum = ($page - 1) * 20;
	$postRs = DB::fetch_all("SELECT * FROM " . DB::table("csdn123toutiao_news") . " where tid_aid>0 ORDER BY send_datetime DESC,ID DESC,tid_aid DESC LIMIT " . $startNum . ",20");
	$nextPage = $server_url . '&page=' . ($page + 1);
	$prePage = $server_url . '&page=' . ($page - 1);
	include template("csdn123com_toutiao:success");
	
}
