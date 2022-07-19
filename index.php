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
            echo "<p>登录后即可发布讨论</p>";
        } else {
            echo "<a href='javascript:ShowEditor()'>发布讨论</a>";
        }
        ?>
    </div>
    <div id="discussesList">
        <?php
        $sql = "SELECT * FROM `discusses` WHERE `floor`=1";
        $discusses = $conn->query($sql);
        while ($row = $discusses->fetch_assoc()) {
            echo "<hr id='lineUp_" . $row['discussion_id'] . "'>";
            echo "<div id='discuss_" . $row['discussion_id'] . "'>";
            echo "<a href='discuss.php?aid=" . $row['discussion_id'] . "''><h1>" . $row["discussionname"] . "</h1></a>";
            echo "<p> 作者: " . GetUserInfo("user_id", $row["user_id"])["username"] . "</p>";
        }
        ?>
    </div>
    <script type="text/javascript">
        function ShowEditor() {
            var editor = document.getElementById("editNewDiscuss");
            var newHtml = "<form action='reply.php' method='POST'><input type='hidden' name='action' value='create'><input type='text' name='title' placeholder='标题'><br><textarea name='content' id='content' cols='50' rows='15'></textarea><br><input type='submit' value='发布'></form>";
            editor.innerHTML = newHtml;
        }
    </script>
</body>

</html>