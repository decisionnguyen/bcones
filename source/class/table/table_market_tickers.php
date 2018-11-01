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

class table_market_tickers extends discuz_table
{
	public function __construct() {

		$this->_table = 'market_tickers';
		$this->_pk    = 'id';

		parent::__construct();
	}
    //1.给定币种代号，获取包含该币种交易的所有市场及该市场的一条交易对（优先以美元、泰达币计价）；
	public function get_tickers_by_symbol_usdStr_prior_exchangeUnique($symbol){
        $tickersArr =  DB::fetch_all("SELECT *,COUNT(distinct exchangeId) FROM %t WHERE coinAbbr=%s GROUP BY exchangeId", array($this->_table,$symbol));
        foreach($tickersArr as $key=>$value){
            if (!strpos($value['tickerName'],'USD')){
               $res = DB::fetch_first("SELECT * FROM %t WHERE coinAbbr=%s AND exchangeName=%s AND LOCATE('USD',tickerName)", array($this->_table, $symbol, $value['exchangeName']));
               if($res){
                   $tickersArr[$key] = $res;
               }
            }
        }
        return $tickersArr;
    }


}

?>