<?php
class m_invite extends spModel
{
    var $pk = "invite_id"; // 数据表的主键
    var $table = "invite"; // 数据表的名称
    var $linker = array(
        array(
            'type' => 'hasone',   // 关联类型，这里是一对一关联
            'map' => 'user_info',    // 关联的标识
            'mapkey' => 'user_id', // 本表与对应表关联的字段名
            'fclass' => 'm_user', // 对应表的类名
            'fkey' => 'user_id',    // 对应表中关联的字段名
            'enabled' => true     // 启用关联
        ),
        array(
            'type' => 'hasone',   // 关联类型，这里是一对一关联
            'map' => 'invited_user_info',    // 关联的标识
            'mapkey' => 'invited_user_id', // 本表与对应表关联的字段名
            'fclass' => 'm_user', // 对应表的类名
            'fkey' => 'user_id',    // 对应表中关联的字段名
            'enabled' => true     // 启用关联
        ),
    );

    public function save_invite($user_id, $invited_user_id){
        if(!$user_id||!$invited_user_id)return false;
        $arr        = array(
            'user_id'           => $user_id,
            'invited_user_id'   => $invited_user_id,
            'invite_time'       => time()
        );
        return $this->create($arr);
    }

    public function pay($user_id, $money=0){
        if(!$user_id)return false;
        $money      = (int)$money;
        $conditions = array(
            'invited_user_id'   => $user_id,
            'has_pay'           => 0
        );
        $info       = $this->find($conditions);
        if(!$info)return false;
        $this->updateField($conditions, 'has_pay', $money);
        return spClass('m_user')->change_money($info['user_id'], $money);
    }
}