<?php
// Initial checkup.

if(!file_exists('config.php')){
	if(file_exists('setup.php')){
		die(header("Location: setup.php"));
	}
	http_response_code(500);
	die("Config file not found. Please configure or reinstall.");
}

require 'config.php';


?>
