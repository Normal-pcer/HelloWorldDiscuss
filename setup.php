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
$db_host = $config['db_host'];
$db_name = $config['db_name'];
$db_user = $config['db_user'];
$db_pass = $config['db_pass'];
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Create tables
// `users`
$sql = "CREATE TABLE IF NOT EXISTS `users` (
    `user_id` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `points` int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
$conn->query($sql);

// `discusses`
$sql = "CREATE TABLE IF NOT EXISTS `discusses` (
    `dis_id` int(11) NOT NULL AUTO_INCREMENT,
    `part_id` int(11) NOT NULL,
    `title` varchar(255) NOT NULL,
    `text` text NOT NULL,
    `user_id` int(11) NOT NULL,
    `countview` int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
$conn->query($sql);

// `parts`
$sql = "CREATE TABLE IF NOT EXISTS `parts` (
    `part_id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    PRIMARY KEY (`part_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
$conn->query($sql);
