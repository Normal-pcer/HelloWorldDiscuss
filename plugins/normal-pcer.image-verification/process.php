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
        header('Location: index.php');
    } else {
        echo "登录失败，<a href='login.php?action=login'>重试</a>";
    }
} else if ($action == "signup_process") {
    $user = CheckVerification($_POST['verification'], $_POST['number']) ? (signup($_POST["username"], $_POST["password"], $_POST["email"])) : false;
    if ($user) {
        echo "注册成功";
        header('Location: login.php?action=signup');
    } else {
        echo "注册失败, <a href='login.php?action=signup'>重试</a>";
    }
}
