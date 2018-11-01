<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/15
 * Time: 18:26
 */
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
require DISCUZ_ROOT.'./marketapi/GettingApiReturn.php';
require DISCUZ_ROOT.'./marketapi/config.inc.php';

$exchangeSymbol = $_GET['exchangeSymbol'];
//1.从金色财经网调取数据的业务逻辑
$coindogExchangeApiUrl = "http://api.coindog.com/api/v1/ticks/$exchangeSymbol";
$exchangeOtherParams = ['unit'=>'CNY'];
$exchangeDetailApi = new GettingApiReturn($coindogExchangeApiUrl, $exchangeOtherParams);
$exchangeDetailResArr = $exchangeDetailApi->store_output_to_assocArray();
foreach($exchangeDetailResArr as $key=>$value){
   $exchangeDetailResArr[$key]["marketCap"] = $value['vol'] * $value['close'];
}
//该市场的总市值；
$exchangeTotalMarketCap = array_sum(array_column($exchangeDetailResArr,'marketCap'));
//var_dump($exchangeTotalMarketCap);
//exit;

//2.从BQI调取数据的业务逻辑；
$curExchangeInfoArr = C::t('market_exchanges')->get_exchangeInfo_by_exchangeSymbol($exchangeSymbol);
$exchangeCodeInBQI = $curExchangeInfoArr['exchangeCodeInBQI'];
$BQIExchangeApiUrl = "https://public.bqi.com/public/v1/exchange";
if($exchangeCodeInBQI){
    $BQIOtherParams = ['code'=>$exchangeCodeInBQI];
    $BQIExchangeDetailApi = new GettingApiReturn($BQIExchangeApiUrl,$BQIOtherParams);
    $BQIExchangeDetailResArr = $BQIExchangeDetailApi->store_output_to_assocArray();
    $BQIExchangeDetailResArr = array_pop($BQIExchangeDetailResArr);
    //var_dump($BQIExchangeDetailResArr);
    //exit;
    //3.统计排名
    $BQIAllExchangeOtherParams = ['limit'=>1000];
    $BQIAllExchangeDetailApi = new GettingApiReturn($BQIExchangeApiUrl,$BQIAllExchangeOtherParams );
    $BQIAllExchangeArr = $BQIAllExchangeDetailApi->store_output_to_assocArray();
    $BQIExchangeValueArr = array_column($BQIAllExchangeArr,'TotalVolume_Cny_Day','ExchangeCode');
    $marketTotalValue = array_sum($BQIExchangeValueArr);
    arsort($BQIExchangeValueArr);
    //总市值排名
    $curExchangeRank = array_search($exchangeCodeInBQI,array_keys($BQIExchangeValueArr)) + 1;
    //总市值占比
    $valueRatio = number_format($BQIExchangeValueArr[$exchangeCodeInBQI]/$marketTotalValue,4);
}
include template('market/market_platform');