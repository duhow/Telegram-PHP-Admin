<?php

$web = file_get_contents("templates/render.html");
$config = array();

function checkvar($v){ return (isset($v) && !empty($v)); }
function getMe($token){
	$url = "https://api.telegram.org/bot" .$_POST['test-token'] ."/getMe";
	return file_get_contents($url);
}

if(checkvar($_POST)){
	if(checkvar($_POST['test-token'])){
		header("Content-Type: application/json");
		die(getMe($_POST['test-token']));
	}elseif(checkvar($_POST['token']) && checkvar($_POST['user']) && checkvar($_POST['passwd'])){
		$hash = $_POST['user'] .":" .password_hash($_POST['passwd'], PASSWORD_BCRYPT, ['cost' => 5]);
		$fp = fopen(".htpasswd", 'a');
		fwrite($fp, $hash ."\n");
		fclose($fp);

		$token = $_POST['token'];
		$bot = getMe($token);
		if(empty($bot)){
			// TODO Error
			http_response_code(404);
			die("Bot token invalid.");
		}
		$token = explode(":", $token);
		$bot = json_decode($bot, TRUE);

		$config['bot'] = [
			'id' => $token[0],
			'key' => $token[1],
			'name' => $bot['result']['first_name'],
			'username' => $bot['result']['username'],
		];

		// If using Telegram-PHP-App
		$config['using-app'] = (
			file_exists("../config.php") &&
			is_dir("../app") &&
			is_dir("../core") &&
			is_dir("../libs/Telegram-PHP")
		);

		// TODO Create login with command or via PHP
		// htpasswd -nBb user passwd
		// user:$2y$05$QwMugLpbvll2SIROdiyIOOCJRrClJaK6DZUOK2U/WcSZ44Ut7jssW

		// Save Config
		// http://stackoverflow.com/a/2237315
		$config = var_export($config, TRUE);
        file_put_contents('config.php', "<?php return $config ;");

		// Delete setup files
		if(file_exists('config.php')){
			$files = ['templates/setup.html', 'setup.php'];
			foreach($files as $f){ unlink($f); }

			header("Location: index.php");
			die();
		}
		// TODO Check config error
		http_response_code(400);
		die("Could not write config.");
	}
}

$setup = file_get_contents("templates/setup.html");
$web = str_replace(
	["%%TITLE%%", "%%BOTNAME%%", "%%MENU%%", "%%MAIN%%"],
	["Configuración", "Telegram-PHP-Admin", "", $setup],
	$web
);

echo $web;

// TODO Add Analytics tag to render Graphs?

?>
