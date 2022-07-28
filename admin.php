<?php
require_once 'funcs.php';
$currentUser = GetUserInCookies();
$config = GetConfig();
?>

<html>

<head>
    <meta charset='UTF-8'>
    <title>网站管理</title>
</head>

<body>
    <h1>Safety/安全</h1>
    <h2>DataBase/数据库</h2>
    <ul>
        <li>数据库主机：<?php echo CheckPermission($currentUser['username'], 'reset-database') ? $config['safety']['database']['host'] : "*****" ?></li>
        <li>数据库名：<?php echo CheckPermission($currentUser['username'], 'reset-database') ? $config['safety']['database']['name'] : "*****"; ?></li>
        <li>数据库用户名：<?php echo CheckPermission($currentUser['username'], 'reset-database') ? $config['safety']['database']['user'] : "*****"; ?></li>
        <li>数据库密码：<?php echo CheckPermission($currentUser['username'], 'reset-database') ? str_repeat("*", strlen($config['safety']['database']['password'])) : "*****"; ?></li>
    </ul>
    <p>请在config.json中修改这些内容</p>
    <h2>Salt/密码加盐</h2>
    <ul>
        <li>启用情况：<?php echo GetConfig()['safety']['password-salt']['enabled'] ? '已启用' : '未启用' ?></li>
        <li>盐值的长度：<span style="color: <?php $val = GetConfig()['safety']['password-salt']['value'];
                                        echo (strlen($val) < 50 ? '#c66' : '#6c6'); ?>"><?php echo strlen($val); ?></span></li>
    </ul>
    <p>长度建议设置为不低于50</p>
    <p>不应修改密码加盐相关内容，否则会使<strong>所有</strong>用户的密码全部失效</p>
    <h1>Plugins/插件</h1>
    <div id="pluginList">
        <?php
        if (CheckPermission($currentUser, "control-plugin")) {
            $fileName = "plugins/index.json";
            $fileData = file_get_contents($fileName);
            $plugins = json_decode($fileData, true);
            foreach ($plugins as $plugin => $pluginInfo) {
                $moreInfo = file_get_contents("plugins/$plugin/info.json");
                $moreInfo = json_decode($moreInfo, true);
                echo "<h2>$plugin/" . $pluginInfo['name'] . "</h2>";
                echo "<ul>";
                echo "<li>描述：" . $pluginInfo['description'] . "</li>";
                echo "<li>作者：" . $moreInfo['author'] . "</li>";
                echo "<li>版本：" . $moreInfo['version'] . "</li>";
                echo "<li><a href='plugins/$plugin/" . $moreInfo["document"] . "'>查看文档</a></li>";
                echo "<li><a href='plugins/$plugin/" . $moreInfo['setting'] . "'>设置</a></li>";
                echo "</ul>";
            }
        } else {
            echo "<p>没有权限</p>";
        }
        ?>
    </div>
</body>

</html>