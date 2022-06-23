<style>
    h1 {
        text-align: center;
    }
</style>

<?php

// Show discuss page
$dis_id = $_GET["id"];
add_discuss_countview($dis_id);
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

        $login = get_user_information_from_cookie();
        if ($login != false && $row["user_id"] == $login["user_id"]) {
            echo "<br><a href='index.php?act=del&id=" . $dis_id . "&floor=" . $row["floor"] . "'>删除</a>";
        }
    } else {
        echo "<h1>" . $row["title"] . "</h1>";
        // get username
        $login = get_user_information_from_cookie();
        $result_user = $conn->query("SELECT * FROM users WHERE user_id='" . $row["user_id"] . "'");
        $user_info = $result_user->fetch_assoc();
        $sendtimedate=date("Y年n月j日 G点i分s秒",$row["sendtime"]);
        $iploc=get_ip_location($row["sendip"]);
        echo "<p><a href=index.php?act=space&uid=" . $user_info["user_id"] . ">作者：" . $user_info["username"] . "</a></p>";
        if($config["plugin.shouiplocation.enabled"]){
            echo "<font color=grey>#1楼（楼主） 发表于".$sendtimedate."<br>IP属地: ".$iploc."</font><br>";
        }
        else{
            echo "<font folor=grey>发表于$sendtimedate</font>";
        }
        
        if ($login != false && $row["user_id"] == $login["user_id"]) {
            echo "<a href='index.php?act=del&id=" . $dis_id . "&floor=" . $row["floor"] . "'>删除</a><br>";
        }
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
