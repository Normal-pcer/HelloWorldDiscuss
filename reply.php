<?php
require_once "funcs.php";

function CreateReply($content, $discussion_id)
{
    $conn = GetConnection();
    // 读取discussion_id为$discussion_id的讨论
    // 获取最高的floor
    $sql = "SELECT MAX(`floor`) FROM `discusses` WHERE `discussion_id` = $discussion_id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $floor = $row['MAX(`floor`)'] + 1;
    // 插入新回复
    $sql = "INSERT INTO `discusses` (`discussion_id`, `floor`, `content`, `sendtime`, `user_id`, `discussionname`) VALUES ($discussion_id, $floor, '$content', " . time() . ", " . GetUserInCookies()["user_id"] . ", 'Re: " . GetSomething("discusses", "discussion_id = " . $discussion_id)["discussionname"] .  "')";
    $result = $conn->query($sql);
}

function CreateRoot($title, $content)
{
    $conn = GetConnection();
    // 获取最高的discussion_id
    $sql = "SELECT MAX(`discussion_id`) FROM `discusses`";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $discussion_id = $row['MAX(`discussion_id`)'] + 1;
    // 插入新讨论
    $sql = "INSERT INTO `discusses` (`discussion_id`, `discussionname`, `content`, `sendtime`, `user_id`) VALUES ($discussion_id, '$title', '$content', " . time() . ", " . GetUserInCookies()["user_id"] . ")";
    $result = $conn->query($sql);

    return $discussion_id;
}

$action = $_POST['action'];
if ($action == 'reply') {
    CreateReply($_POST['content'], $_POST["aid"]);
    header("Location: discuss.php?aid=" . $_POST["aid"]);
} else if ($action == 'create') {
    $aid = CreateRoot($_POST['title'], $_POST['content']);
    header("Location: discuss.php?aid=" . $aid);
}
