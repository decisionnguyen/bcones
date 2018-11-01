<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/29
 * Time: 15:06
 * 本页面为行情主页的右侧涨幅排行榜数据的ajax请求页面。
 */
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
require DISCUZ_ROOT.'./marketapi/GettingApiReturn.php';
require DISCUZ_ROOT.'./marketapi/config.inc.php';

if(empty($_GET['direction'])){
    $_GET['direction'] = 'up';
}
if(empty($_GET['period'])){
    $_GET['period'] = 'percent_change_24h';
}
$url = 'https://public.bqi.com/public/v1/ticker';
$otherParams = ["convert"=>'CNY','limit'=>100];
$api = new GettingApiReturn($url,$otherParams);
$coinInfoArr = $api->store_output_to_assocArray();
$resCoinInfoArr = [];
$assocCoinChangeArr = [];
//重新整理api返回数组，使该二维数组可以在以一维数组中某个值排序时，联动排序。
foreach ($coinInfoArr as $key=>$value){
    $resCoinInfoArr[$value['name']] = $value;
    //获取涨跌幅数组
    $assocCoinChangeArr[$value['name']] = $value[$_GET['period']];
}
    //涨幅降序排列
if($_GET['direction'] == "up"){
    arsort($assocCoinChangeArr);
}
    //跌幅（负数）升序排列
if($_GET['direction'] == "down"){
    asort($assocCoinChangeArr);
}
    $assocCoinChangeArr = array_slice($assocCoinChangeArr,0,10);
    $resCoinInfoHtmlStr = "";
    $i = 1 ;
    foreach($assocCoinChangeArr as $k=>$v){
        //$ascResCoinInfoArr[] = [$k=> $resCoinInfoArr[$k]];
        if($resCoinInfoArr[$k][$_GET['period']] >= 0){
        $resCoinInfoHtmlStr .= "<div class='row'>
     <div style='width: 40px'>$i</div>
     <div class='coin' style='width: 100px'><img src='".$resCoinInfoArr[$k]['logo']."' alt=''>".$resCoinInfoArr[$k]['symbol']."</div>
     <div style='width: 120px'>￥".$resCoinInfoArr[$k]['price_cny']."</div>
     <div style='flex: auto; text-align: right; color: red'>".$resCoinInfoArr[$k][$_GET['period']]."%</div>
     </div>";
        $i++;
    }else{
            $resCoinInfoHtmlStr .= "<div class='row'>
     <div style='width: 40px'>$i</div>
     <div class='coin' style='width: 100px'><img src='".$resCoinInfoArr[$k]['logo']."' alt=''>".$resCoinInfoArr[$k]['symbol']."</div>
     <div style='width: 120px'>￥".$resCoinInfoArr[$k]['price_cny']."</div>
     <div style='flex: auto; text-align: right; color: #00D884'>".$resCoinInfoArr[$k][$_GET['period']]."%</div>
     </div>";
            $i++;
        }
    }
    echo $resCoinInfoHtmlStr;
