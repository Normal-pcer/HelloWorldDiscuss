<?php
$get = $_GET;
$username = "[小某]";
$config = json_decode(file_get_contents('config.json'), true);
$conn = new mysqli(
    $config["database.host"],
    $config["database.user"],
    $config["database.pass"],
    $config["database.name"]
);
$cok = $_COOKIE;
if (is_null($get["uid"])) {
    echo "<script>alert('输入的uid为空')</script>";
    echo "<meta http-equiv=\"Refresh\" content=\"0;url=index.php\">";
    die("1");
    //检测输入的参数是否为空或没带参数
}
if (is_numeric($get["uid"]) == false) {
    echo "<script>alert('输入的参数必须是数字')</script>";
    echo "<meta http-equiv=\"Refresh\" content=\"0;url=index.php\">";
    die("1");
    //检测输入的参数是否是纯数字，防止sql注入和乱七八糟的参数
}
$result1 = $conn->query("SELECT * FROM users WHERE user_id='" . $get["uid"] . "'");

if ($result1->num_rows == 0) {
    echo "<script>alert('没有这个用户')</script>";
    echo "<meta http-equiv=\"Refresh\" content=\"0;url=index.php\">";
} else {
    while ($row = mysqli_fetch_assoc($result1)) {
        $username = $row["username"];
    }
}
?>

<head>
    <?php echo "<title>" . $username . "的个人空间</title>" ?>
    <!-- 载入依赖的样式 -->
    <link rel="stylesheet" href="./style.css">
    <!-- Latest compiled and minified CSS -->
    <!--main style -->
    <style>
        div.main {
            padding-left: 200px;
            top: 100px;
            height: 100%;
        }
    </style>
    <!-- <link rel="stylesheet" href="https://jsd.miaowuawa.cn/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous"> -->

    <!-- Optional theme -->
    <!-- <link rel="stylesheet" href="https://jsd.miaowuawa.cn/npm/bootstrap@3.4.1/dist/css/bootstrap-theme.min.css" integrity="sha384-6pzBo3FDv/PJ8r2KRkGHifhEocL+1X2rVCTTkUfGk7/0pbek5mMa1upzvWbrUbOZ" crossorigin="anonymous"> -->

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://jsd.miaowuawa.cn/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
    <!--sweetalert-->
    <script src="https://jsd.miaowuawa.cn/npm/sweetalert2@8"></script>
    <script src="sweetalert2.all.min.js"></script>
    <!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
    <script src="https://jsd.miaowuawa.cn/npm/promise-polyfill"></script>


</head>

<body>
    <div class="main">
        <?php echo "<h1>$username</h1>" ?>
    </div>
</body>