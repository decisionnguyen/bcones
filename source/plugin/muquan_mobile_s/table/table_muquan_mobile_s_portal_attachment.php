<?php 
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_muquan_mobile_s_portal_attachment extends discuz_table
{	
    public function __construct() {
		$this->_table = 'portal_attachment';
		$this->_pk    = 'attachid';
		parent::__construct();
	}
	
   public function fetch_all_by_aid($aid) {
		return ($aid = dintval($aid, true)) ? DB::fetch_all('SELECT * FROM %t WHERE '.DB::field('aid', $aid).'  AND '.DB::field('isimage', 1).' ORDER BY attachid DESC '.DB::limit(3), array($this->_table), $this->_pk) : array();
   }
   
   public function count_by_aid($aid) {
		return DB::result_first('SELECT COUNT(*) FROM %t WHERE '.DB::field('aid', $aid).'  AND '.DB::field('isimage', 1).' ORDER BY attachid DESC', array($this->_table), $this->_pk);
	}	
	
}
?>