<?php
require_once("autoload.php");
$ajax = new AjaxSession();
$ajax->startSession();
$ajax->processRequest();
?>