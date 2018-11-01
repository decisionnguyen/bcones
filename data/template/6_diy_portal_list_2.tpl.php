<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('list_2');
block_get('139,142,141,140,143');?><?php include template('common/header'); $list = array();?><?php $wheresql = category_get_wheresql($cat);?><?php $list = category_get_list($cat, $wheresql, $page);?><link rel="stylesheet" type="text/css" href="template/elec_201706_bt/style/css/video.css" /><?php echo adshow("text/wp a_t");?><style id="diy_style" type="text/css"></style>
</div>

<!--[diy=diy1]--><div id="diy1" class="area"></div><!--[/diy]-->

<div class="videos-index cl">
<div class="body-container cl">
<div class="video-banner container video-container">
   <!--[diy=diy2]--><div id="diy2" class="area"><div id="frameY4VHR4" class="frame move-span cl frame-1"><div id="frameY4VHR4_left" class="column frame-1-c"><div id="frameY4VHR4_left_temp" class="move-span temp"></div><?php block_display('139');?></div></div></div><!--[/diy]-->
</div>
<div class="video-area container video-container">
   <!--[diy=diy3]--><div id="diy3" class="area"><div id="framemW5OME" class="frame move-span cl frame-1"><div id="framemW5OME_left" class="column frame-1-c"><div id="framemW5OME_left_temp" class="move-span temp"></div><?php block_display('142');?></div></div><div id="framefQiAxS" class="frame move-span cl frame-1"><div id="framefQiAxS_left" class="column frame-1-c"><div id="framefQiAxS_left_temp" class="move-span temp"></div><?php block_display('141');?></div></div><div id="frameigyZqJ" class="frame move-span cl frame-1"><div id="frameigyZqJ_left" class="column frame-1-c"><div id="frameigyZqJ_left_temp" class="move-span temp"></div><?php block_display('140');?></div></div></div><!--[/diy]-->
</div>
<!--[diy=diy_bottom]--><div id="diy_bottom" class="area"><div id="frameBqkVhq" class="frame move-span cl frame-1"><div id="frameBqkVhq_left" class="column frame-1-c"><div id="frameBqkVhq_left_temp" class="move-span temp"></div><?php block_display('143');?></div></div></div><!--[/diy]-->
</div>
</div><?php include template('common/footer'); ?>