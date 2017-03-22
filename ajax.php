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

function sysinfo_RAM(){
	$RAM = array();
	foreach(explode("\n", file_get_contents("/proc/meminfo")) as $l){
		$v = explode(":", $l);
		if(count($v) != 2){ continue; }
		$vf = explode(" ", trim($v[1]));
		$RAM[$v[0]] = trim($vf[0]);
	}
	return (object) $RAM;
}

function sysinfo_CPU(){
	$load_tmp = array();
	foreach(explode(" ", file_get_contents("/proc/loadavg")) as $l){
		$load_tmp[] = trim($l);
	}
	$load['now'] = (float) $load_tmp[0];
	$load['normal'] = (float) $load_tmp[1];
	$load['long'] = (float) $load_tmp[2];
	unset($load_tmp);
	$load['processors'] = (int) trim(exec("nproc"));
	return (object) $load;
}

if(isset($_GET['action'])){
	$data = NULL;
	switch ($_GET['action']) {
		case 'getWebhookInfo':
			$data = file_get_contents(telegram_method($_GET['action']));
		break;
		case 'sysinfo':
			$RAM = sysinfo_RAM();
			$CPU = sysinfo_CPU();

			$data = [
				'uptime' => strtotime(exec("uptime -s")),
				'cpu' => [$CPU->now, $CPU->normal, $CPU->long, $CPU->processors],
				'ram' => [(int) $RAM->MemTotal, (int) $RAM->MemAvailable]
			];

			$data = json_encode($data);
		break;

		default:
			$data = FALSE;
		break;
	}
	header("Content-Type: application/json");
	die($data);
}

?>
