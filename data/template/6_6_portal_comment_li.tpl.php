<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<li> <a name="comment_anchor_<?php echo $comment['cid'];?>"></a>
  <dl id="comment_<?php echo $comment['cid'];?>_li" class="ptm pbm cl">
    <dt class="top_in cl" style="position: relative; line-height: 20px; margin: 0 0 13px 0; color: #BBBBBB;">
      <div class="portrait"> <a href="home.php?mod=space&amp;uid=<?php echo $comment['uid'];?>" c="1"><?php echo avatar($comment[uid],middle);?></a> </div>
       <span class="z">
         <?php if(!empty($comment['uid'])) { ?> 
         <a href="home.php?mod=space&amp;uid=<?php echo $comment['uid'];?>" class="username"><?php echo $comment['username'];?></a>
         <?php } else { ?> 
         游客 
         <?php } ?>
       </span>
       <span class="cutline"></span>
       <span class="z"><?php echo dgmdate($comment[dateline]);?></span>
       <span class="y" style="padding: 0 0 0 10px;">
         <?php if(($_G['group']['allowmanagearticle'] || $_G['uid'] == $comment['uid']) && $_G['groupid'] != 7 && !$article['idtype']) { ?> 
         <a href="portal.php?mod=portalcp&amp;ac=comment&amp;op=edit&amp;cid=<?php echo $comment['cid'];?>" id="c_<?php echo $comment['cid'];?>_edit" onclick="showWindow(this.id, this.href, 'get', 0);" style="padding-right: 5px; color: #CCCCCC;">编辑</a>
         <a href="portal.php?mod=portalcp&amp;ac=comment&amp;op=delete&amp;cid=<?php echo $comment['cid'];?>" id="c_<?php echo $comment['cid'];?>_delete" onclick="showWindow(this.id, this.href, 'get', 0);" style="color: #CCCCCC;">删除</a> 
         <?php } ?> 
       </span>
      <?php if($comment['status'] == 1) { ?><b>(待审核)</b><?php } ?> 
    </dt>
    <dd><?php if($_G['adminid'] == 1 || $comment['uid'] == $_G['uid'] || $comment['status'] != 1) { ?><?php echo $comment['message'];?><?php } else { ?> 审核未通过<?php } ?></dd>
    <dd class="cl" style="padding-top: 18px;"><?php if(!isset($_G['makehtml'])) { ?><div class="reply1 y" style="height: 17px; line-height: 17px;"><a href="javascript:;" onclick="portal_comment_requote(<?php echo $comment['cid'];?>, '<?php echo $article['aid'];?>');" style="color: #BBBBBB; font-size: 14px;"><span class="s_reply"></span>我要点评</a></div> <?php } ?></dd>
  </dl>
</li>

