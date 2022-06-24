    <!-- 载入依赖的样式 -->
    <link rel="stylesheet" href="./style.css">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://jsd.miaowuawa.cn/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://jsd.miaowuawa.cn/npm/bootstrap@3.4.1/dist/css/bootstrap-theme.min.css" integrity="sha384-6pzBo3FDv/PJ8r2KRkGHifhEocL+1X2rVCTTkUfGk7/0pbek5mMa1upzvWbrUbOZ" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://jsd.miaowuawa.cn/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
    <!--sweetalert-->
    <script src="https://jsd.miaowuawa.cn/npm/sweetalert2@8"></script>
    <script src="sweetalert2.all.min.js"></script>
    <!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
    <script src="https://jsd.miaowuawa.cn/npm/promise-polyfill"></script>
    <script charset="utf-8" src="/editor/kindeditor-all.js"></script>
    <script charset="utf-8" src="/editor/lang/zh-CN.js"></script>
    <script>
        KindEditor.ready(function(K) {
            window.editor = K.create('#editor_id');
        });
    </script>
<!-- MDUI CSS -->
<link
  rel="stylesheet"
  href="https://jsd.miaowuawa.cn/npm/mdui@1.0.1/dist/css/mdui.min.css"
  integrity="sha384-cLRrMq39HOZdvE0j6yBojO4+1PrHfB7a9l5qLcmRm/fiWXYY+CndJPmyu5FV/9Tw"
  crossorigin="anonymous"
/>

<!-- MDUI JavaScript -->
<script
  src="https://jsd.miaowuawa.cn/npm/mdui@1.0.1/dist/js/mdui.min.js"
  integrity="sha384-gCMZcshYKOGRX9r6wbDrvF+TcCCswSHFucUzUPwka+Gr+uHgjlYvkABr95TCOz3A"
  crossorigin="anonymous"
></script>
<script>
    function jumpto(url) {
       window.location.href = url ;
}
</script>
    <style>
        html {
            height: 100%;
        }

        body {
            height: 100%;
        }

        div.main {
            height: 100%;
        }

        div.title-bar {
            text-align: center;
            background-color: #6ae;
            color: #fff;
            padding: 0.5em;
        }

        ul.sidebar {
            position: absolute;
            left: 0.5em;
            top: 100px;
            bottom: 0;
            height: 100%;
            width: 200px;
            background-color: #eee;
            padding: 0;
            display: block;
        }

        ul.sidebar>li {
            list-style: none;
            padding: 1em;
            border-bottom: 1px solid #ccc;
        }

        ul.sidebar>li>a {
            text-decoration: none;
            color: #6ae;
        }

        div.main {
            padding-left: 100px;
            top: 100px;
        }

        ul.articles {
            list-style: none;
            padding: 1em;
            left: 100px;
        }

        ul.articles>li {
            padding: 1em;
            border-bottom: 1px solid #ccc;
        }

        ul.articles>li>a {
            color: #000;
            text-decoration: none;
        }

        ul.top-bar {
            list-style: none;
            padding: 1em;
            left: 100px;
        }

        ul.top-bar>li>a {
            color: #000;
            text-decoration: none;
        }

        ul.top-bar>li {
            display: inline-block;
            padding: 0.5em;
        }
    </style>
<script src="http://cdn.static.runoob.com/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
   //定义所需要的函数
    function developalert(){
        Swal.fire({
            title: '错误',
            text: '这个功能正在开发中',
            type: 'error',
            confirmButtonText: '确认'
        });
    }
    function loginerr(){
        Swal.fire({
            title: '错误',
            text: '登录信息有误，请检查后重试(3秒后自动跳转）',
            type: 'error',
            confirmButtonText: '确认',
            onClose: 'window.location.href=index.php?act.login'
        });
    }
    function no_auth(){
        Swal.fire({
            title: '错误',
            text: '未登录，请登录后再试',
            type: 'error',
            confirmButtonText: '确认'
        });
    }
    var inst = new mdui.Drawer('#drawer');
</script>
