<?php if (!defined('THINK_PATH')) exit();?>  <div class="button">
      <div class="button_ds">
        <div class="container-fluid">
          <div class="row-fluid">
            <a href="<?php echo U('Home/Steward/index');?>">
              <div class="col-xs-3"> <img src="/Public/images/icon_sy.png" class="img-responsive img-icon3-dx"> <br>
                <span class="nav-bootom-zt">首页</span>
              </div>
            </a>
             <a href="<?php echo U('Home/Steward/houses');?>">
              <div class="col-xs-3"> <img src="/Public/images/icon_zf.png" class="img-responsive img-icon3-dx"> <br>
                <span class="nav-bootom-zt">房屋管理</span>
              </div>
            </a>
            <a href="<?php echo U('Home/Steward/checkbill');?>">
              <div class="col-xs-3"> <img src="/Public/images/icon_tc.png" class="img-responsive img-icon3-dx"> <br>
                <span class="nav-bootom-zt">集中抄表</span>
              </div>
            </a>
            <a href="<?php echo U('Home/Steward/stewardtasks');?>">
              <div class="col-xs-3"> <img src="/Public/images/icon_wo.png" class="img-responsive img-icon3-dx"> <br>
                <span class="nav-bootom-zt" style="color:#68bb9b;">待办工作</span>
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>
</div>
</body>
</html>