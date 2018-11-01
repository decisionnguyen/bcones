<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('list_1');
block_get('137,138,136');?><?php include template('common/header'); $list = array();?><?php $wheresql = category_get_wheresql($cat);?><?php $list = category_get_list($cat, $wheresql, $page);?><link rel="stylesheet" type="text/css" id="time_diy" href="template/elec_201706_bt/style/css/index.css" />
<link rel="stylesheet" type="text/css" href="template/elec_201706_bt/style/css/pindao.css" />
<script src="template/elec_201706_bt/style/js/jquery.superslide.js" type="text/javascript" type="text/javascript"></script><?php echo adshow("text/wp a_t");?><style id="diy_style" type="text/css"></style>
<div class="wp"> 
  <!--[diy=diy1]--><div id="diy1" class="area"></div><!--[/diy]--> 
</div>
<div class="categoryBt detailName" style="margin: 20px 0 0 0; background-image: url(template/elec_201706_bt/style/01.jpg);">
            <div class="second_nav_icon flex_box midCenter">
                <img src="template/elec_201706_bt/style/ht.png">
            </div>
            <div class="tag_name midCenter flex_box" style="left: 93px;"><!--分类名称:--><?php echo $cat['catname'];?></div>
            <img class="circle_icon" src="template/elec_201706_bt/style/circle_icon.png">
            <?php if(($_G['group']['allowpostarticle'] || $_G['group']['allowmanagearticle'] || $categoryperm[$catid]['allowmanage'] || $categoryperm[$catid]['allowpublish']) && empty($cat['disallowpublish'])) { ?>
           <a href="portal.php?mod=portalcp&amp;ac=article&amp;catid=<?php echo $cat['catid'];?>" class="y post" style="color: #62BAE4; margin: 60px 23px 0 0; font-size: 16px;">发布+</a>
        <?php } ?>
        </div>
<div id="ct" class="ct2 wp inside_box cl" style="margin: 0;">
  <div style="float: left; width: 790px; box-shadow: none;">
  <div class="mn cl" style="float: none; width: 100%; padding: 0; margin: 0; background: none;">
  <!--[diy=diy_topic]--><div id="diy_topic" class="area"></div><!--[/diy]-->
    <?php echo adshow("articlelist/mbm hm/1");?><?php echo adshow("articlelist/mbm hm/2");?> 
    <!--[diy=listcontenttop]--><div id="listcontenttop" class="area"></div><!--[/diy]-->
    <div class="bm" style="margin: 0; background: none;">
      <!-- 文章列表 begin -->
      <div class="list_new Framebox cl" style="padding: 0;"> 
              <div class="box recommend_article">
<div class="removeline cl">
<ul id="itemContainer">
        <?php if(is_array($list['list'])) foreach($list['list'] as $value) { ?> 
        <?php $highlight = article_title_style($value);?> 
        <?php $article_url = fetch_article_url($value);?>        
        
  <div class="mbox_list mod_art_list cl">
    <a href="<?php echo $article_url;?>" target="_blank" class="mod_art_list_pic"><img src="<?php echo $value['pic'];?>" alt="<?php echo $value['title'];?>"/></a>
    <div class="mod_art_list_content">
      <h3 class="list_title"><a href="<?php echo $article_url;?>" target="_blank" <?php echo $highlight;?>><?php echo $value['title'];?></a></h3>
      <div class="mod_art_list_info" style="font-family: Arial, Helvetica, sans-serif;"><a href="home.php?mod=space&amp;uid=<?php echo $comment['authorid'];?>" class="author1"><?php echo avatar($value[uid],middle);?></a><a href="home.php?mod=space&amp;uid=<?php echo $value['uid'];?>" target="_blank"><?php echo $value['username'];?></a><span style="padding: 0 10px 0 20px;"><?php echo $value['dateline'];?></span></div>
      <div class="mod_art_list_simple"><?php echo $value['summary'];?></div>    
      <div class="column-link-box">
         <a href="<?php echo $portalcategory[$value['catid']]['caturl'];?>" class="column-link" target="_blank"><?php echo $value['catname'];?></a>
      </div>  
    </div>
  </div>
        <?php } ?> 
        </ul>
</div>
</div>
      </div>
      <!-- 文章列表 end --> 
      <!--[diy=listloopbottom]--><div id="listloopbottom" class="area"></div><!--[/diy]--> 
    </div>
    <?php echo adshow("articlelist/mbm hm/3");?><?php echo adshow("articlelist/mbm hm/4");?>    
    <!--[diy=diycontentbottom]--><div id="diycontentbottom" class="area"></div><!--[/diy]--> 
  </div>
    <?php if($list['multi']) { ?>
    <div class="pgs cl" style="margin-top: 0;"><?php echo $list['multi'];?></div>
    <?php } ?>
  </div>
  <div class="sd pph" style="box-shadow: none; margin-top: 30px;">
    <div class="drag"> 
      <!--[diy=diyrighttop]--><div id="diyrighttop" class="area"></div><!--[/diy]--> 
    </div>
    
    <!-- 分类 -->
    <?php if($cat['subs']) { ?>
      
      <div class="right-item hot-tags" style="padding-bottom: 20px;">
                    <div class="right-Bt">
                        <span class="z fl_bt classify_logo">精彩频道</span>
                    </div>
                    <ul class="hot-tags-list">
          <?php if(is_array($cat['subs'])) foreach($cat['subs'] as $value) { ?>          <li><a href="<?php echo $portalcategory[$value['catid']]['caturl'];?>"><?php echo $value['catname'];?></a></li>
          <?php } ?>
                                            </ul>
                </div>
      
      <?php } elseif($cat['others']) { ?>
      
            <div class="right-item hot-tags" style="padding-bottom: 20px;">
                    <div class="right-Bt">
                        <span class="z fl_bt classify_logo">相关分类</span>
                    </div>
                    <ul class="hot-tags-list">
          <?php if(is_array($cat['others'])) foreach($cat['others'] as $value) { ?>          <li><a href="<?php echo $portalcategory[$value['catid']]['caturl'];?>"><?php echo $value['catname'];?></a></li>
          <?php } ?>
                                            </ul>
                </div>
      
      <?php } ?>
    <!-- 推荐阅读 -->
    <div class="sbody cl" style="margin: 0;">
      <!--[diy=sbody]--><div id="sbody" class="area"><div id="frameA076Sr" class="frame move-span cl frame-1"><div id="frameA076Sr_left" class="column frame-1-c"><div id="frameA076Sr_left_temp" class="move-span temp"></div><?php block_display('137');?></div></div><div id="frameOFoVTt" class="frame move-span cl frame-1"><div id="frameOFoVTt_left" class="column frame-1-c"><div id="frameOFoVTt_left_temp" class="move-span temp"></div><?php block_display('138');?></div></div><div id="frameWdhc01" class="frame move-span cl frame-1"><div id="frameWdhc01_left" class="column frame-1-c"><div id="frameWdhc01_left_temp" class="move-span temp"></div><?php block_display('136');?></div></div></div><!--[/diy]--> 
    </div>

    <div class="drag"> 
      <!--[diy=diy2]--><div id="diy2" class="area"></div><!--[/diy]--> 
    </div>
  </div>
</div>
<div class="wp mtn"> 
  <!--[diy=diy3]--><div id="diy3" class="area"></div><!--[/diy]--> 
</div>
<script type="text/javascript">
   jQuery(".focusBox").slide({ mainCell:".bd ul",effect:"fold",autoPlay:true,delayTime:300});
</script><?php include template('common/footer'); ?>