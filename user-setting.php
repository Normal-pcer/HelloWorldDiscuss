<?php
require "funcs.php";

if (get_user_information_from_cookie() == false) {
    die("ERR_NOT_LOGIN");
}
?>

<h1>用户设置</h1>
<h2>常规</h2>
<p>用户名: <?php echo get_user_information_from_cookie()["username"]; ?><br>更改为:
<form action="index.php?act=change-username" method="POST">
    <input type="text" name="name"></input>
    <input type="submit" name="sub" value="修改"></input>
</form>
</p>