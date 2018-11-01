<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

dheader('Content-type: application/json');
$timeoffset = $_G['member']['timeoffset'];
$endline = TIMESTAMP-($timeoffset*3600);
switch (strtolower($_GET['dl'])) {
	case '1d':
		$startline = $endline-86400;
		break;
	case '7d':
		$startline = $endline-86400*7;
		break;
	case '1m': 
		$startline = $endline-86400*30;
		break;
	case '3m':
		$startline = $endline-86400*90;
		break;
	case '1y':
		$startline = $endline-86400*365;
		break;
	default:
		$startline = 0;
		break;
}
$url = 'https://graphs2.coinmarketcap.com/currencies/'.$_GET['coin'].($startline?'/'.($startline*1000).'/'.($endline*1000).'/':'');
$data = dfsockopen($url);
$datas = json_decode($data,1);
if(!$datas){
	echo json_encode(array('error'=>$url));exit;
}
$chart = array();
$i = 0;
$count = count($datas['market_cap_by_available_supply']);
$step = ceil($count/5);
foreach($datas as $k=>$data){
	$chart['datasets'][$i]['name'] = substr($k,0,10);
	foreach($data as $j=>$v){
		if($k == 'market_cap_by_available_supply'){
			$time = $v[0]/1000+$timeoffset*3600;
			$chart['xData'][] = date('y-m-d H:i:s',$time);
		}
		$chart['datasets'][$i]['data'][] = $v[1];
	}
	$i++;
}
echo json_encode($chart);exit;