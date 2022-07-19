<?php
function PasswordEncode($password)
{
    // 读取配置
    $config = GetConfig();
    if ($config["safety"]["password-salt"]["enabled"]) {
        $salt = $config["safety"]["password-salt"]["value"];
    } else {
        $salt = "";
    }
    // 加密
    $result = hash("sha512", $password . $salt);
    return $result;
}

function CheckUserLoginInfo($username, $password)
{
    $conn = GetConnection();
    $sql = "SELECT * FROM `users` WHERE `username` = '$username'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (PasswordEncode($password) == $row["password"]) {
            return $row;
        } else {
            return false;
        }
    } else {
        return false;
    }
}
