<?php
$sql = "
ALTER TABLE `users` ADD `avatar` VARCHAR(255) NOT NULL DEFAULT 'plugins/normal-pcer.avatar/default.png';
";
GetConnection()->query($sql);
