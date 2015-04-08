<?php
class m_invite extends spModel
{
    var $pk = "invite_id"; // 数据表的主键
    var $table = "invite"; // 数据表的名称

    public function save_invite($user_id, $invited_user_id){
        $arr        = array(
            'user_id'           => $user_id,
            'invited_user_id'   => $invited_user_id,
            'invite_time'       => time()
        );
        return $this->create($arr);
    }
}