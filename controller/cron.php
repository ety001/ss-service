<?php
class cron extends spController
{
    public function index(){
    }

    public function checkss(){
        set_time_limit(0);
        import('cli.php');
        $buyservice_lib     = spClass('m_buyservice');
        $user_lib           = spClass('m_user');
        $cli_lib            = spClass('cli');

        //获取所有有服务id的用户
        $conditions         = ' service_id <> 0 ';
        $users              = $user_lib->spLinker()->findAll($conditions);

        foreach ($users as $k => $user) {
            $uid                = $user['user_id'];
            $current_service    = $buyservice_lib->get_current_service($uid);
            if(!$current_service){
                $user_lib->updateField( array('user_id'=>$uid), 'service_id', 0);
                continue;
            }
            //如果服务到期，进行停止操作，否则检查是否在运行
            if($current_service['end_time']<time()){
                $tmp_conditions     = array('buyservice_id'=>$current_service['buyservice_id']);
                $buyservice_lib->updateField( $tmp_conditions, 'status', 0);
                $user_lib->updateField( array('user_id'=>$uid), 'service_id', 0);
                $cli_lib->stop($user['ssport'], $user['sspass']);
            } else {
                $tmp_status     = $cli->check_status($user['ssport']);
                if(!$tmp_status){
                    $cli_lib->run($user['ssport'], $user['sspass']);
                }
            }
        }
    }
}