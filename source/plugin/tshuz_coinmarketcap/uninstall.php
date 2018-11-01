<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
if(!function_exists('cloudaddons_deltree')) require libfile('function/cloudaddons');
cloudaddons_deltree(DISCUZ_ROOT .'./source/plugin/tshuz_coinmarketcap/');
$finish = true;
?>