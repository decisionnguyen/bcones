<?php 
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_muquan_mobile_s_forum_thread extends discuz_table
{	
   public function __construct(){
		$this->_table = 'forum_thread';
		$this->_pk    = 'tid';
		parent::__construct();
	}
	
   //conidtions query for forum_thread
	public function fetch_all_by_condition($conditions, $start,$limit,$order = 'dateline', $sort = 'DESC'){
		if(!isset($conditions['sticky']))$conditions['sticky']=0; //normal thread
		$wheresql=C::t($this->_table)->search_condition($conditions);
		$wheresql.=' AND '.DB::field('displayorder',3, '<');
		$ordersql = !empty($order) ? ' ORDER BY '.DB::order($order, $sort) : '';
        $result=DB::fetch_all('SELECT * FROM %t '.$wheresql.' '.$ordersql.' '.DB::limit($start,$limit),array($this->_table),$this->_pk);
		return $result;
	}
	
	//displayorder3
	public function fetch_all_global(){
		$result=DB::fetch_all('SELECT * FROM %t WHERE '.DB::field('displayorder',3),array($this->_table),$this->_pk);
		return $result;
	}
}
?>