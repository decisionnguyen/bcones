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

class table_market_exchanges extends discuz_table
{
	public function __construct() {

		$this->_table = 'market_exchanges';
		$this->_pk    = 'id';

		parent::__construct();
	}

	public function get_exchangeSymbol_by_exchangeName($exchangeName){
         $exchangeInfo = DB::fetch_first("SELECT exchangeSymbol FROM %t WHERE exchangeName=%s", [$this->_table, $exchangeName]);
         return  $exchangeInfo['exchangeSymbol'];
    }

    public function get_exchangeInfo_by_exchangeCode($exchangeCode){
        return  DB::fetch_first("SELECT * FROM %t WHERE exchangeCodeInBQI=%s", [$this->_table, $exchangeCode]);
    }

    public function get_exchangeInfo_by_exchangeSymbol($exchangeSymbol){
        return  DB::fetch_first("SELECT * FROM %t WHERE exchangeSymbol=%s", [$this->_table, $exchangeSymbol]);
    }
}

?>