<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('list_3');
block_get('79');?><?php include template('common/header'); $list = array();?><?php $wheresql = category_get_wheresql($cat);?><?php $list = category_get_list($cat, $wheresql, $page);?><link rel="stylesheet" type="text/css" href="template/elec_201706_bt/style/css/about.css" />
<script src="template/elec_201706_bt/style/js/about.js" type="text/javascript"></script>
<script src="template/elec_201706_bt/style/js/jquery_002.js" type="text/javascript" type="text/javascript"></script><?php echo adshow("text/wp a_t");?><style id="diy_style" type="text/css"></style>
</div>
<div class="wp" style="min-height: 500px;">
  <!--[diy=diy1]--><div id="diy1" class="area"><div id="frameMGDGvu" class="frame move-span cl frame-1"><div id="frameMGDGvu_left" class="column frame-1-c"><div id="frameMGDGvu_left_temp" class="move-span temp"></div><?php block_display('79');?></div></div></div><!--[/diy]-->
</div><?php include template('common/footer'); ?>