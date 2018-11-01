<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('list');?><?php include template('common/header'); ?><link rel="stylesheet" type="text/css" href="source/plugin/tshuz_coinmarketcap/index.css" />
<div id="pt" class="bm cl">
<div class="z">
<a href="./" class="nvhm" title="首页"><?php echo $_G['setting']['bbname'];?></a><em>&raquo;</em><a href="plugin.php?id=tshuz_coinmarketcap">虚拟货币行情</a>
</div>
<div class="z"><?php if(!empty($_G['setting']['pluginhooks']['index_status_extra'])) echo $_G['setting']['pluginhooks']['index_status_extra'];?></div>
</div>
<table cellpadding="0" cellspacing="0" class="table">
  <thead>
    <tr>
      <th style="width:30px;">序号</th>
      <th>名称</th>
      <th>市值</th>
      <th>价格</th>
      <th>交易量(24H)</th>
      <th>流通供给量</th>
      <th>变化量</th>
      <th style="width:34px;">操作</th>
    </tr>
  </thead>
  <tbody id="xunihuobilist"><tr><td colspan="8"><div class="coin_load" style="display:block;"><div class="coin_loading"></div>数据加载中，请稍候</div></td></tr></tbody>
</table>
<script src="static/js/mobile/jquery<?php echo $jqSrc;?>.min.js" type="text/javascript" type="text/javascript"></script>
<script>
var jq = jQuery.noConflict();
loadXnData();
function loadXnData() {
    var cash = "<?php echo $pvars['covert'];?>";
    var size = "<?php echo $pvars['num'];?>";
var cashCode = "<?php echo $pvars['code'];?>";
    jq.get("https://api.coinmarketcap.com/v1/ticker/?convert=" + cash + "&limit=" + size, {},
    function(data, status, xhr) {
        jq("#xunihuobilist").html("");
        var html = "";
        for (var i = 0; i < data.length; i++) {
            html = "<tr style='border-bottom:1px solid #eeeeee;height:35px;'>";
            html += "<td>" + (i + 1) + "</td>";
            html += "<td style='color:#468BCA;font-weight:bold'>"+'<a href="plugin.php?id=tshuz_coinmarketcap&amp;mod=view&amp;coin='+data[i]["name"].toLowerCase().replace(' ', '-')+'" style="color:#468BCA">'+"<div class=\"s-s-" + data[i]["name"].toLowerCase().replace(' ', '-') + " coin-logo\"></div>" + data[i]["name"] + "</a></td>";
            html += "<td>"+cashCode + toThousands(data[i]["market_cap_" + cash].match(/^\d+(?:\d{0,0})?/)  )+ "</td>";
            html += "<td style='color:#468BCA'>"+cashCode + toThousands(data[i]["price_" + cash].match(/^\d+(?:\.\d{0,2})?/) ) + "</td>";
            html += "<td style='color:#468BCA'>"+cashCode + toThousands(data[i]["24h_volume_" + cash].match(/^\d+(?:\d{0,0})?/) ) + "</td>";
            html += "<td style='color:#468BCA'>" + toThousands(data[i]["total_supply"].match(/^\d+(?:\d{0,0})?/) ) + "</td>";
            if (data[i]["percent_change_24h"] < 0) {
                html += "<td style='color:red'>" + data[i]["percent_change_24h"] + "%</td>";
            } else {
                html += "<td style='color:#009933'>" + data[i]["percent_change_24h"] + "%</td>";
            }
html += '<td><a href="plugin.php?id=tshuz_coinmarketcap&amp;mod=view&amp;coin='+data[i]["name"].toLowerCase().replace(' ', '-')+'" style="color:#468BCA">趋势</a></td>'
            html += "</tr>";
            jq("#xunihuobilist").append(html);
        }
    },
    "json");
}
function toThousands(num) {
    var num = (num || 0).toString(), result = '';
var numArr = num.split(".");
num = numArr[0];
    while (num.length > 3) {
        result = ',' + num.slice(-3) + result;
        num = num.slice(0, num.length - 3);
    }
    if (num) { result = num + result; }
if(numArr[1])
result = result+'.'+numArr[1];
    return result;
}
<?php if($pvars['second']) { ?>
window.setInterval(loadXnData, <?php echo $pvars['second'];?>000);
<?php } ?>
</script><?php include template('common/footer'); ?>