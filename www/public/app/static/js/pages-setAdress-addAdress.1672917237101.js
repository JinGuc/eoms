(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-setAdress-addAdress"],{3703:function(e,n,r){"use strict";r("7a82"),Object.defineProperty(n,"__esModule",{value:!0}),n.default=void 0;var t={data:function(){return{form:{name:"",address:""},errorType:["border-bottom"],rules:{name:[{required:!0,message:"请输入名称",trigger:["change","blur"]}],address:[{required:!0,message:"请输入地址",trigger:["change","blur"]}]}}},methods:{submit:function(){var e=this;uni.showLoading({title:"保存中，请稍后",mask:!0}),this.$refs.uForm.validate((function(n){if(n)if(window&&window.jgObj){var r=window.jgObj.addHost(e.form.name,e.form.address);1==r?(uni.showToast({icon:"none",title:"添加成功",duration:2e3}),uni.$emit("freshData",{type:"add"}),uni.navigateBack({delta:1})):uni.showToast({icon:"none",title:"添加失败，请确认地址格式",duration:2e3})}else uni.showToast({icon:"none",title:"添加失败，请联系管理员",duration:2e3});else alert("验证失败")})),uni.hideLoading()}},onReady:function(){this.$refs.uForm.setRules(this.rules)}};n.default=t},"89e9":function(e,n,r){"use strict";r.d(n,"b",(function(){return a})),r.d(n,"c",(function(){return o})),r.d(n,"a",(function(){return t}));var t={uForm:r("f3d7").default,uFormItem:r("cc6c").default,uInput:r("76d6").default,uButton:r("0a9d").default},a=function(){var e=this,n=e.$createElement,r=e._self._c||n;return r("v-uni-view",{staticClass:"menu-box"},[r("u-form",{ref:"uForm",attrs:{model:e.form,"error-type":e.errorType}},[r("u-form-item",{attrs:{label:"名 称","label-width":"120",prop:"name"}},[r("u-input",{model:{value:e.form.name,callback:function(n){e.$set(e.form,"name",n)},expression:"form.name"}})],1),r("u-form-item",{attrs:{label:"地 址","label-width":"120",prop:"adress"}},[r("u-input",{model:{value:e.form.address,callback:function(n){e.$set(e.form,"address",n)},expression:"form.address"}})],1),r("v-uni-view",{staticClass:"sub-btn"},[r("u-button",{staticClass:"custom-style",attrs:{ripple:!0,"ripple-bg-color":"#6f6fed",shape:"square"},on:{click:function(n){arguments[0]=n=e.$handleEvent(n),e.submit.apply(void 0,arguments)}}},[e._v("保存")])],1)],1)],1)},o=[]},9756:function(e,n,r){"use strict";r.r(n);var t=r("89e9"),a=r("e7fe");for(var o in a)["default"].indexOf(o)<0&&function(e){r.d(n,e,(function(){return a[e]}))}(o);r("fab3");var s=r("f0c5"),u=Object(s["a"])(a["default"],t["b"],t["c"],!1,null,"59cbadfa",null,!1,t["a"],void 0);n["default"]=u.exports},"9ad3":function(e,n,r){var t=r("d4f4");t.__esModule&&(t=t.default),"string"===typeof t&&(t=[[e.i,t,""]]),t.locals&&(e.exports=t.locals);var a=r("4f06").default;a("7b7aeb45",t,!0,{sourceMap:!1,shadowMode:!1})},d4f4:function(e,n,r){var t=r("24fb");n=t(!1),n.push([e.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* uni.scss */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */.menu-box[data-v-59cbadfa]{width:100vw;height:100vh;background-color:#f4f4f4;padding:%?20?% %?50?%}.menu-box .sub-btn .custom-style[data-v-59cbadfa]{margin-top:%?40?%;background-color:#5e5ec6;color:#fff}',""]),e.exports=n},e7fe:function(e,n,r){"use strict";r.r(n);var t=r("3703"),a=r.n(t);for(var o in t)["default"].indexOf(o)<0&&function(e){r.d(n,e,(function(){return t[e]}))}(o);n["default"]=a.a},fab3:function(e,n,r){"use strict";var t=r("9ad3"),a=r.n(t);a.a}}]);