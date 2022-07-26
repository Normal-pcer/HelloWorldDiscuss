<script>
    <?php
    $aid = GetSwapData("discussion_id");
    ?>
    var ps = document.getElementsByTagName('p');
    var main = ps[0];
    main.innerHTML = "<img src='<?php echo GetUserInfo("user_id", GetConnection()->query("SELECT * FROM `discusses` WHERE `discussion_id` = " . $aid . " AND `floor` = " . (0 + 1))->fetch_assoc()['user_id'])['avatar']; ?>' alt='用户头像' height='30px'><span style='font-size: 1.25em'><?php echo GetUserInfo("user_id", GetConnection()->query("SELECT * FROM `discusses` WHERE `discussion_id` = " . $aid . " AND `floor` = " . (0 + 1))->fetch_assoc()['user_id'])['username']; ?></span>";
    <?php
    for ($i = 1; $i < GetSwapData("reply_cnt"); $i++) {
        $author = GetUserInfo("user_id", GetConnection()->query("SELECT * FROM `discusses` WHERE `discussion_id` = " . $aid . " AND `floor` = " . ($i + 1))->fetch_assoc()['user_id']);
        $avatarPath = $author["avatar"];
        echo "var authorBox_" . $i . " = document.getElementById('authorBox_" . $i . "');";
        echo "authorBox_" . $i . ".innerHTML = '<img src=\"" . $avatarPath . "\" alt=\"用户头像\" height=\"30px\"><span style=\"font-size: 1.25em\">" . GetUserInfo("user_id", $author["user_id"])["username"] . " 的回复</span>';";
    }
    ?>
</script>