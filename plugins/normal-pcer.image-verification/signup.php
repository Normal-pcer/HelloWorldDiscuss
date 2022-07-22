<script>
    // 根据login.php 如法炮制
    var image_cnt = 5;
    var form = document.getElementById('infoForm');
    var random = Math.floor((Math.random() * image_cnt) + 1);
    var imagename = 'plugins/normal-pcer.image-verification/images/' + random + '.png';

    var newInnerHtml = "<h1>注册</h1>";
    newInnerHtml += "<form action='requirer.php?pluginname=normal-pcer.image-verification&filename=process.php' method='post'>";
    newInnerHtml += "<input type='hidden' name='action' value='signup_process'>";
    newInnerHtml += "<input type='text' name='username' placeholder='用户名'><br>";
    newInnerHtml += "<input type='password' name='password' placeholder='密码'><br>";
    newInnerHtml += "<input type='text' name='email' placeholder='电子邮箱'><br>";
    newInnerHtml += "<input type='text' name='verification' placeholder='图片验证码'><br>";
    newInnerHtml += "<img src='" + imagename + "'><br>";
    newInnerHtml += "<input type='hidden' name='number' value='" + random + "'><br>";
    newInnerHtml += "<input type='submit' value='注册'>";
    newInnerHtml += "</form>";
    form.innerHTML = newInnerHtml;
</script>