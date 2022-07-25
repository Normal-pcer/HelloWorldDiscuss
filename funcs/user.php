<?php
require_once "password.php";

function GetUserInfo($given_type = "user_id", $data)
{
    $conn = GetConnection();
    $sql = "SELECT * FROM `users` WHERE `" . $given_type . "` = " . $data;
    $users = $conn->query($sql);
    $users = $users->fetch_assoc();
    return $users;
}

function GetUserIdByToken($token_id)
{
    // 获得数据库中的token信息
    $conn = GetConnection();
    $sql = "SELECT * FROM `tokens` WHERE `token_id` = '" . $token_id . "'";
    $tokens = $conn->query($sql);

    if ($tokens == false) return false;
    $tokens = $tokens->fetch_assoc();


    // 不检查地址是否匹配
    // if ($tokens['address'] != $_SERVER['REMOTE_ADDR']) {
    //     return false;
    // }
    // 检查是否过期
    if ($tokens['expiretime'] < time()) {
        return false;
    }
    // 返回用户ID
    return $tokens['user_id'];
}

function GetUserInCookies()
{
    if (isset($_COOKIE['login_token'])) {
        $token_id = $_COOKIE['login_token'];
        $user_id = GetUserIdByToken($token_id);
        if ($user_id) {
            return GetUserInfo("user_id", $user_id);
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function AddToken($user_id, $time = 3600 * 24 * 30)
{
    $token_id = md5(uniqid(rand(), true));
    $address = $_SERVER['REMOTE_ADDR'];
    $expire_time = time() + $time;
    $conn = GetConnection();
    $sql = "INSERT INTO `tokens` (`token_id`, `user_id`, `address`, `expiretime`)"
    . " VALUES ('" . $token_id . "', '" . $user_id . "', '" . "114514" . "', '" . $expire_time .
        "')";
    $conn->query($sql);
    return $token_id;
}

function CheckPermission($username, $permission_name)
{
    // 从数据库中找到用户
    $conn = GetConnection();
    $sql = "SELECT * FROM `users` WHERE `username` = \"" . $username . "\"";
    $result = $conn->query($sql);
    $result = $result->fetch_assoc();
<<<<<<< HEAD
    if ($result == false) {
        return false;
    }
=======

>>>>>>> 1930decbc6b2447083b340cf69194151564a6f7a
    foreach (json_decode($result['usergroup']) as $usergroup => $value) {
        // 找到对应的用户组
        $sql = "SELECT * FROM `usergroups` WHERE `usergroup_id` = \"" . $value . "\"";
        $result = $conn->query($sql);
        $result = $result->fetch_assoc();
        // 判断权限
        $permission = $result["permission"];
        $permission = json_decode($permission, true);
        $permission = isset($permission_name, $permission) ? $permission[$permission_name] : false;
<<<<<<< HEAD
        if ($permission) {
            return true;
        }
    }
    return false;
=======

        return $permission;
    }
>>>>>>> 1930decbc6b2447083b340cf69194151564a6f7a
}
