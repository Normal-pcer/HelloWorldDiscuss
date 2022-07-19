<?php
$title = GetSwapData("title");
$content = GetSwapData("content");

$content = str_replace("sb", "hhhh", $content);
SetSwapData("content", $content);
