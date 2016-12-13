<?php if (!defined('THINK_PATH')) exit();?><!--sidebar-menu-->
<div id="sidebar">
  <a href="<?php echo U('Admin/Index/index');?>" class="visible-phone"><i class="icon icon-home"></i>首页</a>
  <ul>
    <li class="<?php if(CONTROLLER_NAME == 'Index'): ?>active<?php endif; ?>">
      <a href="<?php echo U('Admin/Index/index');?>">
        <i class="icon icon-home"></i>
        <span>首页</span>
      </a> 
    </li>
    <li class="submenu <?php if(CONTROLLER_NAME == 'Task'): ?>active<?php endif; ?>">
      <a>
        <i class="icon icon-inbox"></i>
        <span>工作台</span>
        <span id="schedule_count" class="label label-important"><?php echo (session('schedule_count')); ?></span>
      </a>
        <ul>
          <li>
            <a href="<?php echo U('Admin/Task/index');?>">
              <span>待办</span>
              <span id="schedule_count" class="label label-important"><?php echo (session('schedule_count')); ?></span>
            </a>
          </li>
          <li><a href="<?php echo U('Admin/Task/finish_task');?>">已办</a></li>
        </ul>
    </li>

<?php if(($_SESSION['admin_type'] == 99) OR ($_SESSION['admin_type'] == 2) OR ($_SESSION['admin_type'] == 4) ): ?><li class="submenu <?php if(CONTROLLER_NAME == 'Pay'): ?>active<?php endif; ?>">
      <a>
        <i class="icon icon-th"></i>
        <span>支付列表</span>
      </a>
      <ul>
        <li><a href="<?php echo U('Admin/Pay/all_bill');?>">账单列表</a></li>
        <!--<li><a href="<?php echo U('Admin/Pay/daily_bill');?>">日常账单</a></li>
        <li><a href="<?php echo U('Admin/Pay/contract_bill');?>">合同账单</a></li>-->
      </ul>
    </li><?php endif; ?>

    <li class="submenu <?php if(CONTROLLER_NAME == 'Houses'): ?>active<?php endif; ?>">
      <a>
        <i class="icon icon-fullscreen"></i>
        <span>房源管理</span>
      </a>
      <ul>
        <li><a href="<?php echo U('Admin/Houses/area');?>">小区列表</a></li>
        <li><a href="<?php echo U('Admin/Houses/index');?>">房源列表</a></li>
      </ul>
    </li>

<?php if(($_SESSION['admin_type'] == 99) OR ($_SESSION['admin_type'] == 4)): ?><li class="submenu <?php if(CONTROLLER_NAME == 'Steward'): ?>active<?php endif; ?>">
      <a>
        <i class="icon icon-user"></i>
        <span>帐号管理</span>
      </a>
      <ul>
        <?php if(($_SESSION['admin_type'] == 99)): ?><li><a href="<?php echo U('Admin/Steward/account');?>">后台帐号</a></li><?php endif; ?>
        <?php if(($_SESSION['admin_type'] == 99) OR ($_SESSION['admin_type'] == 4)): ?><li><a href="<?php echo U('Admin/Tenant/account');?>">租客帐号</a></li><?php endif; ?>
      </ul>
    </li><?php endif; ?>

    <li class="submenu <?php if(CONTROLLER_NAME == 'Dding'): ?>active<?php endif; ?>">
      <a>
        <i class="icon icon-user"></i>
        <span>丁盯门锁管理</span>
      </a>
      <ul>
        <li><a href="<?php echo U('Admin/Dding/room_manage');?>">房间管理</a></li>
        <li><a href="<?php echo U('Admin/Dding/log');?>">操作日志</a></li>
      </ul>
    </li>
    <li class="submenu <?php if(CONTROLLER_NAME == 'Sms'): ?>active<?php endif; ?>">
      <a>
        <i class="icon icon-user"></i>
        <span>短信管理</span>
      </a>
      <ul>
        <li><a href="<?php echo U('Admin/Sms/log');?>">操作日志</a></li>
      </ul>
    </li>
  </ul>
</div>
<!--sidebar-menu-->