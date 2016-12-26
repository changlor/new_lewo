<?php
namespace Admin\Model;
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
            preg_match('/\(([a-z_0-9]+)\)/i', $key, $match);
            $tableName1 = preg_replace('/\([a-z_0-9]+\)/i', '', $key);
            $tableName2 = $match[1];
            preg_match('/\(([a-z_0-9]+)\)/i', $value, $match);
            $condition1 = preg_replace('/\([a-z_0-9]+\)/i', '', $value);
            $condition2 = $match[1];

            $tableName1 = $this->prefix . $tableName1;
            $tableName2 = $this->prefix . $tableName2;
            $condition1 = $tableName1 . '.' . $condition1;
            $condition2 = $tableName2 . '.' . $condition2;

            $newJoinTable = $newJoinTable->join(
                $tableName2 . ' ON ' . $condition1 . ' = ' . $condition2, 'left'
            );
        }
        return $newJoinTable;
    }
}