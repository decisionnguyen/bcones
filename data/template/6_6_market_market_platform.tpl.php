<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('market_platform');?><?php include template('common/header'); ?><link rel="stylesheet" href="static/css/index.css">
<link rel="stylesheet" href="static/css/platform.css">
    <div class="pageContent">
      <div class="inner platform">
        <div class="left">
          <div class="intro">
            <?php if($exchangeCodeInBQI) { ?>
            <img src="<?php echo $BQIExchangeDetailResArr['Logo'];?>"  alt="">
            <div>
              <div class="tit"><?php echo $BQIExchangeDetailResArr['ExchangeName'];?></div>
              <div class="txt">
                简介：暂无数据。
              </div>
              <div class="foot">
                <span>官方网站：<a href="<?php echo $BQIExchangeDetailResArr['SiteUrl'];?>"><?php echo $BQIExchangeDetailResArr['SiteUrl'];?></a></span>
                <span>国家：<?php echo $BQIExchangeDetailResArr['Country']['countryNameZH'];?></span>
                <span>官方微博：
                  <a href="<?php echo $BQIExchangeDetailResArr['OtherLinks']['weibo'];?>"><img src="./img/search.png" alt=""></a>
                </span>
              </div>
            </div>
            <?php } else { ?>
            <img src=""  alt="">
            <div>
              <div class="tit"><?php echo $curExchangeInfoArr['ExchangeName'];?></div>
              <div class="txt">
                简介：暂无数据。
              </div>
              <div class="foot">
                <span>官方网站：暂无数据</span>
                <span>国家：暂无数据</span>
                <span>官方微博：
                  <img src="./img/search.png" alt="">
                </span>
              </div>
            </div>
            <?php } ?>
          </div>

          <div class="tab">
            <div class="active" data-cls="quotation">行情</div>
            <div data-cls="introduce">平台简介</div>
            <div data-cls="cost">费用说明</div>
          </div>
          <div class="quotation">
            <div class="condition">
              <select name="" id="">
                <option value="">人民币（CNY)</option>
              </select>
            </div>
            <div class="datahead">
              <div style="width: 6%">排名</div>
              <div style="width: 11%">交易对</div>
              <div style="width: 11%">价格</div>
              <div style="width: 14%">成交量</div>
              <div style="width: 11%">涨幅</div>
              <div style="width: 18%">成交额</div>
              <div style="width: 8%">占比</div>
              <div>最后更新时间</div>
            </div>
            <div class="databody">
              <?php if(is_array($exchangeDetailResArr)) foreach($exchangeDetailResArr as $key => $item) { ?>              <div class="row">
                <div style="width: 6%"><?php echo $key+1;?></div>
                <div class="flex" style="width: 11%"><?php echo $item['symbol'];?></div>
                <div style="width: 11%">￥<?php echo number_format($item['close'],2);?> </div>
                <div style="width: 14%"><?php if($item['vol']>=1e4) { echo number_format($item['vol']/1e4,2);?> 万<?php } else { echo number_format($item['vol'],2);?><?php } ?></div>
                <?php if($item['degree']<0) { ?>
                <div style="width: 11%; color: #00D884">
                  <?php } else { ?>
                  <div style="width: 11%; color: red">
                    <?php } ?>
                    <?php echo number_format($item['degree'],2);?>%</div>
                  <div style="width: 18%"><?php if($item['value']>=1e8 ) { echo number_format($item['value']/1e8,2);?>亿 <?php } elseif($item['value']>=1e4) { echo number_format($item['value']/1e4,2);?> 万<?php } else { echo number_format($item['value'],2);?><?php } ?></div>
                  <div style="width: 8%"><?php echo number_format(($item['vol']*$item['close']/$exchangeTotalMarketCap)*100,4);?>%</div>
                  <div><?php echo date('Y-m-d H:i:s',$item['dateTime']/1000);?></div>
                </div>
                <?php } ?>
            </div>
          </div>
          <div class="introduce">
            火币全球专业站，是火币集团旗下服务于全球专业交易用户的创新数字资产交易平台，致力于发现优质的创新数字资产投资机会，目
            前提供四十多种数字资产品类的交易及投资服务，总部位于新加坡，由火币全球专业站团队负责运营。火币集团是一家具有全球竞争力
            与影响力的数字资产综合服务商，为超过130个国家百万级用户提供优质服务。在新加坡、香港、韩国、日本等多个国家和地区均有独
            立的交易业务和运营中心。在技术平台、产品支线、安全风控体系、运营及客户服务体系等方面，火币集团在全球均处于领先地位
          </div>
          <div class="cost">目前所有交易对的吃单及挂单的手续费均为：0.2%</div>
        </div>
        <div class="right">
          <div class="overview">
            <div>
              <div>24小时平台成交额：</div>
              <div class="money">¥4,177,836,740</div>
              <div>$626,675,51196,900BTC</div>
            </div>
            <div class="tag">排名：4</div>
          </div>
          <div class="panel">
            <div class="head">平台最新公告</div>
            <div>
              <div>火币9月20日14:30上线 Groestlcoin</div>
              <div>09-20 14:00</div>
            </div>
            <div>
              <div>火币9月20日14:30上线 Groestlcoin</div>
              <div>09-20 14:00</div>
            </div>
            <div>
              <div>火币9月20日14:30上线 Groestlcoin</div>
              <div>09-20 14:00</div>
            </div>
            <div>
              <div>火币9月20日14:30上线 Groestlcoin</div>
              <div>09-20 14:00</div>
            </div>
            <div>
              <div>火币9月20日14:30上线 Groestlcoin</div>
              <div>09-20 14:00</div>
            </div>
          </div>
          <div class="panel">
            <div class="head">币种成交额占比</div>
            <div>
              <div>
                <img src="./img/pie.png" style="max-width: 100%" alt="">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js" type="text/javascript"></script>
  <script>
      var jQuery = jQuery.noConflict();
      jQuery('.tab').on('click', 'div', function() {
          jQuery('.' + jQuery('.tab .active').removeClass('active').data('cls')).hide();
          jQuery(this).addClass('active');
          jQuery('.' + jQuery(this).data('cls')).show()
      })
  </script>
  <?php include template('common/footer'); ?>