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
<title>乐窝</title>
<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width">
<meta name="format-detection" content="email=no,address=no,telephone=no">

</head>
<style>
    body {
        background-color: #68BB9B;
    }
    .daoshu {
        font-family: "微软雅黑";
        border: 1px solid #000;
        font-size: 9px;
        width:70px;
        height: 27px;
        line-height: 27px;
        text-align: center;
        position: absolute;
        right: 10px;
        top: 10px;
    }
    .loading_page {
        width:100%;
        position: absolute;
        top:0px;
        bottom: 0px;
        left: 0px;
        right: 0px;
        margin:auto;
    } 
</style>
<body>
    <div class="daoshu" id="daoshu">
        倒计时&nbsp;<span id="num">12</span>
    </div>
    <img class="loading_page" src="/Public/images/loading_page.jpg"/>
</body>
</html>
<script>
    window.onload = function(){
        var daoshu = document.getElementById("daoshu");
        var objnum = document.getElementById("num");
        var num = Number(objnum.innerHTML);
        var i = num;
        setInterval(function(){
            i = i - 1;
            objnum.innerHTML = i;
            if ( 0 == i ) {
                window.location.href = '<?php echo U("Home/Mapsearch/index");?>';
            }
        },"1000");
    }
</script>