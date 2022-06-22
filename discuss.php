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
<script charset="utf-8" src="./editor/kindeditor-all.js"></script>
<script charset="utf-8" src="./editor/lang/zh-CN.js"></script>
<script>
    KindEditor.ready(function(K) {
        window.editor = K.create('#editor_id');
    });
</script>

<style>
    h1 {
        text-align: center;
    }
</style>

<?php
// Show discuss page
$dis_id = $_GET["id"];
// Get discuss info from database
// Sort with "floor"
$result = $conn->query("SELECT * FROM discusses WHERE dis_id='$dis_id' ORDER BY floor ASC");
// Output main discuss(floor=0)
// Output Other discusses
while ($row = $result->fetch_assoc()) {
    if ($row["floor"] != 0) {
        echo "
<hr>";
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
        echo "
<hr>";
        echo "<h3>回复主题</h3>";
        echo "<form action=\"index.php?act=reply-next\" method=\"post\">";
        echo "<input type=\"hidden\" name=\"dis_id\" value=$dis_id>";
        echo "<textarea id=\"editor_id\" name=\"content\" placeholder='回复' rows=10 cols=50></textarea><br>";
        echo "<input type=\"submit\" value=\"回复\">";
        echo "</form>";
    }
}
