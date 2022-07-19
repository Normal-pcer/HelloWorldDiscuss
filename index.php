<?php
require "funcs.php";
$conn = GetConnection();
?>

<html>

<head>
    <title>
    </title>
    <meta charset="utf-8">
</head>

<body id="pageBody">
    <div id="mainNavBar">
    </div>
    <div id="editNewDiscuss">
        <?php
        if (GetUserInCookies() == false) {
            echo "<p id='messageLogin'>" . GetWord("messageLogin") . "</p>\n";
        } else {
            echo "<a href='javascript:hwd_ShowEditor()' id='linkToEditor'>" . GetWord("linkToEditor") . "</a>\n";
        }
        ?>
    </div>
    <div id="discussesList">
        <?php
        $sql = "SELECT * FROM `discusses` WHERE `floor`=1";
        $discusses = $conn->query($sql);
        while ($row = $discusses->fetch_assoc()) {
            echo "<hr id='lineUp_" . $row['discussion_id'] . "'>\n";
            echo "<div id='discuss_" . $row['discussion_id'] . "'>";
            echo "<a href='discuss.php?aid=" . $row['discussion_id'] . "'' id='linkTo_" . $row['discussion_id'] .
                "'><h1>" . $row["discussionname"] . "</h1></a>";
            echo "<p id='author_" . $row['discussion_id'] . "'> " . GetWord('author') . ": " .
                GetUserInfo("user_id", $row["user_id"])["username"] . "</p>";
        }
        ?>
    </div>
    <?php
    LoadPlugins("indexPage");
    ?>
    <script type="text/javascript">
        function hwd_ShowEditor() {
            var editor = document.getElementById("editNewDiscuss");
            var newHtml = "<form action='reply.php' method='POST'><input type='hidden' name='action' value='create'><input type='text' name='title' placeholder='标题'><br><textarea name='content' id='content' cols='50' rows='15'></textarea><br><input type='submit' value='发布'></form>";
            editor.innerHTML = newHtml;
        }
    </script>
</body>

</html>