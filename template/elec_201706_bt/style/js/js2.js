jQuery(document).ready(function () {
    var jQueryli   = jQuery('.J-slider li');
    var jQuerybtn  = jQuery('.J-slider-btn a');
    var play  = null;
    var i     = 0;

    //图片切换, 淡入淡出效果
    function slider( index ){
        jQuerybtn.removeClass('z-active').eq(index).addClass('z-active');
        jQueryli.animate({opacity:0}).css({"z-index":1}).eq(index).animate({opacity:1}).css({"z-index":3});
        i = index;
    }

    //自动播放函数
    function autoPlay(){
        if( jQueryli.size() <= 1 ){
            jQuery('.J-slider-btn').hide();
            return;
        }
        clearInterval(play);
        play = setInterval(function () {
            i ++;
            i >= jQueryli.size() && (i = 0);
            slider(i)
        }, 4000);
    }

    //应用
    autoPlay();

    jQuery('.J-list').eq(0).show();

    //事件
    jQuery('body').on('click', '.J-slider-btn a', function (){
        var jQueryindex = jQuerybtn.index(this);
        slider(jQueryindex)
    }).on('mouseover', '.J-slider-box', function (){
        clearInterval(play);
    }).on('mouseout', '.J-slider-box', function (){
        autoPlay();
    }).on('click', '.J-tab-btn a', function (){
        var jQuerythis   = jQuery(this);
        var jQueryindex  = jQuery('.J-tab-btn a').index(this);
        var jQuerylist   = jQuery('.J-list');

        jQuerythis.addClass('z-active').parent().siblings().find('a').removeClass('z-active');
        jQuerylist.hide().eq(jQueryindex).show();

    }).on('click', '.J-load', function (){
        var jQuerythis = jQuery(this);
        jQuerythis.html('加载更多…');

        //数据请求
        // jQuery.ajax({
        //     type: "GET",
        //     url: "",
        //     data: {},
        //     success: function (data) {}
        // });

    }).on('click', '.J-scroll-top', function (){
        jQuery(window).scrollTop(0);
    });

    //滚轮滑动
    jQuery(window).scroll(function(){
        var jQueryscrollTop  = jQuery(window).scrollTop();
        var jQueryindex      = jQuery('.J-index');
        var jQuerydetails    = jQuery('.J-details');
        var jQuerynav        = jQuery('.J-nav');

        ( jQueryindex.size() && jQueryscrollTop > 100 )
        || ( jQuerydetails.size() && jQueryscrollTop > 100 )
            ? jQuerynav.addClass('z-scroll-nav')
            : jQuerynav.removeClass('z-scroll-nav');

    });



});
