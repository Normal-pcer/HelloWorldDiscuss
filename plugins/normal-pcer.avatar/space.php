<script>
    var username = document.getElementById("userName");
    var avatarPath = "<?php echo GetUserInfo('user_id', GetSwapData("user_id"))['avatar']; ?>";
    username.innerHTML = "<img style=\"display: inline-block\" height=\"45px\" src=\"" + avatarPath + "\" alt=\"用户头像\" />" + username.innerHTML;
</script>