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

$webhook = file_get_contents("templates/webhook.html");
$sysinfo = file_get_contents("templates/sysinfo.html");
$loader = file_get_contents("templates/loader.html");

$main = $webhook . $sysinfo . $loader;

$web = str_replace(
	["%%TITLE%%", "%%BOTNAME%%", "%%MENU%%", "%%MAIN%%"],
	["Dashboard", $config['bot']['name'], "", $main],
	$web
);

echo $web;

?>
