<?php

function showLogin(){
	if(!isset($_POST['user']) or !isset($_POST['passwd'])){
		header('WWW-Authenticate:Basic realm="Telegram PHP Admin Login"');
		http_response_code(401);
	}

    $web = file_get_contents("templates/render.html");
    $login = file_get_contents("templates/login.html");

    $web = str_replace(
    	["%%TITLE%%", "%%BOTNAME%%", "%%MENU%%", "%%MAIN%%"],
    	["Iniciar sesión", "Telegram PHP Admin", "", $login],
    	$web
    );

    die($web);
}

session_start(); // HACK

if(!isset($_SESSION['login_time'])){
    if(isset($_POST['user']) && isset($_POST['passwd'])){
        $auth = [$_POST['user'], $_POST['passwd']];
    }else{
        // If not POST, use Authorization field.
        $headers = getallheaders();
        if(!isset($headers['Authorization'])){ showLogin(); }
        if(strpos($headers['Authorization'], "Basic ") !== 0){ showLogin(); } // If not Basic login, error.

        $auth = substr($headers['Authorization'], strlen("Basic "));
        $auth = base64_decode($auth);
        $auth = explode(":", $auth);
    }

    $users = explode("\n", file_get_contents(".htpasswd"));
	if(empty($users)){ showLogin(); }
    foreach($users as $u){
        list($user, $pass) = explode(":", $u);
        if($user == $auth[0]){
            if(password_verify($auth[1], $pass)){
                $_SESSION['login_id'] = $auth[0];
                $_SESSION['login_time'] = (time() + 300);
            }else{
				$access = date("Y/m/d H:i:s");
    			syslog(LOG_WARNING, "Telegram-PHP-Admin Access denied: $access {$_SERVER['REMOTE_ADDR']} ({$_SERVER['HTTP_USER_AGENT']})");
                showLogin();
            }
        }
    }
}

if($_SESSION['login_time'] < time()){
    session_destroy();
    showLogin();
}

$_SESSION['login_time'] = (time() + 300);

?>
