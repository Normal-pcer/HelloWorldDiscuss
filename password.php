<?php
function PasswordEncode($password)
{
    // 读取配置
    $config = GetConfig();
    if ($config["safety"]["password-salt"]["enabled"]) {
        $result = crypt($password, $config["safety"]["password-salt"]["value"]);
    } else {
        $result = crypt($password, "");
    }
    return $result;
}
