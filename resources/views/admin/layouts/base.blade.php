<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <!-- 开发环境版本，包含了用帮助的命令行警告 -->
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <!-- 引入样式 -->
    <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
    <!-- 引入组件库 -->
    <script src="https://unpkg.com/element-ui/lib/index.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    @yield('css')
</head>
<style>
    body{
        margin:0;
    }
    .search-content{
        padding:10px .7%;
        width:98.6%;
        background-color:rgb(240, 242, 247);
        border-radius:4px;
    }
    .aligncenter{
        text-align:center;
    }
</style>
<body>
@yield('content')
</body>
</html>
@yield('js')