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
      /*$subject        = '欢迎注册 GFW.FUCKSPAM.IN';
      $e              = spUrl('main', 'auth', array('u'=>1, 'm'=>md5('ety001@domyself.me')));
      $email_content  = <<<EOF
ETY001, 您好
感谢您注册私人定制GFW，下面是邮箱验证链接，
<a href="http://gfw.fuckspam.in{$e}">http://gfw.fuckspam.in{$e}</a>
希望您使用愉快~
EOF;
            sendmail('ety001@domyself.me', $subject, $email_content, 'ety001');
        //sendmail('ety001@domyself.me', 'test', 'test123', 'ety001');*/
    }

    public function testCliCheckStatus(){
        /*$user_lib               = spClass('m_user');
        import('cli.php');
        $cli_lib                = spClass('cli');
        $this->user_info        = $user_lib->find(array('user_id'=>$_SESSION['user']['user_id']));
        $this->service_status   = $cli_lib->check_status($this->user_info['ssport']);
        var_dump($this->service_status);*/
    }

    public function testXML(){
        $xml = '<?xml version="1.0" encoding="utf-8"?>
        <alipay>
        <is_success>T</is_success>
        <request>
            <param name="partner">2088002007018916</param>
            <param name="logistics_name">天天</param>
            <param name="create_transport_type">EMS</param>
            <param name="trade_no">2008040902681748</param>
            <param name="agent">2088002007018916</param>
            <param name="notify_url">http://10.2.5.100/api/apireceive/returnSuccess.php</param>
            <param name="invoice_no">3455333</param>
            <param name="service">send_goods_confirm_by_platform</param>
            <param name="_input_charset">utf-8</param>
            <param name="transport_type">EMS</param>
            <param name="return_url">http://10.2.5.100/api/returnResultList.php</param>
        </request>
<response>
<tradeBase>
<buyer_account>20880020073014230156</buyer_account>
<buyer_actions>[REFUND,CONFIRM_GOODS]</buyer_actions>
<buyer_login_email>maaimin0577@yahoo.com.cn</buyer_login_email>
<buyer_type>PRIVATE_ACCOUNT</buyer_type>
<buyer_user_id>2088002007301423</buyer_user_id>
<channel>interface/digital</channel>
<create_time>2008-04-09 16:10:25</create_time>
<currency>156</currency>
<gathering_type>1</gathering_type>
<last_modified_time>2008-04-10 14:35:25</last_modified_time>
<operator_role>B</operator_role>
<out_trade_no>12345566654585</out_trade_no>
<partner_id>2088002007018916</partner_id>
<seller_account>20880020073014100156</seller_account>
<seller_actions>[EXTEND_TIMEOUT]</seller_actions>
<seller_login_email>song_xianqun@yahoo.com.cn</seller_login_email>
<seller_type>PRIVATE_ACCOUNT</seller_type>
<seller_user_id>2088002007301410</seller_user_id>
<service_fee>0.00</service_fee>
<service_fee_ratio>0.0</service_fee_ratio>
<stop_timeout>F</stop_timeout>
<total_fee>2.00</total_fee>
<trade_from>INST_PARTNER</trade_from>
<trade_no>2008040902681748</trade_no>
<trade_status>WAIT_BUYER_CONFIRM_GOODS</trade_status>
<trade_type>S</trade_type>
</tradeBase>
</response>
<sign>eb07c7407bafa62ec7c0804751a21c1e</sign>
<sign_type>MD5</sign_type>
</alipay>';
        header("Content-type: text/html; charset=utf-8");
        echo $xml;
    }

    public function testXML2(){
        $xml = file_get_contents('http://ss.dev'.spUrl('test','testXML'));
        //logResult( var_export(simplest_xml_to_array($xml), true) );
    }
}