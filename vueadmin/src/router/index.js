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
