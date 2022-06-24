<ul class="nav nav-tabs">
        <div class="navbar-header">
            <a class="navbar-brand" href="index.php"><?php echo $config["title"]; ?></a>
        </div>

        <li><a href="index.php?act=home" data-toggle="tab">首页</a></li>

        <li><a href="index.php?act=parts" data-toggle="tab">分区</a></li>


        <?php

        if (isset($_COOKIE["uid"])) {
            $spacelink = $_COOKIE["uid"];
            echo "<li><a href=index.php?act=space&uid=" . $spacelink . " data-toggle=tab\">个人空间</a></li>";
            echo "<li><a href=index.php?act=logout data-toggle=tab\">退出登录</a></li>";
        } else {
            echo "<li><a href=\"login.php\" data-toggle=tab\">登录</a></li>";
            echo "<li><a href=\"index.php?act=signup\" data-toggle=tab\">注册</a></li>";
        }
        ?>

</ul>