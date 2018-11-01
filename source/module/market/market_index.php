<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/28
 * Time: 16:56
 */
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

require DISCUZ_ROOT.'marketapi/GettingApiReturn.php';
require DISCUZ_ROOT.'marketapi/config.inc.php';

/*
if(empty($_GET['do'])){
    $_GET['do'] = 'index';
}
*/
//if($_GET['do'] == "index"){
//1.左侧排行榜的基础数据获取
        $url = 'https://public.bqi.com/public/v1/ticker';
        //欲获取市值前多少位的币种条数；
        $totalCoinItems = 200;
        $otherParams = ["convert"=>'CNY','limit'=>50];
        $totalPage = ceil($totalCoinItems/50);
        if(empty($_GET['page'])){
            $_GET['page'] = 1;
            $otherParams['start'] = 0 ;
        }else if($_GET['page'] > $totalPage ){
            $_GET['page'] = $totalPage;
            $otherParams['start'] = ($totalPage-1)*50;
        }else{
            $otherParams['start'] = ($_GET['page']-1)*50;
        }
        $api = new GettingApiReturn($url,$otherParams);
        $coinInfoArr = $api->store_output_to_assocArray();
        //$coinNum = count($coinInfoArr);

//2.页码部分处理逻辑；
        $curPageNum = $_GET['page'];
        $pageStr = "";
        if($totalPage ==1){
            $pageStr = "<div class='active'>1</div>";
        }
        if($totalPage > 1) {
            if ($curPageNum == 1) {
                $pageStr = "<div class='pageNum active'>1</div>";
                for ($i = 1; $i < $totalPage; $i++) {
                    $pageStr .= "<div class='pageNum'>" . ($i + 1) . "</div>";
                }
                $pageStr .= "<div id='nextPage'>></div>";
            } else if ($curPageNum == $totalPage) {
                $pageStr = "<div id='prePage'><</div>";
                for ($i = 1; $i < $totalPage; $i++) {
                    $pageStr .= "<div class='pageNum'>" . $i . "</div>";
                }
                $pageStr .= "<div class='pageNum active'>$totalPage</div>";
            } else {
                $curPageCssStr = "<div class='pageNum active'>" . $curPageNum . "</div>";
                $pageStr = "<div id='prePage'><</div>";
                $allPrePageStr = "";
                $allAftPageStr = "";
                for ($i = 1; $i < $curPageNum; $i++) {
                    $allPrePageStr .= "<div class='pageNum'>" . $i . "</div>";
                }
                for($i = $curPageNum+1;$i<=$totalPage;$i++){
                    $allAftPageStr .= "<div class='pageNum'>" . $i . "</div>";
                }
                $pageStr .= $allPrePageStr.$curPageCssStr.$allAftPageStr. "<div id='nextPage'>></div>";
            }
        }

//3.载入首页后，右侧指定时间段的涨跌幅数据默认为24h内市值前100名的涨幅top10.
        $otherParams = ["convert"=>'CNY','limit'=>100];
        $top100api = new GettingApiReturn($url,$otherParams);
        $top100coinInfoArr = $top100api->store_output_to_assocArray();
        $resCoinInfoArr = [];
        $assocCoinChangeArr = [];
//重新整理api返回数组，使该二维数组可以在以一维数组中某个值排序时，联动排序。
        foreach ($top100coinInfoArr as $key=>$value){
            $resCoinInfoArr[$value['name']] = $value;
            //获取涨跌幅数组
            $assocCoinChangeArr[$value['name']] = $value['percent_change_24h'];
        }
        //涨幅降序排列
            arsort($assocCoinChangeArr);
        $assocCoinChangeArr = array_slice($assocCoinChangeArr,0,10);
        $resCoinInfoHtmlStr = "";
        $i = 1 ;
        foreach($assocCoinChangeArr as $k=>$v){
            //$ascResCoinInfoArr[] = [$k=> $resCoinInfoArr[$k]];
            if($resCoinInfoArr[$k]['percent_change_24h'] >= 0){
                $resCoinInfoHtmlStr .= "<div class='row'>
     <div style='width: 40px'>$i</div>
     <div class='coin' style='width: 100px'><img src='".$resCoinInfoArr[$k]['logo']."' alt=''>".$resCoinInfoArr[$k]['symbol']."</div>
     <div style='width: 120px'>￥".$resCoinInfoArr[$k]['price_cny']."</div>
     <div style='flex: auto; text-align: right; color: red'>".$resCoinInfoArr[$k]['percent_change_24h']."%</div>
     </div>";
                $i++;
            }
        }

//4.首页右侧上方的统计数据
$coinCountParams = ['convert'=>'CNY','limit'=>5000];
$countApi = new GettingApiReturn($url,$coinCountParams);
$coinCountResArr = $countApi->store_output_to_assocArray();
//（1）获取币种数量；
$coinCountNum = count($coinCountResArr);
//(2)统计所有币种的市值与24小时交易量；
$allMarketCap = 0;
$allVolume = 0 ;
foreach ($coinCountResArr as $value){
    $allMarketCap += $value['market_cap_cny'];
    $allVolume += $value['volume_24h_cny'];
}
//(3)统计交易所数量；
$exchangeApiUrl = 'https://public.bqi.com/public/v1/exchange';
$countApi = new GettingApiReturn($exchangeApiUrl,['limit'=>1000]);
$exchangeCountResArr = $countApi->store_output_to_assocArray();
$exchangeCountNum = count($exchangeCountResArr);

        include template('market/market_index');
//}
