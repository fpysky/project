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
        width:12%; 
        background-color:#222d32;
    }
    .contentRight{
        position:absolute;
        right:0;
        top:0;
        width:88%;
    }
    .contentLeft-header{
        width:100%;
        height:55px;
        margin:50px 0 10px 0;
    }
    .contentLeft-header-left{
        float:left;
        height:45px;
        width:45px;
        background-color:#fff;
        margin: 0 0 0 20px;
        border-radius: 50%;
        overflow: hidden;
    }
    .contentLeft-header-left img{
        width:100%;
        height:100%;
    }
    .contentLeft-header-right{
        float:left;
        height:45px;
        width:60%;
        margin:0 0 0 20px;
    }
    .contentLeft-header-right p{
        margin:5px 0;
        color:#fff;
    }
    .contentLeft-header-right p a{
        color:#F56C6C;
        border:1px solid #F56C6C;
    }
    .contentLeft-header-right p:first-child{
        font-size: 17px;
    }
    .contentLeft-header-right p:last-child{
        font-size: 13px;
    }
    #menu{
        width:100%;
    }
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
        /* display:block; */
    }
    #iframe{
        width:100%;
        height:100%;
    }
    #menu .menu-box{
        width:100%;
        overflow:hidden;
    }
    #menu .menu-box i{
        text-indent:20px;
    }
    #menu .menu-box a{
        color: #fff;
        display:inline-block;
        padding:10px 0 10px 6px;
    }
    #menu .menu-box div{
        height:0;
        transition:height .5s;
    }
    #menu .menu-box div p{
        margin:0;
    }
</style>
<body>
<div id="app">
    <div class="content">
        <div class="contentLeft">
            <div class="contentLeft-header">
                <a href="javascript:;" @click="setAdminInfo">
                    <div class="contentLeft-header-left">
                        <img src="/images/header.jpg" alt="" />
                    </div>
                </a>
                <div class="contentLeft-header-right">
                    <p v-text="adminInfo.name"></p>
                    <p><a @click="logout" href="javascript:;">退出登录</a></p>
                </div>
                
            </div>
            <div id="menu">
                <div class="menu-box" v-for="items in permissions" :key="items.id">
                    <i :class="items.icon == ''?'fa-camera-retro':'fa fa-' + items.icon"></i>
                    <a v-text="items.name" href="javascript:;"></a>
                    <div style="background-color:#2A383E;">
                        <p v-for="items_items in items._child" :key="items_items.id">
                            <i :class="items_items.icon == ''?'fa-camera-retro':'fa fa-' + items_items.icon"></i>
                            <a @click="menuAClick(items_items.route)" href="javascript:;" v-text="items_items.name"></a>
                        </p>
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
    new Vue({
        el:'#app',
        data:{
            permissions:[],
            adminInfo:{},
        },
        created(){
            this.initHeight();
            this.getAdminPermission();
            this.getAdminInfo();
        },
        updated() {
            this.$nextTick(function () {
                this.menuActive();
            })
        },
        methods:{
            setAdminInfo(){
                document.getElementById('iframe').setAttribute('src','/admin/admininfo');
                return false;
            },
            getAdminInfo(){
                axios.post('/admin/getAdminInfo',{}).then(response => {
                    this.adminInfo = response.data.list;
                    // console.log(this.adminInfo);
                }).catch(error => {
                    console.log(error);
                });
            },
            menuActive(){
                let menu = document.getElementById('menu');
                let menuBox = menu.getElementsByClassName('menu-box');
                for(let i = 0;i < menuBox.length;i++){ 
                    menuBox[i].getElementsByTagName('a')[0].onclick = function(){
                        let len = this.nextElementSibling.getElementsByTagName('a').length;
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
            },
            getAdminPermission(){
                axios.post('/admin/permission/getAdminPermission',{}).then(response => {
                    this.permissions = response.data.list;
                }).catch(error => {
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
                }).catch(error => {
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