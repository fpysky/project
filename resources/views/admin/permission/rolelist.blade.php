@extends('admin.layouts.base')
@section('css')
@endsection
@section('content')
    <div id="app">
        <el-tabs type="border-card" v-model="activeName" @tab-click="handleClick">
        <el-tab-pane label="角色管理" name="first">
            <div class="search-content">
                <el-button @click="deleteFunc(-1)" type="danger" size="mini" icon="el-icon-delete">删除</el-button>
                <el-input style="width:170px;" v-model="name" placeholder="输入角色名称"></el-input>
                <el-button size="small" type="primary" icon="el-icon-search" @click="search">搜索</el-button>
            </div>
            <el-table v-loading="tableloading" :data="tableData" :height="h" @selection-change="handleSelectionChange">
                <el-table-column type="selection" width="55">
                </el-table-column>
                <el-table-column  prop="name" label="角色名称">
                </el-table-column>
                <el-table-column label="操作" width="180" fixed="right">
                    <template slot-scope="scope">
                        <el-button @click="eidtFunc(scope.$index)" type="text" size="small">编辑</el-button>
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
        <el-tab-pane :disabled="editing" :label="labelName" name="second">
            <el-form style="width:30%;" :model="ruleForm" :rules="rules" ref="ruleForm" label-width="100px">
                <el-form-item label="角色名称：" prop="name">
                    <el-input v-model="ruleForm.name"></el-input>
                </el-form-item>
                <el-form-item label="角色授权:" prop="permissions">
                    <el-checkbox-group v-model="ruleForm.permissions" size="small">
                        <el-checkbox v-for="item in permissions" :label="item.id" :key="item.id" border><span v-text="item.name"></span></el-checkbox>
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
                permissions:[],
                isEdit:false,
                page:1,
                total:0,
                pSize:50,
                labelName:'添加',
                editing:false,
                tableloading:false,
                h:800,
                name:'',
                activeName:'first',
                tableData:[],
                ruleForm:{
                    id:0,
                    name:'',
                    permissions:[]
                },
                rules:{
                    name: [
                        { required: true, message: '请输入角色名称', trigger: 'blur' }
                    ],
                },
                multipleSelection: []
            },
            created(){
                this.getData();
                this.initHeight();
                this.getPermissionList();
            },
            methods:{
                getPermissionList(){
                    axios.post('/admin/permission/getPermissionList', {}).then(response => {
                        this.permissions = response.data.list;
                    }).catch(function (error) {
                        console.log(error);
                    });
                },
                getRolePermission(id){
                    axios.post('/admin/permission/getRolePermission', {id:id}).then(response => {
                        this.ruleForm.permissions = response.data.list;
                    }).catch(function (error) {
                        console.log(error);
                    });
                },
                handleSizeChange(val) {
                    this.pSize = val;
                    this.getData();
                },
                handleCurrentChange(val) {
                    this.page = val;
                    this.getData();
                },
                eidtFunc(index){
                    this.activeName = 'second';
                    this.isEdit = true;
                    this.labelName = '编辑角色';
                    this.ruleForm.id = this.tableData[index].id;
                    this.ruleForm.name = this.tableData[index].name;
                    this.getRolePermission(this.tableData[index].id);
                },
                handleSelectionChange(val) {
                    this.multipleSelection = val;
                },
                deleteFunc(k){
                    if(k == -1 && this.multipleSelection.length == 0){
                        this.$message({
                            message: '请至少选择一条记录',
                            type: 'warning'
                        });
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
                        axios.post('/admin/permission/deleteRole', {ids:ids}).then(response => {
                            if(response.data.code == 0){
                                this.$message({
                                    message: response.data.message,
                                    type: 'success'
                                });
                                this.getData();
                            }else{
                                this.$message({
                                    message: response.data.message,
                                    type: 'warning'
                                });
                            }
                        }).catch(() => {});

                    }).catch(function (error) {
                            console.log(error);
                        });
                    }
                },
                initHeight(){
                    let h = window.parent.document.getElementsByClassName("contentRight")[0].style.height;
                    this.h = parseInt(h) - 200;
                },
                search(){
                    this.page = 1;
                    this.getData();
                },
                submitForm(formName) {
                    this.$refs[formName].validate((valid) => {
                        if (valid) { 
                            axios.post('/admin/permission/rolePost', this.ruleForm).then(response => {
                                if(response.data.code == 0){
                                    this.$message({
                                        message: response.data.message,
                                        type: 'success'
                                    });
                                    this.getData();
                                    this.activeName = 'first';
                                    this.resetData();
                                }else{
                                    this.$message({
                                        message: response.data.message,
                                        type: 'warning'
                                    });
                                }
                            }).catch(function (error) {
                                this.$message.error('出现错误！');
                                console.log(error);
                            });
                        } else {
                            return false;
                        }
                    });
                },
                handleClick(tab, event){
                    if(this.activeName == 'first'){
                        this.resetData();
                    }
                },
                resetData(){
                    this.labelName = '添加';
                    this.isEdit = false;
                    this.resetForm('ruleForm');
                },
                resetForm(formName) {
                    this.$refs[formName].resetFields();
                    this.ruleForm.id = 0;
                },
                getData(){
                    this.tableloading = true;
                    axios.post('/admin/permission/getRoleList', {
                        page:this.page,
                        pSize:this.pSize,
                        name:this.name
                    }).then(response => {
                        this.tableloading = false;
                        if(response.data.code == 0){
                            this.tableData = response.data.list;
                            this.total = response.data.total;
                        }else{
                            this.$message.error(response.data.message);
                        }
                    }).catch(function (error) {
                        console.log(error);
                        this.tableloading = false;
                    });
                },
            },
        });
    </script>
@endsection