<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('market_index');?><?php include template('common/header'); ?><link rel="stylesheet" href="static/css/index.css">
<link rel="stylesheet" href="static/css/quotation.css">
<div class="pageContent">
    <div class="inner quotation">
        <div class="left">
            <div class="condition">
                <div>
                    <select name="limit" id="limit">
                        <option value="200" selected="selected">总市值从高到低</option>
                        <option value="50" >总市值top50从高到低</option>
                    </select>
                    <select name="convert" id="convert">
                        <option value="CNY" selected="selected">人民币(CNY)</option>
                        <option value="USD" >美元(USD)</option>
                    </select>
                </div>
                <div class="search">
                    <img src="static/marketapimg/search.png" onclick="search();" alt="">
                    <input placeholder="输入币种代号">
                </div>
            </div>
            <div class="datahead">
                <div style="width: 9%">排名</div>
                <div style="width: 13.5%">名称</div>
                <div style="width: 13.5%">市值</div>
                <div style="width: 16.5%">交易量（24H）</div>
                <div style="width: 15.5%">价格</div>
                <div style="width: 15.5%">流通供给量</div>
                <div style="width: 13.5%">涨幅（24H）</div>
            </div>
            <div class="databody" id="rankdatabody">
                <?php if(is_array($coinInfoArr)) foreach($coinInfoArr as $key => $item) { ?>                <div class="row">
                    <div style="width: 9%"><?php echo $key+($curPageNum-1)*50+1;?></div>
                    <div class="flex" style="width: 13.5%"><img src="<?php echo $item['logo'];?>" alt=""><a href="market.php?mod=detail&amp;coinid=<?php echo $item['id'];?>" style="color: #0C97E1"><?php echo $item['symbol'];?></a></div>
                    <div style="width: 13.5%"><?php echo number_format($item['market_cap_cny']/1e8,2);?> 亿</div>
                    <div style="width: 16.5%"><?php echo number_format($item['volume_24h_cny']/1e4,2);?> 万</div>
                    <div style="width: 15.5%">￥<?php echo number_format($item['price_cny'],2);?></div>
                    <div style="width: 15.5%"><?php echo number_format($item['available_supply']/1e4);?> 万</div>
                   <?php if($item['percent_change_24h']<0) { ?>
                    <div style="width: 13.5%; color: #00D884">
                    <?php } else { ?>
                    <div style="width: 13.5%; color: red">
                     <?php } ?>
                     <?php echo $item['percent_change_24h'];?>%</div>
                </div>
                <?php } ?>
            </div>
            <div class="page" id="page">
                <?php echo $pageStr;?>
            </div>
        </div>
        <div class="right">
            <div class="overview">
                <div class="o-row">
                    <div style="width: 180px">
                        <div><?php echo $coinCountNum;?>个</div>
                        <div class="label">币种</div>
                    </div>
                    <div>
                        <div>￥<?php echo number_format($allMarketCap/1e8,0);?>亿</div>
                        <div class="label">市值</div>
                    </div>
                </div>
                <div class="o-row">
                    <div style="width: 180px">
                        <div><?php echo $exchangeCountNum;?>个</div>
                        <div class="label">交易所</div>
                    </div>
                    <div>
                        <div>￥<?php echo number_format($allVolume/1e8,2);?>亿</div>
                        <div class="label">24H成交额</div>
                    </div>
                </div>
            </div>
            <div class="panel">
                <div class="ctrl">
                    <div class="tab">
                        <a class="active change" >涨幅</a>|
                        <a class="change">跌幅</a>
                    </div>
                    <div class="radio">
                        <span class="period">1小时</span>
                        <span class="period active">24小时</span>
                        <span class="period">一周</span>
                    </div>
                </div>
                <div class="datahead">
                    <div style="width: 40px">排行</div>
                    <div style="width: 100px">币种</div>
                    <div style="width: 120px">价格</div>
                    <div style="flex: auto; text-align: right">涨幅</div>
                </div>
                <div class="databody" id="changedatabody">
                <?php echo $resCoinInfoHtmlStr;?>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
<script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js" type="text/javascript"></script>
<script>
        var jQuery = jQuery.noConflict();
        jQuery(".change").click (function () {
            jQuery(this).addClass('active').siblings().removeClass('active');
            changeValueStr = jQuery(".tab>.active").text();
            changePeriodStr = jQuery(".radio>.active").text();
            if(changeValueStr === '涨幅'){
                changeDirection = 'up';
            }else{
                changeDirection = 'down';
            }
            if(changePeriodStr === '1小时'){
                changePeriod = 'percent_change_1h';
            }else if(changePeriodStr === '24小时'){
                changePeriod = 'percent_change_24h';
            }else{
                changePeriod = 'percent_change_7d';
            }
            jQuery.ajax({
                'url':'http://www.bcones.net/market.php',
                'type':'get',
                'data':{'mod':'ajaxgetchange','direction':changeDirection,'period':changePeriod},
                'success':function (response) {
                    jQuery('#changedatabody').html(response);
                }
            });
        });
        jQuery(".period").click (function () {
            jQuery(this).addClass('active').siblings().removeClass('active');
            changeValueStr = jQuery(".tab>.active").text();
            changePeriodStr = jQuery(".radio>.active").text();
            if(changeValueStr === '涨幅'){
                changeDirection = 'up';
            }else{
                changeDirection = 'down';
            }
            if(changePeriodStr === '1小时'){
                changePeriod = 'percent_change_1h';
            }else if(changePeriodStr === '24小时'){
                changePeriod = 'percent_change_24h';
            }else{
                changePeriod = 'percent_change_7d';
            }
            jQuery.ajax({
                'url':'http://www.bcones.net/market.php',
                'type':'get',
                'data':{'mod':'ajaxgetchange','direction':changeDirection,'period':changePeriod},
                'success':function (response) {
                    jQuery('#changedatabody').html(response);
                }
            });
        });
        jQuery("select").change(function () {
            jQuery.ajax({
                'url':'http://www.bcones.net/market.php?mod=ajaxgetrank',
                'type':'post',
                'data':{'itemNumbers':jQuery("#limit option:selected").val(),'convert':jQuery("#convert option:selected").val()},
                'success':function (response) {
                    responseObj = jQuery.parseJSON(response);
                    jQuery('#rankdatabody').html(responseObj.resCoinInfoHtmlStr);
                    jQuery('#page').html(responseObj.pageStr);
                }
            });
        });
        jQuery(document).on('click',".pageNum",function () {
            jQuery(this).addClass('active').siblings().removeClass('active');
            jQuery.ajax({
                'url':'http://www.bcones.net/market.php?mod=ajaxgetrank',
                'type':'post',
                'data':{'itemNumbers':jQuery("#limit option:selected").val(),'convert':jQuery("#convert option:selected").val(),'page':jQuery(this).text()},
                'success':function (response) {
                    responseObj = jQuery.parseJSON(response);
                    jQuery('#rankdatabody').html(responseObj.resCoinInfoHtmlStr);
                    jQuery('#page').html(responseObj.pageStr);
                }
            });
        });
        function search() {
            jQuery.ajax({
                'url':'http://www.bcones.net/market.php?mod=ajaxsearch',
                'type':'post',
                'data':{'symbol':jQuery(".search>input").val()},
                'success':function (response) {
                    jQuery('#rankdatabody').html(response);
                    jQuery('#page').html("");
                }
            });
        }
</script><?php include template('common/footer'); ?>