<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<title>房源管理统计</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="/Public/admin/css/bootstrap.min.css" />
	<style type="text/css">
		table td {
			text-align: center;
		}
		.search {
			position: fixed;
			top: 0px;
		}
	</style>
</head>
<body>
<form action="" class="search">
	<input type="text" name="search" value="<?php echo ($search); ?>">
	<button class="btn btn-info">搜索</button>
	<a href="javascript:window.history.go(-1)" class="btn btn-info">返回</a>
</form>
<br/>
<br/>
<table class="table table-striped">
	<thead>
		<tr>
			<th>序列</th>
			<th>房屋编号</th>
			<th>小区以及房间号</th>
			<th>房间</th>
			<th>承租人</th>
			<th>合同</th>
			<th>电话号码</th>
			<th>租期开始</th>
			<th>租期结束</th>
			<th>缴费到期</th>
			<th>押金</th>
			<th>房租</th>
			<th>服务费</th>
			<th>物管</th>
		</tr>
	</thead>
	<tbody>
	<?php if(is_array($room_list)): $i = 0; $__LIST__ = $room_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
			<td><?php echo ($key); ?></td>
			<td><?php echo ($vo["house_code"]); ?></td>
			<td><?php echo ($vo["area_name"]); ?>(<?php echo ($vo["building"]); ?>-<?php echo ($vo["floor"]); ?>-<?php echo ($vo["door_no"]); ?>)</td>
			<td><?php echo ($vo["room_sort"]); ?></td>
			<td><a href="<?php echo U('Admin/Tenant/tenant_info',array('account_id'=>$vo['account_id']));?>" target="_blank" class="btn btn-info btn-mini"><?php echo ($vo["realname"]); ?></a></td>
			<td><a href="<?php echo U('Admin/Tenant/contract_info',array('account_id'=>$vo['account_id'],'room_id'=>$vo['room_id']));?>" target="_blank" class="btn btn-info btn-mini">查看合同</a></td>
			<td><?php echo ($vo["mobile"]); ?></td>
			<td><?php echo ($vo["start_time"]); ?></td>
			<td><?php echo ($vo["end_time"]); ?></td>
			<td><?php echo ($vo["rent_date"]); ?></td>
			<td><?php echo ($vo["deposit"]); ?></td>
			<td><?php echo ($vo["ht_rent"]); ?></td>
			<td><?php echo ($vo["ht_fee"]); ?></td>
		</tr><?php endforeach; endif; else: echo "" ;endif; ?>
	</tbody>
</table>
</body>
</html>