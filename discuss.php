<?php
require "funcs.php";
$conn = GetConnection();
$discuss_id = $_GET['aid'];
$sql = "SELECT * FROM `discusses` WHERE `discussion_id` = " . $discuss_id . " AND `floor` = 1";
$discusses = $conn->query($sql);
$discusses = $discusses->fetch_assoc();
?>

<html lang="zh-cn">

<head>
    <title>
        <?php echo $discusses["discussionname"] . " - " . GetConfig()["websiteSetting"]["title"]; ?>
    </title>
</head>

<body>
    <?php
    echo "<h1>" . $discusses["discussionname"] . "</h1>";
    echo "<p> 作者: " . GetUserInfo("user_id", $discusses["user_id"])["username"] . "</p>";
    echo "<article>";
    echo str_replace("\n", "<br>", $discusses["content"]);
    echo "</article>";
    ?>
    <hr>
    <?php
    $sql = "SELECT * FROM `discusses` WHERE `discussion_id` = " . $discuss_id . " AND `floor` > 1";
    $discusses = $conn->query($sql);
    while ($row = $discusses->fetch_assoc()) {
        echo "<p> " . GetUserInfo("user_id", $row["user_id"])["username"] . " 的回复 </p>";
        echo "<article>";
        echo str_replace("\n", "<br>", $row["content"]);
        echo "</article><br>";
    }

    ?>
</body>

</html>