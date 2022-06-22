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
