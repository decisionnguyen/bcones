<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('market_currencydetail');?><?php include template('common/header'); ?><link rel="stylesheet" href="static/css/index.css">
<link rel="stylesheet" href="static/css/detail.css">

<div class="pageContent">
    <div class="inner detail">
        <div class="overview">
            <div class="coin">
                <img src="<?php echo $logo;?>" alt="">
                <div>
                    <div><?php echo $symbol;?></div>
                    <div class="en"><?php echo $name;?></div>
                </div>
            </div>
            <div class="items">
                <div>
                    <div>￥<?php echo $price;?></div>
                    <div class="label">最新成交价</div>
                </div>
                <div>
                    <?php echo $changeHtmlStr;?>
                    <div class="label">涨跌幅</div>
                </div>
                <div>
                    <div>$ <?php echo $highPrice;?></div>
                    <div class="label">24h最高</div>
                </div>
                <div>
                    <div>$ <?php echo $lowPrice;?></div>
                    <div class="label">24h最低</div>
                </div>
                <div>
                    <div><?php echo $volume;?>万 </div>
                    <div class="label">24h交易量</div>
                </div>
                <div>
                    <div>￥<?php echo $marketCap;?>亿</div>
                    <div class="label">流通市值</div>
                </div>
                <div>
                    <div><?php echo $availableSupply;?>万</div>
                    <div class="label">流通量</div>
                </div>
            </div>
        </div>
        <div class="tab">
            <div class="active" data-cls="market">市场</div>
            <div data-cls="info">资料</div>
            <div data-cls="news">相关新闻</div>
        </div>
        <div class="market">
            <div class="ctrl">
                <div class="search">
                    <img src="./img/search.png" alt="">
                    <input>
                </div>
            </div>
            <div class="datahead">
                <div style="width: 10%">排名</div>
                <div style="width: 13%">交易所</div>
                <div style="width: 13%">交易对</div>
                <div style="width: 17%">价格</div>
                <div style="width: 15%">成交量</div>
                <div style="width: 17%">成交额</div>
                <div>最后更新时间</div>
            </div>
            <div class="databody">
                <?php echo $tickerInfoHtmlStr;?>
            </div>
        </div>
        <div class="info">
            <?php if($currentCoinOffsetInRankArr === FALSE) { ?>
            <?php echo $tip;?>
            <?php } else { ?>
            <div class="items">
                <div>市值：    <?php if($curCoinRank > 100) { ?>第100名之后<?php } else { ?>第 <b><?php echo $curCoinRank;?></b> 名<?php } ?>    <?php echo number_format($coinDetailResArr['market_cap_cny']/1e8,2);?>亿</div>
                <div>英文：<?php echo $coinEnName;?>              </div>
                <div>中文：<?php echo $curCoinInfo['coinCnName'];?></div>
                <div>上架交易所： <?php echo $includedExchangeNum;?>家</div>
                <div>更新时间：<?php echo date('Y-m-d H:i:s',$curCoinInfo['updateTime']);?></div>
                <div>发行总量：<?php echo number_format($curCoinInfo['maxSupply']/1e4,2);?>万</div>
                <div>流通总量：<?php echo number_format($curCoinInfo['supply']/1e4,2);?>万</div>
                <div class="double">官网：暂未收录该数据<!-- <a href="https://bitcoin.org/">https://bitcoin.org/</a><a href="https://www.bitcoin.com/">https://www.bitcoin.com/</a> --></div>
                <div class="double">白皮书：暂未收录该数据<!-- <a href="http://www.bitcoin.org/bitcoin.pdf">http://www.bitcoin.org/bitcoin.pdf</a> --></div>
                <div class="double">浏览器：暂未收录该数据<!-- <a href="http://blockchain.info">http://blockchain.info</a><a href="https://blockexplorer.com/">https://blockexplorer.com/</a> --></div>
            </div>
            <div class="desc">
                <?php echo $curCoinInfo['coinSummary'];?>
            </div>
            <?php } ?>
        </div>
        <div class="news">news</div>
    </div>
</div>
<script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js" type="text/javascript"></script>
<script>
    var jQuery = jQuery.noConflict();
    jQuery('.tab').on('click', 'div', function() {
        jQuery('.' + jQuery('.tab .active').removeClass('active').data('cls')).hide()
        jQuery(this).addClass('active')
        jQuery('.' + jQuery(this).data('cls')).show()
    })
</script><?php include template('common/footer'); ?>