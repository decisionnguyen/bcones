<?php
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

require DISCUZ_ROOT.'./marketapi/config.inc.php';
require DISCUZ_ROOT.'./marketapi/GettingApiReturn.php';
require DISCUZ_ROOT.'./marketapi/GetUrlImage.php';
//>>>>start: 生成详情页上方的统计数据。
$coinId = $_GET['coinid'];
$url = 'https://public.bqi.com/public/v1/ticker';
//欲获取市值前多少位的币种条数；
$coinDetailOtherParams = ["convert"=>'CNY','code'=>$coinId,'isKLine'=>1];
$coinDetailApi = new GettingApiReturn($url,$coinDetailOtherParams);
$coinDetailResArr =array_pop($coinDetailApi->store_output_to_assocArray());
$logo = $coinDetailResArr['logo'];
$symbol = $coinDetailResArr['symbol'];
$name = $coinDetailResArr['name'];
$price = $coinDetailResArr['price_cny'];
$change = $coinDetailResArr['percent_change_24h'];
if($change>=0){
    $changeHtmlStr = "<div style='color: red'>$change%</div>";
}else{
    $changeHtmlStr = "<div style='color:green'>$change%</div>";
}
//$kLineArr = explode(',',$coinDetailResArr['KLine']);

$volume = number_format($coinDetailResArr['volume_24h_cny']/1e4,2);
$availableSupply = number_format($coinDetailResArr['available_supply']/1e4);
$marketCap = number_format($coinDetailResArr['market_cap_cny']/1e8,2);
//>>>>>end

//>>>>>>start:获取市场模块数据业务逻辑实现
$exchangeTickersArr = C::t('market_tickers')->get_tickers_by_symbol_usdStr_prior_exchangeUnique($symbol);
//var_dump($exchangeTickersArr );
$includedExchangeNum = count($exchangeTickersArr);
$tickerInfoHtmlStr = "";
$allTickerArr = [];
$assocTickerVolArr = [];
foreach($exchangeTickersArr as $key=>$value) {
   
    $ticker = $value['tickerName'];
    $tickerUrl = "http://api.coindog.com/api/v1/tick/$ticker?unit=cny";
    $tickerApi = new GettingApiReturn($tickerUrl);
    $tickerArr = $tickerApi->store_output_to_assocArray();
	//var_dump($tickerArr);
	//exit;
    if (empty($tickerArr['msg'])) {
        $allTickerArr[$tickerArr['ticker']] = $tickerArr;
        $assocTickerVolArr[$tickerArr['ticker']] = $tickerArr['vol'];
    }
}
//exit;
arsort($assocTickerVolArr);
$assocTickerVolArr  = array_slice($assocTickerVolArr ,0,10);
$tickerNameArr = array_keys($assocTickerVolArr);
//选取该币种交易量第一名的交易所为基准最高价最低价。
$highPrice = number_format($allTickerArr[$tickerNameArr[0]]['high'],2);
$lowPrice = number_format($allTickerArr[$tickerNameArr[0]]['low'],2);

$i = 1;
foreach($assocTickerVolArr as $k=>$v){
    if($allTickerArr[$k]['vol'] >= 1e4){
        $volStr = number_format($allTickerArr[$k]['vol']/1e4,2)."万";
    }else{
        $volStr = number_format($allTickerArr[$k]['vol'],2);
    }
    $exchangesModel = C::t('market_exchanges');
    $exchangeSymbolOfTicker =$exchangesModel->get_exchangeSymbol_by_exchangeName($allTickerArr[$k]['exchangeName']);
    $tickerInfoHtmlStr .= "<div>
                                <div style='width: 10%'>".$i."</div>
                                <div style='width: 13%' class='coin'><img src='./img/search.png' alt=''><a href='market.php?mod=exchange&exchangeSymbol=$exchangeSymbolOfTicker'>".$allTickerArr[$k]['exchangeName']."</a></div>
                                <div style='width: 13%'>".$allTickerArr[$k]['symbol']."</div>
                                <div style='width: 17%'>¥ ".number_format($allTickerArr[$k]['close'],2)."</div>
                                <div style='width: 15%'>".$volStr."</div>
                                <div style='width: 17%'>¥".number_format($allTickerArr[$k]['value']/1e4,2)."万</div>
                                <div>".date('Y-m-d H:i:s',$allTickerArr[$k]['dateTime']/1e3)."</div>
                                </div>";
    $i ++ ;
}

//>>>>>>end

//>>>>>>start:获取币种介绍数据的业务逻辑；
//1.更新top100coins数据表
$url = 'http://api.coindog.com/api/v1/currency/ranks';
$top100Api = new GettingApiReturn($url);
$urlImage = new GetUrlImage();
$coinInfoArr = $top100Api->store_output_to_assocArray();
$top100coinsModel = C::t('market_top100coins');
$objectCoinArr = [];
foreach ($coinInfoArr as $key=>$item){
    $objectCoinArr[] = $item['currency'];
    $res = $top100coinsModel->find_same_coin($item);
    if($res){
        $updateRes = $top100coinsModel->update_coin_info($item);
        //echo $key."</br>";
        //var_dump($updateRes);
    }else {
        $coinInfo['coinAbbr'] = $item['currency'];
        $coinInfo['coinCnName'] = $item['name'];
        $coinInfo['coinLogo'] = $urlImage->get_image_by_url($item['iconUrl'], 'static/marketapimg/coinlogos/', $item['currency']);
        $coinInfo['coinSummary'] = $item['describe'];
        $coinInfo['price'] = $item['price'];
        $coinInfo['vol'] = $item['vol'];
        $coinInfo['change'] = $item['change'];
        $coinInfo['maxSupply'] = $item['maxSupply'];
        $coinInfo['supply'] = $item['supply'];
        $coinInfo['marketCap'] = $item['marketCap'];
        $coinInfo['updateTime'] = $item['updateTime']/1e3;
        $addCoinRes = $top100coinsModel->add_coin($coinInfo);
        //echo $key."</br>";
        //var_dump($addCoinRes);
    }
}
$setNullRes = $top100coinsModel->set_not_in_symbols_coin_info_null($objectCoinArr);
//var_dump($setNullRes);
//exit;

$top100Rank = $top100coinsModel->get_coins_rank_desc();
$curCoinOffsetInRankArr = array_search($symbol,array_column($top100Rank,'coinAbbr'));
    $tip = "暂未收录此币种数据";
if($curCoinOffsetInRankArr !== FALSE){
    $coinEnName = C::t('market_coins')->get_coinName_by_symbol($symbol);
    $curCoinRank = $curCoinOffsetInRankArr + 1;
    $curCoinInfo = $top100Rank[$curCoinOffsetInRankArr];
}

include template('market/market_currencydetail');