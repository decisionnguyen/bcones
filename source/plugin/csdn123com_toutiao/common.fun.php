<?php
if (!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
function str_replace_once($needle, $replace, $haystack) {
	$pos = strpos($haystack, $needle);
	if ($pos === false) {
		return $haystack;
	}
	return substr_replace($haystack, $replace, $pos, strlen($needle));
}
function ext_getuserbyuid($uid) {
	$uid = intval($uid);
	$userInfo = getuserbyuid($uid, 1);
	if (empty($userInfo['uid']) || $userInfo['uid'] == 0) {
		$userInfo = DB::fetch_first("SELECT uid,username FROM " . DB::table('csdn123toutiao_reguser') . " WHERE uid=" . $uid);
	}
	if (empty($userInfo['uid']) || $userInfo['uid'] == 0) {
		$userInfo = DB::fetch_first("SELECT uid,username FROM " . DB::table('csdn123toutiao_reguser') . " ORDER BY RAND() limit 1");
	}
	return $userInfo;
}
function convert_img($tid, $pid, $uid, $message, $allowhtml = 0) {
    global $_G;
	if($allowhtml==0)
	{
		preg_match_all("/(\[img\]|\[img=\d{1,4}[x|\,]\d{1,4}\])\s*([^\[\<\r\n]+?)\s*\[\/img\]/is", $message, $imglist, PREG_SET_ORDER);
	} else {
		preg_match_all('/<img[^<>]*src\s*=\s*([\'"]?)([^\'">]*)\1[^<>]*>/i', $message, $imglist, PREG_SET_ORDER);
	}
	$nopic=$_G['siteurl'] . 'source/plugin/csdn123com_toutiao/template/nopic.jpg';
    if (count($imglist) > 0) {
        foreach ($imglist as $img) {
		
			$message=str_replace($img[2],$nopic,$message);
			
		}
        return $message;
    } else {
        return $message;
    }
}
function send_thread($ID = NULL, $fromurl = NULL) {
	global $_G;
	$recode = array();
	if (is_null($fromurl) && is_null($ID)) {
		return 'no1';
	}
	if (is_numeric($ID) && $ID > 0) {
		$hzw_news = DB::fetch_first("SELECT * FROM " . DB::table('csdn123toutiao_news') . " WHERE ID=" . $ID);
		$fromurl = $hzw_news['fromurl'];
	} else {
		$fromurl = preg_replace('/\?.+$/', '', $fromurl);
		$hzw_news = DB::fetch_first("SELECT * FROM " . DB::table('csdn123toutiao_news') . " WHERE fromurl='" . $fromurl . "'");
		$ID = $hzw_news['ID'];
	}
	if (count($hzw_news) > 0 && $hzw_news['tid_aid'] > 0) {
		return 'ok';
	}
	DB::update('csdn123toutiao_news',array('tid_aid'=>-1),array('ID'=>$ID));
	$reply_uid = $hzw_news['reply_uid'];
	if(strpos($reply_uid,',')!=false)
	{
		$uidArr = explode(',', $reply_uid);
	} else {
		$uidArr=array($reply_uid);
	}
	$uidCount = count($uidArr);
	$firstUid = $hzw_news['first_uid'];
	if(strpos($firstUid,',')!=false)
	{
		$firstUidArr = explode(',', $firstUid);
		shuffle($firstUidArr);
		$firstUid=$firstUidArr[0];
	}
	$remoteUrl = array();
	$remoteUrl['SiteUrl'] = '{ADDONVAR:SiteUrl}';
	$remoteUrl['ClientUrl'] = '{ADDONVAR:ClientUrl}';
	$remoteUrl['safecode'] = '{ADDONVAR:MD5(SN,SiteUrl)}';
	$remoteUrl['SiteUrl2'] = $_G['siteurl'];
	$remoteUrl['ip'] = $_SERVER['REMOTE_ADDR'];
	$remoteUrl['url'] = $fromurl;
	$remoteUrl['siteur1'] = $_SERVER['HTTP_HOST'];
	$fetchUrl = "http://discuz.csdn123.net/catch/toutiao_news/catch2.php";
	$htmlcode = dfsockopen($fetchUrl, 0, $remoteUrl);
	if ($htmlcode == 'no2') {
		$htmlcode = dfsockopen($fromurl);
		if (strlen($htmlcode) < 100) {
			$htmlcode = dfsockopen($fromurl, 0, '', '', FALSE, '', 15, TRUE, 'URLENCODE', FALSE);
		}
		$htmlcode = base64_encode($htmlcode);
		$remoteUrl['htmlcode'] = $htmlcode;
		$htmlcode = dfsockopen($fetchUrl, 0, $remoteUrl);
	}
	if (strlen($htmlcode) < 100) {
		return 'no3';
	}
	$htmlcode = preg_replace('/^\s+|\s+$/', '', $htmlcode);
	$htmlcode = dunserialize($htmlcode);
	if (is_array($htmlcode) === false) {
		return 'no4';
	}
	$group_id = $htmlcode['group_id'];
	$item_id = $htmlcode['item_id'];
	if (is_numeric($group_id) && is_numeric($item_id)) {
		$comment_url = "http://www.toutiao.com/api/comment/list/?group_id={$group_id}&item_id={$item_id}&offset=0&count=20";
		$comment_htmlcode = dfsockopen($comment_url);
		if (strlen($comment_htmlcode) < 50) {
			$comment_htmlcode = dfsockopen($comment_url, 0, '', '', FALSE, '', 15, TRUE, 'URLENCODE', FALSE);
		}
		$comment_htmlcode = base64_encode($comment_htmlcode);
		$remoteUrl['url'] = $comment_url;
		$remoteUrl['comment_htmlcode'] = $comment_htmlcode;
		$fetchUrl = "http://discuz.csdn123.net/catch/toutiao_news/catch_comment2.php";
		$comment_htmlcode = dfsockopen($fetchUrl, 0, $remoteUrl);
	}
	if (strlen($comment_htmlcode) > 100) {
		$comment_htmlcode = preg_replace('/^\s+|\s+$/', '', $comment_htmlcode);
		$comment_htmlcode = dunserialize($comment_htmlcode);
	}
	$replaynum = intval($hzw_news['replaynum']);
	if ($hzw_news['forum_portal'] == 'forum') {
		
		$_G['forum']['fid'] = $hzw_news['fid'];
		$forumInfo = C::t('forum_forum')->fetch_info_by_fid($hzw_news['fid']);
		$threadtypeid = $hzw_news['typeid'];
		require_once libfile('function/editor');
		$post_text = $htmlcode['firstPost'];
		$post_text = diconv($post_text, 'UTF-8');
		$post_text = str_ireplace('https://', 'http://', $post_text);
		if ($forumInfo['allowhtml'] != 1) {
			$post_text = html2bbcode($post_text);
			$post_text = html_entity_decode($post_text);
		}
		$UserInfo = ext_getuserbyuid($firstUid);
		$modthread = C::m('forum_thread');
		$params = array();
		if (empty($hzw_news['subject'])) {
			$title = diconv($htmlcode['title'], 'UTF-8');
			DB::update('csdn123toutiao_news', array('subject' => $title), 'ID=' . $hzw_news['ID']);
		} else {
			$title = $hzw_news['subject'];
		}
		$title = cutstr($title, 70);
		if ($hzw_news['weiyanchang'] == 'yes') {
			$title = rep_weiyanchang($title);
		}
		if ($hzw_news['simplified'] == 'yes') {
			$title = convert_simplified($title, 'toGBK');
		}
		if ($hzw_news['simplified'] == 'no') {
			$title = convert_simplified($title, 'toBIG');
		}		
		$params['subject'] = $title;
		$params['message'] = '[Just a minute,It is loading]';
		$params['typeid'] = $threadtypeid;
		$params['sortid'] = 0;
		$params['special'] = 0;
		$params['publishdate'] = time() - $hzw_news['intval_time'];
		$params['readperm'] = 0;
		$params['allownoticeauthor'] = 1;
		$params['usesig'] = 1;
		$params['replycredit'] = 0;
		$modthread->newthread($params);
		$tid = $modthread->tid;
		$pid = $modthread->pid;
		$threadData = array();
		$threadData['author'] = $UserInfo['username'];
		$threadData['authorid'] = $UserInfo['uid'];
		$threadData['typeid'] = $threadtypeid;
		$threadData['subject'] = $title;
		$threadData['icon'] = -1;
		DB::update('forum_thread', $threadData, 'tid=' . $tid);
		$postData = array();
		$post_text = convert_img($tid, $pid, $UserInfo['uid'], $post_text, $forumInfo['allowhtml']);
		if ($hzw_news['weiyanchang'] == 'yes') {
			$post_text = rep_weiyanchang($post_text);
		}
		if ($hzw_news['simplified'] == 'yes') {
			$post_text = convert_simplified($post_text, 'toGBK');
		}
		if ($hzw_news['simplified'] == 'no') {
			$post_text = convert_simplified($post_text, 'toBIG');
		}
		$postData['message'] = $post_text;
		if (strpos($post_text, '[attach]') !== false) {
			$postData['attachment'] = 2;
			$threadFirstPost_attachment = 2;
			require_once libfile('function/post');
			$temp_uid = $_G['uid'];
			$temp_ismoderator = $_G['forum']['ismoderator'];
			$temp_forumpicstyle = $_G['setting']['forumpicstyle'];
			$temp_thumbwidth = $_G['setting']['forumpicstyle']['thumbwidth'];
			$temp_thumbheight = $_G['setting']['forumpicstyle']['thumbheight'];
			$_G['uid'] = $UserInfo['uid'];
			$_G['forum']['ismoderator'] = 1;
			$_G['setting']['forumpicstyle'] = array();
			$_G['setting']['forumpicstyle']['thumbwidth'] = 160;
			$_G['setting']['forumpicstyle']['thumbheight'] = 160;
			setthreadcover($pid, $tid);
			$_G['uid'] = $temp_uid;
			$_G['forum']['ismoderator'] = $temp_ismoderator;
			$_G['setting']['forumpicstyle'] = $temp_forumpicstyle;
			$_G['setting']['forumpicstyle']['thumbwidth'] = $temp_thumbwidth;
			$_G['setting']['forumpicstyle']['thumbheight'] = $temp_thumbheight;
		}
		$postData['author'] = $UserInfo['username'];
		$postData['authorid'] = $UserInfo['uid'];
		$postData['bbcodeoff'] = 0;
		if ($forumInfo['allowhtml'] == 1) {
			$postData['htmlon'] = 1;
		}
		DB::update('forum_post', $postData, 'pid=' . $pid);
		unset($postData);
		unset($params);		
		if (is_numeric($UserInfo['uid'])) {
			DB::query('update ' . DB::table('common_member_count') . ' set threads=threads+1 where uid=' . $UserInfo['uid']);
		}
		if (is_array($comment_htmlcode)) {
			
			$postitem_count = count($comment_htmlcode['postitem']);
			$postitem_intval_count = min($postitem_count,$replaynum);
			$postitem_intval_count--;
			$postitem_intval = $hzw_news['intval_time'] / $postitem_intval_count;
			$postitem_intval = intval($postitem_intval);
			foreach ($comment_htmlcode['postitem'] as $post_k => $post_value) {
				if ($replaynum == 0 || $post_k > $replaynum) {
					break;
				}
				$post_text = $post_value['content'];
				$post_text = str_ireplace('https://', 'http://', $post_text);
				if (empty($post_text)) {
					continue;
				}
				$postUid = $post_value['uid'];
				$postUid = $postUid % $uidCount;
				$postUid = $uidArr[$postUid];
				$PostUserInfo = ext_getuserbyuid($postUid);
				if(defined('DISCUZ_VERSION') && DISCUZ_VERSION=='X3')
				{
					$pid = insertpost(array('fid' => $hzw_news['fid'], 'tid' => $tid, 'first' => '0', 'author' => $PostUserInfo['username'], 'authorid' => $PostUserInfo['uid'], 'subject' => '', 'dateline' => getglobal('timestamp'), 'message' => '[Just a minute,It is loading]', 'useip' => getglobal('clientip'), 'invisible' => 0, 'anonymous' => 0, 'usesig' => 1, 'htmlon' => 0, 'bbcodeoff' => 0, 'smileyoff' => - 1, 'parseurloff' => 0, 'attachment' => '0', 'status' => 0));
				} else {
					$pid = insertpost(array('fid' => $hzw_news['fid'], 'tid' => $tid, 'first' => '0', 'author' => $PostUserInfo['username'], 'authorid' => $PostUserInfo['uid'], 'subject' => '', 'dateline' => getglobal('timestamp'), 'message' => '[Just a minute,It is loading]', 'useip' => getglobal('clientip'), 'port' => getglobal('remoteport'), 'invisible' => 0, 'anonymous' => 0, 'usesig' => 1, 'htmlon' => 0, 'bbcodeoff' => 0, 'smileyoff' => - 1, 'parseurloff' => 0, 'attachment' => '0', 'status' => 0));
				}
				$postData = array();
				$post_text = diconv($post_text, 'UTF-8');
				$post_text = $post_text . '<div style="color:red"><br><br><br><br><br><br><br><br><br>' . lang('plugin/csdn123com_toutiao', 'try_info1') . '</div>';
				$post_text = $post_text . '<div><br><a href="http://addon.discuz.com/?@csdn123com_toutiao.plugin" target="_blank">http://addon.discuz.com/?@csdn123com_toutiao.plugin</a></div>';
				$post_text = $post_text . '<div style="color:red">' . lang('plugin/csdn123com_toutiao', 'try_info2') . '</div>';
				$post_text = html2bbcode($post_text);
				$post_text = html_entity_decode($post_text);
				$post_text = convert_img($tid, $pid, $PostUserInfo['uid'], $post_text, $forumInfo['allowhtml']);				
				if ($post_text == '') {
					$post_text = lang('plugin/csdn123com_toutiao', 'post_empty');
				}
				if ($hzw_news['weiyanchang'] == 'yes') {
					$post_text = rep_weiyanchang($post_text);
				}
				if ($hzw_news['simplified'] == 'yes') {
					$post_text = convert_simplified($post_text, 'toGBK');
				}
				if ($hzw_news['simplified'] == 'no') {
					$post_text = convert_simplified($post_text, 'toBIG');
				}
				$postData['message'] = $post_text;
				$time_down = ($postitem_intval_count - $post_k) * $postitem_intval;
				$time_down = $time_down - rand(1, 60);
				$time_down = max(0, $time_down);
				$postData['dateline'] = time() - $time_down;
				if (strpos($post_text, '[attach]') !== false) {
					$postData['attachment'] = 2;
				}
				DB::update('forum_post', $postData, 'pid=' . $pid);
				updatemembercount($PostUserInfo['uid'], array('extcredits2' => 1), true, '', 0, '');
				if (is_numeric($PostUserInfo['uid'])) {
					DB::query('update ' . DB::table('common_member_count') . ' set posts=posts+1 where uid=' . $PostUserInfo['uid']);
				}
				C::t('forum_threadpartake')->insert(array('tid' => $tid, 'uid' => $PostUserInfo['uid'], 'dateline' => $postData['dateline']));
			}
		}
		$lastpostArr = array();
		$lastpostArr['lastpost'] = time();
		if(empty($PostUserInfo['username']))
		{
			$PostUserInfo['username']=$UserInfo['username'];
		}
		$lastpostArr['lastposter'] = $PostUserInfo['username'];
		$lastpostArr['views'] = $hzw_news['views'];
		if ($threadFirstPost_attachment == 2) {
			$lastpostArr['attachment'] = 2;
		}
		$replies = C::t('forum_post')->count_visiblepost_by_tid($tid);
		$replies = intval($replies) - 1;
		$lastpostArr['replies'] = $replies;
		$lastpostArr['maxposition'] = $replies +1;
		DB::update('forum_thread', $lastpostArr, 'tid=' . $tid);
		if ($tid > 0) {
			if (is_numeric($ID)) {
				DB::update('csdn123toutiao_news', array('tid_aid' => $tid,'send_datetime'=>$_G['timestamp']), "ID=" . $ID);
			} else {
				DB::update('csdn123toutiao_news', array('tid_aid' => $tid,'send_datetime'=>$_G['timestamp']), "fromurl='" . $fromurl . "'");
			}
			$lastUserName = $PostUserInfo['username'];
			$lastUserName = $tid . "\t" . daddslashes($title) . "\t" . $_G['timestamp'] . "\t" . daddslashes($lastUserName);
			if(is_numeric($postitem_count)==false || empty($postitem_count))
			{
				$postitem_count=1;
			}
			DB::query("update " . DB::table('forum_forum') . " set threads=threads+1,posts=posts + " . $postitem_count . ",lastpost='" . $lastUserName . "',todayposts=todayposts + " . $postitem_count . " where fid=" . $hzw_news['fid']);
			return 'ok';
		} else {
			return 'no5';
		}
		
	} else {		
		
		if (empty($hzw_news['subject'])) {
			$title = diconv($htmlcode['title'], 'UTF-8');
			DB::update('csdn123toutiao_news', array('subject' => $title), 'ID=' . $hzw_news['ID']);
		} else {
			$title = $hzw_news['subject'];
		}
		$title = cutstr($title, 70);		
		if ($hzw_news['weiyanchang'] == 'yes') {
			$title = rep_weiyanchang($title);
		}
		if ($hzw_news['simplified'] == 'yes') {
			$title = convert_simplified($title, 'toGBK');
		}
		if ($hzw_news['simplified'] == 'no') {
			$title = convert_simplified($title, 'toBIG');
		}		
		$setarr = array();
		$setarr['title'] = $title;
		$setarr['fromurl'] = daddslashes($hzw_news['fromurl']);
		$setarr['dateline'] = TIMESTAMP;
		$setarr['allowcomment'] = 1;
		$csdn123_content = $htmlcode['firstPost'];
		$csdn123_content = diconv($csdn123_content, 'UTF-8');
		if ($hzw_news['weiyanchang'] == 'yes') {
			$csdn123_content = rep_weiyanchang($csdn123_content);
		}
		if ($hzw_news['simplified'] == 'yes') {
			$csdn123_content = convert_simplified($csdn123_content, 'toGBK');
		}
		if ($hzw_news['simplified'] == 'no') {
			$csdn123_content = convert_simplified($csdn123_content, 'toBIG');
		}	
		$summary = $csdn123_content;
		$summary = preg_replace('/\s+/','',$summary);
		$summary = preg_replace('/<script.+?<\/script>/i','',$summary);
		$summary = preg_replace('/<style.+?<\/style>/i','',$summary);
		$summary = cutstr(strip_tags($summary), 200);
		$summary = censor($summary);
		$setarr['summary'] = $summary;
		$setarr['catid'] = $hzw_news['portal'];
		$setarr['highlight'] = '|||';
		$UserInfo = ext_getuserbyuid($firstUid);
		$setarr['uid'] = $UserInfo['uid'];
		$setarr['username'] = $UserInfo['username'];
		$setarr['contents'] = 1;
		$aid = C::t('portal_article_title')->insert($setarr, 1);
		C::t('common_member_status')->update($UserInfo['uid'], array('lastpost' => TIMESTAMP), 'UNBUFFERED');
		C::t('portal_category')->increase($hzw_news['portal'], array('articles' => 1));
		C::t('portal_category')->update($hzw_news['portal'], array('lastpublish' => TIMESTAMP));
		C::t('portal_article_count')->insert(array('aid' => $aid, 'catid' => $hzw_news['portal'], 'viewnum' => $hzw_news['views']));
		$upload = new discuz_upload();
		$arrayimageurl = $temp = $imagereplace = array();
		$nopic=$_G['siteurl'] . 'source/plugin/csdn123com_toutiao/template/nopic.jpg';
		preg_match_all("/\<img.+src=('|\"|)?(.*)(\\1)[^<>]*?>/ismUe", $csdn123_content, $temp, PREG_SET_ORDER);
		if (is_array($temp) && !empty($temp)) {
			foreach ($temp as $tempvalue) {
				
				$csdn123_content = str_replace($tempvalue[2], $nopic, $csdn123_content);
			}
		}
		$csdn123_content = $csdn123_content . '<div style="color:red"><br><br><br><br><br><br><br><br><br>' . lang('plugin/csdn123com_toutiao', 'try_info1') . '</div>';
		$csdn123_content = $csdn123_content . '<div><br><a href="http://addon.discuz.com/?@csdn123com_toutiao.plugin" target="_blank">http://addon.discuz.com/?@csdn123com_toutiao.plugin</a></div>';
		$csdn123_content = $csdn123_content . '<div style="color:red">' . lang('plugin/csdn123com_toutiao', 'try_info2') . '</div>';
		C::t('portal_article_content')->insert(array('aid' => $aid, 'id' => 0, 'title' => '', 'content' => $csdn123_content, 'pageorder' => 1, 'dateline' => TIMESTAMP));
		if (is_array($comment_htmlcode)) {
			
			$postitem_count = count($comment_htmlcode['postitem']);
			$postitem_intval_count = min($postitem_count,$replaynum);
			$postitem_intval = $hzw_news['intval_time'] / $postitem_intval_count;
			$postitem_intval = intval($postitem_intval);
			foreach ($comment_htmlcode['postitem'] as $post_k => $post_value) {
				if ($replaynum == 0 || $post_k > $replaynum) {
					break;
				}
				$post_text = $post_value['content'];
				$post_text = strip_tags($post_text);
				if (empty($post_text)) {
					continue;
				}
				$post_text = diconv($post_text,'UTF-8');
				$postUid = $post_value['uid'];
				$postUid = $postUid % $uidCount;
				$postUid = $uidArr[$postUid];
				$PostUserInfo = ext_getuserbyuid($postUid);
				if ($hzw_news['weiyanchang'] == 'yes') {
					$post_text = rep_weiyanchang($post_text);
				}
				if ($hzw_news['simplified'] == 'yes') {
					$post_text = convert_simplified($post_text, 'toGBK');
				}
				if ($hzw_news['simplified'] == 'no') {
					$post_text = convert_simplified($post_text, 'toBIG');
				}
				$time_down = ($postitem_intval_count - $post_k) * $postitem_intval;
				$time_down = $time_down - rand(1, 60);
				$time_down = max(0, $time_down);
				$dateline = time() - $time_down;
				if(defined('DISCUZ_VERSION') && DISCUZ_VERSION=='X3')
				{
					$setarr = array(
							'uid' => $PostUserInfo['uid'],
							'username' => $PostUserInfo['username'],
							'id' => $aid,
							'idtype' => 'aid',
							'postip' => $_G['clientip'],
							'dateline' => $dateline,
							'status' => 0,
							'message' => $post_text
						);
						
				} else {
				
					$setarr = array(
							'uid' => $PostUserInfo['uid'],
							'username' => $PostUserInfo['username'],
							'id' => $aid,
							'idtype' => 'aid',
							'postip' => $_G['clientip'],
							'port' => $_G['remoteport'],
							'dateline' => $dateline,
							'status' => 0,
							'message' => $post_text
						);
					
				}
				$pcid = C::t('portal_comment')->insert($setarr, true);
				C::t('portal_article_count')->increase($aid, array('commentnum' => 1));	

			}
		}
		if (is_numeric($ID)) {
			DB::update('csdn123toutiao_news', array('tid_aid' => $aid,'send_datetime'=>$_G['timestamp']), "ID=" . $ID);
		} else {
			DB::update('csdn123toutiao_news', array('tid_aid' => $aid,'send_datetime'=>$_G['timestamp']), "fromurl='" . $fromurl . "'");
		}
		return 'ok';
	
	}
}
function getRndUid($num = 80) {
	$uidarray = DB::fetch_all('select uid from ' . DB::table('csdn123toutiao_reguser') . ' order by RAND() limit ' . $num);
	foreach ($uidarray as $uidvalue) {
		$uidstr = $uidvalue['uid'] . ',' . $uidstr;
	}
	$uidstr = substr($uidstr, 0, -1);
	if ($uidstr == "" || empty($uidstr)) {
		return 1;
	} else {
		return $uidstr;
	}
}
function convert_simplified($str, $convertType = 'toBIG') {
	global $_G;
	$csdn123_url = "http://discuz.csdn123.net/catch/common/convert_GBK_BIG.php";
	$strText = strip_tags($str);
	$strText = diconv($strText, CHARSET, 'UTF-8');
	$csdn123_data = array('convertType' => $convertType, 'textdata' => base64_encode($strText), 'siteurl' => urlencode($_G["siteurl"]), 'ip' => $_SERVER['REMOTE_ADDR'], 'charset' => CHARSET);
	$csdn123_return = dfsockopen($csdn123_url, 0, $csdn123_data);
	$csdn123_return = base64_decode($csdn123_return);
	$csdn123_return_arr = dunserialize($csdn123_return);
	foreach ($csdn123_return_arr as $csdn123_return_arrValue) {
		$csdn123_return_arrValue = diconv($csdn123_return_arrValue, "UTF-8");
		$csdn123_arr_LF = explode('=', $csdn123_return_arrValue);
		$csdn123_arr_Left = $csdn123_arr_LF[0];
		$csdn123_arr_Right = $csdn123_arr_LF[1];
		$str = str_replace($csdn123_arr_Left, $csdn123_arr_Right, $str);
	}
	return $str;
}
function rep_weiyanchang($str) {
	$wordRs = DB::fetch_all("SELECT word1,word2 FROM " . DB::table('csdn123toutiao_weiyanchang'));
	foreach ($wordRs as $wordValue) {
		$word1 = $wordValue['word1'];
		$word2 = $wordValue['word2'];
		$word2 = preg_replace('/(.)/', '$1_hzw_', $word2);
		$str = str_replace($word1, $word2, $str);
	}
	$str = str_replace('_hzw_', '', $str);
	return $str;
}
function GetImgFileExt($imgurl)
{
	if(stripos($imgurl,'gif')!=false)
	{
		return 'gif';
	}elseif(stripos($imgurl,'jpg')!=false) {
		return 'jpg';
	}elseif(stripos($imgurl,'jpeg')!=false) {
		return 'jpeg';
	}elseif(stripos($imgurl,'png')!=false) {
		return 'png';
	}else{
		return 'jpg';
	}
}
function getclassname($typeid, $fid,$portal_catid,$forum_portal) {
	
	global $_G;
	$restr="";
	if($forum_portal=='forum')
	{
		$restr=lang('plugin/csdn123com_toutiao', 'forum');
		$fidinfo = C::t('forum_forum')->fetch_info_by_fid($fid);
		$restr=$restr . ' -- ' . $fidinfo['name'];
		if ($typeid > 0) {
			$typeidInfo = C::t('forum_threadclass')->fetch_all_by_typeid_fid($typeid, $fid);
			if(empty($typeidInfo)==false)
			{
				$restr=$restr . ' -- ' . $typeidInfo[$typeid]['name'];
			}
		}
		
	} else {
		
		$restr=lang('plugin/csdn123com_toutiao', 'portal');
		loadcache('portalcategory');
		$category = $_G['cache']['portalcategory'];
		if(empty($category[$portal_catid])==false)
		{
			$restr=$restr . ' -- ' . $category[$portal_catid]['catname'];
		}
	}
	return $restr;
}
function preview_url($forum_portal,$tid_aid)
{
	global $_G;
	if (!isset($_G['cache']['plugin'])) {
		loadcache('plugin');
	}
	$csdn123com_toutiao_setting=$_G['cache']['plugin']['csdn123com_toutiao'];
	$hzw_forum_url=$csdn123com_toutiao_setting['hzw_forum_url'];
	if(empty($hzw_forum_url) || strlen($hzw_forum_url)<3)
	{
		$hzw_forum_url='forum.php?mod=viewthread&tid={ID}';
	}
	$hzw_forum_url=str_replace('{ID}',$tid_aid,$hzw_forum_url);
	$hzw_portal_url=$csdn123com_toutiao_setting['hzw_portal_url'];
	if(empty($hzw_portal_url) || strlen($hzw_portal_url)<3)
	{
		$hzw_portal_url='portal.php?mod=view&aid={ID}';
	}
	$hzw_portal_url=str_replace('{ID}',$tid_aid,$hzw_portal_url);
	if($forum_portal=='forum')
	{		
		return $_G['siteurl'] . $hzw_forum_url;
	} else {
		return $_G['siteurl'] . $hzw_portal_url;
	}
}
function majia_chk()
{
	$chk=DB::fetch_first("SELECT * FROM " . DB::table('csdn123toutiao_reguser') ." LIMIT 1");
	if(count($chk)==0)
	{
		return true;
	} else {
		return false;
	}
}
