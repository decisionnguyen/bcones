<?php
	require_once 'ApiForTest.php';
	$apiUrl = 'http://404.php.net';
	$api = new GettingApiReturn($apiUrl);
	var_dump($api->store_output_to_assocArray());
       