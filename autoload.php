<?php
function __autoload($class_name) {
			
	require_once "controllers/".$class_name . '.class.php';

}
?>