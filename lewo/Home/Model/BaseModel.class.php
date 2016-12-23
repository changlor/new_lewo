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
        // $this->steward_id = $_SESSION['steward_id'];
    }

    public function join($table, $joinTable)
    {
        $newJoinTable = $table;
        foreach ($joinTable as $key => $value) {
            preg_match('/\([a-z_0-9]+\)/i', $key, $match);
            $tableName1 = preg_replace('/\([a-z_0-9]+\)/i', '', $key);
            $tableName2 = $match[1];
            preg_match('/\([a-z_0-9]+\)/i', $value, $match);
            $condition1 = preg_replace('/\([a-z_0-9]+\)/i', '', $value);
            $condition2 = $match[1];

            $tableName1 = $prefix . $tableName1;
            $tableName2 = $prefix . $tableName2;
            $condition1 = $tableName1 . '.' . $condition1;
            $condition2 = $tableName2 . '.' . $condition2;

            $newJoinTable = $newJoinTable->join(
                $tableName2 . ' ON ' . $condition1 . ' = ' . $condition2, 'left'
            );
        }
        return $newJoinTable;
    }
}