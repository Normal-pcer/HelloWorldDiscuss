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

<body>
    <ul class="nav nav-tabs">
        <div class="navbar-header">
            <a class="navbar-brand" href="index.php"><?php echo $config["title"]; ?></a>
        </div>

        <li><a href="index.php?act=home" data-toggle="tab">首页</a></li>

        <li><a href="index.php?act=parts" data-toggle="tab">分区</a></li>


        <?php

        if (isset($_COOKIE["uid"])) {
            $spacelink = $_COOKIE["uid"];
            echo "<li><a href=index.php?act=space&uid=" . $spacelink . " data-toggle=tab\">个人空间</a></li>";
            echo "<li><a href=index.php?act=logout data-toggle=tab\">退出登录</a></li>";
        } else {
            echo "<li><a href=\"index.php?act=login\" data-toggle=tab\">登录</a></li>";
            echo "<li><a href=\"index.php?act=signup\" data-toggle=tab\">注册</a></li>";
        }
        ?>

    </ul>
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
        <h1><?php echo $config["title"]; ?></h1>
    </div>



    <div class="main">
        <?php
        // Get action

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
            // Login next
            $username = $_POST["username"];
            $password = $_POST["password"];
            // Is there a user with this username?
            $result = $conn->query("SELECT * FROM users WHERE username='$username'");
            if ($result->num_rows == 0) {
                echo "用户不存在";
            } else {
                // Get user info from database
                $user_info = $result->fetch_assoc();
                // Check password
                // Get password SHA256
                require "funcs.php";
                if (is_password_true($password, $user_info["password"])) {
                    // Set cookie
                    // header("location: write_into_cookie.php?id=" . $user_info["user_id"]);
                    setcookie("uid", $user_info["user_id"], time() + 2592000); // 30 days
                    header("location: index.php?act=home");
                    // Redirect to home
                } else {
                    echo "密码错误";
                }
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
            } else {
                // Check if username is taken
                $result = $conn->query("SELECT * FROM users WHERE username='$username'");
                if ($result->num_rows != 0) {
                    echo "用户名已被使用";
                } else {
                    require 'funcs.php';
                    // Get password SHA256
                    $password_sha256 = encode_pass($password);
                    // Get user id
                    $user_id = $conn->query("SELECT MAX(user_id) FROM users")->fetch_assoc()["MAX(user_id)"] + 1;  // Get next user id
                    // Insert user into database
                    $timestampnow = time();
                    $conn->query("INSERT INTO `users` (`user_id`, `username`, `password`, `email`) VALUES ('$user_id', '$username', '$password_sha256', '$email')");
                    // Set cookie
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
            $conn->query("INSERT INTO discusses (dis_id, user_id, title, text, floor, part_id) VALUES ($dis_id, '$user_id', '$title', '$content', 0, 0)");
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
            $conn->query("INSERT INTO discusses (dis_id, user_id, title, text, floor, part_id) VALUES ('$dis_id', '$user_id', 'none', '$content', '$floor', 0)");

            echo $_COOKIE["uid"];
            // Redirect to the discuss
            header("location: index.php?act=discuss&id=$dis_id");
        } else if ($act == "space") {
            // Show space page
            require "space.php";
        } else if ($act == "del") {
            require "funcs.php";

            del_discuss($_GET["id"], $_GET["floor"]);
        }
        ?>
    </div>
</body>
</html>
