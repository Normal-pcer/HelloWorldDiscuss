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
    // echo "<script>alert('输入的uid为空')</script>";
    // echo "<meta http-equiv=\"Refresh\" content=\"0;url=index.php\">";
    // die("1");
    // //检测输入的参数是否为空或没带参数
    // 已过时，现在会跳转到个人主页
    // -----
    require 'funcs.php';
    $cook_usr = get_user_information_from_cookie();

    if ($cook_usr) {
        header("Location: space.php?uid=" . $cook_usr["user_id"]);
    } else {
        echo "<script>alert('您还没有登录，请先登录')</script>";
        header("Location: index.php");
        die("ERR_NOT_LOGIN");
    }
}
if (is_numeric($get["uid"]) == false) {
    echo "<script>alert('输入的参数必须是数字')</script>";
    die("ERR_UID_NOT_NUM");
    //检测输入的参数是否是纯数字，防止sql注入和乱七八糟的参数
}
$result1 = $conn->query("SELECT * FROM users WHERE user_id='" . $get["uid"] . "'");

if ($result1->num_rows == 0) {
    echo "<script>alert('没有这个用户')</script>";
    die("ERR_USER_NOT_EXIST");
} else {
    while ($row = mysqli_fetch_assoc($result1)) {
        $username = $row["username"];
        $user_id = $row["user_id"];
        $user_avatar = $row["avatar"];
    }
}
?>

<head>
    <?php
    echo "<title>" . $username . "的个人空间</title>";
    require "cssandjs.php";
    ?>


</head>

<body>
    <div class="main">
        <img src="<?php echo $user_avatar; ?>" alt="头像加载失败" height="60px" style="display:inline-block;" />
        <?php echo "<h1 style=\"display:inline-block;\">$username</h1>" ?>
    </div>
</body>