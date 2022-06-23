<?php
    $cok=$_COOKIE;
    //检测cookie，是否登录？
    if(!isset($cok["uid"])){
        echo "<script>alert('未登录！')</script>";
        die("请登录后进入后台！");
    }
    else{
        //写了一半awa
        $config = json_decode(file_get_contents('config.json'), true);
        $db_host = $config['database.host'];
        $db_name = $config['database.name'];
        $db_user = $config['database.user'];
        $db_pass = $config['database.pass'];

    }

?>