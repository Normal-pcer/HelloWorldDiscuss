# Hello World! Discuss
by normal-pcer and quqi2
## 简介
这是一款开源的轻量级论坛框架，目前拥有最基础的一些讨论功能，并会在未来持续更新。
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
            "pass": "test123"
        },
        "password-salt": {
            "enabled": true,
            "value": ""
        }
    },
    "defaultSetting": {
        "language": "zh-cn"
    }
}
```
以下为各项的含义：
```json
{
    "safety": { 
        "database": {
            "host": "localhost",
            "name": "discuss",
            "user": "discuss",
            "pass": "test123"
        },
        "password-salt": {
            "enabled": true,
            "value": ""
        }
    },
    "defaultSetting": {
        "language": "zh-cn"
    }
}
```