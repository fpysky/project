<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>登陆到后台</title>
    <script src="/js/vue.js"></script>
    <link rel="stylesheet" href="/css/index.css">
    <script src="/js/index.js"></script>
    <script src="/js/axios.min.js"></script>
    <script src="/js/jquery-3.3.1.min.js"></script>
</head>
<style>
    .loginContent{
        width:25%;
        height:40%;
        margin:10% auto 0 auto;
    }
    .loginTitile{
        text-align:center;
        width:100%;
    }
    .captchaBox{
        display:inline-block;
        width:30%;
        height:100%;
        margin:0 0 0 3%;
    }
    .captchaBox img{
        vertical-align:middle;
    }
</style>
<body>
<div id="app">
    <div class="loginContent">
        <div class="loginTitile">
            <h3>登陆</h3>
        </div>
        <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="80px">
            <el-form-item label="帐号：" prop="account">
                <el-input onkeydown=keyDown() v-model="ruleForm.account"></el-input>
            </el-form-item>
            <el-form-item label="密码：" prop="password">
                <el-input onkeydown=keyDown() type="password" v-model="ruleForm.password"></el-input>
            </el-form-item>
            <el-form-item v-if="captchaSwitch" label="验证码：" prop="captcha">
                <el-input onkeydown=keyDown() style="width:60%;" v-model="ruleForm.captcha"></el-input>
                <a @click="getCaptcha" href="javascript:;" class="captchaBox"><img id="captcha" src="<?php echo captcha_src();?>" alt=""></a>
            </el-form-item>
            <el-form-item>
                <el-button style="width:100%;" type="primary" @click="submitForm('ruleForm')" :disabled="submiting" v-html="submiting?'登陆中':'登陆'"></el-button>
            </el-form-item>
        </el-form>
    </div>
</div>
</body>
</html>
<script>
    let vm = new Vue({
        el:'#app',
        data:{
            csrf_token:'{{csrf_token()}}',
            submiting:false,
            captchaSwitch:true,
            ruleForm:{
                account:'',
                password:'',
                captcha:''
            },
            rules:{
                account: [
                    { required: true, message: '请输入帐号', trigger: 'blur' },
                ],
                password: [
                    { required: true, message: '请输入密码', trigger: 'blur' },
                ],
                captcha: [
                    { required: true, message: '请输入验证码', trigger: 'blur' },
                ],
            },
        },
        created(){
            if('{{$captchaSwitch}}' == 'off')
                this.captchaSwitch = false;
            else
                this.captchaSwitch = true;
        },
        methods:{
            submitForm(formName) {
                this.submiting = true;
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        axios.post('/admin/public/loginPost',this.ruleForm).then(response => {
                            if(response.data.code == 0){
                                window.location.href = '/admin';
                            }else{
                                this.$message({
                                    message: response.data.message,
                                    type: 'warning'
                                });
                                this.getCaptcha();
                                this.submiting = false;
                            }
                        }).catch(function (error) {
                            this.submiting = false;
                            console.log(error);
                        });
                    }else{
                        this.submiting = false;
                        return false;
                    }
                });
            },
            getCaptcha(){
                axios.post('/admin/public/getCaptcha',{}).then(response => {
                    document.getElementById('captcha').src = response.data.verifySrc;
                }).catch(function (error) {
                    console.log(error);
                });
            },
        },
    });
    function keyDown(){
        let keyCode = event.keyCode;
        if(event.keyCode == 13){
            vm.submitForm('ruleForm');
        }
    }
</script>
