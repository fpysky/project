@extends('admin.layouts.base')
@section('css')
@endsection
@section('content')
    <div id="app">
        <el-tabs type="border-card" v-model="activeName" @tab-click="handleClick">
        <el-tab-pane label="管理员管理" name="first">
            <div>
                <el-button @click="deleteFunc(-1)" type="danger" size="mini" icon="el-icon-delete">删除</el-button>
                <el-input style="width:160px;" v-model="name" size="small" placeholder="用户名"></el-input>
                <el-button @click="search" type="primary" size="mini" icon="el-icon-search">搜索</el-button>
            </div>
            <el-table :height="h" v-loading="tableloading" :data="tableData" @selection-change="handleSelectionChange">
                <el-table-column type="selection" width="55">
                </el-table-column>
                <el-table-column prop="name" label="用户名">
                </el-table-column>
                <el-table-column label="操作" width="180" fixed="right">
                    <template slot-scope="scope">
                        <el-button @click="editFunc(scope.$index)" type="text" size="mini">编辑</el-button>
                        <el-button @click="deleteFunc(scope.$index)" type="danger" size="mini">删除</el-button>
                    </template>
                </el-table-column>
            </el-table>
            <el-pagination
                @size-change="handleSizeChange"
                @current-change="handleCurrentChange"
                :current-page="page"
                :page-sizes="[50, 100, 150, 200]"
                :page-size="pSize"
                layout="total, sizes, prev, pager, next, jumper"
                :total="total">
            </el-pagination>
        </el-tab-pane>
        <el-tab-pane :label="labelName" name="second">
            <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="100px">
                <el-form-item label="用户名：" prop="name">
                    <el-input style="width:300px;" v-model="ruleForm.name"></el-input>
                </el-form-item>
                <el-form-item label="登陆账户：" prop="account">
                    <el-input style="width:300px;" v-model="ruleForm.account"></el-input>
                </el-form-item>
                <el-form-item label="电子邮箱：" prop="email">
                    <el-input style="width:300px;" v-model.email="ruleForm.email"></el-input>
                </el-form-item>
                <el-form-item v-if="!isEdit" label="密码：" prop="password">
                    <el-input type="password" style="width:300px;" v-model="ruleForm.password"></el-input>
                </el-form-item>
                <el-form-item v-if="!isEdit" label="确认密码：" prop="confirmPw">
                    <el-input type="password" style="width:300px;" v-model="ruleForm.confirmPw"></el-input>
                </el-form-item>
                <el-form-item label="角色分配：" prop="roles">
                    <el-checkbox-group v-model="ruleForm.roles" size="small">
                        <el-checkbox v-for="item in roles" :label="item.id" :key="item.id" border><span v-text="item.name"></span></el-checkbox>
                    </el-checkbox-group>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="submitForm('ruleForm')">确定</el-button>
                </el-form-item>
            </el-form>
        </el-tab-pane>
        </el-tabs>
    </div>
@endsection
@section('js')
    <script>
        new Vue({
            el:'#app',
            data:{
                tableloading:false,
                name:'',
                labelName:'添加',
                isEdit:false,
                roles:[],
                activeName:'first',
                tableData:[],
                multipleSelection: [],
                page:1,
                h:800,
                pSize:50,
                total:0,
                ruleForm:{
                    id:0,
                    name:'',
                    account:'',
                    password:'',
                    confirmPw:'',
                    email:'',
                    roles:[]
                },
                rules:{
                    name: [
                        { required: true, message: '请输入用户名', trigger: 'blur' }
                    ],
                    account: [
                        { required: true, message: '请输入帐户名', trigger: 'blur' }
                    ],
                    email: [
                        { type:'email', message: '请输入正确的电子邮箱', trigger: 'blur' }
                    ],
                    password: [
                        { required: true, message: '请输入密码', trigger: 'blur' },
                        { min: 6, max: 17, message: '密码长度在6到17个字符', trigger: 'blur' }
                    ],
                    confirmPw: [
                        { required: true, message: '请输入确认密码', trigger: 'blur' },
                        { min: 6, max: 17, message: '密码长度在6到17个字符', trigger: 'blur' }
                    ]
                },

            },
            created(){
                this.getData();
                this.getAllRole();
                this.initHeight();
            },
            methods:{
                initHeight(){
                    let h = window.parent.document.getElementsByClassName("contentRight")[0].style.height;
                    this.h = parseInt(h) - 200;
                },
                handleSizeChange(val) {
                    this.pSize = val;
                    this.getData();
                },
                handleCurrentChange(val) {
                    this.page = val;
                    this.getData();
                },
                search(){
                    this.page = 1;
                    this.getData();
                },
                handleSelectionChange(val) {
                    this.multipleSelection = val;
                },
                deleteFunc(k){
                    if(k == -1 && this.multipleSelection.length == 0){
                        this.$message.warning('请至少选择一条记录');
                    }else{
                        this.$confirm('确定删除吗?', '提示', {
                            confirmButtonText: '确定',
                            cancelButtonText: '取消',
                            type: 'warning'
                        }).then(() => {
                            let ids = [];
                            if(k == -1){
                                for(let i = 0;i < this.multipleSelection.length;i++){
                                    ids.push(this.multipleSelection[i].id);
                                }
                            }else{
                                ids.push(this.tableData[k].id);
                            }
                            axios.post('/admin/permission/deleteAdmin', {ids:ids}).then(response => {
                                if(response.data.code == 0){
                                    this.$message.success(response.data.message);
                                    this.getData();
                                }else{
                                    this.$message.warning(response.data.message);
                                }
                        }).catch(() => {});

                    }).catch(function (error) {
                            console.log(error);
                        });
                    }
                },
                getAdminRoles(id){
                    axios.post('/admin/permission/getAdminRoles', {id:id}).then(response => {
                        this.ruleForm.roles = response.data.list;
                    }).catch(error =>{
                        console.log(error);
                    });
                },
                submitForm(formName) {
                    this.$refs[formName].validate((valid) => {
                        if (valid) {
                            if(this.ruleForm.password != this.ruleForm.confirmPw){
                                this.$message.warning('两次密码输入不一致！');
                                return;
                            }
                            axios.post('/admin/permission/adminPost', this.ruleForm).then(response => {
                                if(response.data.code == 0){
                                    this.$message.success(response.data.message);
                                    this.getData();
                                    this.activeName = 'first';
                                    this.resetData();
                                }else{
                                    this.$message.warning(response.data.message);
                                }
                            }).catch(error => {
                                if(error.response.status == 422){
                                    this.$message.warning(error.response.data.errors);
                                }
                            });
                        } else {
                            return false;
                        }
                    });
                },
                getAllRole(){
                    axios.post('/admin/permission/getAllRole', {}).then(response => {
                        this.roles = response.data.list;
                    }).catch(function (error) {
                        console.log(error);
                    });
                },
                resetData(){
                    this.labelName = '添加';
                    this.isEdit = false;
                    this.resetForm('ruleForm');
                },
                editFunc(k){
                    this.labelName = '编辑';
                    this.isEdit = true;
                    this.activeName = 'second';
                    this.ruleForm.id = this.tableData[k].id;
                    this.ruleForm.name = this.tableData[k].name;
                    this.ruleForm.account = this.tableData[k].account;
                    this.ruleForm.email = this.tableData[k].email;
                    this.getAdminRoles(this.tableData[k].id);
                },
                handleClick(tab, event){
                    if(this.activeName == 'first'){
                        this.resetData();
                    }
                },
                resetForm(formName) {
                    this.$refs[formName].resetFields();
                    this.ruleForm.id = 0;
                },
                getData(){
                    this.tableloading = true;
                    axios.post('/admin/permission/getAdminList', {
                        page:this.page,
                        pSize:this.pSize,
                        name:this.name
                    }).then(response => {
                        this.tableloading = false;
                        this.tableData = response.data.list;
                        this.total = response.data.total;
                    }).catch(function (error) {
                        this.tableloading = false;
                        console.log(error);
                    });
                },
            },
        });
    </script>
@endsection