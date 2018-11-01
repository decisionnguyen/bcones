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

class table_market_top100coins extends discuz_table
{
	public function __construct() {

		$this->_table = 'market_top100coins';
		$this->_pk    = 'id';

		parent::__construct();
	}
    //1.增加 一条币种数据；
	public function add_coin($coinData){
        return DB::insert($this->_table,$coinData);
    }

    public function find_same_coin($coinData){
	    return DB::fetch_first("SELECT `id` FROM %t WHERE coinAbbr=%s", array($this->_table, $coinData['currency']));
    }

    public function get_coins_rank_desc(){
	    return DB::fetch_all("SELECT * FROM %t ORDER BY `marketCap` DESC", array($this->_table));
    }

    //将名称不在指定数组中的实时数据设置为NULL；
    public function set_not_in_symbols_coin_info_null($symbolsArr){
        $arr = DB::fetch_all("SELECT `coinAbbr` FROM %t ", array($this->_table));
        $existCoinSymbols = [];
        foreach($arr as $k=>$v){
            $existCoinSymbols[]= $v['coinAbbr'];
        }
        $setNullArr = array_diff($existCoinSymbols,$symbolsArr);
        if($setNullArr){
        $inStr = "('" . implode("','",$setNullArr) . "')";
        $columnNullArr = ['price'=>NULL,'vol'=>NULL,'change'=>NULL,'marketCap'=>NULL,'updateTime'=>NULL];
        return DB::update($this->_table,$columnNullArr,"coinAbbr IN $inStr");
        }else{
            return NULL;
        }
    }

    //更新指定字段
    public function  update_coin_info($coinData){
	    foreach($coinData as $key=>$value){
	        $$key = $value;
        }
        $coinArr = compact(['price','vol','change','marketCap','maxSupply','supply']);
	    $coinArr['updateTime'] = $coinData['updateTime']/1e3;
	    return DB::update($this->_table,$coinArr,"coinAbbr='".$coinData['currency'] . "'");
    }
}


?>