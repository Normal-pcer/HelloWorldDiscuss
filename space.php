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
?>

<html lang='zh-hans-cn'>

<head>
    <meta charset='UTF-8' />
    <title><?php echo GetUserInfo('user_id', $uid)['username']; ?> 的个人主页 - <?php echo GetConfig()['websiteSetting']['title']; ?></title>
</head>

<body>

</body>

</html>