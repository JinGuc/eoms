(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-servicesmanage-services"],{"0589":function(t,e,n){"use strict";n.d(e,"b",(function(){return i})),n.d(e,"c",(function(){return r})),n.d(e,"a",(function(){}));var i=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-uni-view",{staticClass:"u-divider",style:{height:"auto"==t.height?"auto":t.height+"rpx",backgroundColor:t.bgColor,marginBottom:t.marginBottom+"rpx",marginTop:t.marginTop+"rpx"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.click.apply(void 0,arguments)}}},[n("v-uni-view",{staticClass:"u-divider-line",class:[t.type?"u-divider-line--bordercolor--"+t.type:""],style:[t.lineStyle]}),t.useSlot?n("v-uni-view",{staticClass:"u-divider-text",style:{color:t.color,fontSize:t.fontSize+"rpx"}},[t._t("default")],2):t._e(),n("v-uni-view",{staticClass:"u-divider-line",class:[t.type?"u-divider-line--bordercolor--"+t.type:""],style:[t.lineStyle]})],1)},r=[]},"0662":function(t,e,n){"use strict";n("7a82"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0,n("a9e3"),n("c975");var i={name:"u-circle-progress",props:{percent:{type:Number,default:0,validator:function(t){return t>=0&&t<=100}},inactiveColor:{type:String,default:"#ececec"},activeColor:{type:String,default:"#19be6b"},borderWidth:{type:[Number,String],default:14},width:{type:[Number,String],default:200},duration:{type:[Number,String],default:1500},type:{type:String,default:""},bgColor:{type:String,default:"#ffffff"}},data:function(){return{elBgId:this.$u.guid(),elId:this.$u.guid(),widthPx:uni.upx2px(this.width),borderWidthPx:uni.upx2px(this.borderWidth),startAngle:-Math.PI/2,progressContext:null,newPercent:0,oldPercent:0}},watch:{percent:function(t){var e=this,n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:0;t>100&&(t=100),t<0&&(n=0),this.newPercent=t,this.oldPercent=n,setTimeout((function(){e.drawCircleByProgress(n)}),50)}},created:function(){this.newPercent=this.percent,this.oldPercent=0},computed:{circleColor:function(){return["success","error","info","primary","warning"].indexOf(this.type)>=0?this.$u.color[this.type]:this.activeColor}},mounted:function(){var t=this;setTimeout((function(){t.drawProgressBg(),t.drawCircleByProgress(t.oldPercent)}),50)},methods:{drawProgressBg:function(){var t=uni.createCanvasContext(this.elBgId,this);t.setLineWidth(this.borderWidthPx),t.setStrokeStyle(this.inactiveColor),t.beginPath();var e=this.widthPx/2;t.arc(e,e,e-this.borderWidthPx,0,2*Math.PI,!1),t.stroke(),t.draw()},drawCircleByProgress:function(t){var e=this,n=this.progressContext;n||(n=uni.createCanvasContext(this.elId,this),this.progressContext=n),n.setLineCap("round"),n.setLineWidth(this.borderWidthPx),n.setStrokeStyle(this.circleColor);var i=Math.floor(this.duration/100),r=2*Math.PI/100*t+this.startAngle;n.beginPath();var o=this.widthPx/2;if(n.arc(o,o,o-this.borderWidthPx,this.startAngle,r,!1),n.stroke(),n.draw(),this.newPercent>this.oldPercent){if(t++,t>this.newPercent)return}else if(t--,t<this.newPercent)return;setTimeout((function(){e.drawCircleByProgress(t)}),i)}}};e.default=i},"0768":function(t,e,n){var i=n("24fb");e=i(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* uni.scss */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */.u-divider[data-v-fec8ac4c]{width:100%;position:relative;text-align:center;display:flex;flex-direction:row;justify-content:center;align-items:center;overflow:hidden;flex-direction:row}.u-divider-line[data-v-fec8ac4c]{border-bottom:1px solid #e4e7ed;-webkit-transform:scaleY(.5);transform:scaleY(.5);-webkit-transform-origin:center;transform-origin:center}.u-divider-line--bordercolor--primary[data-v-fec8ac4c]{border-color:#2979ff}.u-divider-line--bordercolor--success[data-v-fec8ac4c]{border-color:#19be6b}.u-divider-line--bordercolor--error[data-v-fec8ac4c]{border-color:#2979ff}.u-divider-line--bordercolor--info[data-v-fec8ac4c]{border-color:#909399}.u-divider-line--bordercolor--warning[data-v-fec8ac4c]{border-color:#f90}.u-divider-text[data-v-fec8ac4c]{white-space:nowrap;padding:0 %?16?%;display:inline-flex}',""]),t.exports=e},"0ac1":function(t,e,n){"use strict";n("7a82"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0,n("a9e3");var i={name:"u-card",props:{full:{type:Boolean,default:!1},title:{type:String,default:""},titleColor:{type:String,default:"#303133"},titleSize:{type:[Number,String],default:"30"},subTitle:{type:String,default:""},subTitleColor:{type:String,default:"#909399"},subTitleSize:{type:[Number,String],default:"26"},border:{type:Boolean,default:!0},index:{type:[Number,String,Object],default:""},margin:{type:String,default:"0rpx 0rpx 15rpx 0rpx"},borderRadius:{type:[Number,String],default:"16"},headStyle:{type:Object,default:function(){return{}}},bodyStyle:{type:Object,default:function(){return{}}},footStyle:{type:Object,default:function(){return{}}},headBorderBottom:{type:Boolean,default:!0},footBorderTop:{type:Boolean,default:!0},thumb:{type:String,default:""},thumbWidth:{type:[String,Number],default:"40"},thumbCircle:{type:Boolean,default:!1},padding:{type:[String,Number],default:"30"},showHead:{type:Boolean,default:!0},showFoot:{type:Boolean,default:!0},boxShadow:{type:String,default:"none"}},data:function(){return{}},methods:{click:function(){this.$emit("click",this.index)},headClick:function(){this.$emit("head-click",this.index)},bodyClick:function(){this.$emit("body-click",this.index)},footClick:function(){this.$emit("foot-click",this.index)},subTitleClick:function(){this.$emit("subtitle-click",this.index)}}};e.default=i},"1bc4":function(t,e,n){var i=n("24fb");e=i(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* uni.scss */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */.u-progress-content[data-v-b40b5e4e]{display:flex;align-items:center;justify-content:center}.u-progress-dot[data-v-b40b5e4e]{width:%?16?%;height:%?16?%;border-radius:50%;background-color:#fb9126}.u-progress-info[data-v-b40b5e4e]{width:%?100?%;font-size:%?24?%;padding-left:%?10?%;letter-spacing:%?2?%}',""]),t.exports=e},"1e4d":function(t,e,n){"use strict";n.r(e);var i=n("3f35"),r=n("7bd5");for(var o in r)["default"].indexOf(o)<0&&function(t){n.d(e,t,(function(){return r[t]}))}(o);n("f0a8");var a=n("f0c5"),s=Object(a["a"])(r["default"],i["b"],i["c"],!1,null,"21fb694c",null,!1,i["a"],void 0);e["default"]=s.exports},2032:function(t,e,n){"use strict";n.d(e,"b",(function(){return r})),n.d(e,"c",(function(){return o})),n.d(e,"a",(function(){return i}));var i={uButton:n("daed").default},r=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-uni-view",{staticClass:"u-card",class:{"u-border":t.border,"u-card-full":t.full,"u-card--border":t.borderRadius>0},style:{borderRadius:t.borderRadius+"rpx",margin:t.margin,boxShadow:t.boxShadow},on:{click:function(e){e.stopPropagation(),arguments[0]=e=t.$handleEvent(e),t.click.apply(void 0,arguments)}}},[t.showHead?n("v-uni-view",{staticClass:"u-card__head",class:{"u-border-bottom":t.headBorderBottom},style:[{padding:t.padding+"rpx"},t.headStyle],on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.headClick.apply(void 0,arguments)}}},[t.$slots.head?t._t("head"):n("v-uni-view",{staticClass:"u-flex u-row-between"},[t.title?n("v-uni-view",{staticClass:"u-card__head--left u-flex u-line-1"},[t.thumb?n("v-uni-image",{staticClass:"u-card__head--left__thumb",style:{height:t.thumbWidth+"rpx",width:t.thumbWidth+"rpx",borderRadius:t.thumbCircle?"100rpx":"6rpx"},attrs:{src:t.thumb,mode:"aspectfull"}}):t._e(),n("v-uni-text",{staticClass:"u-card__head--left__title u-line-1",style:{fontSize:t.titleSize+"rpx",color:t.titleColor}},[t._v(t._s(t.title))])],1):t._e(),t.subTitle?n("v-uni-view",{staticClass:"u-card__head--right u-line-1"},[n("u-button",{attrs:{type:"success",size:"mini",plain:!0},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.subTitleClick.apply(void 0,arguments)}}},[t._v(t._s(t.subTitle))])],1):t._e()],1)],2):t._e(),n("v-uni-view",{staticClass:"u-card__body",style:[{padding:t.padding+"rpx"},t.bodyStyle],on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.bodyClick.apply(void 0,arguments)}}},[t._t("body")],2),t.showFoot?n("v-uni-view",{staticClass:"u-card__foot",class:{"u-border-top":t.footBorderTop},style:[{padding:t.$slots.foot?t.padding+"rpx":0},t.footStyle],on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.footClick.apply(void 0,arguments)}}},[t._t("foot")],2):t._e()],1)},o=[]},"203b":function(t,e,n){var i=n("24fb");e=i(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* uni.scss */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */.u-line[data-v-21fb694c]{vertical-align:middle}',""]),t.exports=e},"320c":function(t,e,n){var i=n("0768");i.__esModule&&(i=i.default),"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var r=n("4f06").default;r("5b2d08aa",i,!0,{sourceMap:!1,shadowMode:!1})},"3b54":function(t,e,n){"use strict";n.d(e,"b",(function(){return i})),n.d(e,"c",(function(){return r})),n.d(e,"a",(function(){}));var i=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-uni-view",{staticClass:"u-circle-progress",style:{width:t.widthPx+"px",height:t.widthPx+"px",backgroundColor:t.bgColor}},[n("v-uni-canvas",{staticClass:"u-canvas-bg",style:{width:t.widthPx+"px",height:t.widthPx+"px"},attrs:{"canvas-id":t.elBgId,id:t.elBgId}}),n("v-uni-canvas",{staticClass:"u-canvas",style:{width:t.widthPx+"px",height:t.widthPx+"px"},attrs:{"canvas-id":t.elId,id:t.elId}}),t._t("default")],2)},r=[]},"3f35":function(t,e,n){"use strict";n.d(e,"b",(function(){return i})),n.d(e,"c",(function(){return r})),n.d(e,"a",(function(){}));var i=function(){var t=this.$createElement,e=this._self._c||t;return e("v-uni-view",{staticClass:"u-line",style:[this.lineStyle]})},r=[]},4397:function(t,e,n){"use strict";n("7a82");var i=n("4ea4").default;Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var r=i(n("5530"));n("acd8"),n("c975");var o=i(n("bfe8")),a={data:function(){return{hostId:"",hostInfo:{}}},methods:{getHostInfoEnable:function(){var t=this,e={hostId:this.hostId};this.capis.getHostInfoEnable(e,{}).then((function(e){"success"==e.status&&(t.hostInfo=e.res.data)}))},onLoad:function(t){this.hostId=t.hostId},initWs:function(){var t=this;this.$LaravelEcho.instance.channel("HostStatus").listen("Snmp.HostEvent",(function(e){"status"==e.type&&t.hostId==e.host_id&&(t.hostInfo.running=e.data.running)})),this.$LaravelEcho.instance.channel("SysInfo").listen("Snmp.SysInfoEvent",(function(e){if("CpuInfo"==e.type)t.hostId==e.host_id&&(t.hostInfo.cpuUse=parseFloat(e.data.cpuUse),t.hostInfo.cpuLoad1=e.data.cpuLoad1,t.hostInfo.cpuLoad5=e.data.cpuLoad5,t.hostInfo.cpuLoad15=e.data.cpuLoad15);else if("MemoryInfo"==e.type)t.hostId==e.host_id&&(t.hostInfo.memUsedPercent=parseFloat(e.data.memUsedPercent));else if("ifInfo"==e.type)t.hostId==e.host_id&&(t.hostInfo.netSpeed.in.size=e.data.escape.in.size,t.hostInfo.netSpeed.in.format=e.data.escape.in.format,t.hostInfo.netSpeed.out.size=e.data.escape.out.size,t.hostInfo.netSpeed.out.format=e.data.escape.out.format);else if("BaseInfo"==e.type)t.hostId==e.host_id&&(t.hostInfo.systemRuntime=e.data.systemRuntime,t.hostInfo.systemTime=e.data.systemTime,t.hostInfo.runningServers=e.data.hrswrunname);else if("StorageInfo"==e.type)t.hostId==e.host_id&&(t.hostInfo.storageInfo=e.data);else if("DiskInfo"==e.type){var n=t.hostInfo.diskInfo;if(t.hostId==e.host_id)for(var i=0;i<e.data.length;i++)for(var o=0;o<n.length;o++)if(n[o].disk_name==e.data[i].disk_name){n[o]=(0,r.default)((0,r.default)({},e.data[i]),n[o]);break}}else if("DiskIoInfo"==e.type){for(var a=e.data,s=t.hostInfo.diskInfo,u=0;u<s.length;u++)for(var d=0;d<a.length;d++)if(-1!=s[u].disk_name.indexOf(a[d].disk_name)){s[u].kB_read_avg=a[d].kB_read_avg,s[u].kB_wrtn_avg=a[d].kB_wrtn_avg,s[u].util=a[d].util;break}}else"OpenPortInfo"==e.type&&t.hostId==e.host_id&&(t.hostInfo.tcpPort=e.data.tcp,t.hostInfo.udpPort=e.data.udp)}))}},components:{Usage:o.default},created:function(){var t=this;this.getHostInfoEnable(),this.$nextTick((function(){t.initWs()}))},beforeDestroy:function(){this.$LaravelEcho.instance.leaveChannel("SysInfo"),this.$LaravelEcho.instance.leaveChannel("HostStatus")}};e.default=a},4595:function(t,e,n){"use strict";var i=n("841d"),r=n.n(i);r.a},4771:function(t,e,n){var i=n("24fb");e=i(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* uni.scss */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */.u-card[data-v-56b10954]{position:relative;overflow:hidden;font-size:%?28?%;background-color:#fff;box-sizing:border-box}.u-card-full[data-v-56b10954]{margin-left:0!important;margin-right:0!important}.u-card--border[data-v-56b10954]:after{border-radius:%?16?%}.u-card__head--left[data-v-56b10954]{color:#303133}.u-card__head--left__thumb[data-v-56b10954]{margin-right:%?16?%}.u-card__head--left__title[data-v-56b10954]{max-width:%?400?%}.u-card__head--right[data-v-56b10954]{color:#909399;margin-left:%?6?%}.u-card__body[data-v-56b10954]{color:#606266}.u-card__foot[data-v-56b10954]{color:#909399}',""]),t.exports=e},"543d":function(t,e,n){var i=n("24fb");e=i(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* uni.scss */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */.bg[data-v-5820e450]{width:100vw;height:100vh;background-color:#f6f6f6;padding:%?30?%}.bg .u-card-wrap[data-v-5820e450]{background-color:#f3f4f6;padding:1px}.bg .u-body-item[data-v-5820e450]{font-size:%?24?%;color:#333;padding:%?20?% %?10?%}.bg .u-body-item .label-title[data-v-5820e450]{text-align:right;font-weight:600;width:%?130?%}.bg .u-body-item .neirong[data-v-5820e450]{text-align:left;width:%?500?%}.bg .u-body-item .cpu[data-v-5820e450]{width:%?170?%}.bg .u-body-item .weiyuansu span[data-v-5820e450]:not(:last-child)::after{padding:0 %?2?%;color:#333;content:"/"}.bg .Usage[data-v-5820e450]{display:flex;padding:%?20?% %?10?%;background-color:#fff;border-radius:%?12?%}',""]),t.exports=e},"5c25":function(t,e,n){var i=n("7d18");i.__esModule&&(i=i.default),"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var r=n("4f06").default;r("076e0b4c",i,!0,{sourceMap:!1,shadowMode:!1})},7784:function(t,e,n){var i=n("1bc4");i.__esModule&&(i=i.default),"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var r=n("4f06").default;r("bf62998a",i,!0,{sourceMap:!1,shadowMode:!1})},"78fa":function(t,e,n){"use strict";var i=n("320c"),r=n.n(i);r.a},"7aaf":function(t,e,n){"use strict";n.d(e,"b",(function(){return r})),n.d(e,"c",(function(){return o})),n.d(e,"a",(function(){return i}));var i={uCard:n("f601").default,uIcon:n("9602").default,uDivider:n("eabc").default},r=function(){var t=this,e=t.$createElement,n=t._self._c||e;return t.hostInfo?n("v-uni-view",{staticClass:"bg"},[n("u-card",{attrs:{title:t.hostInfo.name,thumb:"../../static/jiekou_name.png"}},[n("v-uni-view",{attrs:{slot:"body"},slot:"body"},[n("v-uni-view",{staticClass:"u-body-item u-flex u-col-between u-p-t-0"},[n("v-uni-view",{staticClass:"u-body-item-title u-line-2 label-title"},[t._v("设备类型：")]),n("v-uni-view",{staticClass:"u-body-item-title u-line-2 neirong"},[t._v(t._s(1==t.hostInfo.type?"centos服务器":""))])],1),n("v-uni-view",{staticClass:"u-body-item u-flex u-col-between u-p-t-0"},[n("v-uni-view",{staticClass:"u-body-item-title u-line-2 label-title"},[t._v("IP地址：")]),n("v-uni-view",{staticClass:"u-body-item-title u-line-2 neirong"},[t._v(t._s(t.hostInfo.ip))])],1),n("v-uni-view",{staticClass:"u-body-item u-flex u-col-between u-p-t-0"},[n("v-uni-view",{staticClass:"u-body-item-title u-line-2 label-title"},[t._v("操作系统：")]),n("v-uni-view",{staticClass:"u-body-item-title u-line-2 neirong"},[t._v(t._s(t.hostInfo.systemVersion))])],1),n("v-uni-view",{staticClass:"u-body-item u-flex u-col-between u-p-t-0"},[n("v-uni-view",{staticClass:"u-body-item-title u-line-2 label-title"},[t._v("设备状态：")]),n("v-uni-view",{staticClass:"u-body-item-title u-line-2 neirong"},[t._v(t._s("1"==t.hostInfo.running?"在线":"离线"))])],1),n("v-uni-view",{staticClass:"u-body-item u-flex u-col-between u-p-t-0"},[n("v-uni-view",{staticClass:"u-body-item-title u-line-2 label-title"},[t._v("CPU：")]),n("v-uni-view",{staticClass:"u-body-item-title u-line-2 neirong"},[t._v(t._s(t.hostInfo.cpuInfo)+"-("+t._s(t.hostInfo.cpuCoreNum)+"核)")])],1),t.hostInfo.memoryUse&&t.hostInfo.memoryTotal?n("v-uni-view",{staticClass:"u-body-item u-flex u-col-between u-p-t-0"},[n("v-uni-view",{staticClass:"u-body-item-title u-line-2 label-title"},[t._v("内存：")]),n("v-uni-view",{staticClass:"u-body-item-title u-line-2 weiyuansu neirong"},[n("span",[t._v(t._s(t.hostInfo.memoryUse.size)+t._s(t.hostInfo.memoryUse.format))]),n("span",[t._v(t._s(t.hostInfo.memoryTotal.size)+t._s(t.hostInfo.memoryTotal.format)+"(已使用"+t._s(t.hostInfo.memUsedPercent)+"%)")])])],1):t._e(),t.hostInfo.netSpeed?n("v-uni-view",{staticClass:"u-body-item u-flex u-col-between u-p-t-0"},[n("v-uni-view",{staticClass:"u-body-item-title u-line-2 label-title"},[t._v("网络：")]),n("v-uni-view",{staticClass:"u-body-item-title u-line-2 weiyuansu neirong"},[n("span",[n("u-icon",{attrs:{name:"arrow-upward",color:"#2979ff",size:"28"}}),t._v(t._s(t.hostInfo.netSpeed.in.size)+t._s(t.hostInfo.netSpeed.in.format))],1),n("span",[n("u-icon",{attrs:{name:"arrow-downward",color:"#67C23A",size:"28"}}),t._v(t._s(t.hostInfo.netSpeed.out.size)+t._s(t.hostInfo.netSpeed.out.format))],1)])],1):t._e(),n("v-uni-view",{staticClass:"u-body-item u-flex u-col-between u-p-t-0"},[n("v-uni-view",{staticClass:"u-body-item-title u-line-2 label-title"},[t._v("告警数：")]),n("v-uni-view",{staticClass:"u-body-item-title u-line-2 weiyuansu neirong"},[n("span",[t._v("今日：2")]),n("span",[t._v("近七天：4")])])],1),n("v-uni-view",{staticClass:"u-body-item u-flex u-col-between u-p-t-0"},[n("v-uni-view",{staticClass:"u-body-item-title u-line-2 label-title"},[t._v("SSH：")]),n("v-uni-view",{staticClass:"u-body-item-title u-line-2 weiyuansu neirong"},[n("span",[t._v("成功："+t._s(t.hostInfo.toDayLoginSucceed))]),n("span",[t._v("失败："+t._s(t.hostInfo.toDayLoginFail))])])],1),t.hostInfo.tcpPort&&t.hostInfo.udpPort?n("v-uni-view",{staticClass:"u-body-item u-flex u-col-between u-p-t-0"},[n("v-uni-view",{staticClass:"u-body-item-title u-line-2 label-title"},[t._v("启用端口：")]),n("v-uni-view",{staticClass:"u-body-item-title u-line-2 weiyuansu neirong"},[n("span",[t._v("TCP："+t._s(t.hostInfo.tcpPort.length))]),n("span",[t._v("UDP："+t._s(t.hostInfo.udpPort.length))])])],1):t._e(),t.hostInfo.runningServers?n("v-uni-view",{staticClass:"u-body-item u-flex u-col-between u-p-t-0"},[n("v-uni-view",{staticClass:"u-body-item-title u-line-2 label-title"},[t._v("进程数：")]),n("v-uni-view",{staticClass:"u-body-item-title u-line-2 weiyuansu neirong"},[t._v(t._s(t.hostInfo.runningServers.length))])],1):t._e(),n("v-uni-view",{staticClass:"u-body-item u-flex u-col-between u-p-t-0"},[n("v-uni-view",{staticClass:"u-body-item-title u-line-2 label-title"},[t._v("系统时间：")]),n("v-uni-view",{staticClass:"u-body-item-title u-line-2 weiyuansu neirong"},[t._v(t._s(t.hostInfo.systemTime))])],1),n("v-uni-view",{staticClass:"u-body-item u-flex u-col-between u-p-t-0"},[n("v-uni-view",{staticClass:"u-body-item-title u-line-2 label-title"},[t._v("运行天数：")]),n("v-uni-view",{staticClass:"u-body-item-title u-line-2 weiyuansu neirong"},[t._v(t._s(t.hostInfo.systemRuntime))])],1)],1)],1),n("Usage",{staticClass:"Usage",attrs:{hostId:t.hostId}}),n("u-divider",{staticStyle:{"margin-top":"100rpx","background-color":"#f6f6f6"}},[t._v("更多功能开发中")])],1):t._e()},o=[]},"7bd5":function(t,e,n){"use strict";n.r(e);var i=n("b704"),r=n.n(i);for(var o in i)["default"].indexOf(o)<0&&function(t){n.d(e,t,(function(){return i[t]}))}(o);e["default"]=r.a},"7d18":function(t,e,n){var i=n("24fb");e=i(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* uni.scss */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */.u-circle-progress[data-v-cc16d742]{position:relative;display:inline-flex;align-items:center;justify-content:center}.u-canvas-bg[data-v-cc16d742]{position:absolute}.u-canvas[data-v-cc16d742]{position:absolute}',""]),t.exports=e},"841d":function(t,e,n){var i=n("4771");i.__esModule&&(i=i.default),"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var r=n("4f06").default;r("2fe58570",i,!0,{sourceMap:!1,shadowMode:!1})},"85f5":function(t,e,n){"use strict";n("7a82"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;e.default={props:{hostId:{default:""}},data:function(){return{hostInfo:{},progressColors:{success:"#67C23A",warning:"#E6A23C",danger:"#F56C6C"}}},methods:{getHostInfoEnable:function(){var t=this,e={hostId:this.hostId};this.capis.getHostInfoEnable(e,{}).then((function(e){"success"==e.status&&(t.hostInfo=e.res.data,t.hostInfo.memUsedPercent&&(t.hostInfo.memUsedPercent=1*t.hostInfo.memUsedPercent),t.hostInfo.cpuUse&&(t.hostInfo.cpuUse=1*t.hostInfo.cpuUse))}))},getProgressColor:function(t){return t<=70?this.progressColors.success:t>70&&t<=90?this.progressColors.warning:this.progressColors.danger}},created:function(){this.getHostInfoEnable()}}},"87bc":function(t,e,n){"use strict";n.d(e,"b",(function(){return r})),n.d(e,"c",(function(){return o})),n.d(e,"a",(function(){return i}));var i={uCircleProgress:n("989a").default,uLine:n("1e4d").default,uIcon:n("9602").default},r=function(){var t=this,e=t.$createElement,n=t._self._c||e;return t.hostInfo?n("v-uni-view",{},[n("v-uni-view",{staticStyle:{width:"51%"}},[n("u-circle-progress",{attrs:{"active-color":"#67C23A",percent:t.hostInfo.cpuUse,width:"160"}},[n("v-uni-view",{staticClass:"u-progress-content"},[n("v-uni-text",{staticClass:"u-progress-info"},[t._v(t._s(t.hostInfo.cpuUse)+"%")])],1)],1),n("u-circle-progress",{staticStyle:{margin:"10rpx"},attrs:{"active-color":"#F56C6C",percent:t.hostInfo.memUsedPercent,width:"160","show-percent":!0}},[n("v-uni-view",{staticClass:"u-progress-content"},[n("v-uni-text",{staticClass:"u-progress-info"},[t._v(t._s(t.hostInfo.memUsedPercent)+"%")])],1)],1),n("v-uni-text",{staticStyle:{"margin-left":"25rpx","font-weight":"600","font-size":"24rpx"}},[t._v("CPU使用率")]),n("v-uni-text",{staticStyle:{"margin-left":"50rpx","font-weight":"600","font-size":"24rpx"}},[t._v("内存使用率")])],1),n("u-line",{attrs:{color:"#999",direction:"col",length:"200rpx",margin:"10rpx"}}),n("v-uni-view",{},[n("v-uni-view",{staticStyle:{"margin-left":"10rpx",display:"flex","align-items":"center"}},[n("u-icon",{attrs:{name:"integral",color:"#5e5ec6",size:"24"}}),n("p",{staticStyle:{"font-size":"24rpx","font-weight":"600","margin-left":"5rpx"}},[t._v("负载")])],1),n("v-uni-view",{staticStyle:{"margin-left":"10rpx"}},[n("v-uni-view",{staticStyle:{"margin-top":"20rpx",display:"flex"}},[n("p",{staticStyle:{"font-size":"24rpx"}},[t._v("1分钟负载")]),n("p",{staticStyle:{"font-size":"32rpx","margin-left":"31rpx",color:"#5e5ec6","font-weight":"600"}},[t._v(t._s(t.hostInfo.cpuLoad1))])]),n("v-uni-view",{staticStyle:{"margin-top":"20rpx",display:"flex","text-align":"center"}},[n("p",{staticStyle:{"font-size":"24rpx"}},[t._v("5分钟负载")]),n("p",{staticStyle:{"font-size":"32rpx","margin-left":"33rpx",color:"#5e5ec6","font-weight":"600"}},[t._v(t._s(t.hostInfo.cpuLoad5))])]),n("v-uni-view",{staticStyle:{"margin-top":"20rpx",display:"flex"}},[n("p",{staticStyle:{"font-size":"24rpx"}},[t._v("15分钟负载")]),n("p",{staticStyle:{"font-size":"32rpx","margin-left":"20rpx",color:"#5e5ec6","font-weight":"600"}},[t._v(t._s(t.hostInfo.cpuLoad15))])])],1)],1)],1):t._e()},o=[]},9835:function(t,e,n){"use strict";n.r(e);var i=n("7aaf"),r=n("bbd8");for(var o in r)["default"].indexOf(o)<0&&function(t){n.d(e,t,(function(){return r[t]}))}(o);n("a75b");var a=n("f0c5"),s=Object(a["a"])(r["default"],i["b"],i["c"],!1,null,"5820e450",null,!1,i["a"],void 0);e["default"]=s.exports},"989a":function(t,e,n){"use strict";n.r(e);var i=n("3b54"),r=n("aff3");for(var o in r)["default"].indexOf(o)<0&&function(t){n.d(e,t,(function(){return r[t]}))}(o);n("b9ba");var a=n("f0c5"),s=Object(a["a"])(r["default"],i["b"],i["c"],!1,null,"cc16d742",null,!1,i["a"],void 0);e["default"]=s.exports},a75b:function(t,e,n){"use strict";var i=n("fffe"),r=n.n(i);r.a},af71:function(t,e,n){"use strict";var i=n("7784"),r=n.n(i);r.a},aff3:function(t,e,n){"use strict";n.r(e);var i=n("0662"),r=n.n(i);for(var o in i)["default"].indexOf(o)<0&&function(t){n.d(e,t,(function(){return i[t]}))}(o);e["default"]=r.a},b704:function(t,e,n){"use strict";n("7a82"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i={name:"u-line",props:{color:{type:String,default:"#e4e7ed"},length:{type:String,default:"100%"},direction:{type:String,default:"row"},hairLine:{type:Boolean,default:!0},margin:{type:String,default:"0"},borderStyle:{type:String,default:"solid"}},computed:{lineStyle:function(){var t={};return t.margin=this.margin,"row"==this.direction?(t.borderBottomWidth="1px",t.borderBottomStyle=this.borderStyle,t.width=this.$u.addUnit(this.length),this.hairLine&&(t.transform="scaleY(0.5)")):(t.borderLeftWidth="1px",t.borderLeftStyle=this.borderStyle,t.height=this.$u.addUnit(this.length),this.hairLine&&(t.transform="scaleX(0.5)")),t.borderColor=this.color,t}}};e.default=i},b9ba:function(t,e,n){"use strict";var i=n("5c25"),r=n.n(i);r.a},bbd8:function(t,e,n){"use strict";n.r(e);var i=n("4397"),r=n.n(i);for(var o in i)["default"].indexOf(o)<0&&function(t){n.d(e,t,(function(){return i[t]}))}(o);e["default"]=r.a},bddc:function(t,e,n){"use strict";n.r(e);var i=n("85f5"),r=n.n(i);for(var o in i)["default"].indexOf(o)<0&&function(t){n.d(e,t,(function(){return i[t]}))}(o);e["default"]=r.a},bfe8:function(t,e,n){"use strict";n.r(e);var i=n("87bc"),r=n("bddc");for(var o in r)["default"].indexOf(o)<0&&function(t){n.d(e,t,(function(){return r[t]}))}(o);n("af71");var a=n("f0c5"),s=Object(a["a"])(r["default"],i["b"],i["c"],!1,null,"b40b5e4e",null,!1,i["a"],void 0);e["default"]=s.exports},cd3a:function(t,e,n){"use strict";n("7a82"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0,n("a9e3"),n("c975");var i={name:"u-divider",props:{halfWidth:{type:[Number,String],default:150},borderColor:{type:String,default:"#dcdfe6"},type:{type:String,default:"primary"},color:{type:String,default:"#909399"},fontSize:{type:[Number,String],default:26},bgColor:{type:String,default:"#ffffff"},height:{type:[Number,String],default:"auto"},marginTop:{type:[String,Number],default:0},marginBottom:{type:[String,Number],default:0},useSlot:{type:Boolean,default:!0}},computed:{lineStyle:function(){var t={};return-1!=String(this.halfWidth).indexOf("%")?t.width=this.halfWidth:t.width=this.halfWidth+"rpx",this.borderColor&&(t.borderColor=this.borderColor),t}},methods:{click:function(){this.$emit("click")}}};e.default=i},d2ae:function(t,e,n){var i=n("203b");i.__esModule&&(i=i.default),"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var r=n("4f06").default;r("0ea72712",i,!0,{sourceMap:!1,shadowMode:!1})},e16b:function(t,e,n){"use strict";n.r(e);var i=n("cd3a"),r=n.n(i);for(var o in i)["default"].indexOf(o)<0&&function(t){n.d(e,t,(function(){return i[t]}))}(o);e["default"]=r.a},eabc:function(t,e,n){"use strict";n.r(e);var i=n("0589"),r=n("e16b");for(var o in r)["default"].indexOf(o)<0&&function(t){n.d(e,t,(function(){return r[t]}))}(o);n("78fa");var a=n("f0c5"),s=Object(a["a"])(r["default"],i["b"],i["c"],!1,null,"fec8ac4c",null,!1,i["a"],void 0);e["default"]=s.exports},f0a8:function(t,e,n){"use strict";var i=n("d2ae"),r=n.n(i);r.a},f601:function(t,e,n){"use strict";n.r(e);var i=n("2032"),r=n("fd78");for(var o in r)["default"].indexOf(o)<0&&function(t){n.d(e,t,(function(){return r[t]}))}(o);n("4595");var a=n("f0c5"),s=Object(a["a"])(r["default"],i["b"],i["c"],!1,null,"56b10954",null,!1,i["a"],void 0);e["default"]=s.exports},fd78:function(t,e,n){"use strict";n.r(e);var i=n("0ac1"),r=n.n(i);for(var o in i)["default"].indexOf(o)<0&&function(t){n.d(e,t,(function(){return i[t]}))}(o);e["default"]=r.a},fffe:function(t,e,n){var i=n("543d");i.__esModule&&(i=i.default),"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var r=n("4f06").default;r("4ffcc1bf",i,!0,{sourceMap:!1,shadowMode:!1})}}]);