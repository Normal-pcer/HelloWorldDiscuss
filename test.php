<?php
$user_perm =
    "{\"reset-database\": false, " .
    "\"delete-discuss\": false, " .
    "\"change-user\": false" .
    "}";
$sql = "INSERT INTO `usergroups` (`usergroupname`, `permission`) VALUES (\"Users\", \"$user_perm\")";
echo $sql;
