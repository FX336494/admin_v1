<template>
    <div class="sidebar">
        <el-menu 
            class="sidebar-el-menu" 
            :default-active="onRoutes" 
            :collapse="collapse" 
            background-color="#324157"
            text-color="#bfcbd9" 
            active-text-color="#20a0ff" 
            :unique-opened="true"
            router
        >
            <template v-for="(item,i) in items">

                <template v-if="item.children">
                    <el-submenu :index="i" :key="item.id">
                        <template slot="title">
                            <i :class="item.icon"></i>
                            <span slot="title">{{ item.name }}</span>
                        </template>

                        <template v-for="subItem in item.children">
                            <el-submenu v-if="subItem.children" :index="subItem.id" :key="subItem.id">
                                <template slot="title">
                                  <i :class="subItem.icon"></i>
                                  {{ subItem.name }}
                                </template>
                                <el-menu-item v-for="(threeItem,i) in subItem.children" :key="threeItem.id" :index="threeItem.path">
                                    {{ threeItem.name }}
                                </el-menu-item>
                            </el-submenu>

                            <el-menu-item v-else :index="subItem.path" :key="subItem.id">
                                <i :class="subItem.icon"></i>{{ subItem.name }}
                            </el-menu-item>

                        </template>
                    </el-submenu>
                </template>

                <template v-else>
                    <el-menu-item :index="item.path" :key="item.id">
                        <i :class="item.icon"></i><span slot="title">{{ item.name }}</span>
                    </el-menu-item>
                </template>

            </template>
        </el-menu>
    </div>
</template>

<script>
    import bus from '../common/bus';
    export default {
        data() {
            return {
                collapse: false,
                items: [],
            }
        },
        computed:{
            onRoutes(){
                // return this.$route.path.replace('/','');
                return this.$route.path;
            }
        },
        created(){
            // 通过 Event Bus 进行组件间通信，来折叠侧边栏
            bus.$on('collapse', msg => {
                this.collapse = msg;
            })

            let itemMenu = JSON.parse(localStorage.getItem('role_menu'));
            //添加一个首页菜单
            let common = {
                'name': '系统首页',
                'path': '/page/dashboard',
                'icon': 'iconfont icon-Home',
            };
            itemMenu.unshift(common);

            this.items = itemMenu;


            console.log(this.items);
        },
        methods: {

        }

    }
</script>

<style scoped>
    .sidebar{
        display: block;
        position: absolute;
        left: 0;
        top: 70px;
        bottom:0;
        overflow-y: scroll;
    }
    .sidebar::-webkit-scrollbar{
        width: 0;
    }
    .sidebar-el-menu:not(.el-menu--collapse){
        width: 250px;
    }
    .sidebar > ul {
        height:100%;
    }
    .iconfont{
        margin-right: 10px;
    }
</style>
