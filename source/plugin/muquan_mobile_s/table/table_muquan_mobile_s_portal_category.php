<?php 
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_muquan_mobile_s_portal_category extends discuz_table
{
	public function __construct() {

		$this->_table = 'portal_category';
		$this->_pk    = 'catid';

		parent::__construct();
	}
	
	public function fetch_all_pid_category() {
		return DB::fetch_all('SELECT * FROM %t WHERE upid=%d ORDER BY '.DB::order('displayorder','asc'), array($this->_table, 0));
	}
	
}
?>