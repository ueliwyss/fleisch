<?php 
ini_set('error_reporting', '');
ini_set('display_errors', 'off');
$site = getenv("HTTP_HOST");
include("http://start.infomaniak.ch/welcome/$site");
?>
