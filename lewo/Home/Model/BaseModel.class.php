<?php
namespace Home\Model;
use Think\Model;
/**
 * base模型类
 */
class BaseModel extends Model {
    protected $prefix = 'lewo_';
    protected $steward_id;

    public function __construct()
    {
        parent::__construct();
    }

    public function response($res)
    {
        if (!is_array($res)) {
            return ['success' => false, 'msg' => '未知错误', 'data' => []];
        }
        $success = $res[0];
        if (!isset($res[1])) {
            return $success
            ? ['success' => true, 'msg' => '已成功！', 'data' => []]
            : ['success' => false, 'msg' => '未知错误', 'data' => []];
        }
        $msg = $res[1];
        $data = isset($res[2]) ? $res[2] : [];
        return ['success' => $success, 'msg' => $msg, 'data'=> $data];
    }

    public function join($table, $joinTable)
    {
        $newJoinTable = $table;
        foreach ($joinTable as $key => $value) {
            $joinTableName = $this->prefix . $key;
            $match = explode('=', $value);

            $condition1 = $this->prefix . trim($match[0]);
            $condition2 = $this->prefix . trim($match[1]);

            $newJoinTable = $newJoinTable->join(
                $joinTableName . ' ON ' . $condition1 . ' = ' . $condition2, 'left'
            );
        }
        return $newJoinTable;
    }
}