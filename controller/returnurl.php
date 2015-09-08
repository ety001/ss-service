<?php
import(APP_PATH.'/inc/alipay/func.php');//载入alipay函数库
class returnurl extends spController
{
    public function index(){
        global $spConfig;
        $alipay_config = $spConfig['alipay'];
        $alipayNotify = spClass('AlipayNotify', array($alipay_config));
        $verify_result = $alipayNotify->verifyReturn();
        if($verify_result) {//验证成功
            //商户订单号
            $out_trade_no = $_GET['out_trade_no'];
            //支付宝交易号
            $trade_no = $_GET['trade_no'];
            //交易状态
            $trade_status = $_GET['trade_status'];

            $order_lib = spClass('m_order');
            $result = $order_lib->find( array('order_code'=>$out_trade_no) );
            if(!$result){
                $this->error('订单不存在', spUrl('user','order'));
                return;
            }
            if($result['order_status']=='WAIT_BUYER_CONFIRM_GOODS'){
                $this->error('请及时到您的支付宝管理界面确认收货，以完成充值。', spUrl('user','order'));
                return;
            }
            if($result['order_status']=='TRADE_FINISHED'||$result['order_status']=='TRADE_CLOSED'){
                $this->error('订单已完成或关闭', spUrl('user','order'));
                return;
            }
            $order_lib->update( 
                array('order_code'=>$out_trade_no), 
                array('order_status'=> $trade_status,'trade_no'=>$trade_no )
            );
            if($trade_status == 'WAIT_SELLER_SEND_GOODS') {
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
                    $this->error('系统自动发货失败，请联系卖家手动发货', spUrl('user','order'));
                }
            } else {
                logResult('trade_status is not WAIT_SELLER_SEND_GOODS:'.var_export($trade_status, true));
            }
            $this->success('系统自动发货完毕，请确认收货以完成充值', spUrl('user', 'order'));
        } else {
            $this->error('验证失败', spUrl('user','order'));
        }
    }
}