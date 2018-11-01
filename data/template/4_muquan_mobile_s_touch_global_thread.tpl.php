<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?><?php
$return = <<<EOF

{$announcements}
EOF;
 if(is_array($global_thread)) foreach($global_thread as $i => $v) { 
$return .= <<<EOF
<li><a href="forum.php?mod=viewthread&amp;tid={$v['tid']}"><i class="top">{$ms_lang['top']}</i><em>{$v['dateline']}</em>{$v['subject']}</a></li>

EOF;
 } 
$return .= <<<EOF


EOF;
?>