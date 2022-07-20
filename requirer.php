<?php
$filename = $_GET['filename'];
$pluginname = $_GET['pluginname'];

require_once "funcs.php";
require_once "plugins/$pluginname/$filename";
