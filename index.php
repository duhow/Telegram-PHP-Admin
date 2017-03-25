<?php
// Initial checkup.

if(!file_exists('config.php')){
	if(file_exists('setup.php')){
		die(header("Location: setup.php"));
	}
	http_response_code(500);
	die("Config file not found. Please configure or reinstall.");
}

if(file_exists('setup.php')){
	http_response_code(500);
	die("Setup file found. Please delete setup.php before running.");
}

require 'auth.php';

$config = require 'config.php';
$web = file_get_contents("templates/render.html");

$main = "";
$modules = ["webhook", "sysinfo"];
foreach($modules as $m){
	$main .= require "modules/$m.php";
}
foreach(scandir('modules/') as $m){
	$file = "modules/$m.php";
	if(file_exists($file) and is_readable($file)){
		$main .= require_once $file;
	}
}

$main .= file_get_contents("templates/loader.html");

$web = str_replace(
	["%%TITLE%%", "%%BOTNAME%%", "%%MENU%%", "%%MAIN%%"],
	["Dashboard", $config['bot']['name'], "", $main],
	$web
);

echo $web;

?>
