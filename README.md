一个 yii2 + vue的 后台权限管理系统。
这个是一个简单的基础版，里面只有权限管理，不涉及其它的内容
非常适合新开始的项目。

前端代码 在vueadmin里面，框架用的是 vue-manage-system，但只用了他的架子 ，里面的内容都去了。
如果需要可以在github上找到这个项目。

## 安装步骤 ##

    cd vueadmin    // 进入模板目录
    npm install         // 安装项目依赖，等待安装完成之后

## 本地开发 ##

    // 开启服务器，浏览器访问 http://localhost:8080
    npm run dev

## 构建生产 ##

    // 执行构建命令，生成的dist文件夹放在服务器下即可访问
    npm run build    


后端用的是yii2，非常强大的框架，扩展性强。将域名指定apiadmin/web下，做为后台请求接口域名。
将web/data/v1.sql文件导入你建好的库中即可。

##效果图##
![Image text](https://raw.githubusercontent.com/FX336494/admin_v1/master/apiadmin/web/data/1.png)
![Image text](https://raw.githubusercontent.com/FX336494/admin_v1/master/apiadmin/web/data/2.png)
![Image text](https://raw.githubusercontent.com/FX336494/admin_v1/master/apiadmin/web/data/3.png)
![Image text](https://raw.githubusercontent.com/FX336494/admin_v1/master/apiadmin/web/data/4.png)
![Image text](https://raw.githubusercontent.com/FX336494/admin_v1/master/apiadmin/web/data/5.png)

## 赞赏
如果你觉得帮助到了你，可以请作者喝杯咖啡！ 微信号 mxf2252599083
![微信扫一扫](https://raw.githubusercontent.com/FX336494/admin_v1/master/apiadmin/web/data/6.png)