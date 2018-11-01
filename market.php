<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/28
 * Time: 15:40
 */
ini_set("max_execution_time",0);
define('APPTYPEID', 20);
define('CURSCRIPT', 'market');

require './source/class/class_core.php';
$discuz = C::app();
$discuz->init();

$modArr = ['index','ajaxgetchange','ajaxgetrank','ajaxsearch','ajaxautocomplete','detail','exchange'];
if(empty($_GET['mod']) || !in_array($_GET['mod'],$modArr)){
    $_GET['mod'] = 'index';
}
define('CURMODULE',$_GET['mod']);

$_G['disabledwidthauto'] = 1;

require_once libfile('market/'.$_GET['mod'],'module');