<?php

require 'config.php';

function telegram_method($method, $data = NULL){
	$url = "https://api.telegram.org/bot" .$config['bot']['id'] .":" .$config['bot']['key'] ."/" .$method;
	if(!empty($data)){
		if(is_array($data)){ $data = http_build_query($data); }
		$url .= "?$data";
	}
	return $url;
}

?>
