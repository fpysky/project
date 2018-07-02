<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>后台首页</title>
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/index.css">
    <script src="/js/vue.js"></script>
    <script src="/js/index.js"></script>
    <script src="/js/axios.min.js"></script>
</head>
<style>
    body{ 
        margin:0;
        padding:0;
    }
    a{
        text-decoration:none;
    }
    .content{
        position:relative;
    }
    .contentLeft{
        position:absolute;
        left:0;
        top:0;
        width:13%; 
        background: #283643;
    }
    .contentRight{
        position:absolute;
        right:0;
        top:0;
        width:87%; 
    }
    .contentLeft-header{
        width:100%;
        height:50px;
        border-bottom:1px solid #000;
    },
    #menu{
        width:100%;
    },
    #menu a{
        display:block;
    }
    #menu ul li{
        list-style-type:none;
    }
    #menu ul li ul{
        display:none;
    }
    #menu ul li ul a{
        display:block;
    }
    #iframe{
        width:100%;
        height:100%;
    }
    #menu .menu-box{
        width:100%;
        overflow:hidden;
        border-bottom: 1px solid rgba(107, 108, 109, 0.19);
    }
    #menu .menu-box i{
        text-indent:20px;
    }
    #menu .menu-box a{
        color: #B5B5B5;
        display:inline-block;
        padding:10px 0 10px 6px;
    }
    #menu .menu-box div a:first-child{
        padding:14px 10px 7px 10px;
    }
    #menu .menu-box div a:last-child{
        padding:7px 10px 14px 10px;
    }
    #menu .menu-box div{
        height:0;
        transition:height .5s;
    }
    #menu .menu-box div a{
        display:block;
        font-size:15px;
    }
    #menu .menu-box div a{
        padding:7px 10px;
    }
</style>
<body>
<div id="app">
    <div class="content">
        <div class="contentLeft">
            <div class="contentLeft-header">
                <a @click="logout" href="javascript:;">退出登录</a>
            </div>
            <div id="menu">
                <div class="menu-box" v-for="items in permissions" :key="items.id">
                    <i class="fa fa-camera-retro"></i>
                    <a v-text="items.name" href="javascript:;"></a>
                    <div style="background-color:#17191B;text-indent:38px;">
                        <a v-for="items_items in items._child" :key="items_items.id" @click="menuAClick(items_items.route)" href="javascript:;" v-text="items_items.name"></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="contentRight">
            <iframe id="iframe" src="/admin/main" frameborder="0"></iframe>
        </div>
    </div>
</div>
</body>
</html>
<script>
    function menuActive(){
        let menu = document.getElementById('menu');
        let menuBox = menu.getElementsByClassName('menu-box');
        for(let i = 0;i < menuBox.length;i++){ 
            menuBox[i].getElementsByTagName('a')[0].onclick = function(){
                let len = this.nextElementSibling.getElementsByTagName('a').length;
                console.log(len);
                if(this.nextElementSibling.offsetHeight == 0){
                    if(len == 1){
                        this.nextElementSibling.style.height = 34 +'px';
                    }else{
                        this.nextElementSibling.style.height = 34 * len + 20 +'px';
                    }
                    
                }else{
                    this.nextElementSibling.style.height = 0;
                }
            }
        }
    }
    new Vue({
        el:'#app',
        data:{
            permissions:[],
        },
        created(){
            this.initHeight();
            this.getAdminPermission();
            
        },
        updated() {
            this.$nextTick(function () {
                menuActive();
            })
        },
        methods:{
            getAdminPermission(){
                axios.post('/admin/permission/getAdminPermission',{}).then(response => {
                    this.permissions = response.data.list;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            menuAClick(url){
                document.getElementById('iframe').setAttribute('src',url);
                return false;
            },
            logout(){
                axios.post('/admin/public/logout',{}).then(function (response) {
                    window.location.reload();
                }).catch(function (error) {
                    console.log(error);
                });
            },
            initHeight(){
                let h = document.documentElement.clientHeight - 5;
                document.getElementsByClassName('contentLeft')[0].style.height = h + 'px';
                document.getElementsByClassName('contentRight')[0].style.height = h + 'px';
            }
        },
    });
</script>