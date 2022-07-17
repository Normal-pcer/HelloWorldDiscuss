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
    echo "<article>";
    echo str_replace("\n", "<br>", $discusses["content"]);
    echo "</article>";
    ?>
    <hr>
</body>

</html>