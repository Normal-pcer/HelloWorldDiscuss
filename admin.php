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
       //getuserinfo 
        $userinfo=get_user_information_from_cookie();
        //权限校验还没写
        //到喵呜服务器，在线检测更新
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
    <a href="javascript:developalert();" class="mdui-btn mdui-btn-icon">
      <i class="mdui-icon material-icons">more_vert</i>
    </a>
  </div>
  <div class="mdui-tab mdui-color-theme" mdui-tab>
    <a href="#example3-tab1" class="mdui-ripple mdui-ripple-white">首页</a>
    <a href="#example3-tab1" class="mdui-ripple mdui-ripple-white">安全</a>
    <a href="#example3-tab1" class="mdui-ripple mdui-ripple-white">运营</a>
    <a href="#example3-tab1" class="mdui-ripple mdui-ripple-white">平台对接</a>
    <a href="#example3-tab1" class="mdui-ripple mdui-ripple-white">用户</a>
    <a href="#example3-tab1" class="mdui-ripple mdui-ripple-white">SEO</a>
    <a href="#example3-tab1" class="mdui-ripple mdui-ripple-white">扩展</a>
  </div>
</div>

<div class=main>
<br>
<br>
<h1><?php echo $config["title"]."管理后台";?> 首页</h1>
<?php 

    $usingversion=$config["discussversion"];
    echo "<font color=\"#9bef51\" size=4>最新版本:$nowversion</font>";
    if($usingversion==$nowversion){
      echo "<font color=\"#9bef51\" size=4> （您正在使用最新版本）</font>";
    }
    else{
      $tmpusingversion=$config["discussversion"];
      echo "<font color=brown size=4> 您正在使用：$tmpusingversion<br>（为确保安全，请尽快升级）</font>";
    }

    echo "<br>";
    echo "<br>";
    echo "<font size=4>注册用户：-位 总发帖量：-篇 主题：-篇";
    echo "<br>";
    echo "<br>";
    echo "<h3>主要设置概览：</h3>";
    echo "<ul>";
    //加盐开启状态
    if($config["server.salt.enabled"]){
      echo "<li><font color=\"#9bef51\" size=3 >已启用密码加盐</font></li>";
    }
    else{
      echo "<li><font color=brown>未启用密码加盐,启用可有效保证密码安全</font></li>";
    }
    //显示ip属地开启状态
    if($config["plugin.shouiplocation.enabled"]){
      echo "<li><font color=\"#9bef51\" size=3>已启用ip属地显示</font></li>";
    }
    else{
      echo "<li><font color=brown>未启用ip属地显示（启用可有效防治网暴，冒充热点当事人等行为）</font></li>";
    }
    //查看数开启状态
    if($config["plugin.countview.enabled"]){
      if($config["plugin.countview.user_only"]){
        echo "<li><font color=\"#9bef51\" size=3>已启用查看数，已开启去重</font></li>";
      }
      else{
        echo "<li><font color=brown>已启用查看数，但是未开启去重，建议开启</font></li>";
      }
    }
    else{
      echo "<li><font color=grey>未开启查看数</font></li>";
    }

    echo "<li><font size=3>php版本号:".substr(PHP_VERSION,0,3)."(建议使用7.0以上版本）</font>";
    echo "</ul>";


?>
</div>
</body>
</html>