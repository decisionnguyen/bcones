<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/27
 * Time: 15:59
 */

class GetUrlImage
{
    function get_image_by_url($url,$path = "", $filename="" ) {
        if ($url == "") {
            return false;
        }
    //得到图片的扩展名
    $ext = strtolower(substr(strrchr($url, "."),1));
    $imageExtArr = ["gif","jpg","bmp","png"];
    if( !in_array($ext, $imageExtArr,true) ) {
        $ext = "jpg";
    }
    //以时间另起名，在此可指定相对目录 ，未指定则表示同php脚本执行的当前目录
    if($filename == "") {
        $filename =$path. time() .".$ext";
    }else{
        $filename =$path. $filename .".$ext";
    }
    //以流的形式保存图片
    $write_fd = @fopen($filename,"a");
    //将采集来的远程数据写入本地文件
    @fwrite($write_fd, $this->curl_get($url));
    @fclose($write_fd);
    //返回文件名
    return($filename);
    }

    //远程获取
    function curl_get($url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        //模拟浏览器访问
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; SeaPort/1.2; Windows NT 5.1; SV1; InfoPath.2)");
        curl_setopt($curl, CURLOPT_COOKIEJAR, 'cookie.txt');
        curl_setopt($curl, CURLOPT_COOKIEFILE, 'cookie.txt');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
        $values = curl_exec($curl);
        curl_close($curl);
        return($values);
    }
}