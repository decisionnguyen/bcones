<?php

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
require DISCUZ_ROOT.'./marketapi/GettingApiReturn.php';
require DISCUZ_ROOT.'./marketapi/config.inc.php';
$url = 'https://public.bqi.com/public/v1/ticker';
$otherParams = ["convert"=>'CNY','limit'=>5000];
$api = new GettingApiReturn($url,$otherParams);
$coinInfoArr = $api->store_output_to_assocArray();
$symbol = $_POST['symbol'];
$searchRes = [];
foreach ($coinInfoArr as $value){
    if (strtoupper($symbol) === $value['symbol']){
        $searchRes = $value;
        break;
    }
}
if (empty($searchRes)){
    $searchResHtmlStr = '<b>未搜索到输入的币种</b>';
}else{
    if ($searchRes['percent_change_24h'] >= 0) {
        $changeFontColorStr = "<div style='width: 13.5%; color: red'>";
    } else {
        $changeFontColorStr = "<div style='width: 13.5%; color: green'>";
    }
    $searchResHtmlStr .= "<div class='row'>
                    <div style='width: 9%'>" . 1 . "</div>
                    <div class='flex' style='width: 13.5%'><img src='" . $searchRes['logo'] . "' alt=''><a href='market.php?mod=detail&coinid=". $searchRes['id']."'  style='color: #0C97E1'>" . $searchRes['symbol'] . "</a></div>
                    <div style='width: 13.5%'>" . number_format($searchRes["market_cap_cny"] / 1e8, 2) . "亿</div>
                    <div style='width: 16.5%'>" . number_format($searchRes["volume_24h_cny"] / 1e4, 2) . "万</div>
                    <div style='width: 15.5%'>" . '￥'. number_format($searchRes["price_cny" ], 2) . "</div>
                    <div style='width: 15.5%'>" . number_format($searchRes['available_supply'] / 1e4) . "万</div>"
        . $changeFontColorStr . $searchRes['percent_change_24h'] . "%</div>
                    </div>";
    
}
echo $searchResHtmlStr;