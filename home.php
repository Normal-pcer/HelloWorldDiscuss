<?php
$config = json_decode(file_get_contents('config.json'), true);

$conn = new mysqli(
    $config["database.host"],
    $config["database.user"],
    $config["database.pass"],
    $config["database.name"]
);

echo "<ul class=\"articles\">";
echo "<a href=\"index.php?act=create-dis\">发布讨论</a>";
// Get articles from database
$result = $conn->query("SELECT * FROM discusses");
// Output each article
while ($row = $result->fetch_assoc()) {
    if ($row["floor"] == 0) {
        echo "<li><a href=\"index.php?act=discuss&id=" . $row["dis_id"] . "\">" .
            $row["title"] . "</a></li>";
    }
}
echo "</ul>";
