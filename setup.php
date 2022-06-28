<?php
// Try to read setup.lock
// If finded, exit the script
if (file_exists('setup.lock')) {
    echo '<h1>Setup is already done</h1>';
    echo '<p>If you want to reinstall, please delete the file <strong>setup.lock</strong> first.</p>';
    exit;
}

// Read config.json
$config = json_decode(file_get_contents('config.json'), true);

// Connect to database
$db_host = $config['database.host'];
$db_name = $config['database.name'];
$db_user = $config['database.user'];
$db_pass = $config['database.pass'];
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Delete old tables
$conn->query("DROP TABLE IF EXISTS `users`");
$conn->query("DROP TABLE IF EXISTS `discusses`");
$conn->query("DROP TABLE IF EXISTS `usergroups`");
$conn->query("DROP TABLE IF EXISTS `parts`");

// Create tables
// `users`
$sql = "CREATE TABLE IF NOT EXISTS `users` (
    `user_id` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `usergroup` int(11) NOT NULL DEFAULT 1,
    `point` int(11) NOT NULL DEFAULT '0',
    `ban` int(11) NOT NULL DEFAULT '0',
    `bantimes` int(11) NOT NULL DEFAULT 0,
    `qianming` varchar(255) NOT NULL DEFAULT '这个人很懒，什么都没留下',
    `avatar` varchar(255) NOT NULL DEFAULT './images/default-ava.jpg',
    PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
$conn->query($sql);

// `discusses`
$sql = "CREATE TABLE IF NOT EXISTS `discusses` (
    `dis_id` int(11) NOT NULL,
    `part_id` int(11) NOT NULL,
    `title` varchar(255) NOT NULL,
    `text` text NOT NULL,
    `user_id` int(11) NOT NULL,
    `countview` int(11) NOT NULL DEFAULT '0',
    `floor` int(11) NOT NULL DEFAULT '0', 
    `sendtime` int(11) NOT NULL DEFAULT 0, 
    `sendip` varchar(255) NOT NULL DEFAULT '127.0.0.1',
    PRIMARY KEY (`dis_id`, `floor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
$conn->query($sql);

// `parts`
$sql = "CREATE TABLE IF NOT EXISTS `parts` (
    `part_id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    PRIMARY KEY (`part_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
$conn->query($sql);

// `usergroups`
$sql = "CREATE TABLE IF NOT EXISTS `usergroups` (
    `group_id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `delete_everyone_dis` int(11) NOT NULL DEFAULT '1',
    PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
$conn->query($sql);

// Add admin user
require 'funcs.php';

// Get a random number as salt
$salt = rand(100000, 999999);
$salt = md5($salt);

// Get a random number as admin password
$admin_pass = rand(100000, 999999);
$admin_pass = md5($admin_pass);

// write into config.json
$config['server.admin.username'] = 'admin';
$config['server.admin.password'] = $admin_pass;
$config['server.salt.enabled'] = true;
$config['server.salt.value'] = $salt;
$conffile = fopen('config.json', 'w');
fwrite($conffile, json_encode($config));
fclose($conffile);

$admin_pass = encode_pass($admin_pass);

$sql = "INSERT INTO `users` (`username`, `password`, `email`, `point`, `usergroup`) VALUES ('admin', '$admin_pass', 'admin@example.com', '0', '1');";
$conn->query($sql);

// Add admin usergroup
$sql = "INSERT INTO `usergroups` (`title`, `delete_everyone_dis`) VALUES ('admin', 1);";
$conn->query($sql);

// Add normal-user group
$sql = "INSERT INTO `usergroups` (`title`) VALUES ('user');";
$conn->query($sql);

// Add default part
$sql = "INSERT INTO `parts` (`title`) VALUES ('Default');";
$conn->query($sql);

// Add a discuss to default part
$sql = "INSERT INTO `discusses` (`part_id`, `title`, `text`, `user_id`, `countview`, `floor`) VALUES (1, 'Welcome to Discuss', '<p>欢迎使用Hello World Discuss，管理员账号admin，密码 " . $config["server.admin.password"] . " </p>', 1, 0, 0);";
$conn->query($sql);

// Create file setup.lock
$lok = fopen('setup.lock', 'w');
fwrite($lok, "Setup is done.");
fclose($lok);