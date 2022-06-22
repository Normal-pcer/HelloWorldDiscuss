<?php
    $cok=$_COOKIE;
    //检测cookie，是否登录？
    if(!isset($cok["uid"])){
        echo "";
    }
?>