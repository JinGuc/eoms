(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-servicesmanage-fuwuxiangqing-Esxiangqing"],{"0ac1":function(t,e,i){"use strict";i("7a82"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0,i("a9e3");var n={name:"u-card",props:{full:{type:Boolean,default:!1},title:{type:String,default:""},titleColor:{type:String,default:"#303133"},titleSize:{type:[Number,String],default:"30"},subTitle:{type:String,default:""},subTitleColor:{type:String,default:"#909399"},subTitleSize:{type:[Number,String],default:"26"},border:{type:Boolean,default:!0},index:{type:[Number,String,Object],default:""},margin:{type:String,default:"0rpx 0rpx 15rpx 0rpx"},borderRadius:{type:[Number,String],default:"16"},headStyle:{type:Object,default:function(){return{}}},bodyStyle:{type:Object,default:function(){return{}}},footStyle:{type:Object,default:function(){return{}}},headBorderBottom:{type:Boolean,default:!0},footBorderTop:{type:Boolean,default:!0},thumb:{type:String,default:""},thumbWidth:{type:[String,Number],default:"40"},thumbCircle:{type:Boolean,default:!1},padding:{type:[String,Number],default:"30"},showHead:{type:Boolean,default:!0},showFoot:{type:Boolean,default:!0},boxShadow:{type:String,default:"none"}},data:function(){return{}},methods:{click:function(){this.$emit("click",this.index)},headClick:function(){this.$emit("head-click",this.index)},bodyClick:function(){this.$emit("body-click",this.index)},footClick:function(){this.$emit("foot-click",this.index)},subTitleClick:function(){this.$emit("subtitle-click",this.index)}}};e.default=n},"140b":function(t,e,i){"use strict";i("7a82"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;e.default={data:function(){return{name:"",hostId:"",roleId:"",serviceInfo:{}}},methods:{hostseverInfo:function(){var t=this,e={hostId:this.hostId,roleId:this.roleId};this.capis.hostseverInfo(e,{}).then((function(e){"success"==e.status&&(t.serviceInfo=e.res.data)}))}},created:function(){this.hostseverInfo()},onLoad:function(t){this.hostId=t.hostId,this.roleId=t.roleId,this.name=t.name}}},2032:function(t,e,i){"use strict";i.d(e,"b",(function(){return a})),i.d(e,"c",(function(){return r})),i.d(e,"a",(function(){return n}));var n={uButton:i("daed").default},a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{staticClass:"u-card",class:{"u-border":t.border,"u-card-full":t.full,"u-card--border":t.borderRadius>0},style:{borderRadius:t.borderRadius+"rpx",margin:t.margin,boxShadow:t.boxShadow},on:{click:function(e){e.stopPropagation(),arguments[0]=e=t.$handleEvent(e),t.click.apply(void 0,arguments)}}},[t.showHead?i("v-uni-view",{staticClass:"u-card__head",class:{"u-border-bottom":t.headBorderBottom},style:[{padding:t.padding+"rpx"},t.headStyle],on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.headClick.apply(void 0,arguments)}}},[t.$slots.head?t._t("head"):i("v-uni-view",{staticClass:"u-flex u-row-between"},[t.title?i("v-uni-view",{staticClass:"u-card__head--left u-flex u-line-1"},[t.thumb?i("v-uni-image",{staticClass:"u-card__head--left__thumb",style:{height:t.thumbWidth+"rpx",width:t.thumbWidth+"rpx",borderRadius:t.thumbCircle?"100rpx":"6rpx"},attrs:{src:t.thumb,mode:"aspectfull"}}):t._e(),i("v-uni-text",{staticClass:"u-card__head--left__title u-line-1",style:{fontSize:t.titleSize+"rpx",color:t.titleColor}},[t._v(t._s(t.title))])],1):t._e(),t.subTitle?i("v-uni-view",{staticClass:"u-card__head--right u-line-1"},[i("u-button",{attrs:{type:"success",size:"mini",plain:!0},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.subTitleClick.apply(void 0,arguments)}}},[t._v(t._s(t.subTitle))])],1):t._e()],1)],2):t._e(),i("v-uni-view",{staticClass:"u-card__body",style:[{padding:t.padding+"rpx"},t.bodyStyle],on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.bodyClick.apply(void 0,arguments)}}},[t._t("body")],2),t.showFoot?i("v-uni-view",{staticClass:"u-card__foot",class:{"u-border-top":t.footBorderTop},style:[{padding:t.$slots.foot?t.padding+"rpx":0},t.footStyle],on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.footClick.apply(void 0,arguments)}}},[t._t("foot")],2):t._e()],1)},r=[]},"2e1f":function(t,e,i){var n=i("b86c");n.__esModule&&(n=n.default),"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var a=i("4f06").default;a("4a751588",n,!0,{sourceMap:!1,shadowMode:!1})},4595:function(t,e,i){"use strict";var n=i("841d"),a=i.n(n);a.a},4771:function(t,e,i){var n=i("24fb");e=n(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* uni.scss */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */.u-card[data-v-56b10954]{position:relative;overflow:hidden;font-size:%?28?%;background-color:#fff;box-sizing:border-box}.u-card-full[data-v-56b10954]{margin-left:0!important;margin-right:0!important}.u-card--border[data-v-56b10954]:after{border-radius:%?16?%}.u-card__head--left[data-v-56b10954]{color:#303133}.u-card__head--left__thumb[data-v-56b10954]{margin-right:%?16?%}.u-card__head--left__title[data-v-56b10954]{max-width:%?400?%}.u-card__head--right[data-v-56b10954]{color:#909399;margin-left:%?6?%}.u-card__body[data-v-56b10954]{color:#606266}.u-card__foot[data-v-56b10954]{color:#909399}',""]),t.exports=e},"72ad":function(t,e,i){"use strict";i.r(e);var n=i("f46a"),a=i("d7b9");for(var r in a)["default"].indexOf(r)<0&&function(t){i.d(e,t,(function(){return a[t]}))}(r);i("850f");var o=i("f0c5"),u=Object(o["a"])(a["default"],n["b"],n["c"],!1,null,"42dd505c",null,!1,n["a"],void 0);e["default"]=u.exports},"841d":function(t,e,i){var n=i("4771");n.__esModule&&(n=n.default),"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var a=i("4f06").default;a("2fe58570",n,!0,{sourceMap:!1,shadowMode:!1})},"850f":function(t,e,i){"use strict";var n=i("2e1f"),a=i.n(n);a.a},b86c:function(t,e,i){var n=i("24fb");e=n(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* uni.scss */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */.bg[data-v-42dd505c]{width:100vw;height:100vh;background-color:#f6f6f6;padding:%?30?%}.bg .u-card-wrap[data-v-42dd505c]{background-color:#f3f4f6;padding:1px}.bg .u-body-item[data-v-42dd505c]{font-size:%?24?%;color:#333;padding:%?20?% %?10?%}.bg .u-body-item .label-title[data-v-42dd505c]{text-align:right;font-weight:600;width:%?166?%}.bg .u-body-item .neirong[data-v-42dd505c]{text-align:left;width:%?500?%}.bg .u-body-item .weiyuansu span[data-v-42dd505c]:not(:last-child)::after{padding:0 %?2?%;color:#333;content:"/"}',""]),t.exports=e},d7b9:function(t,e,i){"use strict";i.r(e);var n=i("140b"),a=i.n(n);for(var r in n)["default"].indexOf(r)<0&&function(t){i.d(e,t,(function(){return n[t]}))}(r);e["default"]=a.a},f46a:function(t,e,i){"use strict";i.d(e,"b",(function(){return a})),i.d(e,"c",(function(){return r})),i.d(e,"a",(function(){return n}));var n={uCard:i("f601").default},a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{staticClass:"bg"},[i("u-card",{attrs:{title:t.name,thumb:"../../../static/jiekou_name.png"}},[i("v-uni-view",{attrs:{slot:"body"},slot:"body"},[i("v-uni-view",{staticClass:"u-body-item u-flex u-col-between u-p-t-0"},[i("v-uni-view",{staticClass:"u-body-item-title u-line-2 label-title"},[t._v("CPU使用率：")]),i("v-uni-view",{staticClass:"u-body-item-title u-line-2 neirong"},[t._v(t._s(t.serviceInfo.cpu_use)+"%")])],1),i("v-uni-view",{staticClass:"u-body-item u-flex u-col-between u-p-t-0"},[i("v-uni-view",{staticClass:"u-body-item-title u-line-2 label-title"},[t._v("内存使用率：")]),i("v-uni-view",{staticClass:"u-body-item-title u-line-2 neirong"},[t._v(t._s(t.serviceInfo.memory_use)+"%")])],1),i("v-uni-view",{staticClass:"u-body-item u-flex u-col-between u-p-t-0"},[i("v-uni-view",{staticClass:"u-body-item-title u-line-2 label-title"},[t._v("连接数：")]),i("v-uni-view",{staticClass:"u-body-item-title u-line-2 neirong"},[t._v(t._s(t.serviceInfo.connect))])],1),i("v-uni-view",{staticClass:"u-body-item u-flex u-col-between u-p-t-0"},[i("v-uni-view",{staticClass:"u-body-item-title u-line-2 label-title"},[t._v("运行时间：")]),i("v-uni-view",{staticClass:"u-body-item-title u-line-2 neirong"},[t._v(t._s(t.serviceInfo.runtime))])],1)],1)],1)],1)},r=[]},f601:function(t,e,i){"use strict";i.r(e);var n=i("2032"),a=i("fd78");for(var r in a)["default"].indexOf(r)<0&&function(t){i.d(e,t,(function(){return a[t]}))}(r);i("4595");var o=i("f0c5"),u=Object(o["a"])(a["default"],n["b"],n["c"],!1,null,"56b10954",null,!1,n["a"],void 0);e["default"]=u.exports},fd78:function(t,e,i){"use strict";i.r(e);var n=i("0ac1"),a=i.n(n);for(var r in n)["default"].indexOf(r)<0&&function(t){i.d(e,t,(function(){return n[t]}))}(r);e["default"]=a.a}}]);