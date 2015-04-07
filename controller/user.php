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
        import('cli.php');
        $cli_lib                = spClass('cli');

        $this->user_info        = $user_lib->spLinker()->find(array('user_id'=>$_SESSION['user']['user_id']));
        $this->buyservice_info  = $buyservice_lib->get_current_service($_SESSION['user']['user_id']);
        $this->service_status   = $cli_lib->check_status($this->user_info['ssport']);
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
            'title'     => '充值',
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

    public function buyservice(){
        $user_id                    = $_SESSION['user']['user_id'];
        $user_lib                   = spClass('m_user');
        $buyservice_lib             = spClass('m_buyservice');
        $service_lib                = spClass('m_service');
        $this->current_service      = $buyservice_lib->get_current_service($user_id);
        $this->user_info            = $user_lib->spLinker()->find(array('user_id'=>$user_id));
        $this->service_list         = $service_lib->findAll();

        $page               = array(
            'title'     => '购买服务',
            'tag'       => 'buyservice'
        );
        $css_js['head_css'] = array('res/css/global.css');
        $this->page         = $page;
        tpl_display($this, 'user/buyservice.html', $css_js);
    }

    public function buysave(){
        $user_id            = $_SESSION['user']['user_id'];
        
        $service_id         = (int)$this->spArgs('service_id');

        $service_lib        = spClass('m_service');
        $buyservice_lib     = spClass('m_buyservice');
        $user_lib           = spClass('m_user');
        $user_info          = $user_lib->spLinker()->find(array('user_id'=>$user_id));
        //获取服务详情
        $service_info       = $service_lib->find(array('service_id'=>$service_id));        
        $chk                = $user_lib->chk_money($user_id, $service_info['service_money']);
        if(!$chk){
            $this->error('余额不足，请先充值', spUrl('user','order'));
            return;
        }

        $chk_service        = $buyservice_lib->chk_service($user_id);
        if($chk_service){
            $this->error('有正在使用的服务，请等服务到期后再购买。', spUrl('user','buyservice'));
            return;
        }

        $arr    = array(
            'service_id'    => $service_id,
            'user_id'       => $user_id,
            'buy_time'      => time()
        );
        $buyservice_lib->save_service($arr);

        import('cli.php');
        spClass('cli')->run($user_info['ssport'], $user_info['sspass']);
        $this->success('购买成功', spUrl('user','buyservice'));
    }

    public function tutorial(){
        $page               = array(
            'title'     => '下载&教程',
            'tag'       => 'tutorial'
        );
        $css_js['head_css'] = array('res/css/global.css');
        $this->page         = $page;
        tpl_display($this, 'user/tutorial.html', $css_js);
    }

    public function changeservicestatus(){
        $switch     = (int)$this->spArgs('s');
        $user_id    = $_SESSION['user']['user_id'];
        import('cli.php');
        $cli_lib    = spClass('cli');
        $user_lib   = spClass('m_user');
        $user_info  = $user_lib->find(array('user_id'=>$user_id));
        if($switch==1){
            $cli_lib->stop($user_info['ssport'], $user_info['sspass']);
        } else {
            $cli_lib->run($user_info['ssport'], $user_info['sspass']);
        }
        $this->success('操作成功', spUrl('user','index'));
    }

}