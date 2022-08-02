<?php
require_once "funcs.php";
$conn = GetConnection();
$discuss_id = $_GET['aid'];
$sql = "SELECT * FROM `discusses` WHERE `discussion_id` = " . $discuss_id . " AND `floor` = 1";
$discusses = $conn->query($sql);
$discusses = $discusses->fetch_assoc();
?>

<html lang="zh-cn">

<head>
    <title>
        <?php echo $discusses["discussionname"] . " - " . sprintf(GetWord("global.showTitle"), (GetConfig()["websiteSetting"]["title"])); ?>
    </title>
</head>

<body>
    <?php

    echo "<h1>" . $discusses["discussionname"] . "</h1>";
    echo "<p> 作者: " . GetUserInfo("user_id", $discusses["user_id"])["username"] . "</p>";
    echo "<article id='mainDiscuss'>";
    echo str_replace("\n", "<br>", $discusses["content"]);
    echo "</article>";
    ?>
    <hr>
    <form action="reply.php" method="POST" id='replyForm'>
        <input type="hidden" name="action" value="reply">
        <input type="hidden" name="aid" value="<?php echo $_GET["aid"]; ?>">
        <textarea name="content" id="content" cols="50" rows="20"></textarea>
        <input type="submit" value="<?php echo GetWord("reply"); ?>">
    </form>
    <?php
    $sql = "SELECT * FROM `discusses` WHERE `discussion_id` = " . $discuss_id . " AND `floor` > 1";
    $discusses = $conn->query($sql);
    $cnt = 1;
    while ($row = $discusses->fetch_assoc()) {
        echo "<p id='authorBox_" . $cnt . "'> " . GetUserInfo("user_id", $row["user_id"])["username"] . GetWord('sb\'sReply') . "</p>";
        echo "<article id='discussNum_" . $cnt . "'>";
        echo str_replace("\n", "<br>", $row["content"]);
        echo "</article>
        <br>";
        $cnt++;
    }
    SetSwapData("discussion_id", $discuss_id);
    SetSwapData("reply_cnt", $cnt);
    LoadPlugins("discussPage");
    ?>
</body>

</html>