<!--用户主页-->
<!--$_GET: uid='用户ID'(未指定则为当前用户)-->

<?php
require_once "funcs.php";

if (isset($_GET['uid'])) {
    $uid = $_GET['uid'];
} else {
    $uid = GetUserInCookies();
    if ($uid == false) {
        header('Location: login.php?action=login');
    } else {
        $uid = $uid['user_id'];
    }
}

$conn = GetConnection();

?>

<html lang='zh-hans-cn'>

<head>
    <meta charset='UTF-8' />
    <title><?php echo GetUserInfo('user_id', $uid)['username']; ?> 的个人主页 - <?php echo GetConfig()['websiteSetting']['title']; ?></title>
</head>

<body>
    <div id="userName">
        <h1 style="display: inline-block;"><?php echo GetUserInfo('user_id', $uid)['username'] ?></h1>
    </div>
    <h2><?php echo GetWord("thisUsersDiscussions"); ?></h2>
    <?php
    $sql = "SELECT * FROM `discusses` WHERE `user_id` = $uid";
    $result = $conn->query($sql);
    do {
        $d = $result->fetch_assoc();
        echo "<a href='discuss.php?aid=" . $d['discussion_id'] . "'>" . $d['discussionname'] . "</a><br>";
        echo "<article>";
        echo $d['content'];
        echo "</article>";
    } while ($d);
    SetSwapData("user_id", $uid);
    LoadPlugins("spacePage");
    ?>
</body>

</html>