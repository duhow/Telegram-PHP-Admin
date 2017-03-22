<?php

$config = require 'config.php';
$telegram = NULL;

if($config['using-app'] === TRUE){
	/*
	require_once '../libs/Telegram-PHP/src/Autoloader.php';
	$bot = new Telegram\Bot($config['bot']);
	$telegram = new Telegram\Sender($bot);
	*/
}

function telegram_method($method, $data = NULL){
	global $config;
	$url = "https://api.telegram.org/bot" .$config['bot']['id'] .":" .$config['bot']['key'] ."/" .$method;
	if(!empty($data)){
		if(is_array($data)){ $data = http_build_query($data); }
		$url .= "?$data";
	}
	return $url;
}

if(isset($_GET['action']) && $_GET['action'] == 'getWebhookInfo'){
	$data = file_get_contents(telegram_method($_GET['action']));
	header("Content-Type: application/json");
	die($data);
}

?>
