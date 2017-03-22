<?php

$web = file_get_contents("render.html");
$config = array();

function checkvar($v){ return (isset($v) && !empty($v)); }

if(checkvar($_POST)){
	if(checkvar($_POST['test-token'])){
		$url = "https://api.telegram.org/bot" .$_POST['test-token'] ."/getMe";
		$data = file_get_contents($url);
		header("Content-Type: application/json");
		die($data);
	}elseif(checkvar($_POST['token']) && checkvar($_POST['user']) && checkvar($_POST['passwd'])){
		$hash = $_POST['user'] .":" .password_hash($_POST['passwd'], PASSWORD_BCRYPT, ['cost' => 5]);
		$fp = fopen(".htpasswd", 'a');
		fwrite($fp, $hash ."\n");
		fclose($fp);

		// TODO Save Config
		// http://stackoverflow.com/a/2237315

		$files = ['setup.html', 'setup.php'];
		// foreach($files as $f){ unlink($f); }
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
