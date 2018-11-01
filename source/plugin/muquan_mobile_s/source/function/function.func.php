<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
//return highlight info
function muquan_highlight($highlight){
   global $_ms;
   if($_ms['m_hig']==1){
			if(!empty($highlight)) {
						$hl_string = sprintf('%02d', $highlight);
						$hl_stylestr = sprintf('%03b', $hl_string[0]);
						//highlight color
					    $highlight_color = array('', '#EE1B2E', '#EE5023', '#996600', '#3C9D40', '#2897C5', '#2B65B7', '#8F2A90', '#EC1282');
						$highlight = ' style="';
						$highlight .= $hl_stylestr[0] ? 'font-weight: bold;' : '';
						$highlight .= $hl_stylestr[1] ? 'font-style: italic;' : '';
						$highlight .= $hl_stylestr[2] ? 'text-decoration: underline;' : '';
						$highlight .= $hl_string[1] ? 'color: '.$highlight_color[$hl_string[1]].';' : '';
						$highlight .= '"';
						
			} else {
						$highlight = 'xxx';
			}
	}else{
		$highlight='zzz';
	}
	return $highlight;
}
?>