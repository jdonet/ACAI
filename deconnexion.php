<?php 

header('WWW-Authenticate: Basic realm="private"');
header('HTTP/1.0 401 Unauthorized');

	
	require('index.php');
	exit();
?>
