<div class="mdui-appbar mdui-appbar-fixed mdui-appbar-scroll-toolbar-hide ">
  <div class="mdui-toolbar mdui-color-theme">
    <a href="index.php?act=home" class="mdui-btn mdui-btn-icon">
      <i class="mdui-icon material-icons">home</i>
    </a>
    <a href="javascript:;" class="mdui-typo-title"><?php echo $config["title"]; ?></a>
    <div class="mdui-toolbar-spacer"></div>
    <a href="javascript:developalert();" class="mdui-btn mdui-btn-icon">
      <i class="mdui-icon material-icons">search</i>
    </a>
    <a href="javascript:location.reload();" class="mdui-btn mdui-btn-icon">
      <i class="mdui-icon material-icons">refresh</i>
    </a>
    <a href="javascript:developalert();" class="mdui-btn mdui-btn-icon">
      <i class="mdui-icon material-icons">notifications</i>
    </a>
    <a href="javascript:developalert();" class="mdui-btn mdui-btn-icon">
      <i class="mdui-icon material-icons">&#xe7fd;</i>
    </a>
    <a href="javascript:;" class="mdui-btn mdui-btn-icon">
      <i class="mdui-icon material-icons">more_vert</i>
    </a>

  </div>
  <div class="mdui-tab mdui-color-theme" mdui-tab>
    <?php

    if (isset($_COOKIE["uid"])) {
      $spacelink = $_COOKIE["uid"];
      echo "<a href=index.php?act=space&uid=" . $spacelink . " data-toggle=tab\" class=\"mdui-ripple mdui-ripple-white\">个人空间</a>";
      echo "<a href=index.php?act=logout data-toggle=tab\" class=\"mdui-ripple mdui-ripple-white\">退出登录</a>";
    } else {
      echo "<a href=\"login.php\" data-toggle=tab\" class=\"mdui-ripple mdui-ripple-white\">登录</a>";
      echo "<a href=\"index.php?act=signup\" data-toggle=tab\" class=\"mdui-ripple mdui-ripple-white\">注册</a>";
    }
    ?>

  </div>
</div>
