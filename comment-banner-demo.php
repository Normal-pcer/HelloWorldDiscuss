<?php
function comment_banner_process($comment)
{
    $result = str_replace("傻逼", "朽木不可雕也", $comment);
    return $result;
}
