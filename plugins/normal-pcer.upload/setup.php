<?php
// 写入数据库
// 结构：
/*"files": {
    "user_id": "上传的用户",
    "filename_in_server": "服务器中的文件名",
    "filename_source": "上传的文件名"
}*/

$user = GetUserInCookies();
// 检查权限
if ($user == false) {
    echo "请先登录";
    exit();
} else if (!CheckPermission($user['username'], 'control-plugin')) {
    echo "您没有权限";
    exit();
}

$conn = GetConnection();
$sql = "CREATE TABLE IF NOT EXISTS `files` (
    `file_id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `filename_in_server` varchar(255) NOT NULL,
    `filename_source` varchar(255) NOT NULL,
    PRIMARY KEY (`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
$conn->query($sql);
