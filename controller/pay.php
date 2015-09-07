<?php
import(APP_PATH.'/inc/alipay/func.php');//载入alipay函数库
class pay extends spController
{
    public function index(){
        if(!checkHasLogin()){
            $this->error('您还未登录，请先登录！',spUrl('main','login'));
            return;
        }
        $money = (int)$this->spArgs('money');
        if(!$money){
            $this->error('参数错误', spUrl('main','index'));
        }
        if($money!=10&&$money!=20&&$money!=50&&$money!=100){
            $this->error('参数错误', spUrl('main','index'));
        }
        $order_lib = spClass('m_order');
        $order_code = generate_order_code();
        while ($order_lib->chk_order_id($order_code)) {
            $order_code = generate_order_code();
        }
        $order_data = array(
            'order_code' => $order_code,
            'user_id' => $_SESSION['user']['user_id'],
            'order_money' => $money,
            'order_time' => time(),
            'order_status' => 0
        );
        //if( $order_id = $order_lib->create($order_data) ){
        if(1){
            echo $this->topay($money, $order_code);
        } else {
            $this->error('订单保存失败', spUrl('user','order'));
        }
    }

    private function topay($money, $order_code){
        global $spConfig;
        $alipay_config = $spConfig['alipay'];

        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "create_partner_trade_by_buyer",
            "partner" => trim($alipay_config['partner']),
            "seller_email" => trim($alipay_config['seller_email']),
            "payment_type"  => 1,
            "notify_url"    => "http://gfw.fuckspam.in/notifyurl.html",//服务器异步通知页面路径
            "return_url"    => "http://gfw.fuckspam.in/returnurl.html",//页面跳转同步通知页面路径
            "out_trade_no"  => $order_code,//商户订单号
            "subject"   => '充值'.$money.' RMB',
            "price" => $money,
            "quantity"  => 1,//商品数量
            "logistics_fee" => "0.00",//运费
            "logistics_type"    => 'EXPRESS',//EXPRESS（快递）、POST（平邮）、EMS（EMS）
            "logistics_payment" => 'SELLER_PAY',//SELLER_PAY（卖家承担运费）、BUYER_PAY（买家承担运费）
            "body"  => '充值'.$money.' RMB',
            "show_url"  => 'http://gfw.fuckspam.in',
            "receive_name"  => '充值'.$money,
            "receive_address"   => 'Beijing',
            "receive_zip"   => '100000',
            "receive_phone" => '10086',
            "receive_mobile"    => '10010',
            "_input_charset"    => trim(strtolower($alipay_config['input_charset']))
        );
        try {
            //建立请求
            $alipaySubmit = spClass('AlipaySubmit', array($alipay_config));
            $html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
            return $html_text;
        } catch (Exception $e) {
            var_dump($e);
        }
    }
}