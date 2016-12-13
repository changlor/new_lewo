<?php if (!defined('THINK_PATH')) exit();?><!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a> <a>房源管理</a> <a>房源列表</a> <a class="current"><?php echo ($house_code); ?>的账单</a></div>
  </div>
<!--End-breadcrumbs-->
  <div class="container-fluid">
      <div class="widget-box">
      <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
        <h5><?php echo ($house_code); ?>的账单列表</h5>
        <a href="<?php echo U('Admin/Houses/index',array('area_id'=>$_SESSION['area_id']));?>" class="label label-info pull-left">返回</a>
        <!-- <a href="<?php echo U('Admin/Houses/add_house');?>" class="label label-success"><i class="icon-plus"></i>添加房源</a>
        <a href="#" class="label label-info"><i class="icon-calendar"></i>下载水电气汇总</a> -->
      </div>
      <div class="widget-content nopadding">
        <table class="table table-bordered data-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>年</th>
                <th>月</th>
                <th>生成状态</th>
                <th>发送状态(已发/总数)</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody>
              <?php if(is_array($charge_list)): $i = 0; $__LIST__ = $charge_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                <td><?php echo ($vo["id"]); ?></td>
                <td><?php echo ($vo["input_year"]); ?></td>
                <td><?php echo ($vo["input_month"]); ?></td>
                <td>
                  <?php if($vo['is_create'] == 1): ?><span class="label label-success">已生成</span>
                  <?php else: ?>
                    <span id="bill_status<?php echo ($vo["id"]); ?>" class="label label-important">未生成</span><?php endif; ?>
                </td>
                <td>
                  <?php if($vo['sended_count'] == $vo['total_count'] AND $vo['total_count'] != 0): ?><span class="label label-success"><?php echo ($vo["sended_count"]); ?>/<?php echo ($vo["total_count"]); ?></span>
                  <?php else: ?>
                    <span id="bill_status<?php echo ($vo["id"]); ?>" class="label label-important"><?php echo ($vo["sended_count"]); ?>/<?php echo ($vo["total_count"]); ?></span><?php endif; ?>
                </td>
                <td>
                <?php if($vo["is_create"] != 1): ?><a class="btn btn-success btn-mini create_bill" data-id="<?php echo ($vo["id"]); ?>" data-houseId="<?php echo ($vo["house_id"]); ?>" data-year="<?php echo ($vo["input_year"]); ?>" data-month="<?php echo ($vo["input_month"]); ?>">生成账单</a>
                  <?php else: ?>
                  <a class="btn btn-warning btn-mini re_create_bill" data-id="<?php echo ($vo["id"]); ?>" data-houseId="<?php echo ($vo["house_id"]); ?>" data-year="<?php echo ($vo["input_year"]); ?>" data-month="<?php echo ($vo["input_month"]); ?>">重新生成</a><?php endif; ?>
                  <a href="<?php echo U('Admin/Houses/check_bill',array('charge_id'=>$vo['id'],'house_id'=>$vo['house_id'],'year'=>$vo['input_year'],'month'=>$vo['input_month']));?>" class="btn btn-info btn-mini">查看账单</a>
                  <a href="<?php echo U('Admin/Houses/check_SDQ',array('house_id'=>$vo['house_id'],'year'=>$vo['input_year'],'month'=>$vo['input_month']));?>" target="_blank" class="btn btn-info btn-mini">查看水电气</a>
                  <!-- <a href="<?php echo U('Admin/Houses/send_bill',array('house_id'=>$vo['house_id'],'year'=>$vo['input_year'],'month'=>$vo['input_month']));?>" class="btn btn-warning btn-mini" onclick="if(!confirm('是否发送')) return false;">发送账单</a>
                  <a href="#" class="btn btn-danger btn-danger btn-mini" onclick="if(!confirm('是否删除')) return false;">删除</a> -->
                </td>
              </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
          </table>
      </div>
    </div>
  </div>
</div>
<!--main-container-part-->