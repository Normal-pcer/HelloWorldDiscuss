<?php

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
}

function is_password_true($password, $real)
{
    return encode_pass($password) == $real;
}
