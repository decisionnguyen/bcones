<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('view_2');
0
|| checktplrefresh('data/diy/./template/elec_201706_bt//portal/view_2.htm', './template/elec_201706_bt/portal/portal_comment.htm', 1540598369, 'diy', './data/template/6_diy_portal_view_2.tpl.php', 'data/diy/./template/elec_201706_bt/', 'portal/view_2')
|| checktplrefresh('data/diy/./template/elec_201706_bt//portal/view_2.htm', './template/default/common/seccheck.htm', 1540598369, 'diy', './data/template/6_diy_portal_view_2.tpl.php', 'data/diy/./template/elec_201706_bt/', 'portal/view_2')
;?><?php include template('common/header'); ?><link rel="stylesheet" type="text/css" href="template/elec_201706_bt/style/css/pindao.css" />
<script src="template/elec_201706_bt/style/js/jquery.superslide.js" type="text/javascript" type="text/javascript"></script>
<script src="template/elec_201706_bt/style/js/about.js" type="text/javascript"></script>
<script src="template/elec_201706_bt/style/js/jquery_002.js" type="text/javascript" type="text/javascript"></script>
<script src="<?php echo $_G['setting']['jspath'];?>forum_viewthread.js?<?php echo VERHASH;?>" type="text/javascript"></script>
<script type="text/javascript">zoomstatus = parseInt(<?php echo $_G['setting']['zoomstatus'];?>), imagemaxwidth = '<?php echo $_G['setting']['imagemaxwidth'];?>', aimgcount = new Array();</script>
<script src="<?php echo $_G['setting']['jspath'];?>home.js?<?php echo VERHASH;?>" type="text/javascript"></script>

<?php if(!empty($_G['setting']['pluginhooks']['view_article_top'])) echo $_G['setting']['pluginhooks']['view_article_top'];?><?php echo adshow("text/wp a_t");?><style id="diy_style" type="text/css"></style>
<div id="pt" class="bm cl" style="display: none;">
  <div class="z"> <a href="./" class="nvhm" title="首页"><?php echo $_G['setting']['bbname'];?></a> <em>&rsaquo;</em> <a href="<?php echo $_G['setting']['navs']['1']['filename'];?>"><?php echo $_G['setting']['navs']['1']['navname'];?></a> <em>&rsaquo;</em> 
    <?php if(is_array($cat['ups'])) foreach($cat['ups'] as $value) { ?> 
    <a href="<?php echo getportalcategoryurl($value['catid']); ?>"><?php echo $value['catname'];?></a><em>&rsaquo;</em> 
    <?php } ?> 
    <a href="<?php echo getportalcategoryurl($cat['catid']); ?>"><?php echo $cat['catname'];?></a> <em>&rsaquo;</em> 查看内容 </div>
</div>
<div class="wp" style="margin-top: 20px;"> 
  <!--[diy=diy1]--><div id="diy1" class="area"></div><!--[/diy]--> 
</div>
<div id="ct" class="ct2 wp inside_box jumpto-cotainer cl" style="padding-top: 5px;">
  <div class="mn" style="padding: 0; margin: 0; box-shadow: none; background: none;">
  
  
  
   <div id="flow_box" class="viewthread_foot about-subnav cl" style="float: left; width: 60px; margin: 0; z-index: 10;">
   <ul>
<div class="bdsharebuttonbox" style="padding: 0 5px 20px 0;">
<li class="cl"><a title="分享到新浪微博" href="#" class="bds_tsina" data-cmd="tsina"></a></li>
<li class="cl"><a title="分享到微信" href="#" class="bds_weixin" data-cmd="weixin"></a></li>
<li class="cl" style="border-bottom: 0;"><a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间">QQ空间</a></li>
<li class="cl" style="margin-top: 30px;"><a href="#section-1" title="评论" class="bds_reply" id="divGoToBottom"><i class="icon-article-col">回复</i></a></li>
<script type="text/javascript">
  jQuery(document).ready( function() {
    jQuery(".jumpto-cotainer").jumpto();
  });
  	</script>
<li class="cl" style="border-bottom: 0;"><a href="home.php?mod=spacecp&amp;ac=favorite&amp;type=article&amp;id=<?php echo $article['aid'];?>&amp;handlekey=favoritearticlehk_<?php echo $article['aid'];?>" id="a_favorite" onclick="showWindow(this.id, this.href, 'get', 0);" class="k_favorite"><i class="icon-article-col">收藏</i></a></li>
</div>
<script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdPic":"","bdStyle":"0","bdSize":"16"},"share":{}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>
    </ul>
   </div>

<script type="text/javascript">
      jQuery(".viewthread_foot").sticky({ topSpacing: 50,bottomSpacing: 1010});
</script>


    <div class="Framebox cl" style="float: right; width: 800px; padding: 0 0 10px 0;">
      <div class="middle_info cl">
        <div class="bm vw" style="background: none;"> 
          <?php include template('portal/page_info'); ?>          <div class="h hm cl">
            <div class="cl">
              <h1 class="ph"><?php echo $article['title'];?> <?php if($article['status'] == 1) { ?>(待审核)<?php } elseif($article['status'] == 2) { ?>(已忽略)<?php } ?></h1>
            </div>
            <div class="avatar_info cl">
              <div class="cl" style="float: left; width: 100%; overflow: hidden;">
                <p class="authors" style="margin: 0 0 5px 0; font-size: 14px;">
                  <a href="home.php?mod=space&amp;uid=<?php echo $article['uid'];?>"><?php echo $article['username'];?></a>
                  <span style="float: left; margin-left: 20px;"><?php echo $article['dateline'];?></span>
                  <span class="focus_num"><?php if($article['viewnum'] > 0) { ?><?php echo $article['viewnum'];?><?php } else { ?>0<?php } ?>人围观</span>
                  <span style="float: right; margin-right: 20px; color: #62BAE4;"><?php echo $cat['catname'];?></span>
                </p>
              </div>
            </div>
          </div>
          <div class="content_middle cl" style="padding: 0;">
            
            <!--[diy=diysummarytop]-->
            <div id="diysummarytop" class="area"></div>
            <!--[/diy]--> 
            
            <!--[diy=diysummarybottom]-->
            <div id="diysummarybottom" class="area"></div>
            <!--[/diy]-->
            <div class="d"> 
              <!--[diy=diycontenttop]-->
              <div id="diycontenttop" class="area"></div>
              <!--[/diy]-->
              
              <table cellpadding="0" cellspacing="0" class="vwtb">
                <tr>
                  <td id="article_content"><?php echo adshow("article/a_af/1");?> 
                    <?php if($content['title']) { ?>
                    
                    <div class="vm_pagetitle xw1"><?php echo $content['title'];?></div>
                    
                    <?php } ?> 
                    <?php echo $content['content'];?> </td>
                </tr>
              </table>
              <?php if(!empty($_G['setting']['pluginhooks']['view_article_content'])) echo $_G['setting']['pluginhooks']['view_article_content'];?> 
              <?php if($multi) { ?>
              <div class="ptw pbw cl"><?php echo $multi;?></div>
              <?php } ?>
              <div class="o cl ptm pbm" style="display: none;"> 
                <?php if(!empty($_G['setting']['pluginhooks']['view_article_op_extra'])) echo $_G['setting']['pluginhooks']['view_article_op_extra'];?> 
                <a href="misc.php?mod=invite&amp;action=article&amp;id=<?php echo $article['aid'];?>" id="a_invite" onclick="showWindow('invite', this.href, 'get', 0);" class="oshr oivt" style=" display:none;">邀请</a> </div>
              <!--[diy=diycontentbottom]-->
              <div id="diycontentbottom" class="area"></div>
              <!--[/diy]--> 
              <?php if(!empty($contents)) { ?>
              <div id="inner_nav" class="ptn xs1">
                <h3>本文导航</h3>
                <ul class="xl xl2 cl">
                  <?php if(is_array($contents)) foreach($contents as $key => $value) { ?> 
                  <?php $curpage = $key+1;?> 
                  <?php $inner_view_url = helper_page::mpurl($viewurl, 'page=', $curpage);?>                  <li>&bull; <a href="<?php echo $inner_view_url;?>"<?php if($key === $start) { ?> class="xi1"<?php } ?>>第 <?php echo $curpage;?> 页 <?php echo $value['title'];?></a></li>
                  <?php } ?>
                </ul>
              </div>
              <?php } ?> 
              <!--[diy=diycontentclickbottom]-->
              <div id="diycontentclickbottom" class="area"></div>
              <!--[/diy]--> 
            </div>
            <?php if(!empty($aimgs[$content['pid']])) { ?> 
            <script type="text/javascript" reload="1">aimgcount[<?php echo $content['pid'];?>] = [<?php echo implode(',', $aimgs[$content['pid']]);; ?>];attachimgshow(<?php echo $content['pid'];?>);</script> 
            <?php } ?> 
            <?php if(!empty($_G['setting']['pluginhooks']['view_share_method'])) { ?>
            <div class="tshare cl"> <strong>!viewthread_share_to!:</strong> 
              <?php if(!empty($_G['setting']['pluginhooks']['view_share_method'])) echo $_G['setting']['pluginhooks']['view_share_method'];?> 
            </div>
            <?php } ?>
            <div id="click_div"> 
              <?php include template('home/space_click'); ?> 
            </div>
            <!--[diy=diycontentrelatetop]-->
            <div id="diycontentrelatetop" class="area"></div>
            <!--[/diy]--> 
            <?php if($article['preaid'] || $article['nextaid']) { ?>
            <div class="pren pbm cl" style="margin-top: 30px;"> 
              <?php if($article['prearticle']) { ?><em class="z"><span class="i_prev" title="上一篇"></span><a href="<?php echo $article['prearticle']['url'];?>" title="上一篇"><?php echo $article['prearticle']['title'];?></a></em><?php } ?> 
              <?php if($article['nextarticle']) { ?><em class="y"><span class="i_next" title="下一篇"></span><a href="<?php echo $article['nextarticle']['url'];?>" title="下一篇"><?php echo $article['nextarticle']['title'];?></a></em><?php } ?> 
            </div>
            <?php } ?> 
            <div class="contacts cl">
                  <?php if($article['author']) { ?><span class="pipe" style="float: left;"></span>原作者: <?php echo $article['author'];?><?php } ?> 
                  <?php if($article['from']) { ?><span class="pipe" style="float: left;"></span>来自: <?php if($article['fromurl']) { ?><a href="<?php echo $article['fromurl'];?>" target="_blank"><?php echo $article['from'];?></a><?php } else { ?><?php echo $article['from'];?><?php } } ?> 
                  <?php if($_G['group']['allowmanagearticle'] || ($_G['group']['allowpostarticle'] && $article['uid'] == $_G['uid'] && (empty($_G['group']['allowpostarticlemod']) || $_G['group']['allowpostarticlemod'] && $article['status'] == 1)) || $categoryperm[$value['catid']]['allowmanage']) { ?> 
                  <span class="pipe" style="float: left;"></span><a href="portal.php?mod=portalcp&amp;ac=article&amp;op=edit&amp;aid=<?php echo $article['aid'];?>">编辑</a> 
                  <?php if($article['status']>0 && ($_G['group']['allowmanagearticle'] || $categoryperm[$value['catid']]['allowmanage'])) { ?> 
                  <span class="pipe" style="float: left;"></span><a href="portal.php?mod=portalcp&amp;ac=article&amp;op=verify&amp;aid=<?php echo $article['aid'];?>" id="article_verify_<?php echo $article['aid'];?>" onClick="showWindow(this.id, this.href, 'get', 0);">审核</a> 
                  <?php } else { ?> 
                  <span class="pipe" style="float: left;"></span><a href="portal.php?mod=portalcp&amp;ac=article&amp;op=delete&amp;aid=<?php echo $article['aid'];?>" id="article_delete_<?php echo $article['aid'];?>" onClick="showWindow(this.id, this.href, 'get', 0);">删除</a><span class="pipe" style="float: left;"></span> 
                  <?php } ?> 
                  <a  id="related_article" href="portal.php?mod=portalcp&amp;ac=related&amp;aid=<?php echo $article['aid'];?>&amp;catid=<?php echo $article['catid'];?>&amp;update=1" onClick="showWindow(this.id, this.href, 'get', 0);return false;">添加相关文章</a><span class="pipe" style="float: left;"></span> 
                  <?php } ?> 
                  <?php if($article['status']==0 && ($_G['group']['allowdiy']  || getstatus($_G['member']['allowadmincp'], 4) || getstatus($_G['member']['allowadmincp'], 5) || getstatus($_G['member']['allowadmincp'], 6))) { ?> 
                  <a href="portal.php?mod=portalcp&amp;ac=portalblock&amp;op=recommend&amp;idtype=aid&amp;id=<?php echo $article['aid'];?>" onClick="showWindow('recommend', this.href, 'get', 0)">模块推送</a> 
                  <?php } ?> 
                  <?php if(!empty($_G['setting']['pluginhooks']['view_article_subtitle'])) echo $_G['setting']['pluginhooks']['view_article_subtitle'];?>
            </div>
            <?php echo adshow("article/mbm hm/2");?><?php echo adshow("article/mbm hm/3");?> 
            

            
          </div>
          <!--[diy=diycontentrelate]-->
          <div id="diycontentrelate" class="area"></div>
          <!--[/diy]--> 
        </div>
      </div>
      <?php if($article['allowcomment']==1) { ?> 
      <?php $data = &$article?> 
          <?php if(!$data['htmlmade']) { ?>
    <div id="section-1" class="reply_box cl">
    <div class="tag_box cl" style="padding-top: 6px; margin-bottom: 8px; font-size: 16px; color: #474e5d; font-weight: 400;">我有话说......</div>
    <div class="comment_box cl">
     <form id="cform" name="cform" action="<?php echo $form_url;?>" method="post" autocomplete="off">
<div class="tedt" id="tedt">
<div class="area">
<textarea name="message" rows="3" class="pt" id="message"  <?php if(!$_G['uid']) { ?> placeholder="登录后才能发表内容及参与互动"<?php } ?> onkeydown="ctrlEnter(event, 'commentsubmit_btn');"></textarea>
</div>
</div>
                <?php if($_G['uid']) { ?>
                <div class="tedt_down cl">


<?php if($secqaacheck || $seccodecheck) { ?><?php
$sectpl = <<<EOF
<sec> <span id="sec<hash>" onclick="showMenu(this.id);"><sec></span><div id="sec<hash>_menu" class="p_pop p_opt" style="display:none"><sec></div>
EOF;
?>
<div class="z" style="margin: 0 -12px 0 10px;"><?php $sechash = !isset($sechash) ? 'S'.($_G['inajax'] ? 'A' : '').$_G['sid'] : $sechash.random(3);
$sectpl = str_replace("'", "\'", $sectpl);?><?php if($secqaacheck) { ?>
<span id="secqaa_q<?php echo $sechash;?>"></span>		
<script type="text/javascript" reload="1">updatesecqaa('q<?php echo $sechash;?>', '<?php echo $sectpl;?>', '<?php echo $_G['basescript'];?>::<?php echo CURMODULE;?>');</script>
<?php } if($seccodecheck) { ?>
<span id="seccode_c<?php echo $sechash;?>"></span>		
<script type="text/javascript" reload="1">updateseccode('c<?php echo $sechash;?>', '<?php echo $sectpl;?>', '<?php echo $_G['basescript'];?>::<?php echo CURMODULE;?>');</script>
<?php } ?></div>
<?php } if(!empty($topicid) ) { ?>
<input type="hidden" name="referer" value="<?php echo $topicurl;?>#comment" />
<input type="hidden" name="topicid" value="<?php echo $topicid;?>">
<?php } else { ?>
<input type="hidden" name="portal_referer" value="<?php echo $viewurl;?>#comment">
<input type="hidden" name="referer" value="<?php echo $viewurl;?>#comment" />
<input type="hidden" name="id" value="<?php echo $data['id'];?>" />
<input type="hidden" name="idtype" value="<?php echo $data['idtype'];?>" />
<input type="hidden" name="aid" value="<?php echo $aid;?>">
<?php } ?>
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>">
<input type="hidden" name="replysubmit" value="true">
<input type="hidden" name="commentsubmit" value="true" />
<button type="submit" name="commentsubmit_btn" id="commentsubmit_btn" value="true" class="pn y">发布</button>
                </div>
                <?php } ?>
</form>
           </div>
    </div>
    
    
    

   
    
        <script type="text/javascript">
    jQuery(function(){
jQuery("#tedt .pt").focus(function(){
  jQuery(this).addClass("bgchange");
}).blur(function(){
  jQuery(this).removeClass("bgchange");
});
    });
    </script> 

    
    <?php } ?>
<div id="comment" class="bm cl">
  <div class="comment_tit cl" style="display: none;"> 
    <a><strong><?php echo $data['commentnum'];?></strong> 个评论</a>
  </div>
  <div id="comment_ul"> 
    
    <?php if(!empty($pricount)) { ?>
    <p class="mtn mbn y">提示：本页有 <?php echo $pricount;?> 个评论因未通过审核而被隐藏</p>
    <?php } ?> 
     
    <ul>
    <?php if(is_array($commentlist)) foreach($commentlist as $comment) { ?> 
    <?php $i++;?>    <?php settype($data[commentnum], 'integer');?>    <?php $floor = $data[commentnum] - $i + 1;?>    <?php include template('portal/comment_li'); ?> 
    <?php if(!empty($aimgs[$comment['cid']])) { ?> 
    <script type="text/javascript" reload="1">aimgcount[<?php echo $comment['cid'];?>] = [<?php echo implode(',', $aimgs[$comment['cid']]);; ?>];attachimgshow(<?php echo $comment['cid'];?>);</script> 
    <?php } ?> 
     <?php if($i==6)break;?>    <?php } ?>
    </ul>
    <?php if($i > 5) { ?>
    <p class="ptn cl" style=" text-align:center">
       <a href="<?php echo $common_url;?>" class="xi2">查看全部评论>></a>
      </p>
    <?php } ?>  
  </div>
</div>
            <?php if($article['related']) { ?>
            <div id="related_article" class="bm">
              <div class="tag_box cl" style="margin-bottom: 0;">
                <span class="span-mark-author">相关文章</span>
              </div>
              <div class="bm_c">
                <ul class="xl xl2 cl" id="raid_div">
                  <?php if(is_array($article['related'])) foreach($article['related'] as $raid => $rvalue) { ?>                  <input type="hidden" value="<?php echo $raid;?>" />
                  <li><a href="<?php echo $rvalue['uri'];?>"><?php echo $rvalue['title'];?></a></li>
                  <?php } ?>
                </ul>
              </div>
            </div>
            <?php } ?>
 
      <?php } ?> 
      <!--[diy=diycontentcomment]-->
      <div id="diycontentcomment" class="area"></div>
      <!--[/diy]--> 
    </div>
  </div>
  <div class="sd pph" style="width: 282px; padding: 0; box-shadow: none; background: none;">
    <?php if($cat['others']) { ?>
<div class="box-moder" style="margin-bottom: 20px;">
            <h3><span class="span-mark span-mark2"></span><b>相关分类</b></h3>
<div class="portal_sort Framebox2 cl" style="width: 266px; margin: 0 0 0 20px;">
<ul class="cl"><?php if(is_array($cat['others'])) foreach($cat['others'] as $value) { ?><li style="width: 116px;"><a href="<?php echo getportalcategoryurl($value['catid']); ?>"><?php echo $value['catname'];?></a></li>
<?php } ?>
</ul>
</div>
</div>
<?php } if($cat['subs']) { ?>
<div class="box-moder" style="margin-bottom: 20px;">
            <h3><span class="span-mark span-mark2"></span><b>下级分类</b></h3>
<div class="portal_sort Framebox2 cl" style="width: 266px; margin: 0 0 0 20px;">
<ul class="cl"><?php if(is_array($cat['subs'])) foreach($cat['subs'] as $value) { ?><li style="width: 116px;"><a href="<?php echo getportalcategoryurl($value['catid']); ?>"><?php echo $value['catname'];?></a></li>
<?php } ?>
</ul>
</div>
</div>
<?php } ?>
    <!--[diy=diy2]--><div id="diy2" class="area"></div><!--[/diy]-->
    <!--[diy=diy6]--><div id="diy6" class="area"></div><!--[/diy]-->
    <div id="recommendArticle"> 
      <!--[diy=diy7]-->
      <div id="diy7" class="area"></div>
      <!--[/diy]--> 
    </div>
  </div>
</div>

<?php if($_G['relatedlinks']) { ?> 
<script type="text/javascript">
var relatedlink = [];<?php if(is_array($_G['relatedlinks'])) foreach($_G['relatedlinks'] as $key => $link) { ?>relatedlink[<?php echo $key;?>] = {'sname':'<?php echo $link['name'];?>', 'surl':'<?php echo $link['url'];?>'};
<?php } ?>
relatedlinks('article_content');
</script> 
<?php } ?>

<div class="wp mtn"> 
  <!--[diy=diy3]-->
  <div id="diy3" class="area"></div>
  <!--[/diy]--> 
</div>
<input type="hidden" id="portalview" value="1">
<script type="text/javascript"> 
jQuery(function() {
jQuery("span").click(function() {
var thisEle = jQuery("#article_content").css("font-size");
var textFontSize = parseFloat(thisEle, 10);
var unit = thisEle.slice( - 2);
var cName = jQuery(this).attr("class");
if (cName == "bigger") {
if (textFontSize <= 22) {
textFontSize += 2;
}
} else if (cName == "smaller") {
if (textFontSize >= 12) {
textFontSize -= 2;
}
}
jQuery("#article_content").css("font-size", textFontSize + unit);
});
});
</script> <?php include template('common/footer'); ?>