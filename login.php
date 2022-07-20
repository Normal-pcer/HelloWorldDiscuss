<?php
require_once "funcs.php";
function signup($username, $password, $email)
{
    $password = PasswordEncode($password);
    $conn = GetConnection();
    $sql = "INSERT INTO `users` (`username`, `password`, `email`) VALUES ('$username', '$password', '$email')";
    $result = $conn->query($sql);
}
function login($username, $password)
{
    $password = PasswordEncode($password);
    $conn = GetConnection();
    $sql = "SELECT * FROM `users` WHERE `username` = '$username' AND `password` = '$password'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    if ($row) {
        SetUserInCookies($row['user_id']);
        return $row;
    } else {
        return false;
    }
}
?>

<html>

<head>
    <title><?php echo $_GET['action'] == 'signup' ? '注册' : '登录'; ?></title>
</head>

<body>
    <div id="infoForm">
        <?php
        if ($_GET['action'] == 'signup') {
            echo '<h1>注册</h1>';
            echo '<form action="login.php" method="post">';
            echo '<input type="hidden" name="action" value="signup_process">';
            echo '<input type="text" name="username" placeholder="用户名">';
            echo '<input type="password" name="password" placeholder="密码">';
            echo '<input type="email" name="email" placeholder="邮箱">';
            echo '<input type="submit" value="注册">';
            LoadPlugins('signupForm');
        } else if ($_GET['action'] == 'login') {
            echo '<h1>登录</h1>';
            echo '<form action="login.php" method="post">';
            echo '<input type="hidden" name="action" value="login_process">';
            echo '<input type="text" name="username" placeholder="用户名">';
            echo '<input type="password" name="password" placeholder="密码">';
            echo '<input type="submit" value="登录">';
            LoadPlugins('loginForm');
        } else if ($_GET['action'] == 'login_process') {
            $user = login($_POST['username'], $_POST['password']);
            if ($user) {
                echo '登录成功';
            } else {
                echo '登录失败';
            }
        } else if ($_GET['action'] == 'signup_process') {
            signup($_POST['username'], $_POST['password'], $_POST['email']);
            echo '注册成功';
        }
        ?>
    </div>
</body>

</html>