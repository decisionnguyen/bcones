<?php 
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_muquan_mobile_s_forum_announcement extends discuz_table
{
	public function __construct() {

		$this->_table = 'forum_announcement';
		$this->_pk    = 'id';

		parent::__construct();
	}
	
	public function fetch_by_id($id) {
		return DB::fetch_first('SELECT * FROM %t WHERE id=%d', array($this->_table, $id));
	}
	
}
?>