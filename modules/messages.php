<?php

global $config;
if($config['using-app'] == TRUE){
	// Load stuff
	return file_get_contents(dirname(__FILE__) ."/../templates/messages.html");
}

return NULL;

?>
