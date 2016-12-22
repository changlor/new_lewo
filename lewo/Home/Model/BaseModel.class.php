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

    public function join($table, $tableName, $joinTable)
    {
        $newJoinTable = $table;
        foreach ($joinTable as $key => $value) {
            $oldModelName = $prefix . $tableName;
            $newModelName = $prefix . $key;
            $newJoinTable = $newJoinTable->join(
                $oldModelName . ' ON ' . $oldModelName . $value . ' = ' . $newModelName . $value
            );
        }
        return $newJoinTable;
    }
}