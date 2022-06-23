<?php
$config = json_decode(file_get_contents('config.json'), true);

$conn = new mysqli(
    $config["database.host"],
    $config["database.user"],
    $config["database.pass"],
    $config["database.name"]
);

echo "<ul class=\"articles\">";
$windowlocationhref="window.location.href = \"index.php?act=create-dis\";" ;
if(isset($_COOKIE["uid"])){
    echo "<button class=\"mdui-btn mdui-btn-raised mdui-ripple mdui-color-theme-accent\" onclick=jumpto(\"index.php?act=create-dis\");>发布主题</button>";
}
else{
    echo "<button class=\"mdui-btn mdui-btn-raised mdui-ripple\" disabled>登录即可发布主题</button>";
    
}

// Get articles from database
//先发出来的先显示 desc
$result = $conn->query("SELECT * FROM discusses order by sendtime desc");
// Output each article
while ($row = $result->fetch_assoc()) {
    if ($row["floor"] == 0) {
            $user_info = $conn->query("SELECT * FROM users WHERE user_id='$row[user_id]'");
            $user_info = $user_info->fetch_assoc();
            $liulanliang=$row["countview"];
            echo "<li><a href=\"index.php?act=discuss&id=" . $row["dis_id"] . "\">" .
                        $row["title"] . "</a> <div align=right> <a href='space.php?uid=" . $user_info["user_id"] .
                        "'> 楼主：" . $user_info["username"] . " </a><i class=\"mdui-icon material-icons\">&#xe417;</i><font color=grey>$liulanliang</font></div></li>";
            
    }
}
echo "</ul>";
?>
