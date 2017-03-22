<?php

$web = file_get_contents("render.html");

if(isset($_POST) && !empty($_POST)){
	if(isset($_POST['test-token']) && !empty($_POST['test-token'])){
		$url = "https://api.telegram.org/bot" .$_POST['test-token'] ."/getMe";
		$data = file_get_contents($url);
		header("Content-Type: application/json");
		die($data);
	}
}

$setup = file_get_contents("setup.html");
$web = str_replace(
	["%%TITLE%%", "%%BOTNAME%%", "%%MENU%%", "%%MAIN%%"],
	["Configuración", "Telegram-PHP-Admin", "", $setup],
	$web
);

echo $web;

// TODO Detect if using Telegram-PHP-App (back directory)
// core-app folder + config file

// TODO Create login with command or via PHP
// htpasswd -nBb user passwd
// user:$2y$05$QwMugLpbvll2SIROdiyIOOCJRrClJaK6DZUOK2U/WcSZ44Ut7jssW

// TODO Add Analytics tag to render Graphs?

?>
