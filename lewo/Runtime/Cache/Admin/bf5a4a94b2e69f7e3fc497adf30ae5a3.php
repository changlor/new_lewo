<?php if (!defined('THINK_PATH')) exit();?><!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a> <a>房源管理</a> <a class="current">房源列表</a></div>
  </div>
<!--End-breadcrumbs-->
  <div class="container-fluid">
      <div class="widget-box">
      <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
        <h5>房源列表</h5>
        <a href="<?php echo U('Admin/Houses/area');?>" class="label label-info pull-left">返回</a>
        <a href="<?php echo U('Admin/Houses/add_house');?>" class="label label-success"><i class="icon-plus"></i>添加房源</a>
        <a href="<?php echo U('Admin/Houses/all_houses_table');?>" class="label label-info"><i class="icon-calendar"></i>租客管理统计</a>
        <a href="#" class="label label-info"><i class="icon-calendar"></i>下载水电气汇总</a>
      </div>
      <div class="widget-content nopadding">
        <table class="table table-bordered data-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>地区</th>
                <th>所在小区</th>
                <th>房屋编号</th>
                <th>出租类型</th>
                <th>栋-层-房号</th>
                <th>管家</th>
                <th>管家电话</th>
                <th>租住</th>
                <th>账单信息(已发:总数)</th>
<!--                 <th>该月账单状态(已发/总数)</th> -->
                <th>操作</th>
              </tr>
            </thead>
            <tbody>
              <?php if(is_array($housesList)): $i = 0; $__LIST__ = $housesList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                <td><?php echo ($vo["house_id"]); ?></td>
                <td><?php echo ($vo["city_id"]); ?></td>
                <td><?php echo ($vo["area_name"]); ?></td>
                <td><?php echo ($vo["house_code"]); ?></td>
                <td>
                  <?php switch($vo["type"]): case "1": ?>间<?php break;?>
                    <?php case "2": ?>床<?php break; endswitch;?>
                </td>
                <td><?php echo ($vo["building"]); ?>-<?php echo ($vo["floor"]); ?>-<?php echo ($vo["door_no"]); ?></td>
                <td><?php echo ($vo["steward_nickname"]); ?></td>
                <td><?php echo ($vo["steward_mobile"]); ?></td>
                <td>
                  <?php if($vo['is_checkin'] == true): ?><span class="label label-success">有租
                    <?php else: ?>
                    <span class="label label-important">未租<?php endif; ?>
                  <?php echo ($vo["yz_count"]); ?>/<?php echo ($vo["count"]); ?></span>
                </td>
                <td>
                  <a href="<?php echo U('Admin/Houses/check_bill',array('house_id'=>$vo['house_id'],'year'=>$year,'month'=>$month));?>" class="btn btn-success btn-mini check-bill" data-houseId="<?php echo ($vo["id"]); ?>" data-year="<?php echo ($year); ?>" data-month="<?php echo ($month); ?>"><?php echo ($month); ?>月:<?php echo ($vo["now_sended_count"]); ?>/<?php echo ($vo["now_total_count"]); ?></a>
                  <a href="<?php echo U('Admin/Houses/check_bill',array('house_id'=>$vo['house_id'],'year'=>$lastYear,'month'=>$lastMonth));?>" class="btn btn-success btn-mini check-bill" data-houseId="<?php echo ($vo["id"]); ?>" data-year="<?php echo ($lastYear); ?>" data-month="<?php echo ($lastMonth); ?>"><?php echo ($lastMonth); ?>月:<?php echo ($vo["last_sended_count"]); ?>/<?php echo ($vo["last_total_count"]); ?></a>
                </td>
 <!--                <td>
   <?php if($vo['sended_count'] == $vo['total_count'] AND $vo['total_count'] != 0): ?><span class="label label-success"><?php echo ($vo["sended_count"]); ?>/<?php echo ($vo["total_count"]); ?></span>
   <?php else: ?>
     <span id="bill_status<?php echo ($vo["id"]); ?>" class="label label-important"><?php echo ($vo["sended_count"]); ?>/<?php echo ($vo["total_count"]); ?></span><?php endif; ?>
 </td> -->
                <td>
                  <a href="<?php echo U('Admin/Houses/detail_house',array('house_code'=>$vo['house_code'],'area_id'=>$area_id));?>" class="btn btn-info btn-mini">查看房屋</a>
                  <a href="<?php echo U('Admin/Houses/bill_list',array('house_id'=>$vo['house_id'],'area_id'=>$area_id));?>" class="btn btn-info btn-mini">查看账单</a>
                  <a href="<?php echo U('Admin/Houses/update_house',array('house_code'=>$vo['house_code']));?>" class="btn btn-warning btn-mini" target="_blank">修改</a>
                  <!-- <a href="#" class="btn btn-danger btn-danger btn-mini" onclick="if(!confirm('是否删除')) return false;">删除</a> -->
                </td>
              </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
          </table>
      </div>
    </div>
  </div>
</div>
<!--main-container-part-->