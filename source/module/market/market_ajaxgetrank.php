<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/30
 * Time: 17:11
 * 本页面为行情主页的左侧币种市值排行榜数据的ajax请求页面。
 */
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
require DISCUZ_ROOT.'./marketapi/GettingApiReturn.php';
require DISCUZ_ROOT.'./marketapi/config.inc.php';

$url = 'https://public.bqi.com/public/v1/ticker';
//点击下拉列表后的业务逻辑；
if(!isset($_POST['page'])) {
    $otherParams = ['convert' => $_GET['convert'], 'limit' => 50];
    $rankApi = new GettingApiReturn($url, $otherParams);
    $rankedCoinInfoArr = $rankApi->store_output_to_assocArray();
    $resCoinInfoHtmlStr = "";
    foreach ($rankedCoinInfoArr as $key => $item) {
        if ($_GET['convert'] === "CNY") {
            $moneySymbol = '￥';
        } else {
            $moneySymbol = '$';
        }
        if ($item['percent_change_24h'] >= 0) {
            $changeFontColorStr = "<div style='width: 13.5%; color: red'>";
        } else {
            $changeFontColorStr = "<div style='width: 13.5%; color: green'>";
        }
        $resCoinInfoHtmlStr .= "<div class='row'>
                    <div style='width: 9%'>" . ($key + 1) . "</div>
                    <div class='flex' style='width: 13.5%'><img src='" . $item['logo'] . "' alt=''><a href='market.php?mod=detail&coinid=". $item['id']."'  style='color: #0C97E1'>" . $item['symbol'] . "</a></div>
                    <div style='width: 13.5%'>" . number_format($item["market_cap_" . strtolower($_GET['convert'])] / 1e8, 2) . "亿</div>
                    <div style='width: 16.5%'>" . number_format($item["volume_24h_" . strtolower($_GET['convert'])] / 1e4, 2) . "万</div>
                    <div style='width: 15.5%'>" . $moneySymbol . number_format($item["price_" . strtolower($_GET['convert'])], 2) . "</div>
                    <div style='width: 15.5%'>" . number_format($item['available_supply'] / 1e4) . "万</div>"
            . $changeFontColorStr . $item['percent_change_24h'] . "%</div>
                    </div>";
    }
    $totalPage = ceil($_POST['itemNumbers']/50);
    $api = new GettingApiReturn($url,$otherParams);
    $coinInfoArr = $api->store_output_to_assocArray();
    $pageStr = "";
    if($totalPage ==1){
        $pageStr = "<div class='active'>1</div>";
    }
    if($totalPage > 1) {
        $pageStr = "<div class='pageNum active'>1</div>";
        for ($i = 1; $i < $totalPage; $i++) {
           $pageStr .= "<div class='pageNum'>" . ($i + 1) . "</div>";
        }
        $pageStr .= "<div id='nextPage'>></div>";
    }
    $responseStrArr = compact(['resCoinInfoHtmlStr','pageStr']);
    echo json_encode($responseStrArr);
}else{
    //点击页码后的业务逻辑；
    $curPageNum = $_POST['page'];
    $totalPage = ceil($_POST['itemNumbers']/50);
    $otherParams = ['convert' => $_GET['convert'], 'limit' => 50,'start'=>($curPageNum-1)*50];
    $rankApi = new GettingApiReturn($url, $otherParams);
    $rankedCoinInfoArr = $rankApi->store_output_to_assocArray();
    $resCoinInfoHtmlStr = "";
    foreach ($rankedCoinInfoArr as $key => $item) {
        if ($_GET['convert'] === "CNY") {
            $moneySymbol = '￥';
        } else {
            $moneySymbol = '$';
        }
        if ($item['percent_change_24h'] >= 0) {
            $changeFontColorStr = "<div style='width: 13.5%; color: red'>";
        } else {
            $changeFontColorStr = "<div style='width: 13.5%; color: green'>";
        }
        $resCoinInfoHtmlStr .= "<div class='row'>
                    <div style='width: 9%'>" . ($key + 1 + ($curPageNum-1)*50) . "</div>
                    <div class='flex' style='width: 13.5%'><img src='" . $item['logo'] . "' alt=''><a href='market.php?mod=detail&coinid=". $item['id']."'  style='color: #0C97E1'>" . $item['symbol'] . "</a></div>
                    <div style='width: 13.5%'>" . number_format($item["market_cap_" . strtolower($_GET['convert'])] / 1e8, 2) . "亿</div>
                    <div style='width: 16.5%'>" . number_format($item["volume_24h_" . strtolower($_GET['convert'])] / 1e4, 2) . "万</div>
                    <div style='width: 15.5%'>" . $moneySymbol . number_format($item["price_" . strtolower($_GET['convert'])], 2) . "</div>
                    <div style='width: 15.5%'>" . number_format($item['available_supply'] / 1e4) . "万</div>"
            . $changeFontColorStr . $item['percent_change_24h'] . "%</div>
                    </div>";
    }
    //生成页码的样式html字符串；
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
    $responseStrArr = compact(['resCoinInfoHtmlStr','pageStr']);
    echo json_encode($responseStrArr);
}