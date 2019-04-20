<template>
    <div class="container">
        <h2>选择图标</h2>
        <ul>
            <li class="icon-li" v-for="(item,index) in list" :key="index">
                <div class="icon-li-content" @click="select_icon(item)">
                    <i :class="` iconfont ${item}`"></i>
                    <span>{{item}}</span>
                </div>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        data: function(){
            return {
                keyword: '',
                iconList: [
                    'icon-ico_fenlei',
                    'icon-tianjiatupian',
                    'icon-sousuo',
                    'icon-xiugaimima',
                    'icon-upgrade',
                    'icon-tousu',
                    'icon-gonggao',
                    'icon-cengji',
                    'icon-ziyuan',
                    'icon-weixinzhifu1',
                    'icon-zhifubao',
                    'icon-zhifu',
                    'icon-jiazai1',
                    'icon-meiyoudingdan-01',
                    'icon-pingjia',
                    'icon-fenlei',
                    'icon-shezhi',
                    'icon-right',
                    'icon-dianpu',
                    'icon-gouxuan1',
                    'icon-Home',
                    'icon-Home',
                    'icon-gouwuche',
                    'icon-left',
                    'icon-tuichu',
                    'icon-jianshao_l',
                    'icon-loveit',
                ]
            }
        },
        computed: {
            list(){
                return this.iconList.filter((item) => {
                    return item.indexOf(this.keyword) !== -1;
                })
            }
        },
        methods:{
            select_icon(icon) {
                this.$emit('selectIcon','iconfont '+icon);
            }
        }
    }
</script>

<style scoped>

.container{
    position: fixed;
    top: 20%;
    left: 15%;
    width: 70%;
    bottom: 10%;
    /*background: #f0f0f0;*/
    z-index: 999999;
    border:1px solid gray;
    overflow-y: auto;
}
.container ul{
    background: #fff;
}

ul,li{
    list-style: none;
}
.icon-li{
    display: inline-block;
    padding: 10px;
    width: 120px;
    height: 120px;
}
.icon-li-content{
    display: flex;
    height: 100%;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}
.icon-li-content i{
    font-size: 36px;
    color: #606266;
}
.icon-li-content span{
    margin-top: 10px;
    color: #787878;
}
</style>