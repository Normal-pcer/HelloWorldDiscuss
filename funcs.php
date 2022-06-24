<?php

function get_user_information_from_uid($user_id, $check_pass = false, $pass = 0)
{
    if (!is_numeric($user_id)) {
        return false;
    }
    // Read config.json
    $config = json_decode(file_get_contents('config.json'), true);
    $db_host = $config['database.host'];
    $db_name = $config['database.name'];
    $db_user = $config['database.user'];
    $db_pass = $config['database.pass'];

    // Connect to database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Get user information from database
    $sql = "SELECT * FROM `users` WHERE `user_id` = '$user_id'";
    $result = $conn->query($sql);

    // Check if user exists
    if ($result->num_rows > 0) {
        // Get user information
        $row = $result->fetch_assoc();

        if ($check_pass) {
            // Check if password is correct
            if (is_password_true($pass, $row['password'])) {
                // Return user information
                return $row;
            } else {
                return false;
            }
        } else {
            // Return user information
            return $row;
        }
    } else {
        return false;
    }
}

function get_user_information_from_username($username)
{
    // Read config.json
    $config = json_decode(file_get_contents('config.json'), true);
    $db_host = $config['database.host'];
    $db_name = $config['database.name'];
    $db_user = $config['database.user'];
    $db_pass = $config['database.pass'];

    // Connect to database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Get user information from database
    $sql = "SELECT * FROM `users` WHERE `username` = '$username'";
    $result = $conn->query($sql);

    // Check if user exists
    if ($result->num_rows > 0) {
        // Get user information
        $row = $result->fetch_assoc();

        // Return user id
        return $row;
    } else {
        return false;
    }
}

function get_user_information_from_cookie()
{
    // Get cookie
    if (!isset($_COOKIE['uid'])) {
        return false;
    }


    $user_id = $_COOKIE['uid'];
    $user_password = $_COOKIE['pass_sha256'];

    $result =  get_user_information_from_uid($user_id, false);
    if ($result) {
        if ($user_password == $result['password']) {
            return $result;
        } else {
            return false;
        }
    }
}


function encode_pass($pass)
{
    // Read config.json
    $config = json_decode(file_get_contents('config.json'), true);
    $salt = $config["server.salt.value"];
    $issalt = $config["server.salt.enabled"];
    if ($issalt) {
        $pass = hash('sha256', $pass . $salt);
    } else {
        $pass = hash('sha256', $pass);
    }
    return $pass;
}

function is_password_true($password, $real)
{
    if (encode_pass($password) == $real)
        return true;
    else
        return false;
}

function get_ip_location($ipaddress)
{
    //这一段直接抄csdn上的了
    $ch = curl_init();
    $url = 'https://whois.pconline.com.cn/ipJson.jsp?ip=' . $ipaddress;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //https SSL
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    //获取
    $location = curl_exec($ch);
    //至此已经获得ip属地，关闭连接
    curl_close($ch);
    //转码
    $location = mb_convert_encoding($location, 'utf-8', 'GB2312');
    $location = substr($location, strlen('({') + strpos($location, '({'), (strlen($location) - strpos($location, '})')) * (-1));
    //将截取的字符串$location中的‘，’替换成‘&’   将字符串中的‘：‘替换成‘=’
    $location = str_replace('"', "", str_replace(":", "=", str_replace(",", "&", $location)));
    //php内置函数，将处理成类似于url参数的格式的字符串  转换成数组
    parse_str(
        $location,
        $ip_location
    );
    return $ip_location['addr'];
}

function get_discuss($dis_id, $floor)
{
    // Read config.json
    $config = json_decode(file_get_contents('config.json'), true);
    $db_host = $config['database.host'];
    $db_name = $config['database.name'];
    $db_user = $config['database.user'];
    $db_pass = $config['database.pass'];

    // Connect to database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Get discuss information from database
    $sql = "SELECT * FROM `discusses` WHERE `dis_id` = '$dis_id' AND `floor` = '$floor'";
    $result = $conn->query($sql);
    $result = $result->fetch_assoc();
    return $result;
}

function del_discuss($dis_id, $floor)
{
    // check if user is the owner of the discuss or admin
    $user = get_user_information_from_cookie();
    if ($user == false) {
        die("ERR_NOT_LOGIN");
    }
    $dis = get_discuss($dis_id, $floor);

    $config = json_decode(file_get_contents('config.json'), true);
    $conn = new mysqli($config['database.host'], $config['database.user'], $config['database.pass'], $config['database.name']);
    $sql = "SELECT * FROM `usergroups` WHERE `group_id` = '" . $user["usergroup"] . "'";
    $result = $conn->query($sql);
    $group = $result->fetch_assoc();

    if (
        $user['user_id'] == $dis['user_id'] || $group['delete_everyone_dis'] == 1
    ) {
        // Read config.json
        $config = json_decode(file_get_contents('config.json'), true);
        $db_host = $config['database.host'];
        $db_name = $config['database.name'];
        $db_user = $config['database.user'];
        $db_pass = $config['database.pass'];

        // Connect to database
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

        // Delete discuss from database
        $sql = "DELETE FROM `discusses` WHERE `dis_id` = '$dis_id' AND `floor` = '$floor'";
        $result = $conn->query($sql);
    } else {
        die("ERR_NOT_OWNER");
    }
}
function getusrip()
{
    $unknown = 'unknown';
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)) {
        $ip = $_SERVER['REMOTE_ADDR'];
        if (false !== strpos($ip, ','))
            $ip = reset(explode(',', $ip));
        return $ip
            /*

    处理多层代理的情况
    
    或者使用正则方式：$ip = preg_match("/[\d\.]{7,15}/", $ip, $matches) ? $matches[0] : $unknown;
    
    */;
    }
}
function antisql($data)
{
    return addslashes(strip_tags(trim($data)));
}

function add_discuss_countview($dis_id)
{
    $config = json_decode(file_get_contents('config.json'), true);
    if (($config['plugin.countview.user_only'] && get_user_information_from_cookie() != false) ||
        ($config['plugin.countview.user_only'] == false)
    ) {
        $db_host = $config['database.host'];
        $db_name = $config['database.name'];
        $db_user = $config['database.user'];
        $db_pass = $config['database.pass'];

        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
        $sql = "UPDATE `discusses` SET `countview` = `countview` + 1 WHERE `dis_id` = '$dis_id'";
        $result = $conn->query($sql);
    }
    return true;
}

function check_user_login_token($username, $password)
{
    $r_pass = get_user_information_from_username($username);

    if ($r_pass == false)
        die("ERR_USER_NOT_EXIST" . $username);
    $r_pass = $r_pass['password'];

    $res = is_password_true($password, $r_pass);
    if ($res) {
        return true;
    } else {
        return false;
    }
}

function get_group_information_from_group_id($group_id)
{
    $config = json_decode(file_get_contents('config.json'), true);
    $db_host = $config['database.host'];
    $db_name = $config['database.name'];
    $db_user = $config['database.user'];
    $db_pass = $config['database.pass'];

    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    $sql = "SELECT * FROM `usergroups` WHERE `group_id` = '" . $group_id . "'";
    $result = $conn->query($sql);
    $result = $result->fetch_assoc();
    return $result;
}

function get_group_information_from_user_id($user_id)
{
    $config = json_decode(file_get_contents('config.json'), true);
    $db_host = $config['database.host'];
    $db_name = $config['database.name'];
    $db_user = $config['database.user'];
    $db_pass = $config['database.pass'];

    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    $sql = "SELECT * FROM `users` WHERE `user_id` = '$user_id'";
    $result = $conn->query($sql);
    $result = $result->fetch_assoc();

    return get_group_information_from_group_id($result["usergroup"]);
}