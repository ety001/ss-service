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

    var $addrules = array(
        'is_exist'          => array('m_user', 'user_exist_check'),
        'email_is_exist'    => array('m_user', 'email_exist_check')
    );

    var $verifier = array(
        "rules" => array( // 规则
            'username'  => array(
                'notnull'       => true,
                'minlength'     => 5,
                'maxlength'     => 20,
                'is_exist'      => true,
            ),
            'email'     => array(
                'notnull'           => true,
                'email'             => true,
                'maxlength'         => 50,
                'email_is_exist'    => true,
            ),
            'sspass'    => array(
                'notnull'       => true,
            )
        ),
        "messages" => array( // 提示信息
            'username'  => array(
                'notnull'   => "用户名不能为空",
                'minlength' => "用户名不能少于5个字符",
                'maxlength' => "用户名不能大于20个字符",
                'is_exist'  => '用户名已存在'
            ),
            'email'     => array(
                'notnull'   => '邮箱不能为空',
                'email'     => '邮箱格式不正确',
                'maxlength' => '邮箱长度不能超过50',
                'email_is_exist'    => '邮箱已存在'
            ),
            'sspass'    => array(
                'notnull'   => 'Shadowsocks密码不能为空'
            )
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

    public function change_money($user_id=0, $chg=0){
        $conditions = array('user_id'=>$user_id);
        $user_info  = $this->find($conditions);
        $new_money_amount = $user_info['money_amount'] + $chg;
        if($new_money_amount<0){
            return false;
        }
        return $this->updateField($conditions, 'money_amount', $new_money_amount);
    }

    public function user_exist_check($username){
        $user = $this->find(array('username'=>$username));
        if( !$user ){
            return true;
        } else {
            return false;
        }
    }

    public function email_exist_check($email){
        $user = $this->find(array('email'=>$email));
        if( !$user ){
            return true;
        } else {
            return false;
        }
    }
}