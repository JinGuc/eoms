(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-devices-devices"],{4295:function(t,e,o){"use strict";o.r(e);var a=o("97e3"),i=o.n(a);for(var n in a)["default"].indexOf(n)<0&&function(t){o.d(e,t,(function(){return a[t]}))}(n);e["default"]=i.a},7212:function(t,e,o){var a=o("d383");a.__esModule&&(a=a.default),"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var i=o("4f06").default;i("7c96dd1c",a,!0,{sourceMap:!1,shadowMode:!1})},"97e3":function(t,e,o){"use strict";o("7a82"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0,o("a9e3");var a={name:"u-tag",props:{type:{type:String,default:"primary"},disabled:{type:[Boolean,String],default:!1},size:{type:String,default:"default"},shape:{type:String,default:"square"},text:{type:[String,Number],default:""},bgColor:{type:String,default:""},color:{type:String,default:""},borderColor:{type:String,default:""},closeColor:{type:String,default:""},index:{type:[Number,String],default:""},mode:{type:String,default:"light"},closeable:{type:Boolean,default:!1},show:{type:Boolean,default:!0}},data:function(){return{}},computed:{customStyle:function(){var t={};return this.color&&(t.color=this.color),this.bgColor&&(t.backgroundColor=this.bgColor),"plain"==this.mode&&this.color&&!this.borderColor?t.borderColor=this.color:t.borderColor=this.borderColor,t},iconStyle:function(){if(this.closeable){var t={};return"mini"==this.size?t.fontSize="20rpx":t.fontSize="22rpx","plain"==this.mode||"light"==this.mode?t.color=this.type:"dark"==this.mode&&(t.color="#ffffff"),this.closeColor&&(t.color=this.closeColor),t}},closeIconColor:function(){return this.closeColor?this.closeColor:this.color?this.color:"dark"==this.mode?"#ffffff":this.type}},methods:{clickTag:function(){this.disabled||this.$emit("click",this.index)},close:function(){this.$emit("close",this.index)}}};e.default=a},a0f9:function(t,e,o){"use strict";var a=o("ddd2"),i=o.n(a);i.a},b5da:function(t,e,o){"use strict";o("7a82"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0,o("14d9"),o("d3b7"),o("159b");var a={data:function(){return{list:[],progressColors:{success:"#67C23A",warning:"#E6A23C",danger:"#F56C6C"}}},methods:{getEnableServices:function(){var t=this;this.capis.getEnableServices({},{}).then((function(e){if("success"==e.status){for(var o=0;o<e.res.data.length;o++){var a=e.res.data[o];a.time=a.time>0?t.ctool.getYMDHMS(a.time):"-",t.list.push(a)}t.list=e.res.data}}))},getProgressColor:function(t){return t<=70?this.progressColors.success:t>70&&t<=90?this.progressColors.warning:this.progressColors.danger},getLoadClass:function(t){return t<=5?"succ":t>5&&t<=10?"warn":"dang"},onPullDownRefresh:function(){this.getEnableServices(),uni.stopPullDownRefresh()},goTo:function(t,e){"services"==t&&uni.navigateTo({url:"/pages/servicesmanage/services?hostId="+e})},initWs:function(){var t=this;this.$LaravelEcho.instance.channel("HostStatus").listen("Snmp.HostEvent",(function(e){"status"==e.type?t.list&&t.list.length>0&&t.list.forEach((function(o,a){o.id==e.host_id&&(t.list[a].running=e.data.running,t.list[a].time=e.time>0?t.ctool.getYMDHMS(e.time):"-")})):"HostAdd"!=e.type&&"HostDel"!=e.type&&"HostChange"!=e.type||t.getEnableServices()})),this.$LaravelEcho.instance.channel("ServerInfo").listen("Snmp.ServerInfoEvent",(function(e){"status"==e.type?t.list&&t.list.length>0&&t.list.forEach((function(o,a){o.id==e.host_id&&t.list[a].role.forEach((function(o,i){t.list[a].role[i].roleId==e.data.roleId&&(t.list[a].role[i].running=e.data.running)}))})):"roleId"!=e.type&&"HostDel"!=e.type&&"HostChange"!=e.type||t.getEnableServices()})),this.$LaravelEcho.instance.channel("SysInfo").listen("Snmp.SysInfoEvent",(function(e){t.list.length>0&&("CpuInfo"==e.type?t.list.forEach((function(o,a){o.id==e.host_id&&(t.list[a].cpuUse=e.data.cpuUse,t.list[a].cpuLoad1=e.data.cpuLoad1,t.list[a].cpuLoad5=e.data.cpuLoad5,t.list[a].cpuLoad15=e.data.cpuLoad15,t.list[a].time=e.time>0?t.ctool.getYMDHMS(e.time):"-")})):"MemoryInfo"==e.type?t.list.forEach((function(o,a){o.id==e.host_id&&(t.list[a].memUsedPercent=e.data.memUsedPercent,t.list[a].time=e.time>0?t.ctool.getYMDHMS(e.time):"-")})):"ifInfo"==e.type?t.list.forEach((function(o,a){o.id==e.host_id&&(t.list[a].netSpeed.in.size=e.data.escape.in.size,t.list[a].netSpeed.in.format=e.data.escape.in.format,t.list[a].netSpeed.out.size=e.data.escape.out.size,t.list[a].netSpeed.out.format=e.data.escape.out.format,t.list[a].time=e.time>0?t.ctool.getYMDHMS(e.time):"-")})):"StorageInfo"==e.type&&t.list.forEach((function(o,a){o.id==e.host_id&&(t.list[a].storageInfo=e.data,t.list[a].time=e.time>0?t.ctool.getYMDHMS(e.time):"-")})))})),this.$LaravelEcho.instance.channel("SysWarning").listen("Snmp.SysWarningEvent",(function(e){t.list.length>0&&"storage"==e.type&&t.list.forEach((function(o,a){o.id==e.host_id&&(t.list[a].storageWarning=e.data.warning,t.list[a].time=e.time>0?t.ctool.getYMDHMS(e.time):"-")}))}))}},created:function(){var t=this;this.getEnableServices(),this.$nextTick((function(){t.initWs()}))},onHide:function(){this.$LaravelEcho.instance.leaveChannel("SysInfo"),this.$LaravelEcho.instance.leaveChannel("HostStatus"),this.$LaravelEcho.instance.leaveChannel("ServerInfo"),this.$LaravelEcho.instance.leaveChannel("SysWarning")},onShow:function(){this.initWs()}};e.default=a},bb73:function(t,e,o){"use strict";var a=o("7212"),i=o.n(a);i.a},be85:function(t,e,o){"use strict";o.r(e);var a=o("f20d"),i=o("4295");for(var n in i)["default"].indexOf(n)<0&&function(t){o.d(e,t,(function(){return i[t]}))}(n);o("bb73");var s=o("f0c5"),r=Object(s["a"])(i["default"],a["b"],a["c"],!1,null,"0cf9d257",null,!1,a["a"],void 0);e["default"]=r.exports},c488:function(t,e,o){"use strict";o.r(e);var a=o("f10b"),i=o("c8e4");for(var n in i)["default"].indexOf(n)<0&&function(t){o.d(e,t,(function(){return i[t]}))}(n);o("a0f9");var s=o("f0c5"),r=Object(s["a"])(i["default"],a["b"],a["c"],!1,null,"2a309746",null,!1,a["a"],void 0);e["default"]=r.exports},c8e4:function(t,e,o){"use strict";o.r(e);var a=o("b5da"),i=o.n(a);for(var n in a)["default"].indexOf(n)<0&&function(t){o.d(e,t,(function(){return a[t]}))}(n);e["default"]=i.a},d383:function(t,e,o){var a=o("24fb");e=a(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* uni.scss */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */.u-tag[data-v-0cf9d257]{box-sizing:border-box;align-items:center;border-radius:%?6?%;display:inline-block;line-height:1}.u-size-default[data-v-0cf9d257]{font-size:%?22?%;padding:%?12?% %?22?%}.u-size-mini[data-v-0cf9d257]{font-size:%?20?%;padding:%?6?% %?12?%}.u-mode-light-primary[data-v-0cf9d257]{background-color:#ecf5ff;color:#2979ff;border:1px solid #a0cfff}.u-mode-light-success[data-v-0cf9d257]{background-color:#dbf1e1;color:#19be6b;border:1px solid #71d5a1}.u-mode-light-error[data-v-0cf9d257]{background-color:#fef0f0;color:#fa3534;border:1px solid #fab6b6}.u-mode-light-warning[data-v-0cf9d257]{background-color:#fdf6ec;color:#f90;border:1px solid #fcbd71}.u-mode-light-info[data-v-0cf9d257]{background-color:#f4f4f5;color:#909399;border:1px solid #c8c9cc}.u-mode-dark-primary[data-v-0cf9d257]{background-color:#2979ff;color:#fff}.u-mode-dark-success[data-v-0cf9d257]{background-color:#19be6b;color:#fff}.u-mode-dark-error[data-v-0cf9d257]{background-color:#fa3534;color:#fff}.u-mode-dark-warning[data-v-0cf9d257]{background-color:#f90;color:#fff}.u-mode-dark-info[data-v-0cf9d257]{background-color:#909399;color:#fff}.u-mode-plain-primary[data-v-0cf9d257]{background-color:#fff;color:#2979ff;border:1px solid #2979ff}.u-mode-plain-success[data-v-0cf9d257]{background-color:#fff;color:#19be6b;border:1px solid #19be6b}.u-mode-plain-error[data-v-0cf9d257]{background-color:#fff;color:#fa3534;border:1px solid #fa3534}.u-mode-plain-warning[data-v-0cf9d257]{background-color:#fff;color:#f90;border:1px solid #f90}.u-mode-plain-info[data-v-0cf9d257]{background-color:#fff;color:#909399;border:1px solid #909399}.u-disabled[data-v-0cf9d257]{opacity:.55}.u-shape-circle[data-v-0cf9d257]{border-radius:%?100?%}.u-shape-circleRight[data-v-0cf9d257]{border-radius:0 %?100?% %?100?% 0}.u-shape-circleLeft[data-v-0cf9d257]{border-radius:%?100?% 0 0 %?100?%}.u-close-icon[data-v-0cf9d257]{margin-left:%?14?%;font-size:%?22?%;color:#19be6b}.u-icon-wrap[data-v-0cf9d257]{display:inline-flex;-webkit-transform:scale(.86);transform:scale(.86)}',""]),t.exports=e},ddd2:function(t,e,o){var a=o("e025");a.__esModule&&(a=a.default),"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var i=o("4f06").default;i("12c44fea",a,!0,{sourceMap:!1,shadowMode:!1})},e025:function(t,e,o){var a=o("24fb");e=a(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* uni.scss */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */.services-list[data-v-2a309746]{background-color:#f4f4f4;height:100vh}.services-list .service-item .service-title[data-v-2a309746]{display:flex;font-size:%?20?%;font-weight:600}.services-list .service-item .service-title .service-border[data-v-2a309746]{width:%?10?%;height:%?26?%;background-color:#5e5ec6;border-radius:%?5?%}.services-list .service-item .service-title .service-tit[data-v-2a309746]{margin-left:%?20?%}.services-list .service-item .service-content[data-v-2a309746]{padding:%?24?%;display:flex;align-items:stretch;flex-wrap:wrap}.services-list .service-item .service-content .box-card .box-info .header-info[data-v-2a309746]{width:20%;display:flex;flex-direction:column;justify-content:center}.services-list .service-item .service-content .box-card .box-info .body-info[data-v-2a309746]{width:100%}.box-card[data-v-2a309746]{margin:5px;display:inline-block;box-sizing:border-box}.box-card .box-info[data-v-2a309746]{display:flex;align-items:stretch;box-sizing:border-box;font-size:12px}.box-card .box-info .header-info[data-v-2a309746]{text-align:center}.box-card .box-info .header-info .hostIcon[data-v-2a309746]{display:flex;justify-content:center;align-items:center}.box-card .box-info .header-info ul[data-v-2a309746]{list-style:none;padding:%?10?%;margin:0}.box-card .box-info .header-info ul li[data-v-2a309746]{display:flex;justify-content:center;align-items:center;margin-top:5px}.box-card .box-info .status-dot[data-v-2a309746]{padding-top:10px}.box-card .box-info .body-info[data-v-2a309746]{border-left:1px solid #f6f6f6;margin:0 0 0 10px;padding:0 0 0 10px;width:calc(100% - 100px)}.box-card .box-info .body-info .hostName[data-v-2a309746]{margin-bottom:10px;font-size:16px;font-weight:700;overflow:hidden;text-overflow:ellipsis;flex:1;white-space:nowrap}.box-card .box-info .body-info .item.no-margin[data-v-2a309746]{margin-top:0}.box-card .box-info .body-info .item[data-v-2a309746]{display:flex;align-items:center;margin-top:8px}.box-card .box-info .body-info .item.nowrap[data-v-2a309746]{white-space:nowrap}.box-card .box-info .body-info .item .cont[data-v-2a309746]{flex-grow:1;margin-left:10px}.box-card .box-info .body-info .item .cont.load span[data-v-2a309746]{padding:0 10px;border-left:1px solid #eee;white-space:nowrap}.box-card .box-info .body-info .item .cont.load span[data-v-2a309746]:first-child{border-left:none}.box-card .box-info .body-info .item .cont.load .succ[data-v-2a309746]{color:#909399}.box-card .box-info .body-info .item .cont.load .warn[data-v-2a309746]{color:#e6a23c}.box-card .box-info .body-info .item .cont.load .dang[data-v-2a309746]{color:#f56c6c}.box-card .box-info .body-info .item .network[data-v-2a309746]{margin-left:10px}.box-card .box-info .body-info .item .network .up[data-v-2a309746]{color:#e6a23c}.box-card .box-info .body-info .item .network .down[data-v-2a309746]{color:#409eff}.box-card .box-info .body-info .time-ago[data-v-2a309746]{color:#888;margin-left:auto}.box-card .box-info .body-info .disk[data-v-2a309746]{margin-left:10px}.box-card .box-info .body-info .item .disk .basis[data-v-2a309746]{color:#c0c4cc}.box-card .box-info .body-info .item .disk .succ[data-v-2a309746]{color:#67c23a}.box-card .box-info .body-info .item .disk .warn[data-v-2a309746]{color:#e6a23c}.box-card .box-info .body-info .item .disk .dang[data-v-2a309746]{color:#f56c6c}.box-card .box-info .itemServe[data-v-2a309746]{border-top:1px solid #eee;padding-top:15px;margin-top:15px}.box-card .box-info .itemServe .item[data-v-2a309746]{display:inline-block}.box-card .box-info .itemServe .item .service[data-v-2a309746]{margin-right:5px;margin-bottom:5px}',""]),t.exports=e},f10b:function(t,e,o){"use strict";o.d(e,"b",(function(){return i})),o.d(e,"c",(function(){return n})),o.d(e,"a",(function(){return a}));var a={uCard:o("f601").default,uLineProgress:o("f1a5").default,uIcon:o("9602").default,uTag:o("be85").default},i=function(){var t=this,e=t.$createElement,o=t._self._c||e;return o("div",{staticClass:"services-list"},[o("div",{staticClass:"service-item"},[t.list?o("div",{staticClass:"service-content"},t._l(t.list,(function(e,a){return o("u-card",{key:a,staticClass:"box-card",attrs:{"show-head":!1},on:{click:function(o){arguments[0]=o=t.$handleEvent(o),t.goTo("services",e.id)}}},[o("div",{staticClass:"box-info",attrs:{slot:"body"},slot:"body"},[o("div",{staticClass:"header-info"},[o("div",{staticClass:"hostIcon"},[o("svg",{staticClass:"icon",attrs:{t:"1671607474879",viewBox:"0 0 1024 1024",version:"1.1",xmlns:"http://www.w3.org/2000/svg","p-id":"16303",width:"48",height:"48"}},[o("path",{attrs:{d:"M982.03983 1021.871767H44.048436c-9.172386 0.359701-18.114963-2.937562-24.849373-9.182378A34.554317 34.554317 0 0 1 8.138245 988.62936a34.706191 34.706191 0 0 1 35.940166-36.439752h57.132576V43.913548c0-24.01007 19.214051-43.493897 42.944353-43.493896H607.420793c23.670352 0 42.854427 19.483827 42.854428 43.493896v908.27606h45.821964V368.923764c0-23.980095 19.214051-43.563839 42.854427-43.563838h188.743329c23.700327 0 42.914378 19.583744 42.914377 43.563838v583.295819h11.430512c18.964258 0 34.331502 15.587062 34.331502 34.821096 0 19.244026-15.367244 34.851071-34.331502 34.851071v-0.019983zM581.672167 70.031868H169.903966v882.15774h411.768201V70.031868z m320.274147 325.020208H764.690247v557.177499h137.256067V395.052076zM249.937532 255.777685h251.641119c18.934283 0 34.301527 15.587062 34.301527 34.79112s-15.367244 34.791121-34.301527 34.791121H249.937532a34.537331 34.537331 0 0 1-24.359779-10.291457 34.51435 34.51435 0 0 1-9.941748-24.499664c0-19.204059 15.337269-34.791121 34.301527-34.79112z m0 232.157291h251.641119c18.934283 0 34.301527 15.587062 34.301527 34.791121s-15.367244 34.821096-34.301527 34.821095H249.937532a34.562311 34.562311 0 0 1-24.369771-10.31144 34.509355 34.509355 0 0 1-9.931756-24.519647c0-19.194067 15.337269-34.781129 34.301527-34.781129z m0 232.157291h251.641119c18.934283 0 34.301527 15.587062 34.301527 34.791121s-15.367244 34.821096-34.301527 34.821096H249.937532c-19.084159-1.388847-33.452232-17.965088-32.103351-37.059238 1.009162-17.365585 14.747758-31.284032 32.103351-32.532995v-0.019984z m0 0",fill:"#333333","p-id":"16304"}})])]),o("ul",[o("li",[t._v(t._s(e.host))]),o("li",[e.system_version?o("span",[t._v(t._s(e.system_version))]):o("span",[t._v("-")])]),o("li",[e.cpu_core_num&&e.memory_total?o("span",[t._v(t._s(e.cpu_core_num)+"核 "+t._s(e.memory_total.size)+t._s(e.memory_total.format))]):o("span",[t._v("-")])]),o("li",{style:"color:"+(e.running?"#67C23A":"#888888")},[o("svg",{staticClass:"icon",attrs:{t:"1671606719452",viewBox:"0 0 1024 1024",version:"1.1",xmlns:"http://www.w3.org/2000/svg","p-id":"10405",width:"18",height:"18"}},[o("path",{attrs:{d:"M554 849.574c0 23.365-18.635 42.307-42 42.307s-42-18.941-42-42.307V662.719c0-23.365 18.635-42.307 42-42.307v-7.051c23.365 0 42 25.993 42 49.358v186.855z",fill:e.running?"#67C23A":"#888888","p-id":"10406"}}),o("path",{attrs:{d:"M893 888.5c0 17.397-14.103 31.5-31.5 31.5h-700c-17.397 0-31.5-14.103-31.5-31.5s14.103-31.5 31.5-31.5h700c17.397 0 31.5 14.103 31.5 31.5zM926 174.426C926 135.484 894.686 105 855.744 105H168.256C129.314 105 98 135.484 98 174.426V533h828V174.426zM98 630.988C98 669.931 129.314 702 168.256 702h687.488C894.686 702 926 669.931 926 630.988V596H98v34.988z",fill:e.running?"#67C23A":"#888888","p-id":"10407"}})]),t._v(t._s(e.running?"在线":"离线"))])])]),o("div",{staticClass:"body-info"},[o("div",{staticClass:"item no-margin"},[o("span",{staticClass:"time-ago"},["-"!=e.time?o("timeago",{attrs:{datetime:e.time,"auto-update":60}}):o("span",[t._v("-")])],1)]),o("div",{staticClass:"item"},[o("span",{staticClass:"hostName"},[t._v(t._s(e.name))])]),o("div",{staticClass:"item nowrap"},[o("span",{staticStyle:{width:"160rpx"},attrs:{slot:"content"},slot:"content"},[t._v("cpu："+t._s(e.cpuUse?parseFloat(e.cpuUse)+"%":"-"))]),o("div",{staticClass:"cont"},[o("u-line-progress",{attrs:{percent:parseFloat(e.cpuUse),"active-color":t.getProgressColor(e.cpuUse)}})],1)]),o("div",{staticClass:"item nowrap"},[o("span",{staticStyle:{width:"160rpx"},attrs:{slot:"content"},slot:"content"},[t._v("内存："+t._s(e.memUsedPercent?parseFloat(e.memUsedPercent)+"%":"-"))]),o("div",{staticClass:"cont"},[o("u-line-progress",{attrs:{percent:parseFloat(e.memUsedPercent),"active-color":t.getProgressColor(e.memUsedPercent)}})],1)]),o("div",{staticClass:"item"},[o("div",[t._v("负载")]),o("span",{staticClass:"cont load"},[o("span",{class:t.getLoadClass(e.cpuLoad1)},[t._v(t._s(e.cpuLoad1))]),o("span",{class:t.getLoadClass(e.cpuLoad5)},[t._v(t._s(e.cpuLoad5))]),o("span",{class:t.getLoadClass(e.cpuLoad15)},[t._v(t._s(e.cpuLoad15))])])]),o("div",{staticClass:"item nowrap"},[o("div",[t._v("网络")]),o("span",{staticClass:"network"},[o("span",{staticStyle:{"margin-right":"20rpx"}},[o("u-icon",{staticClass:"up",attrs:{name:"arrow-upward"}}),t._v(t._s(e.netSpeed.out.size)+" "+t._s(e.netSpeed.out.format)+"/s")],1),o("span",[o("u-icon",{staticClass:"down",attrs:{name:"arrow-downward"}}),t._v(t._s(e.netSpeed.in.size)+" "+t._s(e.netSpeed.in.format)+"/s")],1)])]),o("div",{staticClass:"item"},[o("div",[t._v("硬盘")]),e.storageInfo&&e.storageInfo.length>0?o("span",{staticClass:"disk",attrs:{slot:"reference"},slot:"reference"},[e.storageWarning?t._e():o("u-icon",{staticClass:"el-icon-success succ",attrs:{name:"checkmark-circle-fill"}}),e.storageWarning?o("u-icon",{staticClass:"dang",attrs:{name:"info-circle-fill"}},[t._v("告警")]):o("span",{staticStyle:{"margin-left":"10rpx"}},[t._v("正常")]),e.storageWarning?o("span",{staticStyle:{"margin-left":"10rpx"}},[t._v("告警")]):t._e()],1):o("span",{staticClass:"disk"},[o("i",{staticClass:"el-icon-info basis"},[t._v("未获取到数据")])])]),e.role?o("div",{staticClass:"itemServe"},t._l(e.role,(function(t,e){return o("div",{key:e,staticClass:"item"},["防火墙服务"==t.name?o("u-tag",{staticClass:"service",attrs:{text:t.name,size:"mini",type:1==t.running?"success":"info"}}):o("u-tag",{staticClass:"service",attrs:{size:"mini",type:1==t.running?"success":"info",text:t.name}})],1)})),0):t._e()])])])})),1):t._e()])])},n=[]},f20d:function(t,e,o){"use strict";o.d(e,"b",(function(){return i})),o.d(e,"c",(function(){return n})),o.d(e,"a",(function(){return a}));var a={uIcon:o("9602").default},i=function(){var t=this,e=t.$createElement,o=t._self._c||e;return t.show?o("v-uni-view",{staticClass:"u-tag",class:[t.disabled?"u-disabled":"","u-size-"+t.size,"u-shape-"+t.shape,"u-mode-"+t.mode+"-"+t.type],style:[t.customStyle],on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.clickTag.apply(void 0,arguments)}}},[t._v(t._s(t.text)),o("v-uni-view",{staticClass:"u-icon-wrap",on:{click:function(e){e.stopPropagation(),arguments[0]=e=t.$handleEvent(e)}}},[t.closeable?o("u-icon",{staticClass:"u-close-icon",style:[t.iconStyle],attrs:{size:"22",color:t.closeIconColor,name:"close"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.close.apply(void 0,arguments)}}}):t._e()],1)],1):t._e()},n=[]}}]);