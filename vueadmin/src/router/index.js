import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

export const constantRouterMap = [
    {
        path: '/',
        redirect: '/login'
    },
    {
        path: '/login',
        component: resolve => require(['../page/Login.vue'], resolve)
    },

    {
        path: '/403',
        component: resolve => require(['../page/403.vue'], resolve),
        meta: { title: '403' }
    }, 
    {
        path: '/404',
        component: resolve => require(['../page/404.vue'], resolve),
        meta: { title: '404' }
    },  
    {
        path: '/common/Home.vue',
        component: resolve => require(['../components/common/Home.vue'], resolve),
        meta: { title: '自述文件' },        
    },
];

export default new Router({
  scrollBehavior: () => ({ y: 0 }),
  routes: constantRouterMap
});



export const asyncRouterMap = [  
    //用户权限相关  
    {
        path: '/page/auth/menu_list',
        component: resolve => require(['../page/auth/menu_list.vue'], resolve),
        meta: { title: '菜单管理' },
        index:'menu_list',
        name: '菜单管理',        
    },     
    {
        path: '/page/auth/user_list',
        component: resolve => require(['../page/auth/user_list.vue'], resolve),
        meta: { title: '用户列表' },
        index:'user_list',
        name: '用户列表',        
    },
    {
        path: '/page/auth/role_list',
        component: resolve => require(['../page/auth/role_list.vue'], resolve),
        meta: { title: '角色列表' },
        index:'role_list',
        name: '角色列表',        
    },    


    //商品管理相关
    {
        path: '/page/goods/class_list',
        component: resolve => require(['../page/goods/class_list.vue'], resolve),
        meta: { title: '分类管理' },
        index:'role_list',
        name: '分类管理',        
    },           

];