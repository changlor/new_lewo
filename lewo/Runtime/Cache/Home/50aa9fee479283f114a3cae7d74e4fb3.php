<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="expires" content="0">    
<meta http-equiv="keywords" content="keyword1,keyword2,keyword3">
<meta http-equiv="description" content="This is my page">
<title><?php echo ($info['room_nickname']); ?></title>
<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width">
<meta name="format-detection" content="email=no,address=no,telephone=no">
<link href="/Public/css/normalize.css" rel="stylesheet" type="text/css">
<link href="/Public/css/bootstrap.min.css" rel="stylesheet">
<link href="/Public/css/bootstrap-theme.min.css" type="text/css">
<link rel="stylesheet" href="/Public/css/jquery-ui.min.css">
<link href="/Public/css/common.css" rel="stylesheet" type="text/css">
<link href="/Public/css/zt-fjxx.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/Public/js/iscroll.js"></script>
<!-- <script type="text/javascript" src="/Public/js/api"></script>
<script type="text/javascript" src="/Public/js/getscript"></script> -->
<script type="text/javascript">

var myScroll;

function loaded () {
	myScroll = new IScroll('#wrapper', { mouseWheel: true, click: true });
}

document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);

</script>
<style>
#wrapper {position: absolute; top: 44px; bottom: 49px; left: 0; width: 100%; overflow: hidden; background-color:#fff; }
.img-responsive {display: inline-block; height: auto; max-width: 100%; }
.table>tbody>tr>td{line-height:24px; white-space:nowrap; }
ol, ul{margin-bottom:0; }
</style>

</head>

<body onload="loaded()" class="keBody">
<div class="container-fluid header">
	<div class="row-fluid">
    	<div class="col-xs-4 header-icon1"><a href="javascript:history.go(-1);"><div class="retun-db-box"><img src="/Public/images/icon-db-retun.png" class="icon-db-retun"></div></a></div>
        <div class="col-xs-4 header-icon2"></div>
    </div>
</div>
<div id="wrapper">
    <div style="transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; transform: translate(0px, -288px) translateZ(0px);">
        <!--banner效果html开-->
        <div class="kePublic">
            <div class="main_visual">
                <div class="flicking_con">
                    <?php if(is_array($info["room_images"])): $i = 0; $__LIST__ = $info["room_images"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="" class=""></a><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
                <div class="main_image">
                    <ul style="width: 400px; overflow: visible;">
                        <?php if(is_array($info["room_images"])): $i = 0; $__LIST__ = $info["room_images"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><span class="img_1"><img src="/Uploads/<?php echo ($vo); ?>" class="img-zt-fjzp-ys"></span></li><?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                </div>
            </div>
        </div>
        <!--banner效果html关-->
        <!--你关心的html开-->
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="col-xs-12 titles-box-ys"><span class="titles-box-text-ys">你关心的</span></div>
            </div>
            <div class="row-fluid">
                <div class="col-xs-6 text-fjxx-wzys txet-padding-left20">小区名称：<?php echo ($info['area_name']); ?></div>
                <div class="col-xs-6 text-fjxx-wzys">房间编号：<?php echo ($info['room_code']); ?></div>
            </div>
            <div class="row-fluid">
                <div class="col-xs-6 text-fjxx-wzys txet-padding-left20"><span class="text-fjxx-letter-spacing-2">租</span>金：<span text-fjxx-zj-color=""><?php echo ($info['rent']); ?>/月</span></div>
                <div class="col-xs-6 text-fjxx-wzys text-fjxx-fwf-color"><span class="text-fjxx-letter-spacing-3">服务</span>费：<?php echo ($info['room_fee']); ?>/月</div>
            </div>
            <div class="row-fluid">
                <div class="col-xs-6 text-fjxx-wzys txet-padding-left20"><span class="text-fjxx-letter-spacing-2">面</span>积：<?php echo ($info['room_area']); ?>m²</div>
                <div class="col-xs-6 text-fjxx-wzys"><span class="text-fjxx-letter-spacing-2">楼</span>层：<?php echo ($info['house_info']['building']); ?>-<?php echo ($info['house_info']['floor']); ?>-<?php echo ($info['house_info']['door_no']); ?></div>
            </div>
            <div class="row-fluid">
                <div class="col-xs-12 text-fjxx-wzys txet-padding-left20"><span class="text-fjxx-letter-spacing-2">地</span>址：<?php echo ($info['address']); ?></div>
            </div>
        </div>
        <!--你关心的html关-->
        <!--房间配置html开-->
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="col-xs-12 titles-box-ys"><span class="titles-box-text-ys">房间配置</span></div>
            </div>
            <div class="row-fluid padding-z0y30">
                <div class="col-md-3">
                    <div class="row-fluid">
                        <div class="col-xs-4 box-h40">
                            <div class="img-ztf-pzicon-box img-ztf-icon-fjpzicon-1"><img src="/Public/images/ztf-icon-pzicon.png"></div>
                            <span class="icon-pzicon-text">床</span>
                        </div>
                        <div class="col-xs-4 box-h40">
                            <div class="img-ztf-pzicon-box img-ztf-icon-fjpzicon-2"><img src="/Public/images/ztf-icon-pzicon.png"></div>
                            <span class="icon-pzicon-text">书桌</span>
                        </div>
                        <div class="col-xs-4 box-h40">
                            <div class="img-ztf-pzicon-box img-ztf-icon-fjpzicon-3"><img src="/Public/images/ztf-icon-pzicon.png"></div>
                            <span class="icon-pzicon-text">空调</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="row-fluid">
                        <div class="col-xs-4 box-h40">
                            <div class="img-ztf-pzicon-box img-ztf-icon-fjpzicon-4"><img src="/Public/images/ztf-icon-pzicon.png"></div>
                            <span class="icon-pzicon-text">椅子</span>
                        </div>
                        <div class="col-xs-4 box-h40">
                            <div class="img-ztf-pzicon-box img-ztf-icon-fjpzicon-5"><img src="/Public/images/ztf-icon-pzicon.png"></div>
                            <span class="icon-pzicon-text">衣柜</span>
                        </div>
                        <div class="col-xs-4 box-h40">
                            <div class="img-ztf-pzicon-box img-ztf-icon-fjpzicon-6"><img src="/Public/images/ztf-icon-pzicon.png"></div>
                            <span class="icon-pzicon-text">鞋架</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="row-fluid">
                        <div class="col-xs-4 box-h40">
                            <div class="img-ztf-pzicon-box img-ztf-icon-fjpzicon-7"><img src="/Public/images/ztf-icon-pzicon.png"></div>
                            <span class="icon-pzicon-text">穿衣镜</span>
                        </div>
                        
                        
                    </div>
                </div>
                <div class="col-md-3"> 
                    <div class="row-fluid">
                        
                       <!--  <div class="col-xs-4 box-h40">
                            <div class="img-ztf-pzicon-box img-ztf-icon-fjpzicon-11"><img src="view//Public/images/ztf-icon-pzicon.png"></div>
                            <span class="icon-pzicon-text"></span>
                        </div>
                        <div class="col-xs-4 box-h40">
                            <div class="img-ztf-pzicon-box img-ztf-icon-fjpzicon-12"><img src="view//Public/images/ztf-icon-pzicon.png"></div>
                            <span class="icon-pzicon-text"></span>
                        </div>--> 
                    </div>
                </div>
            </div>
        </div>
        <!--房间配置html关-->
        <!--公共区域配置html开-->
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="col-xs-12 titles-box-ys"><span class="titles-box-text-ys">公共区域配置</span></div>
            </div>
            <div class="row-fluid padding-z0y30">
                <div class="col-md-3">
                    <div class="row-fluid">
                        <div class="col-xs-4 box-h40">
                            <div class="img-ztf-pzicon-box img-ztf-icon-ggpzicon-1"><img src="/Public/images/ztf-icon-pzicon.png"></div>
                            <span class="icon-pzicon-text">储物区</span>
                        </div>
                        <div class="col-xs-4 box-h40">
                            <div class="img-ztf-pzicon-box img-ztf-icon-ggpzicon-2"><img src="/Public/images/ztf-icon-pzicon.png"></div>
                            <span class="icon-pzicon-text">鞋柜</span>
                        </div>
                        <div class="col-xs-4 box-h40">
                            <div class="img-ztf-pzicon-box img-ztf-icon-ggpzicon-3"><img src="/Public/images/ztf-icon-pzicon.png"></div>
                            <span class="icon-pzicon-text">餐桌</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="row-fluid">
                        <div class="col-xs-4 box-h40">
                            <div class="img-ztf-pzicon-box img-ztf-icon-ggpzicon-4"><img src="/Public/images/ztf-icon-pzicon.png"></div>
                            <span class="icon-pzicon-text">餐椅</span>
                        </div>
                        <div class="col-xs-4 box-h40">
                            <div class="img-ztf-pzicon-box img-ztf-icon-ggpzicon-5"><img src="/Public/images/ztf-icon-pzicon.png"></div>
                            <span class="icon-pzicon-text">冰箱</span>
                        </div>
                        <div class="col-xs-4 box-h40">
                            <div class="img-ztf-pzicon-box img-ztf-icon-ggpzicon-6"><img src="/Public/images/ztf-icon-pzicon.png"></div>
                            <span class="icon-pzicon-text">热水器</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="row-fluid">
                        <div class="col-xs-4 box-h40">
                            <div class="img-ztf-pzicon-box img-ztf-icon-ggpzicon-7"><img src="/Public/images/ztf-icon-pzicon.png"></div>
                            <span class="icon-pzicon-text">洗衣机</span>
                        </div>
                        <div class="col-xs-4 box-h40">
                            <div class="img-ztf-pzicon-box img-ztf-icon-ggpzicon-8"><img src="/Public/images/ztf-icon-pzicon.png"></div>
                            <span class="icon-pzicon-text">微波炉</span>
                        </div>
                        <div class="col-xs-4 box-h40">
                            <div class="img-ztf-pzicon-box img-ztf-icon-ggpzicon-9"><img src="/Public/images/ztf-icon-pzicon.png"></div>
                            <span class="icon-pzicon-text">拖把</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="row-fluid">
                        <div class="col-xs-4 box-h40">
                            <div class="img-ztf-pzicon-box img-ztf-icon-ggpzicon-10"><img src="/Public/images/ztf-icon-pzicon.png"></div>
                            <span class="icon-pzicon-text">公共厨房</span>
                        </div>
                        <div class="col-xs-4 box-h40">
                            <div class="img-ztf-pzicon-box img-ztf-icon-ggpzicon-11"><img src="/Public/images/ztf-icon-pzicon.png"></div>
                            <span class="icon-pzicon-text">笤帚</span>
                        </div>
                        <div class="col-xs-4 box-h40">
                            <div class="img-ztf-pzicon-box img-ztf-icon-ggpzicon-12"><img src="/Public/images/ztf-icon-pzicon.png"></div>
                            <span class="icon-pzicon-text">WIFI</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--公共区域配置html关-->
        <!--户型图html开-->
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="col-xs-12 titles-box-ys"><span class="titles-box-text-ys">户型图</span></div>
            </div>
            <div class="row-fluid">
                <div class="col-xs-12 img-zt-fjhxt-box">
                    <div><img src=""></div>
                </div> 
            </div>
        </div>
        <!--户型图html关-->
        
        <!--视频html开-->
        <!-- <div class="container-fluid">
            <div class="row-fluid">
                <div class="col-xs-12 titles-box-ys"><span class="titles-box-text-ys">视频</span></div>
            </div>
            <div class="row-fluid">
                <div class="col-xs-12 img-fjsp-video-box">
                    <div><span><img src="/Public/images/sy-rmfy1.jpg"></span>
                        <a href="" class="sy-rmfy-img"><img src="/Public/images/icon-sy-viedo.png" class="icon-sy-viedo-box"></a>
                    </div>
                </div> 
            </div>
        </div> -->
        <!--视频html关-->
        <!--管家html开-->
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="col-xs-12 text-fjgl-box">
                	<p>军人管家24小时贴心守护您的居住安全</p>
                    <p>维修达人&nbsp;&nbsp;生活帮手&nbsp;&nbsp;认真专业</p>
                    <h3>乐窝管家·<?php echo ($info['steward_info']['nickname']); ?></h3>
                </div> 
            </div>
        </div>
        <!--管家html关-->
        <!--其他房间html开-->
        <!-- <div class="container-fluid">
            <div class="row-fluid">
                <div class="col-xs-12 titles-box-ys"><span class="titles-box-text-ys">其他房间</span></div>
            </div>
            <div class="row-fluid">
                <div class="col-xs-12 qtfj-padding-ys">
                    <div id="accordion" class="ui-accordion ui-widget ui-helper-reset" role="tablist">
                    <?php if(is_array($otherRoom)): $i = 0; $__LIST__ = $otherRoom;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>第一间房间html开+++++注：第一个房间都要加margin-top0
                        <a class="ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all" role="tab" id="ui-accordion-accordion-header-1" aria-controls="ui-accordion-accordion-panel-1" aria-selected="false" aria-expanded="false" tabindex="-1"><span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>
                            <ul class="box-boreer-margin-ys ">
                                <li class="icon-qtfj-box-wn1"></li>
                                <li class="text-qtfj-float-left ">房间<?php echo ($vo["room_sort"]); ?></li>
                                <li class="text-qtfj-float-right ">￥<?php echo ($vo["rent"]); ?></li>
                            </ul>
                        </a>
                        <div onclick="location.href='<?php echo U('Home/Steward/roominfo',array('id'=>$vo['id']));?>'" class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom" id="ui-accordion-accordion-panel-1" aria-labelledby="ui-accordion-accordion-header-1" role="tabpanel" aria-hidden="true">
                            <ul class="qtfj-img-box-overflow">
                                <li class="text-qtfj-float-left text-qtfj-padding0">
                                <?php if($vo['room_parameter']['yangtai'] == 1): ?><span class="text-bq-sx">阳台</span><?php endif; ?>
                                <?php if($vo['room_parameter']['piaochuang'] == 1): ?><span class="text-bq-sx">飘窗</span><?php endif; ?>
                                <?php if($vo['room_parameter']['duwei'] == 1): ?><span class="text-bq-sx">独卫</span><?php endif; ?>
                                <?php if($vo['room_parameter']['kongtiao'] == 1): ?><span class="text-bq-sx">空调</span><?php endif; ?>
                               <br>拎包入住&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ($vo["room_area"]); ?>m²
                                </li>
                                
                                <li class="img-fjzp-slt-box">
                                    <?php if(is_array($vo["room_images"])): $i = 0; $__LIST__ = $vo["room_images"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><img src="/Uploads/<?php echo ($v); ?>" class="img-fjzp-slt-ys"><?php endforeach; endif; else: echo "" ;endif; ?>
                                </li> 
                            </ul>
                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                </div>
            </div> 
        </div> -->
        <!--其他房间html关-->
        <!--附近房源html开-->
        <!-- <div class="container-fluid">
            <div class="row-fluid fynav-left-griht-box">
                <a><div class="col-xs-6 fynav-left-box">附近房源</div></a> 
                <a><div class="col-xs-6 fynav-griht-box"></div></a>
            </div>
            附近房间缩列图html开
            
            <a href="http://mp.loveto.co/account/roomInfo.lo?roomId=145949916532527"> 
            <div class="row-fluid">
                <div class="col-xs-12">
                    <div class="img-zt-fjzp-box"><img src="/Public/images/jsp_source_20151117142542193zz0jjt0k.jpg"></div>
                    <div class="img-zt-fjzp-textms-box">
                        <ul>
                            <li class="textms-box-ys1">老街印象6栋5-3&nbsp;&nbsp;ZYJ00089A</li>
                            <li class="textms-box-ys2">0米</li>
                            <li class="textms-box-ys3">1000.0元/月</li>
                            <li class="textms-box-ys4"></li>
                        </ul>
                    </div>
                </div>
            </div>
            </a>
            附近房间缩列图html关
            
            <a href="http://mp.loveto.co/account/roomInfo.lo?roomId=145949916532557"> 
            <div class="row-fluid">
                <div class="col-xs-12">
                    <div class="img-zt-fjzp-box"><img src="/Public/images/jsp_source_20151117142542193zz0jjt0k.jpg"></div>
                    <div class="img-zt-fjzp-textms-box">
                        <ul>
                            <li class="textms-box-ys1">老街印象6栋5-3&nbsp;&nbsp;ZYJ00089D</li>
                            <li class="textms-box-ys2">0米</li>
                            <li class="textms-box-ys3">900.0元/月</li>
                            <li class="textms-box-ys4"></li>
                        </ul>
                    </div>
                </div>
            </div>
            </a>
            附近房间缩列图html关
            
            <a href="http://mp.loveto.co/account/roomInfo.lo?roomId=145949916532537"> 
            <div class="row-fluid">
                <div class="col-xs-12">
                    <div class="img-zt-fjzp-box"><img src="/Public/images/jsp_source_20151117142542193zz0jjt0k.jpg"></div>
                    <div class="img-zt-fjzp-textms-box">
                        <ul>
                            <li class="textms-box-ys1">老街印象6栋5-3&nbsp;&nbsp;ZYJ00089B</li>
                            <li class="textms-box-ys2">0米</li>
                            <li class="textms-box-ys3">700.0元/月</li>
                            <li class="textms-box-ys4"></li>
                        </ul>
                    </div>
                </div>
            </div>
            </a>
            附近房间缩列图html关
            
            <a href="http://mp.loveto.co/account/roomInfo.lo?roomId=145949916532547"> 
            <div class="row-fluid">
                <div class="col-xs-12">
                    <div class="img-zt-fjzp-box"><img src="/Public/images/jsp_source_20151117142542193zz0jjt0k.jpg"></div>
                    <div class="img-zt-fjzp-textms-box">
                        <ul>
                            <li class="textms-box-ys1">老街印象6栋5-3&nbsp;&nbsp;ZYJ00089C</li>
                            <li class="textms-box-ys2">0米</li>
                            <li class="textms-box-ys3">680.0元/月</li>
                            <li class="textms-box-ys4"></li>
                        </ul>
                    </div>
                </div>
            </div>
            </a>
            附近房间缩列图html关
            
            <a href="http://mp.loveto.co/account/roomInfo.lo?roomId=145947919271317"> 
            <div class="row-fluid">
                <div class="col-xs-12">
                    <div class="img-zt-fjzp-box"><img src="/Public/images/jsp_source_20151117142542193zz0jjt0k.jpg"></div>
                    <div class="img-zt-fjzp-textms-box">
                        <ul>
                            <li class="textms-box-ys1">渝景新天地5栋20-7&nbsp;&nbsp;ZYJ00001C</li>
                            <li class="textms-box-ys2">13米</li>
                            <li class="textms-box-ys3">800.0元/月</li>
                            <li class="textms-box-ys4"></li>
                        </ul>
                    </div>
                </div>
            </div>
            </a>
            附近房间缩列图html关
            
        </div> -->
        <!--附近房源html关-->
    </div>
</div>
<!--电话-在线预约开html开-->
<div class="container-fluid bottom-gjdl-call-yd">
    <div class="row-fluid">
        <a href="<?php echo U('Home/Steward/order',array('id'=>$info['id']));?>">
            <div class="col-xs-6 bottom-yd-box">
            <div class="bottom-yd-box-ys"><span><img src="/Public/images/icon-call-text.png"></span></div>
            <p>交定</p>
        </div>
        </div>
        </a>
        <a href="<?php echo U('Home/Steward/checkin',array('id'=>$info['id']));?>">
            <div class="col-xs-6 bottom-yd-box">
            <div class="bottom-yd-box-ys"><span><img src="/Public/images/icon-call-text.png"></span></div>
            <p>签约</p>
        </div>
        </div>
        </a>
    </div>
</div>
<!--电话-在线预约开html关-->

<script type="text/javascript" src="/Public/js/jquery-1.9.1.js"></script>
<script src="/Public/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/Public/js/jquery.event.drag-1.5.min.js"></script>
<script src="/Public/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="/Public/js/jquery.touchSlider.js"></script>

<script type="text/javascript">

//=====================banner触屏滑动开=====================
$(document).ready(function(){

    $(".main_visual").hover(function(){
        $("#btn_prev,#btn_next").fadeIn()
    },function(){
        $("#btn_prev,#btn_next").fadeOut()
    });
    
    $dragBln = false;
    
    $(".main_image").touchSlider({
        flexible : true,
        speed : 200,
        btn_prev : $("#btn_prev"),
        btn_next : $("#btn_next"),
        paging : $(".flicking_con a"),
        counter : function (e){
            $(".flicking_con a").removeClass("on").eq(e.current-1).addClass("on");
        }
    });
    
    $(".main_image").bind("mousedown", function() {
        $dragBln = false;
    });
    
    $(".main_image").bind("dragstart", function() {
        $dragBln = true;
    });
    
    $(".main_image a").click(function(){
        if($dragBln) {
            return false;
        }
    });
    
    timer = setInterval(function(){
        $("#btn_next").click();
    }, 5000);
    
    $(".main_visual").hover(function(){
        clearInterval(timer);
    },function(){
        timer = setInterval(function(){
            $("#btn_next").click();
        },5000);
    });
    
    $(".main_image").bind("touchstart",function(){
        clearInterval(timer);
    }).bind("touchend", function(){
        timer = setInterval(function(){
            $("#btn_next").click();
        }, 5000);
    });
    
});
//=====================banner触屏滑动关=====================

//=====================房间点击展开收起开=====================
$(function() {
$( "#accordion" ).accordion({
  collapsible: true
});
});
//=====================房间点击展开收起关=====================
</script>

</body></html>