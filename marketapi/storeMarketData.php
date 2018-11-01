<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/12
 * Time: 15:08
 */
ini_set("max_execution_time",0);
header("Content-type: text/html; charset=utf-8");
require_once 'GettingApiReturn.php';
require_once 'config.inc.php';
require_once 'dbconfig.inc.php';
$url = 'http://api.coindog.com/api/v1/symbols';
$api = new GettingApiReturn($url);
$returnInfoArr = $api->store_output_to_assocArray();
//var_dump($returnInfoArr);
//exit;
$exchange = new Pre_Market_Exchanges($pdo);
$ticker = new Pre_Market_Tickers($pdo);
$coin = new Pre_Market_Coins($pdo);
$exchange->truncate();
$ticker->truncate();
$coin->truncate();

foreach ($returnInfoArr as $item){

   if (! $exchange->select_fetchOne(['columns' => 'id', 'where' => "exchangeName='".$item['exchangeTraded']."'"])){
       $exchangeInfo['exchangeName'] = $item['exchangeTraded'];
	   $tickerArr = explode(':',$item['ticker']);
       $exchangeInfo['exchangeSymbol'] = $tickerArr[0];
       $exchange->insert($exchangeInfo);
   }

   if (! $coin->select_fetchOne(['columns' => 'id', 'where' => "coinAbbr='".$item['currency']."'"])){
       $coinInfo['coinName'] = $item['name_en'];
       $coinInfo['coinAbbr'] = $item['currency'];
       $coinInfo['coinCnName'] = $item['name_cn'];
	   $coin->insert($coinInfo);
   }

   $verifyTickerRes = $ticker->select_fetchOne(['columns' => 'id', 'where' => "tickerName='".$item['ticker']."'"]);
   $exchangeRes = $exchange->select_fetchOne(['columns' => 'id,exchangeName', 'where' => "exchangeName='".$item['exchangeTraded']."'"]);
   $coinRes = $coin->select_fetchOne(['columns' => 'id,coinAbbr', 'where' => "coinAbbr='".$item['currency']."'"]);

   if (!$verifyTickerRes && $exchangeRes && $coinRes){
       $tickerInfo['tickerName'] = $item['ticker'];
       $tickerInfo['exchangeId'] = $exchangeRes['id'];
       $tickerInfo['exchangeName'] = $exchangeRes['exchangeName'];
       $tickerInfo['coinId'] = $coinRes['id'];
       $tickerInfo['coinAbbr'] = $coinRes['coinAbbr'];
       $ticker->insert($tickerInfo);
   }
}
echo 'Finish!!!';
