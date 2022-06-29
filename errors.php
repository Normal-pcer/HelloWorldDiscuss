<?php
function error_not_login()
{
    $errmsg =
        "出现错误: <br>ERROR_NOT_LOGIN<br>" .
        "可能的原因:<br>" .
        "<ul>" .
        "<li>您尚未登录</li>" .
        "<li>您的登录已过期</li>" .
        "<li>您上次登录的信息有错误</li>" .
        "</ul>" .
        "解决方案:<br>" .
        "<ul>" .
        "<li>请<a href=\"index.php?act=login\">重新登录</a>后再次访问</li>" .
        "<li>如果问题仍然存在，请联系管理员并出示以下信息</li>" .
        "<li>Cookie中的用户编号: " . (array_key_exists("uid", $_COOKIE) ? $_COOKIE["uid"] : "null") .
        "<li>Cookie中的登录密钥: " . (array_key_exists("pass_sha256", $_COOKIE) ? $_COOKIE["pass_sha256"] : "null") .
        "</ul>";

    die($errmsg);
}

function error_permission_denied($act_name)
{
    $errmsg =
        "出现错误: <br>ERROR_PERMISSION_DENIED<br>" .
        "可能的原因:<br>" .
        "<ul>" .
        "<li>您尚未登录</li>" .
        "<li>请求的操作需要权限</li>" .
        "</ul>" .
        "解决方案:<br>" .
        "<ul>" .
        "<li>请<a href=\"index.php?act=login\">登录</a>拥有合适权限的账户后再次访问</li>" .
        "<li>如果问题仍然存在，请联系管理员并出示以下信息</li>" .
        "<li>Cookie中的用户编号: " . (array_key_exists("uid", $_COOKIE) ? $_COOKIE["uid"] : "null") .
        "<li>请求的操作：" . $act_name .
        "</ul>";

    die($errmsg);
}

function error_login_fail()
{
    $errmsg =
        "出现错误: <br>ERROR_LOGIN_FAIL<br>" .
        "可能的原因:<br>" .
        "<ul>" .
        "<li>账号或密码错误</li>" .
        "</ul>" .
        "解决方案:<br>" .
        "<ul>" .
        "<li>检查您的账号和密码是否正确</li>" .
        "<li>如果问题仍然存在，请联系管理员</li>" .
        "</ul>";

    die($errmsg);
}

function error_exist_users()
{
    $errmsg =
        "出现错误: <br>ERROR_EXIST_USERS<br>" .
        "可能的原因:<br>" .
        "<ul>" .
        "<li>数据库中存在除初始管理员外的用户</li>" .
        "</ul>" .
        "解决方案:<br>" .
        "<ul>" .
        "<li>很抱歉，暂时并没有可行的解决方案，因为SHA256算法是不可逆的</li>" .
        "<li>您可以手动重设数据表`users`中的`password`字段</li>" .
        "</ul>";

    die($errmsg);
}
