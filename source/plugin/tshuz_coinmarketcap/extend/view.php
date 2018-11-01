<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$coin = strtolower(addslashes( $_GET['coin']));
$url = 'https://api.coinmarketcap.com/v1/ticker/'.$coin.'/?convert='.$pvars['covert'];
$data = dfsockopen($url);
$data = json_decode($data,1);
$data = $data[0];
if(!$data) showmessage(lang('plugin/tshuz_coinmarketcap','XfZ2bb'));
$convert = strtoupper($pvars['covert']);
$data['market_cap_btc'] = ceil($data['market_cap_usd']/$data['price_usd']*$data['price_btc']);
$data['24h_volume_btc'] = floor($data['24h_volume_'.$pvars['covert']]/$data['price_'.$pvars['covert']]*$data['price_btc']);
$navtitle = $data['name'].lang('plugin/tshuz_coinmarketcap','R3uMEg');
include template("tshuz_coinmarketcap:view");