<?php
class test extends spController
{
    public function testBuyService(){
        $arr = array(
            'service_id'    => 1,
            'user_id'       => 1,
            'buy_time'      => time()
        );
        $buyservice_lib     = spClass('m_buyservice');
        $user_lib           = spClass('m_user');
        $chk                = $user_lib->chk_money(1, 1);
        if(!$chk){
            echo '余额不足';
            return;
        }

        $chk_service        = $buyservice_lib->chk_service(1);
        if($chk_service){
            echo '有正在使用的服务';
            return;
        }

        echo $buyservice_lib->save_service($arr);
    }

    public function email(){
        sendmail('ety001@domyself.me', 'test', 'test123', 'ety001');
    }
}