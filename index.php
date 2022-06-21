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
    <!-- 载入依赖的样式 -->
    <link rel="stylesheet" href="./style.css">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://jsd.miaowuawa.cn/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://jsd.miaowuawa.cn/npm/bootstrap@3.4.1/dist/css/bootstrap-theme.min.css" integrity="sha384-6pzBo3FDv/PJ8r2KRkGHifhEocL+1X2rVCTTkUfGk7/0pbek5mMa1upzvWbrUbOZ" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://jsd.miaowuawa.cn/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
    <!--sweetalert-->
    <script src="https://jsd.miaowuawa.cn/npm/sweetalert2@8"></script>
    <script src="sweetalert2.all.min.js"></script>
    <!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
    <script src="https://jsd.miaowuawa.cn/npm/promise-polyfill"></script>
    <script charset="utf-8" src="/editor/kindeditor-all.js"></script>
    <script charset="utf-8" src="/editor/lang/zh-CN.js"></script>
    <script>
        KindEditor.ready(function(K) {
                window.editor = K.create('#editor_id');
        });
    </script>

    <style>
        html {
            height: 100%;
        }

        body {
            height: 100%;
        }

        div.main {
            height: 100%;
        }

        div.title-bar {
            text-align: center;
            background-color: #6ae;
            color: #fff;
            padding: 0.5em;
        }

        ul.sidebar {
            position: absolute;
            left: 0.5em;
            top: 100px;
            bottom: 0;
            height: 100%;
            width: 200px;
            background-color: #eee;
            padding: 0;
            display: block;
        }

        ul.sidebar>li {
            list-style: none;
            padding: 1em;
            border-bottom: 1px solid #ccc;
        }

        ul.sidebar>li>a {
            text-decoration: none;
            color: #6ae;
        }

        div.main {
            padding-left: 200px;
            top: 100px;
        }

        ul.articles {
            list-style: none;
            padding: 1em;
            left: 100px;
        }

        ul.articles>li {
            padding: 1em;
            border-bottom: 1px solid #ccc;
        }

        ul.articles>li>a {
            color: #000;
            text-decoration: none;
        }

        ul.top-bar {
            list-style: none;
            padding: 1em;
            left: 100px;
        }

        ul.top-bar>li>a {
            color: #000;
            text-decoration: none;
        }

        ul.top-bar>li {
            display: inline-block;
            padding: 0.5em;
        }
    </style>

</head>

<body>
    <ul class="nav nav-tabs">
    <div class="navbar-header">
    <a class="navbar-brand" href="index.php"><?php echo $config["title"]; ?></a>
    </div>

    <li><a href="index.php?act=home" data-toggle=tab">首页</a></li>

    <li><a href="index.php?act=parts" data-toggle=tab">分区</a></li>

   
    <?php
    
    if(isset($_COOKIE["uid"])){
        $spacelink=$_COOKIE["uid"];
        echo "<li><a href=space.php?uid=".$spacelink." data-toggle=tab\">个人空间</a></li>";
        echo "<li><a href=index.php?act=logout data-toggle=tab\">退出登录</a></li>";
    }
    else
    {
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
            echo "<ul class='top-bar'>";
            if (isset($_COOKIE["uid"])) {
                $login = $_COOKIE["uid"];
            } else {
                $login = "not logged in";
            }
            if ($login == "not logged in") {
                echo "<li><a href='index.php?act=login'>登录</a></li>";
                echo "<li><a href='index.php?act=signup'>注册</a></li>";
            } else {
                // Get user info from database
                $user_info = $conn->query("SELECT * FROM users WHERE user_id='$login'");
                $user_info = $user_info->fetch_assoc();
                echo "<li><a href='index.php?act=user'>" . $user_info["username"] . "</a></li>";
                echo "<li><a href='index.php?act=create-dis'>发帖</a></li>";
                echo "<li><a href='index.php?act=logout'>退出登录</a></li>";
            }
            echo '</ul>';
            echo "<ul class=\"articles\">";
            // Get articles from database
            $result = $conn->query("SELECT * FROM discusses");
            // Output each article
            while ($row = $result->fetch_assoc()) {
                if ($row["floor"] == 0) {
                    echo "<li><a href=\"index.php?act=discuss&id=" . $row["dis_id"] . "\">" .
                        $row["title"] . "</a></li>";
                }
            }
            echo "</ul>";
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
                $password_sha256 = hash("sha256", $password);
                if ($password_sha256 == $user_info["password"]) {
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

            // Check if password is the same
            if ($password != $pass_again) {
                echo "两次密码不一致";
            } else {
                // Check if username is taken
                $result = $conn->query("SELECT * FROM users WHERE username='$username'");
                if ($result->num_rows != 0) {
                    echo "用户名已被使用";
                } else {
                    // Get password SHA256
                    $password_sha256 = hash("sha256", $password);
                    // Get user id
                    $user_id = $conn->query("SELECT MAX(user_id) FROM users")->fetch_assoc()["MAX(user_id)"] + 1;  // Get next user id
                    // Insert user into database
                    $timestampnow=time();
                    $conn->query("INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `point`, `qianming`, `ban`, `avatar`, `bantimes`, `isadmin`) VALUES ('$user_id', '$username', '$password_sha256', '$email', '1', '这个人很懒，什么也没有写', '0', 'no', '0', '0')");
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
            $timestampn=time();
            $conn->query("INSERT INTO discusses (dis_id, user_id, title, text, floor, part_id,sendtime) VALUES ($dis_id, '$user_id', '$title', '$content', 0, 0 , $timestampn)");
            // Redirect to home
            header("location: index.php?act=home");
        } else if ($act == "discuss") {
            // Show discuss page
            $dis_id = $_GET["id"];
            // Get discuss info from database
            // Sort with "floor"
            $result = $conn->query("SELECT * FROM discusses WHERE dis_id='$dis_id' ORDER BY floor ASC");
            // Output main discuss(floor=0)
            // Output Other discusses
            while ($row = $result->fetch_assoc()) {
                if ($row["floor"] != 0) {
                    echo "<hr>";
                    // get username
                    $result_user = $conn->query("SELECT username FROM users WHERE user_id='" . $row["user_id"] . "'");
                    $user_info = $result_user->fetch_assoc();
                    echo "<p>" . $user_info["username"] . "：</p>";
                    echo $row["text"];
                } else {
                    echo "<h1>" . $row["title"] . "</h1>";
                    // get username
                    $result_user = $conn->query("SELECT * FROM users WHERE user_id='" . $row["user_id"] . "'");
                    $user_info = $result_user->fetch_assoc();
                    echo "<p>作者：" . $user_info["username"] . "</p>";
                    echo $row["text"];
                    echo "<hr>";
                    echo "<h3>回复主题</h3>";
                    echo "<form action=\"index.php?act=reply-next\" method=\"post\">";
                    echo "<input type=\"hidden\" name=\"dis_id\" value=$dis_id>";
                    echo "<textarea id=\"editor_id\" name=\"content\" placeholder='回复' rows=10 cols=50></textarea><br>";
                    echo "<input type=\"submit\" value=\"回复\">";
                    echo "</form>";
                }
            }
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
           // if (strpos($content, "<script") !== false) {
           //     die("请不要使用恶意代码，听我说谢谢你");
            //}

            // 转义特殊字符
            //$content = htmlentities($content);

            $user_id = $_COOKIE["uid"];
            $floor = $conn->query("SELECT MAX(floor) FROM discusses WHERE dis_id='$dis_id'")->fetch_assoc()["MAX(floor)"] + 1;  // Get next floor
            // Insert discuss into database
            $timestampnow2=time();
            $conn->query("INSERT INTO discusses (dis_id, user_id, title, text, floor, part_id, sendtime) VALUES ('$dis_id', '$user_id', 'none', '$content', '$floor', 0, $timestampnow2)");

            echo $_COOKIE["uid"];
            // Redirect to the discuss
            header("location: index.php?act=discuss&id=$dis_id");
        }
        ?>
    </div>
</body>
<footer>
    <div class="container-fluid" style="padding-top: 40px;">
    <div class="row clearfix">
        <div class="col-md-12 column">
            <div class="jumbotron">
                <div class="container">
                    <center>

                        <p>Copyright© 2022 All Rights Reserved. </p>
                        <p>正在开发中</p>
                    </center>

                </div>
            </div>
        </div>
    </div>
</div>
</footer>
</html>