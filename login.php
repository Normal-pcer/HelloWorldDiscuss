<?php
// Read config.json
$config = json_decode(file_get_contents('config.json'), true);

?>
<html>

<head>
	<title>登录 <?php echo $config["title"]; ?></title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/login.css" />
</head>

<body>
	<form name="login" action="index.php?act=login_next" method="post">
		<?php echo "<h1>" . $config["title"] . "登录</h1>"; ?>
		<input class=input_1 id=username name="username" placeholder=用户名><br />
		<input class=input_1 id=password type=text name="password" placeholder=密码><br />

		<br />
		<input class=input_3 type="submit" value="登录" />
		<input class=input_3 type="button" onclick=document.form1.reset() value="注册" />
	</form>

	<script>
		function login() {
			if (form1.username.value == '') {
				alert('用户名不能为空！');
				return false;
			}
			if (form1.password.value == '') {
				alert('密码不能为空！');
				return false;
			}
			form1.action = "#";
			form1.submit();
		}
		window.onload = function() {
			var i3 = document.getElementsByClassName('input_3');
			for (var i = 0; i < i3.length; i++) {
				i3[i].onmouseover = function() {
					this.style.backgroundColor = "#23271F";
					this.style.color = "#fff";
				}
				i3[i].onmouseout = function() {
					this.style.backgroundColor = "#fff";
					this.style.color = "#23271F";
				}
			}
			var pass = document.getElementById("password");
			pass.onfocus = function() {
				pass.type = "password";
			}
		}
	</script>
</body>

</html>