<?php
namespace Home\Model;
use Think\Model;
/**
 * bill模型类
 */
class BillModel extends Base {
    public function __construct()
    {
        parent::__construct();
    }

    public function getBills($where)
    {
        $steward_id = parent::steward_id;
        if (!is_numeric($steward_id)) {
            $this->login(); die();
        }
    }
}