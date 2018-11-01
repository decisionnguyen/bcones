<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_common_admincp_cmenu.php 27806 2012-02-15 03:20:46Z svn_project_zhangjie $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_market_coins extends discuz_table
{
	public function __construct() {

		$this->_table = 'market_coins';
		$this->_pk    = 'id';

		parent::__construct();
	}

	public function get_coinName_by_symbol($symbol){
        $coin =  DB::fetch_first("SELECT * FROM %t WHERE coinAbbr=%s", [$this->_table, $symbol]);
        return $coin['coinName'];
    }


}

?>