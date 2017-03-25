<?php

global $config;
if($config['using-app'] == TRUE){
	// Load stuff
	return file_get_contents("../templates/messages.html");
}

return NULL;

?>
