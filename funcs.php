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
            if (password_verify($pass, $row['password'])) {
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

function get_user_information_from_cookie()
{
    // Get cookie
    $user_id = $_COOKIE['uid'];
    $user_password = $_COOKIE['pass_sha256'];

    return get_user_information_from_uid($user_id, true, $user_password);
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
}

function is_password_true($password, $real)
{
    return encode_pass($password) == $real;
}
