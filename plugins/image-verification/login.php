<script>
    var image_cnt = 5;
    var form = document.getElementById('infoForm');
    var random = Math.floor((Math.random() * image_cnt) + 1);
    var imagename = 'plugins/image-verification/images/' + random + '.png';

    var newInnerHtml = "<h1>登录</h1>";
    newInnerHtml += "<form action='requirer.php?pluginname=image-verification&filename=process.php' method='post'>";
    newInnerHtml += "<input type='hidden' name='action' value='login_process'>";
    newInnerHtml += "<input type='text' name='username' placeholder='用户名'><br>";
    newInnerHtml += "<input type='password' name='password' placeholder='密码'><br>";
    newInnerHtml += "<input type='text' name='verification' placeholder='图片验证码'><br>";
    newInnerHtml += "<img src='" + imagename + "'><br>";
    newInnerHtml += "<input type='hidden' name='number' value='" + random + "'><br>";
    newInnerHtml += "<input type='submit' value='登录'>";
    newInnerHtml += "</form>";

    form.innerHTML = newInnerHtml;
</script>