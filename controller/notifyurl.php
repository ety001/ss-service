<?php
import(APP_PATH.'/inc/alipay/func.php');//载入alipay函数库
class notifyurl extends spController
{
    public function index(){
        global $spConfig;
        $alipay_config = $spConfig['alipay'];
        $alipayNotify = spClass('AlipayNotify', array($alipay_config));
        $verify_result = $alipayNotify->verifyNotify();

        if($verify_result) {//验证成功
            //商户订单号
            $out_trade_no = $_POST['out_trade_no'];
            //支付宝交易号
            $trade_no = $_POST['trade_no'];
            //交易状态
            $trade_status = $_POST['trade_status'];

            $order_lib = spClass('m_order');
            $result = $order_lib->find( array('order_code'=>$out_trade_no) );
            if(!$result){
                echo 'fail';
                return;
            }

            if($trade_status == 'WAIT_BUYER_PAY') {
                if($result['order_status']=='WAIT_BUYER_PAY'){
                    $order_lib->update( 
                        array('order_code'=>$out_trade_no), 
                        array('trade_no'=>$trade_no )
                    );
                }
                echo 'success';
            } else if($trade_status == 'WAIT_SELLER_SEND_GOODS') {
                //构造要请求的参数数组，无需改动
                $parameter = array(
                    "service" => "send_goods_confirm_by_platform",
                    "partner" => trim($alipay_config['partner']),
                    "trade_no" => $trade_no,
                    "logistics_name"    => '不需物流',
                    "invoice_no"    => '',
                    "transport_type"    => 'EXPRESS',
                    "_input_charset"    => trim(strtolower($alipay_config['input_charset']))
                );
                //建立请求
                $alipaySubmit = spClass('AlipaySubmit', array($alipay_config));
                $html_text = $alipaySubmit->buildRequestHttp($parameter);

                $arr = simplest_xml_to_array($html_text);
                if($arr['is_success']==='T'){
                    $order_lib->update( 
                        array('order_code'=>$out_trade_no), 
                        array('order_status'=> 'WAIT_BUYER_CONFIRM_GOODS' )
                    );
                } else {
                    logResult('系统自动发货失败，请手动发货:'.var_export($arr, true));
                }
                echo "success";     //请不要修改或删除
            } else if($trade_status == 'WAIT_BUYER_CONFIRM_GOODS') {
                echo "success";     //请不要修改或删除
            } else if($trade_status == 'TRADE_FINISHED') {
                //该判断表示买家已经确认收货，这笔交易完成
                $money = $result['order_money'];
                $now_status = $result['order_status'];
                $r1 = $order_lib->update(
                    array('order_code'=>$out_trade_no),
                    array('order_status'=>'TRADE_FINISHED')
                );
                if($r1){
                    $user_lib = spClass('m_user');
                    $r2 = $user_lib->change_money($_SESSION['user']['user_id'], $money);
                    if(!$r2){
                        $order_lib->update(
                            array('order_code'=>$out_trade_no),
                            array('order_status'=>$now_status);
                        );
                        echo 'fail';
                    } else {
                        echo 'success';
                    }
                } else {
                    echo 'fail';
                }
            } else {
                //其他状态判断
                echo "success";
                logResult ('其他状态判断:'.$trade_status);
            }
        } else {
            //验证失败
            echo "fail";
            logResult("notify fail");
        }
    }
}