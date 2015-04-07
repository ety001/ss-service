<?php
class user extends spController
{
    public function __construct(){
        parent::__construct();
        if(!checkHasLogin()){
            $this->error('您还未登录，请先登录！',spUrl('main','login'));
            return;
        }
    }

    public function index(){
        $user_lib               = spClass('m_user');
        $buyservice_lib         = spClass('m_buyservice');
        $this->user_info        = $user_lib->spLinker()->find(array('user_id'=>$_SESSION['user']['user_id']));
        $this->buyservice_info  = $buyservice_lib->get_current_service($_SESSION['user']['user_id']);
        $page                   = array(
            'title'     => '管理',
            'tag'       => 'index'
        );
        $css_js['head_css'] = array('res/css/global.css');
        $this->page         = $page;
        tpl_display($this, 'user/index.html', $css_js);
    }

    public function order(){
        $order_lib          = spClass('m_order');
        $this->order_info   = $order_lib->findAll(array('user_id'=>$_SESSION['user']['user_id']));
        $page               = array(
            'title'     => '购买',
            'tag'       => 'order'
        );
        $css_js['head_css'] = array('res/css/global.css');
        $this->page         = $page;
        tpl_display($this, 'user/order.html', $css_js);
    }

    public function orderSave(){
        $order_id           = $this->spArgs('order_id', false, 'post');
        $user_id            = $_SESSION['user']['user_id'];
        $order_lib          = spClass('m_order');
        $user_lib           = spClass('m_user');
        if($order_lib->find(array('order_id'=>$order_id))){
            $this->error('订单已存在', spUrl('user', 'order'));
        } else {
            $info   = auth_order_has_paid($order_id);
            switch ($info['status']) {
                case -1:
                    $this->error($info['msg'], spUrl('user','order'));
                    break;
                case -2:
                    $this->error($info['msg'], spUrl('user','order'));
                    break;
                case -3:
                    $this->error($info['msg'], spUrl('user','order'));
                    break;
                case -4:
                    $this->error($info['msg'], spUrl('user','order'));
                    break;
                case 1:
                    $amount     = caculate_money($info['data']);
                    send_order($order_id);
                    break;
                default:
                    # code...
                    break;
            }

            $info           = array(
                'order_code'    => $order_id,
                'user_id'       => $user_id,
                'order_money'   => $amount,
                'order_time'    => time(),
                'order_status'  => 1
            );
            $order_lib->create($info);
            $user_lib->change_money($user_id, $amount);

            $this->success('充值'.$amount.' S币成功', spUrl('user', 'order'));
        }
    }

    public function tutorial(){
        $page               = array(
            'title'     => '教程',
            'tag'       => 'tutorial'
        );
        $css_js['head_css'] = array('res/css/global.css');
        $this->page         = $page;
        tpl_display($this, 'user/tutorial.html', $css_js);
    }

}