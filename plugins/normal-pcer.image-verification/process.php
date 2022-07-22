<?php
require_once "login.php";

$action = $_POST["action"];

function CheckVerification($input, $id)
{
    $verifications = file_get_contents('plugins/normal-pcer.image-verification/verifications.json');
    $verifications = json_decode($verifications, true);
    if (array_key_exists($id, $verifications)) {
        if ($verifications[$id] == $input) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

if ($action == "login_process") {
    $user = CheckVerification($_POST['verification'], $_POST['number']) ? (login($_POST["username"], $_POST["password"])) : false;
    if ($user) {
        echo "登录成功";
    } else {
        echo "登录失败";
    }
}
