(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-setAdress-editAdress"],{2168:function(e,n,o){"use strict";o.d(n,"b",(function(){return r})),o.d(n,"c",(function(){return s})),o.d(n,"a",(function(){return t}));var t={uForm:o("f3d7").default,uFormItem:o("cc6c").default,uInput:o("76d6").default,uButton:o("0a9d").default},r=function(){var e=this,n=e.$createElement,o=e._self._c||n;return o("v-uni-view",{staticClass:"menu-box"},[o("u-form",{ref:"uForm",attrs:{model:e.form,"error-type":e.errorType}},[o("u-form-item",{attrs:{label:"名 称","label-width":"120",prop:"name"}},[o("u-input",{model:{value:e.form.name,callback:function(n){e.$set(e.form,"name",n)},expression:"form.name"}})],1),o("u-form-item",{attrs:{label:"地 址","label-width":"120",prop:"adress"}},[o("u-input",{model:{value:e.form.address,callback:function(n){e.$set(e.form,"address",n)},expression:"form.address"}})],1),o("v-uni-view",{staticClass:"sub-btn"},[o("u-button",{staticClass:"custom-style",attrs:{ripple:!0,"ripple-bg-color":"#6f6fed",shape:"square"},on:{click:function(n){arguments[0]=n=e.$handleEvent(n),e.submit.apply(void 0,arguments)}}},[e._v("保存")])],1)],1)],1)},s=[]},"3a87":function(e,n,o){"use strict";var t=o("a62c"),r=o.n(t);r.a},"4a12":function(e,n,o){"use strict";o.r(n);var t=o("2168"),r=o("aa07");for(var s in r)["default"].indexOf(s)<0&&function(e){o.d(n,e,(function(){return r[e]}))}(s);o("3a87");var a=o("f0c5"),i=Object(a["a"])(r["default"],t["b"],t["c"],!1,null,"e92b546a",null,!1,t["a"],void 0);n["default"]=i.exports},"4d29":function(e,n,o){var t=o("24fb");n=t(!1),n.push([e.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* uni.scss */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */.menu-box[data-v-e92b546a]{width:100vw;height:100vh;background-color:#f4f4f4;padding:%?20?% %?50?%}.menu-box .sub-btn .custom-style[data-v-e92b546a]{margin-top:%?40?%;background-color:#5e5ec6;color:#fff}',""]),e.exports=n},a62c:function(e,n,o){var t=o("4d29");t.__esModule&&(t=t.default),"string"===typeof t&&(t=[[e.i,t,""]]),t.locals&&(e.exports=t.locals);var r=o("4f06").default;r("232fbb92",t,!0,{sourceMap:!1,shadowMode:!1})},aa07:function(e,n,o){"use strict";o.r(n);var t=o("e3b5"),r=o.n(t);for(var s in t)["default"].indexOf(s)<0&&function(e){o.d(n,e,(function(){return t[e]}))}(s);n["default"]=r.a},e3b5:function(e,n,o){"use strict";o("7a82"),Object.defineProperty(n,"__esModule",{value:!0}),n.default=void 0,o("e25e");var t={data:function(){return{id:"",choose:!1,form:{name:"",address:""},errorType:["border-bottom"],rules:{name:[{required:!0,message:"请输入名称",trigger:["change","blur"]}],address:[{required:!0,message:"请输入地址",trigger:["change","blur"]}]}}},methods:{submit:function(){var e=this;uni.showLoading({title:"保存中，请稍后",mask:!0}),this.$refs.uForm.validate((function(n){if(n){if(window&&window.jgObj){var o=window.jgObj.setHost(parseInt(e.id),e.form.name,e.form.address);1==o&&(uni.showToast({icon:"none",title:"修改成功",duration:2e3}),console.log("65",e.choose),e.choose?(console.log(67,e.choose),e.changeHost(e.form.address),console.log(69,e.choose)):(console.log(73),uni.$emit("freshData",{type:"update"}),uni.navigateBack({delta:1})))}}else alert("验证失败")})),uni.hideLoading()},changeHost:function(e){if(console.log("107",e),window&&window.jgObj){var n=window.jgObj.changeHost(e);1==n?uni.showToast({icon:"none",title:"切换成功",duration:2e3}):(console.log(120),uni.showToast({icon:"none",title:"切换失败，请重试",duration:2e3}))}else uni.showToast({icon:"none",title:"切换失败，请重试",duration:2e3})}},onReady:function(){this.$refs.uForm.setRules(this.rules)},onLoad:function(e){this.id=e.id,this.choose=e.choose,this.form.name=e.name,this.form.address=e.address}};n.default=t}}]);