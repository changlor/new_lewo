<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>找房</title>
  <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width">
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">    
	<meta http-equiv="keywords" content="keyword1,keyword2,keyword3">
	<meta http-equiv="description" content="This is my page">
	<link href="/Public/css/normalize.css" rel="stylesheet" type="text/css">
	<link href="/Public/css/bootstrap.min.css" rel="stylesheet">
	<link href="/Public/css/bootstrap-theme.min.css" type="text/css">
	<link href="/Public/css/common.css" rel="stylesheet" type="text/css">
	<link href="/Public/css/map-search.css" rel="stylesheet" type="text/css">
	<script src="/Public/js/jquery-1.9.1.js" type="text/javascript"></script>
	<script src="/Public/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="/Public/js/iscroll.js"></script>
  <script type="text/javascript">

  var myScroll;

  function loaded () {
    myScroll = new IScroll('#wrapper', { mouseWheel: true, click: true  });
  }

  document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);

  </script>
  <style>
  .loading_page {
    width:100%;
    height: 100%;
    background-color:#68BB9B;
    font-family: "微软雅黑";
    position: relative;
    z-index: 999;
    top: 0px;
  }
  .loading_page img {
    width:50%;
    position: absolute;
    top: 0px;
    bottom: 0px;
    left: 0px;
    right: 0px;
    margin:auto;
  }
  .loading_page .daojishi {
    border:1px solid #000;
    border-radius: 10px;
    background-color: #fff;
    width: 70px;
    text-align: center;
    position: absolute;
    top:5px;
    right: 5px;
    display: none;
  }
  .loading_page .check {
    display: block;
    width: 80px;
    height: 30px;
    line-height: 30px;
    text-align: center;
    border:1px solid #000;
    border-radius: 10px;
    background-color: #fff;
    margin:auto;
    position: absolute;
    bottom: 0px;
    top: 400px;
    right: 0px;
    left: 0px;
  }
  .loading_page .des {
    color: #fff;
  }
  </style>
</head>

<body onload="loaded();">
<div id="fwxx">
  <!--着落页开-->
  <div class="loading_page" id="loading_page">
    <div class="daojishi">倒计时&nbsp;<span id="num">3</span></div>
    <img src="/Public/images/logo2.png"/>
    <!-- <a class="check" href="#">点击查看</a>
       -->
  </div>
  <script>
      var loading_page = document.getElementById("loading_page");
      var objnum = document.getElementById("num");
      var num = Number(objnum.innerHTML);
      var i = num;
      setInterval(function(){
          i = i - 1;
          objnum.innerHTML = i;
          if ( 0 == i ) {
              loading_page.style.display = "none";
          }
      },"1000");
  </script>
  <!--着落页关-->
	<div class="container-fluid header">
  	<div class="row-fluid">
    	<div class="col-xs-2 header-icon1"><a href="javascript:window.history.back(-1)"><div class="retun-db-box"><img src="/Public/images/icon-db-retun.png" class="icon-db-retun"></div></a></div>
        <div class="col-xs-8">
	        <div class="ssearch">
	          <form action="">
	            <input class="iSearch" type="search" name="" value="" placeholder="请输入">
	            <!-- <input class="isubmit" type="submit" value="搜索"> -->
	          </form>
	        </div>
        </div>
	    <div class="col-xs-2 ztfj-top-pad2">
	        <a href="#">
	           <div class="ztfj-soos-icon-dw"></div> 
	        </a>
	    </div>
    </div>
  </div>

  <div id="wrapper" style="height:540px;">
      <div class="container-fluid ">
      <!-- 搜索功能 -->
      <div>
        <ul class="search-wrap">
          <li class="col-xs-3 nav-item search-nav-first">
            <div>区域</div>
            <ul class="search-nav-second">
              <?php if(is_array($region_arr)): $i = 0; $__LIST__ = $region_arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                <div><?php echo ($vo["region_name"]); ?></div>
                  <ul class="search-nav-third">
                    <li><div class="clickSearch">不限</div></li>
                    <?php if(is_array($vo["c"])): $i = 0; $__LIST__ = $vo["c"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li class="clickSearch" data-region-id="<?php echo ($v["id"]); ?>"><div><?php echo ($v["region_name"]); ?></div></li><?php endforeach; endif; else: echo "" ;endif; ?>
                  </ul>
              </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
          </li>
          <li class="col-xs-3 nav-item search-nav-first">
            <div>租金</div>
            <ul class="search-nav-second">
              <li><div class="clickSearch">不限</div></li>
              <li><div class="clickSearch" data-money="0-1000">0~1000</div></li>
              <li><div class="clickSearch" data-money="1000-2000">1000~2000</div></li>
              <li><div class="clickSearch" data-money="2000">2000以上</div></li>
            </ul>
          </li>
          <li class="col-xs-3 nav-item search-nav-first">
            <div>类型</div>
            <ul class="search-nav-second">
              <li><div class="clickSearch">不限</div></li>
              <li><div class="clickSearch" data-type="1">单间</div></li>
              <li><div class="clickSearch" data-type="2">床位</div></li>
            </ul>
          </li>
          <li class="col-xs-3 nav-item search-nav-first">
            <div>排序</div>
            <ul class="search-nav-second">
              <li><div class="clickSearch">默认</div></li>
              <li><div class="clickSearch" data-sort="asc">低价优先</div></li>
              <li><div class="clickSearch" data-sort="desc">高级优先</div></li>
            </ul>
          </li>
        </ul>
      </div>
      <!-- 搜索功能END -->
      <?php if(is_array($room_arr)): $i = 0; $__LIST__ = $room_arr;if( count($__LIST__)==0 ) : echo "没有搜索到信息..." ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="<?php echo U('Home/Mapsearch/detail_room',array('id'=>$vo['id']));?>"> 
        <div class="row-fluid">
              <div class="col-xs-12 boxcolor-top-bot-margin10">
                  <div class="img-zt-fjzp-box">

                  <?php if($vo["room_head_images"] != ''): ?><img class="lazy" src="/Uploads/<?php echo ($vo["room_head_images"]); ?>" width="140" height="100">
                    <?php else: ?>
                    <img class="lazy" src="/Public/images/sorry_img.png" width="140" height="100"><?php endif; ?>
                    
                  </div>
                  <div class="img-zt-fjzp-textms-box">
                      <ul>
                          <li class="textms-box-ys1"><?php echo ($vo["area_name"]); echo ($vo["buliding"]); ?>栋<?php echo ($vo["floor"]); ?>-<?php echo ($vo["door_no"]); ?>&nbsp;&nbsp;<?php echo ($vo["room_code"]); echo ($vo["bed_code"]); ?></li>
                          <li class="textms-box-ys3"><?php echo ($vo["rent"]); ?>元/月-<?php echo ($vo["rent_out_type_name"]); ?></li>
                          <li class="textms-box-ys4">
                            <?php if($vo['room_parameter']['yangtai'] != null): ?><span class="text-bq-sx">阳台</span><?php endif; ?>
                            <?php if($vo['room_parameter']['piaochuang'] != null): ?><span class="text-bq-sx">飘窗</span><?php endif; ?>
                            <?php if($vo['room_parameter']['duwei'] != null): ?><span class="text-bq-sx">独卫</span><?php endif; ?>
                            <?php if($vo['room_parameter']['kongtiao'] != null): ?><span class="text-bq-sx">空调</span><?php endif; ?>
                          </li>
                      </ul>
                  </div>
              </div>
           
        </div>
        </a><?php endforeach; endif; else: echo "没有搜索到信息..." ;endif; ?>
      </div>
    </div>
  </div>

  <!-- <div class="button">
    <div class="button_ds">
      <div class="container-fluid">
        <div class="row-fluid">
          <a href="">
            <div class="col-xs-4">
              <img src="/Public/images/icon_sy.png" class="img-responsive img-icon3-dx"><br>
              <span class="nav-bootom-zt">首页</span>
            </div>
          </a>
          <a href="http://mp.loveto.co/account/mapSearch.lo">
            <div class="col-xs-4">
              <img src="/Public/images/icon_zf.png" class="img-responsive img-icon3-dx"><br>
              <span class="nav-bootom-zt">找房</span>
            </div>
          </a>
          <a href="">
            <div class="col-xs-4"> <img src="/Public/images/icon_wo.png" class="img-responsive img-icon3-dx"> <br>
              <span class="nav-bootom-zt" style="color:#68bb9b;">我</span>
            </div>
          </a>
        </div>
      </div>
    </div>
  </div> -->
</body>
</html>
<script>
$(window).load(function(){
  $(document).bind("click",function(e){
    var target = $(e.target);
    if(target.closest(".search-wrap").length == 0){
      $(".search-nav-first > .search-nav-second").each(function(k,v){
        $(v).hide();
      });
    }
  })
  /*二级的显示/隐藏*/
  $(".search-nav-first > div").click(function(e){
    //获取二级
    var UL = $(this).siblings(".search-nav-second");

    if ($(UL).css("display") == "block") {
      $(UL).slideUp();
      return false;
    }
    //二级的同级隐藏
    $(".search-nav-first > .search-nav-second").each(function(k,v){
      $(v).hide();
    });
    $(UL).slideDown();
  });
  /*区域三级的显示/隐藏*/
  $(".search-nav-second > li > div").click(function(e){
    //获取二级
    //获取当前二级的三级ul
    var thirdUl = $(this).siblings(".search-nav-third");
    //消除二级的点击效果
    $(".search-nav-second").each(function(k,v){
      var subli = $(v).find(".action");
      $(subli).removeClass("action");
    });
    //添加action点击效果
    var cn = $(this).attr("class");
    if ( cn != "pack-up"){
      $(this).addClass("action");
    }
    //隐藏全部三级ul
    $(".search-nav-third").each(function(k,v){
      $(v).hide();
    });
    //显示当前点击的三级ul
    thirdUl.show();
  });
  /*收起*/
  $(".pack-up").click(function(){
    var pUL = $(this).parent("li").parent(".search-nav-second");
    pUL.slideUp();
  });

  $(".clickSearch").click(function(e){

    if ( $(this).attr("data-region-id") != undefined ) {
      window.location.href = '/index.php/Home/Mapsearch/index?region_id='+$(this).attr("data-region-id");
      return false;
    }

    if ( $(this).attr("data-money") != undefined ) {
      window.location.href = '/index.php/Home/Mapsearch/index?money='+$(this).attr("data-money");
      return false;
    }

    if ( $(this).attr("data-type") != undefined ) {
      window.location.href = '/index.php/Home/Mapsearch/index?type='+$(this).attr("data-type");
      return false;
    }

    if ( $(this).attr("data-sort") != undefined ) {
      window.location.href = '/index.php/Home/Mapsearch/index?sort='+$(this).attr("data-sort");
      return false;
    }
    
     window.location.href = '/index.php/Home/Mapsearch/index';
  });
});

</script>