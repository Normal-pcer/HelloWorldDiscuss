<?php
function ErrorLoginInfoWrong()
{
    $text = "<h1>出现错误</h1>" .
        "<p> 错误代码：ERR_LOGIN_INFO_WRONG </p>" .
        "<p>可能的原因：</p>" .
        "<ul> <li> 账号名或密码错误 </li> </ul>";
    echo $text;
}

function ErrorNoPremission()
{
    $text = "<h1>出现错误</h1>" .
        "<p> 错误代码：ERR_NO_PERMISSION </p>" .
        "<p> 可能的原因：</p>" .
        "<ul> <li> 请求的操作需要权限 </li> </ul>";
    echo $text;
}
