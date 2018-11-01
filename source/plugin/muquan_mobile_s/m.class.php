<?php
/**
 * © www.muquan.net 
 * by mugua
 **/  
if(!defined('IN_DISCUZ')) {  
    exit('Access Denied');  
}  
class mobileplugin_muquan_mobile_s {  

     public function __construct(){  
      global $_G;
	  $this->plugin_setting=$_G['cache']['plugin']['muquan_mobile_s'];
    }
	
	function discuzcode() {
      global $_G;
	  if(strpos($_G['discuzcodemessage'], '[/img]') !== FALSE) {
			$_G['discuzcodemessage'] = preg_replace(array(
				"/\[img\]\s*([^\[\<\r\n]+?)\s*\[\/img\]/ies",
				"/\[img=(\d{1,4})[x|\,](\d{1,4})\]\s*([^\[\<\r\n]+?)\s*\[\/img\]/ies"
			),array(
				"parseimg(0, 0, '\\1', '', '', '')",
				"parseimg('\\1', '\\2', '\\3','','','')"
			), $_G['discuzcodemessage']);
	   }
   }
	
   function global_header_mobile(){
	   global $mq_lang,$_G;
	   $mq_lang=lang('plugin/muquan_mobile_s');
   }
}  

class mobileplugin_muquan_mobile_s_forum extends mobileplugin_muquan_mobile_s{
    
	//global
	public function index_global_thread(){
		global $_G;
		$ms_lang=lang('plugin/muquan_mobile_s');
		$announcements = '';
		if($_G['cache']['announcements']) {
			$readapmids = !empty($_G['cookie']['readapmid']) ? explode('D', $_G['cookie']['readapmid']) : array();
			foreach($_G['cache']['announcements'] as $announcement) {
				if(!$announcement['endtime'] || $announcement['endtime'] > TIMESTAMP && (empty($announcement['groups']) || in_array($_G['member']['groupid'], $announcement['groups']))) {
					if(empty($announcement['type'])) {
						$announcements .= '<li><a href="forum.php?mod=announcement&id='.$announcement['id'].'"  class="cl"><i>'.$ms_lang['announcements'].'</i><em>'.date('m-d',$announcement['starttime']).'</em>'.$announcement['subject'].'</a></li>';
					} elseif($announcement['type'] == 1) {
						$announcements .= '<li><a href="'.$announcement['message'].'"  class="cl"><i>'.$ms_lang['announcements'].'</i><em>'.date('m-d',$announcement['starttime']).'</em>'.$announcement['subject'].'</a></li>';
					}
				}
			}
		}
		$global_thread=C::t('#muquan_mobile_s#muquan_mobile_s_forum_thread')->fetch_all_global();
		foreach($global_thread as $i=>$v){
			$global_thread[$i]['dateline']=date('m-d',$v['dateline']);
		}
		include template('muquan_mobile_s:global_thread'); 
	    return $return;
	}
	
	public function announcement_view(){
		$id=intval($_GET['id']);
		require_once libfile('function/discuzcode');
		$post=C::t('#muquan_mobile_s#muquan_mobile_s_forum_announcement')->fetch_by_id($id);
		$post['dateline']=date('Y-m-d',$post['starttime']);
		$post['message'] = $post['type'] == 1 ? "[url]$post[message]}[/url]" : $post['message'];
		$post['message'] = nl2br(discuzcode($post['message'], 0, 0, 1, 1, 1, 1, 1));
		include template('muquan_mobile_s:announcement'); 
	    return $return;
	}
	
	
	public function index_home_index(){
	   global $_ms;
	   //plugin setting
	   $_ms=$this->plugin_setting;
	   //require function
	   require_once DISCUZ_ROOT . './source/plugin/muquan_mobile_s/source/function/function.func.php';
	   //data from
	   $inforum=unserialize($_ms['m_data']);
	   $inforum=!empty($inforum[0])?$inforum:'';
	   if(is_array($inforum))  $conditions['inforum']=$inforum;
	   //order
	   if($_ms['m_order']==2) $order='lastpost';
	   elseif($_ms['m_order']==3) $order='replies';
	   elseif($_ms['m_order']==4) $order='views';
	   else $order= 'dateline';
	   //pages
	   $limit=$page_num=$_ms['m_num']?$_ms['m_num']:10;
	   //count
	   $num=count(C::t('#muquan_mobile_s#muquan_mobile_s_forum_thread')->fetch_all_by_condition($conditions));
	   $paged=isset($_GET['page'])?intval($_GET['page']):1;
	   $next=$paged+1;
	   $pages=ceil($num/$page_num);
	   $start=($paged-1)*$page_num;
	   $url='forum.php';
	   //return data
	   $muquan_mobile_s_data=C::t('#muquan_mobile_s#muquan_mobile_s_forum_thread')->fetch_all_by_condition($conditions, $start,$limit,$order, $sort = 'DESC');

	   //include function 
	   require_once libfile('function/attachment');
	   require_once libfile('function/post');
	   //loop
	   foreach($muquan_mobile_s_data as $i=>$value){
	     $muquan_index_list[$i]['tid']=$value['tid'];
	     $muquan_index_list[$i]['subject']=$value['subject'];
	     $muquan_index_list[$i]['author']=$value['author'];
	     $muquan_index_list[$i]['dateline']=date('Y-m-d H:i',$value['dateline']);
	     $muquan_index_list[$i]['views']=$value['views'];
	     $muquan_index_list[$i]['replies']=$value['replies'];
	     $muquan_index_list[$i]['digest']=$value['digest'];
	     $muquan_index_list[$i]['rate']=$value['rate'];
	     $muquan_index_list[$i]['displayorder']=$value['displayorder'];
	     $muquan_index_list[$i]['attachment']=$value['attachment'];
		 $muquan_index_list[$i]['highlight']=muquan_highlight($value['highlight']);
		 $muquan_index_list[$i]['avatar']=avatar($thread['authorid'],'small');
		 $data=C::t('forum_attachment_n')->fetch_all_by_id('tid:'.$value['tid'], 'tid',$value['tid'], 'aid',false,false,false,50);
		 $count=count($data);
		 $return_string='';
		 $k=0;
		 $class=($count==1)?'f cl':'';
		 $return_string.='<div class="cont '.$class.'">';
		 if($value['attachment'] == 2 && $count>0){  
			  $return_string.='<div class="pic-list-'.$count.' pic-list cl ">';	
			   //loop pic	
			   foreach($data as $value){
				   if($k<3) {
					   if($count==1){
							 $imgurl=getforumimg($value['aid'], 0, 400,250	, '');
						}
						elseif($count==2){
							$imgurl=getforumimg($value['aid'], 0, 200, 150	, '');
						}
						else{
							$imgurl=getforumimg($value['aid'], 0, 150, 120	, '');
						}
					  $return_string.= '<span class="a'.$k.'"><img class="postalbum_i" lid="img_'.$value['aid'].'"  src="'.$imgurl.'" /></span>';
				   }
				   $k++;
			   }
			   //pics num
			   if($count>3) {
			   			$return_string.='<div class="pic-num">'.lang('plugin/muquan_mobile_s', 'gong') .'<i>'.$count.'</i>'.lang('plugin/muquan_mobile_s', 'zhang') .'</div>';
		       }
			   $return_string.='</div>';
		 }
		 
		 $sdata= C::t('forum_post')->fetch_all_by_tid_position($value['posttableid'],$value['tid'],1);
         $sdata= array_shift($sdata);
         $return_string.='<div class="s">'.messagecutstr($sdata['message'], 120).'</div>';
		 $return_string.='</div>';
		 $muquan_index_list[$i]['hook']=$return_string;
		
	   }
	   include template('muquan_mobile_s:index'); 
	   return $return;
    }
	
	public function index_portal_index(){
	   global $_ms,$_G;
	   $ms_lang=lang('plugin/muquan_mobile_s');
	   //plugin setting
	   $_ms=$this->plugin_setting;
	   //pages
	   $limit=$page_num=$_ms['m_num']?$_ms['m_num']:10;
	   //count
	   $num=count(C::t('portal_article_title')->fetch_all_by_sql('','',0,20000));
	   $paged=isset($_GET['page'])?intval($_GET['page']):1;
	   $next=$paged+1;
	   $pages=ceil($num/$page_num);
	   $start=($paged-1)*$page_num;
	   $url='forum.php';
	   $data=C::t('portal_article_title')->fetch_all_by_sql($where, 'ORDER BY aid DESC', $start, $limit);
	   foreach($data as $i=>$post){
			$data[$i]['pics']=C::t('#muquan_mobile_s#muquan_mobile_s_portal_attachment')->fetch_all_by_aid($post['aid']);
			$data[$i]['picnum']=C::t('#muquan_mobile_s#muquan_mobile_s_portal_attachment')->count_by_aid($post['aid']);
			$data[$i]['time']=date('Y-m-d',$post['dateline']);
	   }
	   
	   $cats=C::t('#muquan_mobile_s#muquan_mobile_s_portal_category')->fetch_all_pid_category();
	   $portal_banner=explode("\n",$_ms['portal_banner']);
	   foreach($portal_banner as $i=>$v){
		   $portal_banner[$i]=explode(':',$v);
	   }
	   $portal_nav=explode("\n",$_ms['portal_nav']);
	   foreach($portal_nav as $i=>$v){
		   $portal_nav[$i]=explode(':',$v);
	   }	
	   include template('muquan_mobile_s:portal'); 
	   return $return;
    }
	
	public function forumdisplay_threadsimglist_output(){
	   global $_G;
	   $return = array();
       require_once libfile('function/attachment');
	   require_once libfile('function/post');
	   foreach($_G['forum_threadlist'] as $thread){
		   //return thread attachment pic
	       $data=C::t('forum_attachment_n')->fetch_all_by_id('tid:'.$thread['tid'], 'tid', $thread['tid'], 'aid',false,false,false,50);
		   $count=count($data);
		   $return_string='';
		   $k=0;
		   $class=($count==1)?'f cl':'';
		   $return_string.='<div class="cont '.$class.'">';
		   if($thread['attachment'] == 2 && $count>0){  
			   $return_string.='<div class="pic-list-'.$count.' pic-list cl ">';	
			   //loop pic	
			   foreach($data as $value){
				   if($k<3) {
					   if($count==1){
							 $imgurl=getforumimg($value['aid'], 0, 400,250	, '');
						}
						elseif($count==2){
							$imgurl=getforumimg($value['aid'], 0, 200, 150	, '');
						}
						else{
							$imgurl=getforumimg($value['aid'], 0, 150, 120	, '');
						}
					   $return_string.= '<span class="a'.$k.'"><img class="postalbum_i" lid="img_'.$value['aid'].'"  src="'.$imgurl.'" /></span>';
				   }
				   $k++;
			   }
			   //pics num
			   if($count>3) {
			   			$return_string.='<div class="pic-num">'.lang('plugin/muquan_mobile_s', 'gong') .'<i>'.$count.'</i>'.lang('plugin/muquan_mobile_s', 'zhang') .'</div>';
		       }
			   $return_string.='</div>';
		   
		  }
		   //return thread summary
		   $thread['post'] = C::t('forum_post')->fetch_all_by_tid_position($thread['posttableid'],$thread['tid'],1);
           $thread['post'] = array_shift($thread['post']);
           $return_string.='<div class="s">'.messagecutstr($thread['post']['message'], 120).'</div>';
		   $return_string.='</div>';
		   $return[]= $return_string;
	   }
	   return $return;
    }
	
	
	
}


?> 