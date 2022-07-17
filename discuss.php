<?php
require "funcs.php";
$conn = GetConnection();
$discuss_id = $_GET['aid'];
$sql = "SELECT * FROM `discusses` WHERE `discuss_id` = " . $discuss_id;
$discusses = $conn->query($sql);
$discusses = $discusses->fetch_assoc();
echo array_count_values($discusses);
?>

<html lang="zh-cn">

<head>
    <title>
        <?php echo $discusses[0]["title"] . GetConfig()["websiteSetting"]["title"]; ?>
    </title>
</head>

</html>