(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-serviceSettingAgain-serviceSettingAgain"],{1039:function(r,t,e){var n=e("24fb");t=n(!1),t.push([r.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* uni.scss */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */.wrap[data-v-bced207e]{padding:0 %?50?%}',""]),r.exports=t},"115c":function(r,t,e){"use strict";e.r(t);var n=e("54c8"),o=e("d092");for(var i in o)["default"].indexOf(i)<0&&function(r){e.d(t,r,(function(){return o[r]}))}(i);e("71d0");var u=e("f0c5"),a=Object(u["a"])(o["default"],n["b"],n["c"],!1,null,"bced207e",null,!1,n["a"],void 0);t["default"]=a.exports},"54c8":function(r,t,e){"use strict";e.d(t,"b",(function(){return o})),e.d(t,"c",(function(){return i})),e.d(t,"a",(function(){return n}));var n={uForm:e("8ae9").default,uFormItem:e("17f3").default,uInput:e("2b3d2").default,uButton:e("daed").default},o=function(){var r=this,t=r.$createElement,e=r._self._c||t;return e("v-uni-view",{staticClass:"wrap"},[e("u-form",{ref:"uForm",attrs:{model:r.form,"error-type":r.errorType}},[e("u-form-item",{attrs:{label:"服务器IP",prop:"ip","label-width":"150"}},[e("u-input",{model:{value:r.form.ip,callback:function(t){r.$set(r.form,"ip",t)},expression:"form.ip"}})],1),e("u-form-item",{attrs:{label:"服务器端口",prop:"port","label-width":"150"}},[e("u-input",{model:{value:r.form.port,callback:function(t){r.$set(r.form,"port",t)},expression:"form.port"}})],1)],1),e("u-button",{attrs:{type:"primary"},on:{click:function(t){arguments[0]=t=r.$handleEvent(t),r.submit.apply(void 0,arguments)}}},[r._v("提交")])],1)},i=[]},"71d0":function(r,t,e){"use strict";var n=e("946e"),o=e.n(n);o.a},8142:function(r,t,e){"use strict";e("7a82"),Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0,e("fb6a");var n={data:function(){return{form:{ip:"",port:""},errorType:["toast"],rules:{ip:[{required:!0,message:"请输入IP",trigger:["blur","change"]},{pattern:/^((2[0-4]\d|25[0-5]|[01]?\d\d?)\.){3}(2[0-4]\d|25[0-5]|[01]?\d\d?)$/,transform:function(r){return String(r)},message:"IP格式不正确"}],port:[{required:!0,message:"请输入端口",trigger:["blur","change"]}]}}},created:function(){this.getIp()},methods:{getIp:function(){var r=this;uni.getStorage({key:"service_ip",success:function(t){var e=t.data;e=e.slice(7),r.form.ip=e.split(":")[0],r.form.port=e.split(":")[1]}})},submit:function(){var r=this;this.$refs.uForm.validate((function(t){t?uni.setStorage({key:"service_ip",data:"http://"+r.form.ip+":"+r.form.port,success:function(){try{uni.removeStorageSync("username")}catch(r){}try{uni.removeStorageSync("password")}catch(r){}try{uni.removeStorageSync("user_id")}catch(r){}try{uni.removeStorageSync("phone_number")}catch(r){}plus.runtime.restart(),uni.redirectTo({url:"/pages/login/login"})}}):console.log("验证失败")}))}},onReady:function(){this.$refs.uForm.setRules(this.rules)}};t.default=n},"946e":function(r,t,e){var n=e("1039");n.__esModule&&(n=n.default),"string"===typeof n&&(n=[[r.i,n,""]]),n.locals&&(r.exports=n.locals);var o=e("4f06").default;o("33c007e2",n,!0,{sourceMap:!1,shadowMode:!1})},d092:function(r,t,e){"use strict";e.r(t);var n=e("8142"),o=e.n(n);for(var i in n)["default"].indexOf(i)<0&&function(r){e.d(t,r,(function(){return n[r]}))}(i);t["default"]=o.a}}]);