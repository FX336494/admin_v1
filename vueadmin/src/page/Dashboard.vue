<template>
    <div>
        <el-row :gutter="20">
            <el-col :span="24">
                <el-card shadow="hover" class="mgb20" style="height:252px;">
                    <div class="user-info">
                        <div class="avatar">
                            <span><upload  @showImg="showImg" :size="100" :opacity="0"></upload></span>
                            <img :src="avatar?avatar:'static/img/img.jpg'" class="user-avator" alt="">
                        </div>
                       
                        <div class="user-info-cont">
                            <div class="user-info-name">{{form.user_name}}</div>
                            <div>{{role}}</div>
                            <el-button type="success" icon="el-icon-edit" size="mini" @click="showEdit">信息修改</el-button>
                        </div>
                    </div>
                    <div class="user-info-list">上次登录时间：{{lastLogin*1000 | formatDate}}</div>
                </el-card>
            </el-col>
        </el-row>

        <!-- 编辑弹出框 -->
        <el-dialog :title="'信息修改'" :visible.sync="editVisible" width="30%">
            <el-form ref="form" :model="form" label-width="100px">
                <el-form-item label="用户名">
                    <el-input v-model="form.user_name"></el-input>
                </el-form-item>              
                <el-form-item label="旧密码">
                    <el-input type="password" v-model="form.old_pass"></el-input>
                </el-form-item>  
                <el-form-item label="新密码">
                    <el-input type="password" v-model="form.new_pass"></el-input>
                </el-form-item>                               
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button @click="editVisible = false">取 消</el-button>
                <el-button type="primary" @click="saveEdit">确 定</el-button>
            </span>
        </el-dialog>

        


    </div>
</template>

<script>
    import bus from '../components/common/bus';
    import upload from '../components/utils/upload';
    export default {
        name: 'dashboard',
        data() {
            return {
                editVisible:false,
                lastLogin:'',
                form:{
                    user_name:'',
                    new_pass:'',
                    old_pass:'',
                    id:0,
                },
                avatar:'',
            }
        },
        components:{upload},
        computed: {
            role() {
                return this.form.user_name === 'admin' ? '超级管理员' : '普通用户';
            }
        },
        created(){
            this.$nextTick(()=>{
                this.get_data();
            })
        },
        methods: {
            get_data() {
                this.$post_('admin/user/user_info',{},(res)=>{
                    console.log(res);
                    this.lastLogin = res.data.lastLogin;
                    this.form.user_name = res.data.user_name;
                    this.form.id = res.data.id;
                    this.avatar = res.data.avatar;
                });
            },
            showEdit() {
                this.editVisible = true;
                this.form.new_pass = '';
                this.form.old_pass = '';
            },
            saveEdit() {
                // console.log(this.form);
                this.$post_('admin/user/edit_info',this.form,(res)=>{
                    if(res.code=='0'){
                        this.$message.success(res.msg);
                    }else{
                        this.$message.warning(res.msg);
                    }
                    this.editVisible= false;
                })
            },
            showImg(imgUrl) {
                console.log(imgUrl);
                this.$post_('admin/user/edit_info',{avatar:imgUrl,id:this.form.id},(res)=>{
                    if(res.code=='0'){
                        this.avatar = imgUrl;
                        this.$message.success('修改成功');
                    }else{
                        this.$message.warning(res.msg);
                    }
                });
            }
        }
    }

</script>


<style scoped>
    .el-row {
        margin-bottom: 20px;
    }

    .user-info {
        display: flex;
        align-items: center;
        padding-bottom: 20px;
        border-bottom: 2px solid #ccc;
        margin-bottom: 20px;
    }

    .user-avator {
        width: 120px;
        height: 120px;
        border-radius: 50%;
    }

    .user-info-cont {
        padding-left: 50px;
        flex: 1;
        font-size: 14px;
        color: #999;
    }

    .user-info-cont div:first-child {
        font-size: 30px;
        color: #222;
    }

    .user-info-list {
        font-size: 14px;
        color: #999;
        line-height: 25px;
    }

    .user-info-list span {
        margin-left: 70px;
    }

    .mgb20 {
        margin-bottom: 20px;
    }

    .avatar {
        position: relative;
    }
    .avatar span{
        position: absolute;
        top: 0px;
        left: 0px;
        display: inline-block;
        width: 100%;
    }

</style>
