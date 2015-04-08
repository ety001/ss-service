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

    public function testAuthOrder(){
        /*$order_id   = '1016983543099926';
        $info       = auth_order_has_paid($order_id);*/

        $info = array (
          'status' => -4,
          'msg' => '等待付款',
          'data' => 
          array (
            0 => 
            array (
              'adjust_fee' => '0.00',
              'buyer_rate' => false,
              'discount_fee' => '0.00',
              'num' => 4,
              'num_iid' => 44601411246,
              'oid' => 1016983543109926,
              'payment' => '10.00',
              'pic_path' => 'http://img04.taobaocdn.com/bao/uploaded/i4/TB1Slg3HXXXXXX9apXXXXXXXXXX_!!0-item_pic.jpg',
              'price' => '10.00',
              'refund_status' => 'NO_REFUND',
              'seller_rate' => false,
              'seller_type' => 'C',
              'status' => 'WAIT_BUYER_PAY',
              'title' => 'SS网站充值，10 S币',
              'total_fee' => '10.00',
            ),
            1 => 
            array (
              'adjust_fee' => '0.00',
              'buyer_rate' => false,
              'discount_fee' => '0.00',
              'num' => 1,
              'num_iid' => 43850827196,
              'oid' => 1016983543119926,
              'payment' => '365.00',
              'pic_path' => 'http://img04.taobaocdn.com/bao/uploaded/i4/TB1Slg3HXXXXXX9apXXXXXXXXXX_!!0-item_pic.jpg',
              'price' => '365.00',
              'refund_status' => 'NO_REFUND',
              'seller_rate' => false,
              'seller_type' => 'C',
              'status' => 'WAIT_BUYER_PAY',
              'title' => '请ETY001吃一年的巴依老爷~~',
              'total_fee' => '365.00',
            ),
            2 => 
            array (
              'adjust_fee' => '0.00',
              'buyer_rate' => false,
              'discount_fee' => '0.00',
              'num' => 2,
              'num_iid' => 44667681336,
              'oid' => 1016983543129926,
              'payment' => '4.00',
              'pic_path' => 'http://img04.taobaocdn.com/bao/uploaded/i4/TB1Slg3HXXXXXX9apXXXXXXXXXX_!!0-item_pic.jpg',
              'price' => '2.00',
              'refund_status' => 'NO_REFUND',
              'seller_rate' => false,
              'seller_type' => 'C',
              'status' => 'WAIT_BUYER_PAY',
              'title' => '请ETY001吃包辣条',
              'total_fee' => '4.00',
            ),
          ),
        );
        $amount     = caculate_money($info['data']);
        var_dump($amount);
    }

    public function email(){
      $subject        = '欢迎注册 GFW.FUCKSPAM.IN';
      $e              = spUrl('main', 'auth', array('u'=>1, 'm'=>md5('ety001@domyself.me')));
      $email_content  = <<<EOF
ETY001, 您好
感谢您注册私人定制GFW，下面是邮箱验证链接，
<a href="http://gfw.fuckspam.in{$e}">http://gfw.fuckspam.in{$e}</a>
希望您使用愉快~
EOF;
            //sendmail('ety001@domyself.me', $subject, $email_content, 'ety001');
        sendmail('ety001@domyself.me', 'test', 'test123', 'ety001');
    }

    public function testCliCheckStatus(){
        /*$user_lib               = spClass('m_user');
        import('cli.php');
        $cli_lib                = spClass('cli');
        $this->user_info        = $user_lib->find(array('user_id'=>$_SESSION['user']['user_id']));
        $this->service_status   = $cli_lib->check_status($this->user_info['ssport']);
        var_dump($this->service_status);*/
    }
}