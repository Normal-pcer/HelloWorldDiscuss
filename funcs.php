<?
require_once "funcs/user.php";
require_once "funcs/errors.php";
/*
命名方式：
function ZhangSan(li_si)
{
wangErMazi = 114514;
}
*/
function GetConfig()
{
    // 获取配置文件的内容
    $fileName = "config.json"; // 配置文件名
    $fileData = file_get_contents($fileName); // 读取配置文件
    $config = json_decode(
        $fileData,
        true
    ); // 解析配置文件
    return $config;
}
function WriteConfig($config)
{
    $fileName = "config.json";
    $file = fopen($fileName, 'w');
    fwrite($file, json_encode($config));
    fclose($file);
}
function GetSomething($table, $where)
{
    $conn = GetConnection();
    $sql = "SELECT * FROM `" . $table . "` WHERE " . $where;
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row;
}
function GetConnection()
{
    $config = GetConfig();
    $config = $config["safety"]["database"]; // 获取配置文件的内容
    $conn = new mysqli(
        $config["host"],
        $config["user"],
        $config["password"],
        $config["name"]
    ); // 创建数据库连接
    return $conn;
}

function GetLanguageName()
{
    // 检测用户的Cookies是否存在语言选项
    if (isset($_COOKIE["language"])) {
        $language = $_COOKIE["language"]; // 获取用户的语言选项
    } else {
        // 如果用户的Cookies不存在语言选项，则读取config.json文件中的默认语言选项
        $config = GetConfig(); // 获取配置文件的内容
        $language = $config["defaultSettings"]["language"]; // 获取默认语言选项
    }
    // 读取plugins/index.json
    $fileName = "plugins/index.json"; // 插件索引文件名
    $fileData = file_get_contents($fileName); // 读取插件索引文件
    $plugins = json_decode($fileData, true); // 解析插件索引文件
    if (!array_key_exists($language, $plugins)) {
        $language = "zh-cn";  // 如果选择的语言插件不存在则默认使用中文
    }
    return $language;
}

function GetWord($word)
{
    $language = GetLanguageName();
    $fileName = "plugins/" . $language . "/index.json"; // 语言插件文件名
    $fileData = file_get_contents($fileName); // 读取语言插件文件
    $languageData = json_decode($fileData, true); // 解析语言插件文件
    if (array_key_exists($word, $languageData)) {
        $result = $languageData[$word]; // 获取语言插件中的词语
    } else {
        $result = false; // 如果语言插件中不存在该词语，则返回false
    }
    return $result;
}

function LoadPlugins($action_name)
{
    $indexFileName = "plugins/index.json"; // 插件索引文件名
    $indexFileData = file_get_contents($indexFileName); // 读取插件索引文件
    $plugins = json_decode($indexFileData, true); // 解析插件索引文件

    // 遍历所有的 plugins/$name/index.json
    foreach ($plugins as $name => $plugin) {
        $fileName = "plugins/" . $name . "/index.json"; // 插件索引文件名
        $fileData = file_get_contents($fileName); // 读取插件索引文件
        $pluginData = json_decode($fileData, true); // 解析插件索引文件
        if (array_key_exists($action_name, $pluginData)) {
            $actionFile = "plugins/" . $name . "/" . $pluginData[$action_name]; // 文件名
            require $actionFile; // 加载文件
        }
    }
    return;
}

function ConsoleLog($message)
{
    echo "<script type=\"text/javascript\">";
    echo "console.log(\"" . str_replace("\"", "\\\"", $message) . "\")";
    echo "</script>";
}
