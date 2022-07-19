<?php
require_once "funcs.php";
function GetInfo0()
{
    echo "<form action=\"setup.php\" method=\"POST\">";
    echo "<input type=\"hidden\" value=\"run\" name=\"status\">";
    echo "<h2>安全设置</h2>";
    echo "<h3>数据库</h3>";
    echo "<p>主机: <input type=\"text\" name=\"database.host\"></p>";
    echo "<p>数据库名: <input type=\"text\" name=\"database.name\"></p>";
    echo "<p>用户名: <input type=\"text\" name=\"database.user\"></p>";
    echo "<p>密码: <input type=\"password\" name=\"database.pass\"></p>";
    echo "<h3>密码保护</h3>";
    echo "<p>密码盐值: <input type=\"text\" name=\"salt.value\" placeholder=\"不使用加盐请留空\"></p>";
    echo "<h2>初始管理员</h2>";
    echo "<p>用户名: <input type=\"text\" name=\"admin.name\" value=\"root\">";
    echo "<p>密码: <input type=\"password\" name=\"admin.pass\"><p>";
    echo "<h2>其他</h2>";
    echo "<p>标题: <input type='text' name='title'></p>";
    echo "<p>默认语言: <input type='text' name='lang' value='zh-cn'></p>";
    echo "<hr>";
    echo "<input type=\"submit\" name=\"嗨害嗨\" value=\"开始安装\">";
}

function GetInfo1()
{
    $config = json_decode('{
    "safety": {
        "database": {
            "host": "' . $_POST['database_host'] . '",
            "name": "' . $_POST['database_name'] . '",
            "user": "' . $_POST['database_user'] . '",
            "password": "' . $_POST['database_pass'] . '"
        },
        "password-salt": {
            "enabled": ' . ($_POST['salt_value'] != '' ? 'true' : 'false') . ',
            "value": "' . $_POST["salt_value"] . '"
        }
    },
    "defaultSetting": {
        "language": "' . $_POST["lang"] . '"
    },
    "websiteSetting": {
        "title": "' . $_POST["title"] . '"
    }
}');
    WriteConfig($config);
    Run();
}

function Run()
{
    // 删除已有的数据表
    $conn = GetConnection();
    $conn->query("DROP TABLE users,discusses,usergroups,tokens");
    // 创建数据表
    // `users`, 参考example/database/users.json
    /*{
    "user_id": "[Primary Key]用户唯一标识",
    "username": "用户名",
    "password": "密码(crypt加密)",
    "email": "邮箱",
    "usergroup": "用户组",
    "status": "状态(normal正常, banned封禁)",
    "avatar": "头像",
    "qianming": "个人签名"
} */
    $conn->query(
        "CREATE TABLE `users` (
        `user_id` INT NOT NULL AUTO_INCREMENT,
        `username` VARCHAR(20) NOT NULL DEFAULT '神秘人',
        `password` VARCHAR(255) NOT NULL DEFAULT '123456',
        `email` VARCHAR(255) NOT NULL DEFAULT 'example@example.com',
        `usergroup` VARCHAR(255) NOT NULL DEFAULT '[3]',
        `status` VARCHAR(10) NOT NULL DEFAULT 'normal',
        `avatar` VARCHAR(255) NOT NULL DEFAULT 'source/images/ava.png',
        `qianming` VARCHAR(255) NOT NULL DEFAULT '嗨害嗨',
        PRIMARY KEY (`user_id`)
    )"
    );
    // `discusses`, 参考example/database/discusses.json
    /*
    {
    "discussion_id": "[Primary Key]讨论唯一标识",
    "floor": "[Primary Key]楼层",
    "user_id": "发布的用户",
    "discussionname": "标题",
    "content": "内容",
    "sendtime": "发布时间"
}*/
    $conn->query("CREATE TABLE `discusses` (
        `discussion_id` INT NOT NULL DEFAULT 1,
        `floor` INT NOT NULL DEFAULT 1,
        `user_id` INT NOT NULL,
        `discussionname` VARCHAR(255) NOT NULL,
        `content` TEXT NOT NULL,
        `sendtime` TEXT NOT NULL,
        PRIMARY KEY (`discussion_id`, `floor`)
    )");
    // `usergroups`, 参考example/database/usergroups.json
    /*{
    "usergroup_id": "[Primary Key]用户组唯一标识",
    "usergroupname": "用户组名",
    "permission": "权限"
}
    permission为json字符串，参考：
    {
    "reset-database": "重置",
    "delete-discuss": "删除讨论",
    "change-user": "修改用户信息"
}
*/
    $conn->query("CREATE TABLE `usergroups` (
        `usergroup_id` INT NOT NULL AUTO_INCREMENT,
        `usergroupname` VARCHAR(20) NOT NULL DEFAULT 'Users',
        `permission` VARCHAR(255) NOT NULL DEFAULT '{}',
        PRIMARY KEY(`usergroup_id`))
    ");
    // `tokens`, 参考example/database/tokens.json
    /*{
    "token_id": "[Primary Key]登录令牌唯一标识",
    "user_id": "对应的用户",
    "address": "登录设备的地址",
    "expire_time": "过期时间"

}*/
    $conn->query("CREATE TABLE `tokens` (
        `token_id` VARCHAR(255) NOT NULL ,
        `user_id` INT NOT NULL,
        `address` VARCHAR(255) NOT NULL,
        `expiretime` VARCHAR(255) NOT NULL,
        PRIMARY KEY (`token_id`)
    )");

    // 创建root用户组
    $root_perm =
        "{\\\"reset-database\\\": true, " .
        "\\\"delete-discuss\\\": true, " .
        "\\\"change-user\\\": true" .
        "}";
    $sql = "INSERT INTO `usergroups` (`usergroupname`, `permission`) VALUES (\"Roots\", \"$root_perm\")";
    $conn->query($sql);


    // 创建管理员用户组
    $admin_perm =
        "{\\\"reset-database\\\": false, " .
        "\\\"delete-discuss\\\": true, " .
        "\\\"change-user\\\": true" .
        "}";
    $sql = "INSERT INTO `usergroups` (`usergroupname`, `permission`) VALUES (\"Administrators\", \"$admin_perm\")";
    $conn->query($sql);

    // 创建普通用户组
    $user_perm =
        "{\\\"reset-database\\\": false, " .
        "\\\"delete-discuss\\\": false, " .
        "\\\"change-user\\\": false, " .
        "}";
    $sql = "INSERT INTO `usergroups` (`usergroupname`, `permission`) VALUES (\"Users\", \"$user_perm\")";
    $conn->query($sql);

    // 创建初始管理员
    $sql = "INSERT INTO `users` (`username`, `password`, `usergroup`) VALUES (\"" . $_POST["admin_name"] . "\", \"" . PasswordEncode($_POST["admin_pass"]) . "\", \"[1]\")";
    $conn->query($sql);

    // 创建初始讨论
    $sql = "INSERT INTO `discusses` (`user_id`, `discussionname`, `content`, `sendtime`) VALUES (1, '创建成功', '欢迎来到" . GetConfig()["websiteSetting"]["title"] . "论坛！', " . time() . ")";
    $conn->query($sql);

    $lok = fopen("setup.lock", "w");
    fwrite($lok, "嗨害嗨");
    fclose($lok);
}

if (file_exists("setup.lock") && file_get_contents("setup.lock") != "awa") {
    // 存在setup.lock文件
    if (
        array_key_exists("status", $_POST)
    ) {
        if ($_POST["status"] == "check-admin") {
            $name = $_POST["username"];
            $password = $_POST["password"];
            $isTrue = CheckUserLoginInfo($name, $password);
            if ($isTrue == False) {
                ErrorLoginInfoWrong();
            } else {
                // 检测是否有权限
                if (!CheckPermission($isTrue["username"], "reset-database")) {
                    ErrorNoPremission();
                } else {
                    $lok = fopen("setup.lock", "r");
                    fwrite($lok, "awa");
                    GetInfo0();
                }
            }
        } else {
            echo
            "<form action='setup.php' method='POST'>" .
                "<input type='hidden' name='status' value='check-admin'>" .
                "<input type='text' name='username'>" .
                "<input type='password' name='password' placeholder='密码'>" .
                "<input type='submit' value='验证'>" .
                "</form>";
        }
    } else {
        echo
        "<form action='setup.php' method='POST'>" .
            "<input type='hidden' name='status' value='check-admin'>" .
            "<input type='text' name='username'>" .
            "<input type='password' name='password' placeholder='密码'>" .
            "<input type='submit' value='验证'>" .
            "</form>";
    }
} else {
    if ($_POST["status"] == "run")  Run();
    else
        GetInfo0();
}
?>