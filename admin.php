<html>
<?php
require "funcs.php";

if (get_user_information_from_cookie() != false) {
    //getuserinfo 
    $userinfo = get_user_information_from_cookie();

    if (get_group_information_from_user_id($userinfo["user_id"])["group_id"] != 1)
        die("ERR_USER_NOT_ADMIN");

    $config = json_decode(file_get_contents('config.json'), true);
    $db_host = $config['database.host'];
    $db_name = $config['database.name'];
    $db_user = $config['database.user'];
    $db_pass = $config['database.pass'];
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    $curlcheckforupdate = "123";
    $curlcheckforupdate = curl_init();
    curl_setopt($curlcheckforupdate, CURLOPT_URL, "https://miaowuawa.cn/hwdupdate.txt");
    curl_setopt($curlcheckforupdate, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curlcheckforupdate, CURLOPT_USERAGENT, 'Helloworlddiscuss updatechecker(curl)');
    $nowversion = curl_exec($curlcheckforupdate);
    $httpCode = curl_getinfo($curlcheckforupdate, CURLINFO_HTTP_CODE);
    //获取喵呜服务器上的公告


} else {
    die("ERR_NOT_LOGIN");
}

if (array_key_exists("act", $_GET)) {
    $act = $_GET["act"];
    if ($act == "server.salt.disable") {
        // check if there is only one user
        $result = $conn->query("SELECT * FROM users");
        if (mysqli_num_rows($result) > 1) {
            die("ERR_HAVE_USER");
        }
        if (get_user_information_from_uid(1) != $config["server.admin.username"]) {
            die("ERR_ADMIN_USERNAME_CHANGED");
        }
        if (!is_password_true($config["server.admin.password"], get_user_information_from_uid(1)["password"])) {
            die("ERR_ADMIN_PASSWORD_CHANGED");
        }
        $config["server.salt.enabled"] = false;
        file_put_contents("config.json", json_encode($config));

        $conn->query("UPDATE `users` SET `password`='" . encode_pass($config["server.admin.password"]) . "' WHERE `user_id`=1");
    } else if ($act == "server.salt.enable") {
        $result = $conn->query("SELECT * FROM users");
        if (mysqli_num_rows($result) > 1) {
            die("ERR_HAVE_USER");
        }
        if (get_user_information_from_uid(1) != $config["server.admin.username"]) {
            die("ERR_ADMIN_USERNAME_CHANGED");
        }
        if (!is_password_true($config["server.admin.password"], get_user_information_from_uid(1)["password"])) {
            die("ERR_ADMIN_PASSWORD_CHANGED");
        }
        $config["server.salt.enabled"] = true;
        file_put_contents("config.json", json_encode($config));

        $conn->query("UPDATE `users` SET `password`='" . encode_pass($config["server.admin.password"]) . "' WHERE `user_id`=1");

    } else if ($act == "users-more") {
        $sql = "SELECT * FROM `usergroups`";
        $result = $conn->query($sql);
        echo "<table>";
        echo "<tr><th>用户组ID</th><th>用户组名称</th><th>操作</th<</tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["group_id"] . "</td><td>" . $row["title"] . "</td><td>" .
                "<a href=\"admin.php?act=group-control&gid=" . $row["group_id"] . "\">编辑组</a>" . "</td></tr>";
        }
        echo "</table><hr>";

        $sql = "SELECT * FROM `users`";
        $result = $conn->query($sql);
        echo "<table>";
        echo "<tr><th>用户ID</th><th>用户名</th><th>邮箱</th><th>归属的用户组</th>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["user_id"] . "</td>";
            echo "<td>" . $row["username"] . "</td>";
            echo "<td>" . $row["email"] . "</td>";
            echo "<td>" . $row["usergroup"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<a href=\"admin.php\">返回到管理主页</a>";
        die("");
    } else if ($act == "group-control") {
        $gid = $_GET["gid"];
        $sql = "SELECT * FROM `usergroups` WHERE `group_id` = '$gid'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        echo "<form action=\"admin.php?act=group-control-submit\" method=\"post\">";
        echo "<input type=\"hidden\" name=\"gid\" value=\"$gid\">";
        echo "<p>用户组名称：<input type=\"text\" name=\"title\" value=\"" . $row["title"] . "\"></p>";
        echo "删除任何人的讨论：<input type=\"text\" name=\"delete_everyone_dis\" value=\"" . $row["delete_everyone_dis"] . "\"></input>";
        echo "<input type=\"submit\" value=\"提交\">";
        die("");
    } else if ($act == "group-control-submit") {
        $gid = $_POST["gid"];
        $delete_everyone_dis = $_POST["delete_everyone_dis"];

        $sql = "UPDATE `usergroups` SET `delete_everyone_dis` = '$delete_everyone_dis' WHERE `group_id` = '$gid'";
        $result = $conn->query($sql);
    }
}

?>

<head>
    <title><?php echo $config["title"] . "管理后台"; ?></title>
    <?php require "cssandjs.php" ?>
</head>

<body class="mdui-theme-primary-blue">
    <div class="mdui-appbar">
        <div class="mdui-toolbar mdui-color-theme">
            <a href="javascript:;" class="mdui-btn mdui-btn-icon">
                <i class="mdui-icon material-icons">menu</i>
            </a>
            <a href="javascript:alert('点我干嘛？');" class="mdui-typo-title"><?php echo $config["title"] . "管理后台"; ?></a>
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
            <a href="#home" class="mdui-ripple mdui-ripple-white">首页</a>
            <a href="#safety" class="mdui-ripple mdui-ripple-white">安全</a>
            <a href="#operation" class="mdui-ripple mdui-ripple-white">运营</a>
            <a href="#platforms" class="mdui-ripple mdui-ripple-white">平台对接</a>
            <a href="#user" class="mdui-ripple mdui-ripple-white" data-toggle="tab">用户</a>
            <a href="#seo" class="mdui-ripple mdui-ripple-white">SEO</a>
            <a href="#plugins" class="mdui-ripple mdui-ripple-white">扩展</a>
        </div>
    </div>

    <div class=main>
        <br>
        <br>
        <h1><?php echo $config["title"] . "管理后台"; ?></h1>
        <h2 id="safety">安全</h2>
        <h3>框架版本</h3>
        <?php
        $usingversion = $config["discussversion"];
        if ($httpCode != 200) {

            echo "<font color=brown> 更新检测服务器异常 </font>";
        } else {
            $usingversion = $config["discussversion"];
            echo "<font color=\"#9bbd51\" size=4>最新版本:$nowversion</font>";
        }

        if ($usingversion == $nowversion) {
            echo "<font color=\"#9bbd51\" size=4> （您正在使用最新版本）</font>";
        } else {
            $tmpusingversion = $config["discussversion"];
            echo "<font color=brown size=4> 您正在使用：$tmpusingversion<br>（为确保安全，请尽快升级）</font>";
        }
        ?>
        <h3>密码加盐</h3>
        <?php
        if ($config["server.salt.enabled"] == true) {
            echo "<a href=\"admin.php?act=server.salt.disable\"><font color=\"#9bbd51\" size=4> 已开启密码加盐，点击关闭 </font> <br></a>";
            echo "<font color=\"#9bbd51\" size=4> 盐值：" . $config["server.salt.value"] . " </font> ";
        } else {
            echo "<a href=\"admin.php?act=server.salt.enable\"><font color=brown size=4> 已关闭密码加盐，点击开启 </font></a> ";
        }
        ?>
        <h3>数据库配置</h3>
        <p>数据库主机: <?php echo $config["database.host"]; ?></p>
        <p>数据库名: <?php echo $config["database.name"]; ?></p>
        <p>数据库用户名: <?php echo $config["database.user"]; ?></p>
        <p>数据库密码: <?php echo $config["database.pass"]; ?></p>

        <h2 id="user">用户</h2>
        <h3>用户管理</h3>
        <?php
        $conn = new mysqli($config["database.host"], $config["database.user"], $config["database.pass"], $config["database.name"]);

        $sql = "SELECT * FROM `users`";
        $res = mysqli_query($conn, $sql);
        $user_num = mysqli_num_rows($res);
        echo "<p>当前共有 $user_num 个用户";
        $sql = "SELECT * FROM `usergroups`";
        $res = mysqli_query($conn, $sql);
        $usergroup_num = mysqli_num_rows($res);
        echo "和 $usergroup_num 个用户组。</p>";
        echo "<a href=\"admin.php?act=users-more\"> 点击查看更多用户信息 </a>";
        ?>
        <?php
        //查看数开启状态
        // if ($config["plugin.countview.enabled"]) {
        //     if ($config["plugin.countview.user_only"]) {
        //         echo "<li><font color=\"#9bbd51\" size=3>已启用查看数，已开启去重</font></li>";
        //     } else {
        //         echo "<li><font color=brown>已启用查看数，但是未开启去重，建议开启</font></li>";
        //     }
        // } else {
        //     echo "<li><font color=grey>未开启查看数</font></li>";
        // }

        // if ($config["plugin.point.enabled"]) {

        //     echo "<li><font color=grey>积分系统已启用</font><li>";
        // } else {
        //     echo "<li><font color=grey>积分系统未启用</font><li>";
        // }

        // echo "<li><font size=3>php版本号:" . substr(PHP_VERSION, 0, 3) . "(建议使用7.0以上版本）</font>";
        // echo "</ul>";



        ?>
        <h2 id="plugin">插件管理</h2>
        <h3></h3>
    </div>
</body>

</html>