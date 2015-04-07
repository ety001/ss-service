<?php
class m_user extends spModel
{
    var $pk = "user_id"; // 数据表的主键
    var $table = "user"; // 数据表的名称

    var $linker = array(
        array(
            'type' => 'hasone',   // 关联类型，这里是一对一关联
            'map' => 'service_info',    // 关联的标识
            'mapkey' => 'service_id', // 本表与对应表关联的字段名
            'fclass' => 'm_service', // 对应表的类名
            'fkey' => 'service_id',    // 对应表中关联的字段名
            'enabled' => true     // 启用关联
        )
    );

    public function chk_money($user_id=0, $consumption){
        if(!$consumption)return false;
        $user_info  = $this->find(array('user_id'=>$user_id));
        if($consumption <= $user_info['money_amount']){
            return true;
        } else {
            return false;
        }
    }
}