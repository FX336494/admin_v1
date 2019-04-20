<template>
    <div class="table">
        <div class="crumbs">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item><i class="el-icon-lx-cascades"></i> 菜单权限管理</el-breadcrumb-item>
                <el-breadcrumb-item>角色列表</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <div class="container">

            <div>
                <el-button icon="el-icon-add" @click="handleAdd"  type="success">添加</el-button>
            </div>

            <el-table :data="tableData" border class="table" ref="multipleTable" @selection-change="handleSelectionChange">

                <el-table-column type="selection" width="55" align="center"></el-table-column>
                <el-table-column prop="id" label="序号" sortable width="150"></el-table-column>
                <el-table-column prop="role_name" label="名称" width="160"> 
                </el-table-column>
                <el-table-column prop="role_desc" label="描述">
                </el-table-column>
                
                <el-table-column label="操作" width="240" align="center">
                    <template slot-scope="scope">
                        <el-button type="text" icon="el-icon-edit" @click="roleAuth(scope.row)">
                            角色权限
                        </el-button>
                        <el-button type="text" icon="el-icon-edit" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
                        <el-button type="text" icon="el-icon-delete" class="red" @click="handleDelete(scope.$index, scope.row)">删除</el-button>
                    </template>
                </el-table-column>
            </el-table>
        </div>

        <!-- 编辑弹出框 -->
        <el-dialog :title="idx>0?'编辑':'添加'" :visible.sync="editVisible" width="30%">
            <el-form ref="form" :model="form" label-width="100px">
                <el-form-item label="角色名称">
                    <el-input v-model="form.role_name"></el-input>
                </el-form-item>               
                <el-form-item label="角色描述">
                    <el-input v-model="form.role_desc"></el-input>
                </el-form-item>              
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button @click="editVisible = false">取 消</el-button>
                <el-button type="primary" @click="saveEdit">确 定</el-button>
            </span>
        </el-dialog>

        <!-- 删除提示框 -->
        <el-dialog title="提示" :visible.sync="delVisible" width="300px" center>
            <div class="del-dialog-cnt">删除不可恢复，是否确定删除？</div>
            <span slot="footer" class="dialog-footer">
                <el-button @click="delVisible = false">取 消</el-button>
                <el-button type="primary" @click="deleteRow">确 定</el-button>
            </span>
        </el-dialog>

        <el-dialog
          title="权限修改"
          :visible.sync="showAuth"
          width="60%">
            <el-tree
              ref="tree"
              :data="treeData"
              show-checkbox
              node-key="id"
              default-expand-all
              :default-checked-keys="checkedValue"
              :props="defaultProps"
              >
            </el-tree>            
          <span slot="footer" class="dialog-footer">
            <el-button @click="showAuth = false">取 消</el-button>
            <el-button type="primary" @click="editAuth">确 定</el-button>
          </span>
        </el-dialog>

    </div>
</template>

<script>
    export default {
        name: 'roleList',
        components:{},
        data() {
            return {
                tableData: [],
                cur_page: 1,
                multipleSelection: [],
                editVisible: false,
                delVisible: false,
                showIcon:false,
                form: {
                    role_name: '',
                    role_desc:'',
                    id:0,
                },
                idx: -1,
                id:0,
                treeData:[],
                checkedValue:[],
                defaultProps: {
                  children: 'children',
                  label: 'name'
                },
                showAuth:false,
                curRoleId:0,
            }
        },
        created() {
            this.getData();
        },
        activated() {
            this.form.pid = 0;
            this.getData();
        },

        methods: {
            // 分页导航
            handleCurrentChange(val) {
                this.cur_page = val;
                this.getData();
            },
            // 获取 easy-mock 的模拟数据
            getData() {
                console.log(this.form.pid);
                this.$post_('admin/role/role_list',{pid:this.form.pid},(res)=>{
                    console.log(res);
                    this.tableData = res.data;
                });
            },
            //添加
            handleAdd(){
                this.form.role_name = '';
                this.form.role_desc = '';
                this.idx = -1;
                this.id = 0;
                this.editVisible = true;
            },

            //修改
            handleEdit(index, row) {
                this.idx = index;
                this.id = row.id;
                const item = this.tableData[index];
                this.form = {
                    role_name: item.role_name,
                    role_desc: item.role_desc,
                    id:this.id,
                }
                this.editVisible = true;
            },
            handleDelete(index, row) {
                this.id = row.id;
                this.idx = index;
                this.delVisible = true;
            },
            handleSelectionChange(val) {
                this.multipleSelection = val;
            },

            // 保存编辑
            saveEdit() {
                // console.log(this.form);return;
                this.$post_('admin/role/role_add',this.form,(res)=>{
                    if(res.code=='0'){
                        if(this.id<1){
                            // this.tableData.push(res.data);
                            this.getData();
                        } 
                        if(this.id>0) this.$set(this.tableData, this.idx,res.data);
                        this.$message.success(res.msg);
                    }else{
                        this.$message.success(res.msg);
                    }
                })
                this.editVisible = false;
            },
            // 确定删除
            deleteRow(){
                this.$post_('admin/role/role_del',{id:this.id},(res)=>{
                    console.log(res);
                    if(res.code=='0'){
                        this.$message.success(res.msg);
                    }else{
                        this.$message.warning(res.msg);
                    }
                })
                this.delVisible = false;
                this.tableData.splice(this.idx, 1);
            },
            //角色权限
            roleAuth(row){
                let params = {id:row.id};
                this.curRoleId = row.id;
                this.$post_('admin/role/role_auth_list',params,(res)=>{
                    console.log(res);
                    this.treeData = res.data.auth_tree;
                    this.checkedValue = res.data.role_auth;
                    this.showAuth = true;
                })
            },
            editAuth() {
                let authData = this.$refs.tree.getCheckedNodes();
                let authIds = '';
                authData.forEach((val)=>{
                    authIds += val.id+',';
                })
                authIds = authIds.substr(0, authIds.length - 1);
                // console.log(authIds);
                this.$post_('admin/role/role_auth_edit',{auths:authIds,id:this.curRoleId},(res)=>{
                    console.log(res);
                    this.showAuth = false;
                    if(res.code=='0'){
                        this.$message.success(res.msg);
                    }else{
                        this.$message.warning(res.msg);
                    }
                })
            }
        }
    }

</script>

<style scoped>
    .iconfont{
        font-size: 20px;
        /*font-weight: bold;*/
    }
    .handle-box {
        margin-bottom: 20px;
    }

    .handle-select {
        width: 120px;
    }

    .handle-input {
        width: 300px;
        display: inline-block;
    }
    .del-dialog-cnt{
        font-size: 16px;
        text-align: center
    }
    .table{
        width: 100%;
        font-size: 14px;
    }
    .red{
        color: #ff0000;
    }
</style>
