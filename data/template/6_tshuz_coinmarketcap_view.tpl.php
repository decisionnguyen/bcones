<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('view');?><?php include template('common/header'); ?><link rel="stylesheet" type="text/css" href="source/plugin/tshuz_coinmarketcap/index.css" />
<div id="pt" class="bm cl">
<div class="z">
<a href="./" class="nvhm" title="首页"><?php echo $_G['setting']['bbname'];?></a><em>&raquo;</em><a href="plugin.php?id=tshuz_coinmarketcap">虚拟货币行情</a><em>&raquo;</em><?php echo $data['name'];?>行情
</div>
</div>
<div class="coin_info">
<table cellpadding="0" cellspacing="0" class="table">
    	<tr>
        	<th>虚拟货币</th>
            <td><div class="s-s-<?php echo $coin;?> coin-logo"></div><?php echo $data['name'];?>(<?php echo $data['symbol'];?>)</td>
          	<th>市值</th>
            <td><?php echo $pvars['code'];?><?php echo $data['market_cap_cny'];?> <?php echo $convert;?><span class="dgrey"><?php echo $data['market_cap_btc'];?> BTC</span></td>
        </tr>
        <tr>
        	<th>货币单价</th>
            <td><?php echo $pvars['code'];?><?php echo $data['price_'.$pvars['covert']];?>(<?php echo $convert;?>)</td>
          	<th>交易量(24H)</th>
            <td><?php echo $pvars['code'];?><?php echo $data['market_cap_cny'];?> <?php echo $convert;?><span class="dgrey"><?php echo $data['market_cap_btc'];?> BTC</span></td>
        </tr>
        <tr>
        	<th>24小时涨幅</th>
            <td><span class="c_<?php if($data['percent_change_24h']<0) { ?>minus<?php } else { ?>add<?php } ?>"><?php echo $data['percent_change_24h'];?>%</span><b class="ico_<?php if($data['percent_change_24h']<0) { ?>fall<?php } else { ?>increase<?php } ?>">&nbsp;</b></td>
          	<th>流通供给量</th>
            <td><?php echo $data['available_supply'];?> <?php echo $data['symbol'];?></td>
        </tr>
        <tr>
        	<th>7天涨幅</th>
            <td><span class="c_<?php if($data['percent_change_7d']<0) { ?>minus<?php } else { ?>add<?php } ?>"><?php echo $data['percent_change_7d'];?>%</span><b class="ico_<?php if($data['percent_change_7d']<0) { ?>fall<?php } else { ?>increase<?php } ?>">&nbsp;</b></td>
          	<th>最大供给量</th>
            <td><?php echo $data['max_supply'];?> <?php echo $data['symbol'];?></td>
        </tr>
</table>
<script src="static/js/mobile/jquery<?php echo $jqSrc;?>.min.js" type="text/javascript" type="text/javascript"></script>
<script src="source/plugin/tshuz_coinmarketcap/highcharts/highcharts.js" type="text/javascript"></script>
    <ul class="tb cl" id="litab">
    	<li id="li_1d"><a href="javascript:;" onclick="loadchart('1d');">1天</a></li>
    	<li id="li_7d"><a href="javascript:;" onclick="loadchart('7d');">7天</a></li>
    	<li id="li_1m"><a href="javascript:;" onclick="loadchart('1m');">1个月</a></li>
    	<li id="li_3m"><a href="javascript:;" onclick="loadchart('3m');">3个月</a></li>
    	<li id="li_1y"><a href="javascript:;" onclick="loadchart('1y');">1年</a></li>
    	<li id="li_all"><a href="javascript:;" onclick="loadchart('all');">全部</a></li>
</ul>
    <div class="c_chart">
    
    <div class="coin_load"><div class="coin_loading"></div>数据加载中，请稍候</div>
    <div id="container"></div>
    </div>
    <script>
var jq = jQuery.noConflict();
var chart = null;
loadchart('7d');
function label_format_fiat() {
val = Math.round(this.value);
if(val<10000){
val = Math.round(val/10000);	
}else if(val< 100000000){
val = Math.round(val/10000)+' 万'	
}else{
val = Math.round(val/100000000)+' 亿'	
}
return '$' + val;
}
var chartColors = ['#56b4e9', '#ff9f00', '#009e73', '#777'];
function loadchart(DayTYPE){
jq('#container').html('');
jq('.coin_load').show();
jq('#litab li').each(function(){
jq(this).removeClass('a');
})
jq('#li_'+DayTYPE).addClass('a');
jq.getJSON('plugin.php?id=tshuz_coinmarketcap&mod=chart&coin=<?php echo $coin;?>&dl='+DayTYPE, function (datas) {
if(datas.error ){
showDialog('页面加载失败，请刷新重试'+datas.error,'alert');return false;	
}
jq('#container').highcharts({
chart: {
backgroundColor: 'transparent',
type: 'line',
zoomType: 'x',
height:620,
ignoreHiddenSeries: true,
},
colors: chartColors,
title: {
text: '<?php echo $data['name'];?> 图表',
align: "center",
style: {
color: '#757575',
fontSize: "24px"
}
},

xAxis: [{
labels: {
rotation:0,
},
showFirstLabel:false,
categories: datas.xData,
crosshair: true,
gridLineWidth:0,
type: 'datetime',

}],
yAxis: [{
labels: {
formatter: function (){
return '$'+this.value;	
},
style: {
color: chartColors[2],
},
align: "left",
x: 15
},
title: {
text: '价格(美元)',
style: {
color: chartColors[2],
'font-weight': 'bold'
}
},
showEmpty: false,
height: '80%',
opposite: true,
showLastLabel:false,
floor: 0
}, { 
labels: {
formatter: label_format_fiat,
align: 'right',
style: {
color: chartColors[0]
}
},
title: {
text: '市值',
style: {
color: chartColors[0],
'font-weight': 'bold'
}
},
showEmpty: false,
height: '80%',
opposite: false,
floor: 0,
lineWidth: 1,
showLastLabel:false,
opposite: false

}, { // Tertiary yAxis
labels: {
formatter:  function() {
return this.value+' BTC';
},
style: {
color: chartColors[1],
},
align: "left",
x: 15
},
title: {
text: '价格(BTC)',
style: {
color: chartColors[1],
'font-weight': 'bold'
}
},
showEmpty: false,
height: '80%',
opposite: true,
showLastLabel:false,
floor: 0
},{
labels: {
formatter: label_format_fiat,
align: 'right',
style: {
color: chartColors[3],
}
},
title: {
text: '交易量',
style: {
color: chartColors[3],
'font-weight': 'bold'
}
},
showEmpty: false,
top: '80%',
height: '20%',
offset: 0,
lineWidth: 1,
opposite: false,
showLastLabel:false
}
],
tooltip: {
shared: true
},
series: [{
name: '市值',
type: 'spline',
yAxis: 1,
data: datas.datasets[0].data,
tooltip: {
valueSuffix: ' USD'
}
}, {
name: '价格(BTC)',
type: 'spline',
yAxis: 2,
data: datas.datasets[1].data,
marker: {
enabled: false
},
tooltip: {
valueSuffix: ''
}
}, {
name: '价格(美元)',
type: 'spline',
data: datas.datasets[2].data,
yAxis:0,
tooltip: {
valueSuffix: ''
}
}, {
name: '交易量',
type: 'column',
            		yAxis: 3,
data: datas.datasets[3].data,
tooltip: {
valueSuffix: ' USD'
},
dataGrouping: {
approximation: "average",
enabled: false
}
}]
});
jq('.coin_load').hide();
})
}


</script>
</div><?php include template('common/footer'); ?>