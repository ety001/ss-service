<?php
class m_buyservice extends spModel
{
    var $pk = "buyservice_id"; // 数据表的主键
    var $table = "buyservice"; // 数据表的名称
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

    public function get_current_service($user_id=0){
        if($user_id){
            $conditions     = array(
                'user_id'   => $user_id,
                'status'    => 1
            );
            return $this->spLinker()->find($conditions);
        } else {
            return false;
        }
    }

    /**
     * 保存购买的服务，并扣费
     */
    public function save_service($arr){
        if($arr){
            $service_lib    = spClass('m_service');
            $user_lib       = spClass('m_user');
            $service_info   = $service_lib->find(array('service_id'=>$arr['service_id']));
            if($service_info['service_type']==2){
                $arr['end_time']    = $arr['buy_time'] + 24*3600*$service_info['service_val'];
            }
            if($this->create($arr)){
                return $user_lib->change_money($arr['user_id'], -1*$service_info['service_money']);
            }
        } else {
            return false;
        }
    }

    public function chk_service($user_id=0){
        $conditions     = array(
            'user_id'   => $user_id,
            'status'    => 1
        );
        return $this->find($conditions);
    }
}
