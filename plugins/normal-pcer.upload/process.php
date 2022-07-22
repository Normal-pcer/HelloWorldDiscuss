<?php
$file = $_FILES['file'];
$file_name = $file['name'];
$file_tmp_name = $file['tmp_name'];
$file_size = $file['size'];
$file_error = $file['error'];
$file_type = $file['type'];
$file_ext = explode('.', $file_name);
$file_ext = strtolower(end($file_ext));
$config = file_get_contents('plugins/normal-pcer.upload/config.json');
$config = json_decode($config, true);
$allowed = $config['allowExt'];
$maxSize = $config['maxSize'];
$user = GetUserInCookies();

if ($user == false) {
    echo "请先登录";
    exit();
} else if ($allowed == '*' || in_array($file_ext, $allowed)) {
    if ($file_error === 0) {
        if ($file_size <= $maxSize) {
            $file_name_new = uniqid('', true) . '.' . $file_ext;
            $file_destination = 'plugins/normal-pcer.upload/uploads/' . $file_name_new;
            if (move_uploaded_file($file_tmp_name, $file_destination)) {
                echo '上传成功';
                // 写入数据库
                $conn = GetConnection();
                // 结构：
                /*"files": {
            "user_id": "上传的用户",
            "filename_in_server": "服务器中的文件名",
            "filename_source": "上传的文件名"
        }*/
                $sql = "INSERT INTO `files` (`user_id`, `filename_in_server`, `filename_source`) VALUES (" . $user['user_id'] . ", '" . $file_name_new . "', '" . $file_name . "');";
                $conn->query($sql);
                $conn->close();
            } else {
                echo '上传失败，未知原因';
            }
        } else {
            echo '上传失败，原因：文件过大';
        }
    } else {
        echo '上传失败，原因：' . $file_error;
    }
} else {
    echo '上传失败，原因：不允许的文件类型';
}
