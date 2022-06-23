<html>
<?php
    require "funcs.php";
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
        $curlcheckforupdate="123";
        $curlcheckforupdate = curl_init();
        curl_setopt($curlcheckforupdate, CURLOPT_URL, "https://miaowuawa.cn/hwdupdate.txt");
        curl_setopt($curlcheckforupdate, CURLOPT_RETURNTRANSFER, 1); 
        $nowversion=curl_exec($curlcheckforupdate);
        
        

    }

?>
<head>
    <title><?php echo $config["title"]."管理后台";?></title>
    <?php require "cssandjs.php"?>
</head>
<body>
<div class="mdui-appbar">
  <div class="mdui-toolbar mdui-color-theme">
    <a href="javascript:;" class="mdui-btn mdui-btn-icon">
      <i class="mdui-icon material-icons">menu</i>
    </a>
    <a href="javascript:alert('点我干嘛？');" class="mdui-typo-title"><?php echo $config["title"]."管理后台";?></a>
    <div class="mdui-toolbar-spacer"></div>
    <a href="javascript:developalert();" class="mdui-btn mdui-btn-icon">
      <i class="mdui-icon material-icons">search</i>
    </a>
    <a href="javascript:location.reload ();" class="mdui-btn mdui-btn-icon">
      <i class="mdui-icon material-icons">refresh</i>
    </a>
    <a href="javascript:;" class="mdui-btn mdui-btn-icon">
      <i class="mdui-icon material-icons">more_vert</i>
    </a>
  </div>
  <div class="mdui-tab mdui-color-theme" mdui-tab>
    <a href="#example3-tab1" class="mdui-ripple mdui-ripple-white">首页</a>
    <a href="#example3-tab1" class="mdui-ripple mdui-ripple-white">安全</a>
    <a href="#example3-tab1" class="mdui-ripple mdui-ripple-white">运营</a>
    <a href="#example3-tab1" class="mdui-ripple mdui-ripple-white">用户</a>
    <a href="#example3-tab1" class="mdui-ripple mdui-ripple-white">SEO</a>
  </div>
</div>

<div class=main>
<br>
<br>
<h1>管理后台首页</h1>
<?php 
    $usingversion=$config["discussversion"];
    echo "<font color=\"#9bef51\" size=4>最新版本:$nowversion</font>";
    if($usingversion==$nowversion){
      echo "<font color=\"#9bef51\" size=4> （您正在使用最新版本）</font>";
    }
    else{
      echo "<font color=brown size=4> （为确保安全，请尽快升级）</font>";
    }

    echo "<br>";
    echo "<br>";
    echo "<font size=4>注册用户：-位 总发帖量：-篇 主题：-篇";

?>
</div>
</body>
</html>