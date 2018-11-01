<?php
if (!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class plugin_csdn123com_toutiao {
	
	protected static $csdn123_conf = array();
	public function plugin_csdn123com_toutiao() {
		global $_G;
		if (!isset($_G['cache']['plugin'])) {
			loadcache('plugin');
		}
		self::$csdn123_conf = $_G['cache']['plugin']['csdn123com_toutiao'];
	}
	function global_footer()
	{
		global $_G;
		$cronUrl = $_G['siteurl'] . 'plugin.php?id=csdn123com_toutiao';
		dfsockopen($cronUrl);
	}
	
}
class plugin_csdn123com_toutiao_forum extends plugin_csdn123com_toutiao {
	public function post_top_output() {
		global $_G;
		$args = self::$csdn123_conf['hzw_useuid'];
		if (strpos($args, ',') === false) {
			$argsArr = array($args);
		} else {
			$argsArr = explode(',', $args);
		}
		if (in_array($_G['uid'], $argsArr)) {
			require './source/plugin/csdn123com_toutiao/common.fun.php';
			$reply_uid=getRndUid();
			$views=rand(1,1000);
			include template('csdn123com_toutiao:one_send_forum');
			return $return;
		}
	}
}
class plugin_csdn123com_toutiao_portal extends plugin_csdn123com_toutiao  {
	public function portalcp_top_output(){
		global $_G;
		$args = self::$csdn123_conf['hzw_useuid'];
		if (strpos($args, ',') === false) {
			$argsArr = array($args);
		} else {
			$argsArr = explode(',', $args);
		}
		if (in_array($_G['uid'], $argsArr)) {
			require './source/plugin/csdn123com_toutiao/common.fun.php';
			$reply_uid=getRndUid();
			$views=rand(1,1000);
			include template('csdn123com_toutiao:one_send_portal');
			return $return;
		}
	}
}

