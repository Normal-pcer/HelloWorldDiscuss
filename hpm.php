<!-- Hello World Discuss 包管理器 -->
<?php
require_once "funcs.php";

$currentUser = GetUserInCookies();  // 尝试读取Cookie中的用户
if ($currentUser == false) {
    // 跳转到登录界面
    header("Location: login.php");
    exit();
} else if (!CheckPermission($currentUser['username'], "control-plugin")) {
    // 没有权限
    ErrorNoPremission();
    exit();
}
if (isset($_POST['action'])) {
    $action = $_POST['action'];
} else {
    $action = "start";
}

if ($action == 'start') {
    // 显示初始表单
    echo "<h1>Hello World Discuss 包管理器</h1>";
    echo "<form action='hpm.php' method='post'>";
    echo "<input type='hidden' name='action' value='install'>";
    echo "请输入zip包的路径(如'./package.zip', 'https://example.com/releases/package-1.1.2.zip')<input type='text' name='path'>";
    echo "<input type='submit' value='安装'>";
} else if ($action == 'install') {
    // 安装
    $path = $_POST['path'];
    // 读取文件
    $fileData = file_get_contents($path);
    $tmpname = "./cache/" . uniqid() . ".zip";
    $tmpfile = fopen($tmpname, 'wb');
    fwrite($tmpfile, $fileData);
    fclose($tmpfile);
    // 解压
    $zip = new ZipArchive();
    $res = $zip->open($tmpname);
    if ($res === TRUE) {
        $zip->extractTo("plugins/");
        $newName = dirname("plugins/" . $zip->getNameIndex(0));
        $zip->close();
        $index = json_decode(file_get_contents("plugins/index.json"), true);
        $pluginInfo = file_get_contents($newName . '/info.json');
        $pluginInfo = json_decode($pluginInfo, true);
        $index[$pluginInfo['name']]['name'] = $pluginInfo['name'];
        $index[$pluginInfo['name']]['version'] = $pluginInfo['version'];
        $index[$pluginInfo['name']]['tag'] = $pluginInfo['tag'];
        $index[$pluginInfo['name']]['description'] = $pluginInfo['description'];
        file_put_contents("plugins/index.json", json_encode($index));
    } else {
        echo "文件解压失败";
    }
    unlink($tmpname);
}
