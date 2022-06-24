<?php ob_start();
require "funcs.php";
?>
<html>

<head>
    <?php
    // Read config.json
    $config = json_decode(file_get_contents('config.json'), true);
    //act,cookie
    $act = $_GET;
    $cok = $_COOKIE;
    ?>
    <title><?php echo $config['title']; ?></title>
    <meta charset="utf-8">
    <?php require "cssandjs.php" ?>
</head>

<body class=mdui-theme-primary-<?php echo $config["themecolor"]?>>

    <?php require "navbar.php" //获取navbar 直接引用;
    ?>
    <?php


    // Connect to MySQL
    $conn = new mysqli(
        $config["database.host"],
        $config["database.user"],
        $config["database.pass"],
        $config["database.name"]
    );
    ?>

    <div class="title-bar">
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <h1><?php echo $config["title"]; ?></h1>
    </div>



    <div class="main">
        <?php
        // Get action

        ob_start();
        if (array_key_exists("act", $act)) {
            $act = $act["act"];
        } else {
            $act = "home";
        }
        // Load action
        if ($act == "home") {
            require "home.php";
        } else if ($act == "login") {
            // Login page

            echo "<form action=\"index.php?act=login_next\" method=\"post\">";
            echo "<input type=\"text\" name=\"username\" placeholder=\"用户名\"><br>";
            echo "<input type=\"password\" name=\"password\" placeholder=\"密码\"><br>";
            echo "<input type=\"submit\" value=\"登录\">";
            echo "</form>";
        } else if ($act == "login_next") {
            if (check_user_login_token($_POST["username"], $_POST["password"])) {
                setcookie("uid", get_user_information_from_username($_POST["username"])["user_id"], time() + 3600 * 24 * 7);
                setcookie("pass_sha256", encode_pass($_POST["password"]), time() + 3600 * 24 * 7);
                header("Location: index.php?act=home");
            } else {
                die("ERR_LOGIN_FAIL");
            }
        } else if ($act == "logout") {
            // Logout
            setcookie("uid", "", time() - 3600);  // 设置为过时
            header("location: index.php?act=home");
        } else if ($act == "signup") {
            // Signup page
            echo "<form action=\"index.php?act=signup_next\" method=\"post\">";
            echo "<input type=\"text\" name=\"username\" placeholder=\"用户名\"><br>";
            echo "<input type=\"email\" name=\"email\" placeholder=\"邮箱\"><br>";
            echo "<input type=\"password\" name=\"password\" placeholder=\"密码\"><br>";
            echo "<input type='password' name='pass_again' placeholder='确认密码'><br>";
            echo "<input type=\"submit\" value=\"注册\">";
            echo "</form>";
        } else if ($act == "signup_next") {
            // Signup next
            $username = $_POST["username"];
            $password = $_POST["password"];
            $email = $_POST["email"];
            $pass_again = $_POST["pass_again"];

            $pass_check = '/(?!^\\d+$)(?!^[a-zA-Z]+$)(?!^[_#@]+$).{6,}/';

            // Check if password is the same
            if ($password != $pass_again) {
                echo "两次密码不一致";
            } else if (
                preg_match($pass_check, $password) == 0
            ) {
                echo "密码强度不足，需包含：";
                echo '<ul><li>6位及以上字符</li><li>字母、数字和特殊符号</li></ul>';
                echo '(3秒后回到注册页面）<br>';
                echo "<a href=\"index.php?act=signup\">如果您的浏览器没有自动跳转，请点击此处</a>";
                echo "<meta http-equiv=\"Refresh\" content=\"3;url=index.php?act=signup\" />";
            } else {
                // Check if username is taken
                $result = $conn->query("SELECT * FROM users WHERE username='$username'");
                if ($result->num_rows != 0) {
                    echo "用户名已被使用";
                } else {
                    // Get password SHA256
                    $password_sha256 = encode_pass($password);
                    // Get user id
                    $user_id = $conn->query("SELECT MAX(user_id) FROM users")->fetch_assoc()["MAX(user_id)"] + 1;  // Get next user id
                    // Insert user into database
                    $timestampnow = time();
                    $sql = "INSERT INTO `users` (`user_id`, `username`, `password`, `email`) VALUES ('$user_id', '$username', '$password_sha256', '$email')";
                    $conn->query($sql);
                    // Set cookie
                    ob_start();
                    setcookie("uid", $user_id, time() + 2592000); // 30 days
                    header("location: index.php?act=home");
                }
            }
        } else if ($act == "create-dis") {
            // Create discuss page
            echo "<form action=\"index.php?act=create-dis-next\" method=\"post\">";
            echo "<br>";
            echo "标题:";
            echo "<input type=\"text\" name=\"title\" placeholder=\"标题\"><br>";
            echo "<textarea id=\"editor_id\" name=\"content\" placeholder=\"内容\" rows=20 cols=100></textarea><br>";
            echo "<input type=\"submit\" value=\"发布\">";
            echo "</form>";
        } else if ($act == "create-dis-next") {
            // Create discuss next
            $title = $_POST["title"];
            $content = $_POST["content"];
            // Get user id
            $user_id = $_COOKIE["uid"];
            // Get discuss id
            $dis_id = $conn->query("SELECT MAX(dis_id) FROM discusses")->fetch_assoc()["MAX(dis_id)"] + 1;  // Get next discuss id
            // Insert discuss into database
            //get unique_id by $timestamp
            $timestampn = time();
            $ipaddress=getusrip();
            $conn->query("INSERT INTO `discusses` (`dis_id`, `part_id`, `title`, `text`, `user_id`, `floor`, `sendtime`, `countview`, `sendip`) VALUES ('$dis_id', '0', '$title', '$content', '$user_id', '0', '$timestampn', '1', '$ipaddress')");
            // Redirect to home
            header("location: index.php?act=home");
        } else if ($act == "discuss") {
            require "discuss.php";
        } else if ($act == "reply-next") {
            // Reply next
            $dis_id = $_POST["dis_id"];
            $content = $_POST["content"];
            // Get user id
            if (!isset($_COOKIE["uid"])) {
                echo "<script>alert('请先登录！')</script>";
                header("location: index.php?act=login");
                die("请先登录");
            }

            $user_id = $_COOKIE["uid"];
            $floor = $conn->query("SELECT MAX(floor) FROM discusses WHERE dis_id='$dis_id'")->fetch_assoc()["MAX(floor)"] + 1;  // Get next floor
            // Insert discuss into database
            $timestampnow2 = time();
            $ipaddress1=getusrip();
            $conn->query("INSERT INTO `discusses` (`dis_id`, `part_id`, `title`, `text`, `user_id`, `floor`, `sendtime`, `countview`, `sendip`) VALUES ('$dis_id', '0', 'none', '$content', '$user_id', '$floor', '$timestampnow2', '1', '$ipaddress1')");

            echo $_COOKIE["uid"];
            // Redirect to the discuss
            header("location: index.php?act=discuss&id=$dis_id");
        } else if ($act == "space") {
            // Show space page
            require "space.php";
        } else if ($act == "del") {
            del_discuss($_GET["id"], $_GET["floor"]);
        } else if ($act == "change-username") {
            $conn->query("UPDATE users SET username='" . $_POST["name"] .
                "' WHERE user_id='" . get_user_information_from_cookie()["user_id"] . "'");
            header("location: index.php?act=space&uid=" . get_user_information_from_cookie()["user_id"]);
        } else if ($act == "change-password") {
            $conn->query("UPDATE users SET password='" . encode_pass($_POST["password"]) .
                "' WHERE user_id='" . get_user_information_from_cookie()["user_id"] . "'");
            echo "<script>alert('密码修改成功，请重新登录')</script>";
            header("location: index.php?act=logout");
        } else if ($act == "admin") {
            header("location: admin.php");
        } else {
            echo "<script>alert('操作未定义')</script>";
            header("location: index.php");
        }
        ?>
    </div>
</body>

</html>
