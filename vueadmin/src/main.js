import Vue from 'vue';
import App from './App';
import router from './router';
import axios from 'axios';
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';    // 默认主题
// import $ from 'jquery';
import '../static/css/icon.css';
import "babel-polyfill";
import store from './store';
import {post_} from './components/js/request.js';
import * as cusFilter from './components/js/filters.js';

Vue.use(ElementUI, { size: 'small' });
Vue.prototype.$post_ = post_;

// 导出的是对象，可以直接通过key和value来获得过滤器的名和过滤器的方法
Object.keys(cusFilter).forEach(key => {
    Vue.filter(key, cusFilter[key])
})


//刷新的时候重新加载路由
let localRules = window.localStorage.getItem('rules');
if(localRules){
    localRules = JSON.parse(localRules);
    store.dispatch('GenerateRoutes',localRules).then(() => {
       router.addRoutes(store.getters.addRouters)
    })
}


//使用钩子函数对路由进行权限跳转
router.beforeEach((to, from, next) => {
    next();
})

new Vue({
    router,
    render: h => h(App)
}).$mount('#app');