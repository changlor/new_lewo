<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="expires" content="0">    
    <meta http-equiv="keywords" content="keyword1,keyword2,keyword3">
    <meta http-equiv="description" content="This is my page">
    <title>租客个人信息</title>
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width">
    <script src="/Public/js/jquery-1.9.1.js" type="text/javascript"></script>
    <link href="/Public/css/normalize.css" rel="stylesheet" type="text/css">
    <link href="/Public/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Public/css/bootstrap-theme.min.css" type="text/css">
    <link href="/Public/css/common.css" rel="stylesheet" type="text/css">
    <link href="/Public/css/grzx.css" rel="stylesheet" type="text/css">
    <script src="/Public/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/Public/js/iscroll.js"></script>
    <script type="text/javascript">

    var myScroll;

    function loaded () {
        myScroll = new IScroll('#wrapper', { mouseWheel: true,click:true });
    }

    document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);

    </script>
    <style>
    #wrapper {
        position: absolute;
        top: 44px;
        bottom: 50px;
        left: 0;
        width: 100%;
        overflow: hidden;
        background-color:#f4f4f4;
    }
    .img-responsive {
        display: inline-block;
        height: auto;
        max-width: 100%;
    }

    </style>
</head>

<body onload="loaded()">
<div id="fwxx">
    <div class="container-fluid header">
        <div class="row-fluid">
            <div class="col-xs-4 header-icon1"><a href="<?php echo U('Home/Tenant/index');?>"><div class="retun-db-box"><img src="/Public/images/icon-db-retun.png" class="icon-db-retun"></div></a></div>
        </div>
    </div>
    <div id="wrapper">
      <div style="transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; transform: translate(0px, 0px) translateZ(0px);">
        <div class="container-fluid">
          <div class="row-fluid">
            <div class="col-xs-12">
              <div class="row-fluid">
                <div class="col-xs-12">
                  <div class="img-icon1-dx">
                    <div class="row-fluid">
                      <div class="col-xs-12">
                        <div class="img-icon1-tx"><img src="/Public/images/user.jpg" style="margin-top:20px; width:66px; height:66px; border-radius:66px; border:2px solid #fff;" class="img-responsive"></div>
                      </div>
                    </div>
                    <div class="row-fluid">
                      <div class="col-xs-12">
                        <div class="banner-wz"><span style="font-size:14px;"><?php echo ($account_info["realname"]); ?></span><br>
                        <div class="banner-wz"><span style="font-size:12px;">
                          余额:<?php echo ($account_info["balance"]); ?>
                        </span>
                          </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row-fluid">
              <div class="col-xs-12">
                <div class="border-bot-xt-wdfj"></div>
              </div>
            </div>
            <!--第一部分头-->
            <div>
              <div class="row-fluid">
                <div class="col-xs-12">
                  <a href="<?php echo U('Home/Tenant/edit_myinfo',array('type'=>'realname'));?>">
                  <ul class="border-bot-xt-grxx-icon">
                    <li class="left-grxx-icon"><img src="/Public/images/icon-grzx-nc.png" class="icon-grxi-szxq-dx"><span class="icon-grxx-wz">姓名</span></li>
                    <li class="right-grxx-icon"><span class="icon-grxx-wz-xq"><?php echo ($account_info["realname"]); ?></span><img src="/Public/images/icon-grxi-jt.png" class="icon-grxi-jt-dx"></li>
                  </ul>
                  </a>
                  <a href="<?php echo U('Home/Tenant/edit_myinfo',array('type'=>'sex'));?>">
                  <ul class="border-bot-xt-grxx-icon">
                    <li class="left-grxx-icon"><img src="/Public/images/icon-grzx-xb.png" class="icon-grxi-szxq-dx"><span class="icon-grxx-wz">性别</span></li>
                    <li class="right-grxx-icon">
                      <span class="icon-grxx-wz-xq">

                        <?php switch($account_info["sex"]): case "1": ?>男<?php break;?>
                          <?php case "2": ?>女<?php break;?>
                          <?php default: ?>未选择<?php endswitch;?>

                      </span>
                      <img src="/Public/images/icon-grxi-jt.png" class="icon-grxi-jt-dx">
                    </li>
                  </ul>
                  </a>
                  <a href="<?php echo U('Home/Tenant/edit_myinfo',array('type'=>'birthday'));?>">
                  <ul class="border-bot-xt-grxx-icon">
                    <li class="left-grxx-icon"><img src="/Public/images/icon-grzx-sr.png" class="icon-grxi-szxq-dx"><span class="icon-grxx-wz">生日</span></li>
                    <li class="right-grxx-icon"><span class="icon-grxx-wz-xq">
                      <?php echo ($account_info["birthday"]); ?>
                    </span><img src="/Public/images/icon-grxi-jt.png" class="icon-grxi-jt-dx"></li>
                  </ul>
                  </a>
                  <a href="<?php echo U('Home/Tenant/edit_myinfo',array('type'=>'tag'));?>">
                  <ul class="border-bot-xt-grxx-icon  border-bot-0px">
                    <li class="left-grxx-icon"><img src="/Public/images/icon-grzx-ms.png" class="icon-grxi-szxq-dx"><span class="icon-grxx-wz">自我描述</span></li>
                    <li class="right-grxx-icon"><span class="icon-grxx-wz-xq"><?php echo ($account_info["tag"]); ?></span><img src="/Public/images/icon-grxi-jt.png" class="icon-grxi-jt-dx"></li>
                  </ul>
                  </a>
                  <div class="icon-grxx-te-15px"></div>
                </div>
              </div>
            </div>
            <!--第一部分尾-->
            <!--第二部分头-->
            <div>
              <div class="row-fluid">
                <div class="col-xs-12">
                  <a href="<?php echo U('Home/Tenant/edit_myinfo',array('type'=>'password'));?>">
                  <ul class="border-bot-xt-grxx-icon">
                    <li class="left-grxx-icon"><img src="/Public/images/icon-grxi-xgma.png" class="icon-grxi-szxq-dx"><span class="icon-grxx-wz">修改密码</span></li>
                    <li class="right-grxx-icon"><span class="icon-grxx-wz-xq"></span><img src="/Public/images/icon-grxi-jt.png" class="icon-grxi-jt-dx"></li>
                  </ul>
                  </a>
                  <a href="<?php echo U('Home/Tenant/edit_myinfo',array('type'=>'wx'));?>">
                  <ul class="border-bot-xt-grxx-icon  border-bot-0px">
                    <li class="left-grxx-icon"><img src="/Public/images/icon-grxi-sfz.png" class="icon-grxi-szxq-dx"><span class="icon-grxx-wz">微信绑定</span></li>
                    <li class="right-grxx-icon"><span class="icon-grxx-wz-xq"></span><img src="/Public/images/icon-grxi-jt.png" class="icon-grxi-jt-dx"></li>
                  </ul>
                  </a>
                  <div class="icon-grxx-te-15px"></div>
                </div>
              </div>
            </div>
            <!--第二部分尾-->
            <div class="row-fluid">
              <div class="col-xs-12"><div style="height:45px;"></div></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container-fluid button">
    <div class="row-fluid button-ds">
        <div class="col-xs-12 padding-left-right-24">
            <div class="button-ckzi-col12"><a href="<?php echo U('Home/Tenant/login_out');?>" class="button-htzh-dx">退出登录</a></div>
        </div>
    </div>
  </div>
</div>
</body>
</html>