# Hello World! Discuss
by normal-pcer and quqi2
## 简介
这是一款开源的轻量级论坛框架，目前拥有最基础的一些讨论功能，并会在未来持续更新。
你可以利用它轻松地创建各种主题的论坛，细节见下文。
## 安装
### 通过Git
你可以直接通过本Git仓库进行安装，参考以下命令:
```bash
git clone https://github.com/normal-pcer/HelloWorldDiscuss.git
cd HelloWorldDiscuss
git checkout main
```
我们会保证main分支运行着最稳定的版本，如果想要使用测试功能，可以切换到develop开发分支（不推荐）。参考以下命令：
```bash
git checkout develop
```
### 通过Releases
我们也会在Release中发布稳定版本。您可以从本仓库的Releases中获取url，然后下载到您的服务器并解压。
## 配置
完成下载后，在代码编辑器中打开config.json，默认为如下信息：
```json
{
    "safety": {
        "database": {
            "host": "localhost",
            "name": "discuss",
            "user": "discuss",
            "password": "test123"
        },
        "password-salt": {
            "enabled": false,
            "value": ""
        }
    },
    "defaultSetting": {
        "language": "zh-cn"
    },
    "websiteSetting": {
        "title": "mkbk"
    }
}
```
以下为各项的含义：
```json
{
    "safety": {
        "database": {
            "host": "数据库主机",
            "name": "数据库名",
            "user": "用户名",
            "password": "数据库密码"
        },
        "password-salt": {
            "enabled": "是否启用密码加盐",
            "value": "加盐的值"
        }
    },
    "defaultSetting": {
        "language": "网站默认语言"
    },
    "websiteSetting": {
        "title": "网站标题"
    }
}
```
## 插件
插件是一种改变外观或功能的方式。本节介绍的是插件的使用方法，至于插件的实现方式，请参考[后文](#开发者手册)。
### 安装插件
我们提供了hpm.php以进行插件的安装。参考以下安装步骤

方法一（在线安装）
1. 在浏览器中访问https://example.com/hpm.php
2. 在文本框中输入开发者提供的插件网址，格式应为https://example.com/foo/bar.zip
3. 等待程序在后台下载，此过程约几秒到几十秒不等（依网络连接情况和插件大小而定）

方法二 （本地安装）
1. 连接到服务器，参考：`ssh root@host`
2. 在适当的文件夹中从开发者提供的网址下载zip包，参考：`wget https://example.com/foo/bar.zip`
3. 在浏览器中访问https://example.com/hpm.php，输入本期路径，参考：`/path/bar.zip`
4. 等待程序在后台处理，约在十秒内完成

# 开发者手册
## 目录结构
参考：
```plaintext
/
- plugins
    - foo
        - index.json
        - info.json
        - ...
    - bar
        - index.json
        - info.json
        - ...
    - index.json
- ...
```
其中的```plugins/index.json```为插件列表（包含基本信息），```plugins/$name/index.json```记录着每个接管的动作。

## 动作接管
我们先来看一个```plugins/$pluginname$/index.json```的示例
```json
{
    "indexPage": "mainPage.php",
    "discussPage": "disPage.php"
}
```
其中的```"indexPage": "mainPage.php"```表示mainPage.php接管```indexPage```的动作，即当用户访问index.php时，会调用一个函数：
```php
loadPlugins("indexPage");
```
该函数的内部实现为：
```php
// funcs.php
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
            require_once $actionFile; // 加载文件
        }
    }
    return;
}
```
这会使得```mainPage.php```被调用。

| 动作名 | 动作描述 | 出现的位置 | 所有交换数据 | 在主程序中更新的交换数据 |
| ------ | ------ | ------ | ------ | ------ |
| indexPage | 加载适用于主页的插件 | index.php | (none) | (none) 
| discussPage | 加载适用于讨论页的插件 | discuss.php | ```{discussion_id: "讨论页的ID", reply_cnt: "总回复数量"}``` | (none)
| createReply | 回复讨论 | reply.php | ```{content: "回复内容", discussion_id: "讨论ID"} | {"content": "回复内容"}
| createRoot | 发布新的讨论 | reply.php | ```{content: "讨论内容", title: "讨论标题"} | {"content": "讨论内容", "title": "讨论标题"}



## 数据交换
在插件中，可以使用提供的```GetSwapData()```和```SetSwapData()```函数来交换数据。
### GetSwapData
在调用插件之前，会设置一些交换数据，插件中可以使用```GetSwapData()```函数获取。
实现：
```php
function GetSwapData($key)
{
    if (array_key_exists($key, $_SESSION["swapData"])) {
        return $_SESSION["swapData"][$key];
    } else {
        return false;
    }
}
```
举例：
在创建回复时，可以使用```GetSwapData("content")```获得回复内容。
### SetSwapData
在插件中可以设置交换数据，但值得注意的是，主程序并不一定会更新全部数据，再以回复为例：
```php
function CreateReply($content, $discussion_id)
{
    $conn = GetConnection();
    $content = str_replace("'", "\\'", $content);
    $content = str_replace("\"", "\\\"", $content);
    SetSwapData("content", $content);
    SetSwapData("discussion_id", $discussion_id);
    LoadPlugins("createReply");
    $content = GetSwapData("content");
    // 读取discussion_id为$discussion_id的讨论
    // 获取最高的floor
    $sql = "SELECT MAX(`floor`) FROM `discusses` WHERE `discussion_id` = $discussion_id";
    $result = $conn->query($sql);
    echo $sql;
    $row = $result->fetch_assoc();
    $floor = $row['MAX(`floor`)'] + 1;
    // 插入新回复
    $sql = "INSERT INTO `discusses` (`discussion_id`, `floor`, `content`, `sendtime`, `user_id`, `discussionname`) VALUES ($discussion_id, $floor, '$content', " . time() . ", " . GetUserInCookies()["user_id"] . ", 'Re: " . str_replace("'", "''", GetSomething("discusses", "discussion_id = " . $discussion_id)["discussionname"]) .  "')";
    $result = $conn->query($sql);
}
```
可以看到，调用插件之后，主程序只更新了```content```的值，```discussion_id```的值并没有更新。

## 修改界面内容
我们并没有提供专用的修改界面内容的方法，但这可以轻易地通过JavaScript来实现。
```javascript
var element = document.getElementById("elementId");
element.innerHTML = "new content";
```
利用这段代码，可以修改ID为```elementId```的元素的内容。
### 示例
在插件```normal-pcer.markdown-editor```中，就用到了这个方法修改界面内容。
```javascript
function pluginmd_ShowEditor() {
    var div = document.getElementById("editNewDiscuss");
    var newHtml = "<form action='requirer.php?pluginname=normal-pcer.markdown-editor&filename=root.php' method='POST'><input type='hidden' name='action' value='create'><input type='text' name='title' placeholder='标题'><br><textarea name='content' id='content' cols='50' rows='15'></textarea><br><input type='submit' value='发布'></form>";
    div.innerHTML = newHtml;
    useEditor('content');
}
var link = document.getElementById("linkToEditor");
link.href = "javascript:pluginmd_ShowEditor()";
```
