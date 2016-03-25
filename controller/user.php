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
        $user_lib           = spClass('m_user');
        $order_lib          = spClass('m_order');
        $this->user_info    = $user_lib->find(array('user_id'=>$_SESSION['user']['user_id']));
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
        if($order_lib->find(array('order_code'=>$order_id))){
            $this->error('该订单已充值', spUrl('user', 'order'));
        } else {
            $info   = auth_order_has_paid($order_id);
            if($info['status']==1){
                $amount     = caculate_money($info['data']);
                send_order($order_id);
            } else {
                $this->error($info['msg'], spUrl('user','order'));
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

            //邀请判断并完成佣金支付
            spClass('m_invite')->pay($user_id, $amount);

            $this->success('充值'.$amount.' RMB成功', spUrl('user', 'order'));
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

        $this->service_limit        = !$buyservice_lib->chk_service_limit();

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

        if(!$buyservice_lib->chk_service_limit()){
            $this->error('服务已售光，请等待扩容 :( ', spUrl('user','index'));
        }

        $user_info          = $user_lib->spLinker()->find(array('user_id'=>$user_id));

        if($user_info['email_chk']==0){
            $this->error('邮箱还未验证 :( ', spUrl('user','index'));
        }

        //获取服务详情
        $service_info       = $service_lib->find(array('service_id'=>$service_id));        
        $chk                = $user_lib->chk_money($user_id, $service_info['service_money']);
        if(!$chk&&$user_info['user_type']!=1){
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
        $user_lib->updateField(array('user_id'=>$user_id), 'service_id', $service_id);

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
        $cli_lib            = spClass('cli');
        $user_lib           = spClass('m_user');
        $buyservice_lib     = spClass('m_buyservice');
        $user_info          = $user_lib->spLinker()->find(array('user_id'=>$user_id));
        $buyservice_info    = $buyservice_lib->get_current_service($user_id);
        if($switch==1){
            $cli_lib->stop($user_info['ssport'], $user_info['sspass']);
        } else {
            if($buyservice_info['end_time']<time()){
                $this->error('您的服务已经终止了，请续费继续使用', spUrl('user','buyservice'));
            }
            $cli_lib->run($user_info['ssport'], $user_info['sspass']);
        }
        $this->success('操作成功', spUrl('user','index'));
    }

    public function invite(){
        $this->user_id      = $_SESSION['user']['user_id'];

        $this->invite_list  = spClass('m_invite')->spLinker()->findAll(array('user_id'=>$this->user_id));
        $page               = array(
            'title'     => '邀请好友',
            'tag'       => 'invite'
        );
        $css_js['head_css'] = array('res/css/global.css');
        $this->page         = $page;
        tpl_display($this, 'user/invite.html', $css_js);
    }

    public function addorder() {
        $user_id    = $_SESSION['user']['user_id'];

        $order_id = $this->spArgs('order_id');
        $order_info = get_weidian_order_info($order_id);

        $order_lib = spClass('m_order');
        if($order_lib->find(array('order_code'=>$order_id))) {
            $this->error('该订单已存在，请直接在列表中操作', spUrl('user','order'));
            return;
        }
        if($order_info['status']['status_code']==0){
            $result = $order_info['result'];
            $order_data = array(
                'order_code'    => $order_id,
                'user_id'       => $user_id,
                'order_money'   => (int)$result['total'],
                'order_time'    => strtotime($result['add_time']),
                'order_status'  => $result['status']
            );
            //如果已付款，则发货
            if($order_data['order_status']=='pay'){
                $status = send_weidian_order($order_id);
                if($status['status']['status_reason']=='success'){
                    logResult($order_id.' has shipped');
                    $order_data['order_status'] = 'ship';
                }
            }
            $order_lib->create($order_data);
            if($order_data['order_status']=='ship'){
                $this->success('添加订单成功，请前往微店确认收货', spUrl('user','order'));
            } else {
                $this->success('添加订单成功，您还未支付，请前往微店进行支付', spUrl('user','order'));
            }
            return;
        } else {
            $this->error($order_info['status']['status_reason'], spUrl('user','order'));
            return;
        }
    }

    public function haspaid() {
        $user_id    = $_SESSION['user']['user_id'];

        $order_code = $this->spArgs('order_code');

        $order_lib = spClass('m_order');
        $order = $order_lib->find(array('order_code'=>$order_code));
        if(!$order) {
            $this->error('订单不存在', spUrl('user','order'));
            return;
        }

        if($order['order_status']!='unpay') {
            $this->error('你的订单状态不是未支付，无法继续操作', spUrl('user','order'));
            return;
        }

        $order_info = get_weidian_order_info($order_code);
        if($order_info['status']['status_code']==0){
            $result = $order_info['result'];
            switch ($result['status']) {
                case 'unpay':
                    $this->error('你的订单未支付，无法继续操作', spUrl('user','order'));
                    return;
                    break;
                case 'pay':
                    $status = send_weidian_order($order_code);
                    if($status['status']['status_reason']=='success'){
                        $order_lib->updateField(array('order_code'=>$order_code), 'order_status', 'ship');
                        $this->success('处理成功，请到 微店 确认收货', spUrl('user', 'order'));
                        return;
                    } else {
                        $this->error('发货处理失败，'.$status['status']['status_reason'], spUrl('user', 'order'));
                        return;
                    }
                    break;
                default:
                    $order_lib->updateField(array('order_code'=>$order['order_code']), 'order_status', $result['status']);
                    $this->error('你的订单状态为'.$result['status'].'，无法继续操作', spUrl('user','order'));
                    return;
                    break;
            }
        } else {
            $this->error($order_info['status']['status_reason'], spUrl('user','order'));
            return;
        }
    }

    public function hasreceived() {
        $user_id    = $_SESSION['user']['user_id'];

        $order_code = $this->spArgs('order_code');

        $order_lib = spClass('m_order');
        $order = $order_lib->find(array('order_code'=>$order_code));
        if(!$order) {
            $this->error('订单不存在', spUrl('user','order'));
            return;
        }

        if($order['order_status']!='ship') {
            $this->error('你的订单状态不是已发货状态，无法继续操作', spUrl('user','order'));
            return;
        }

        $order_info = get_weidian_order_info($order_code);
        if($order_info['status']['status_code']==0){
            $result = $order_info['result'];
            switch ($result['status']) {
                case 'unpay':
                    $this->error('你的订单未支付，无法继续操作', spUrl('user','order'));
                    return;
                    break;
                case 'pay':
                    $status = send_weidian_order($order_code);
                    if($status['status']['status_reason']=='success'){
                        $order_lib->updateField(array('order_code'=>$order_code), 'order_status', 'ship');
                        $this->success('处理成功，请到 微店 确认收货', spUrl('user', 'order'));
                        return;
                    } else {
                        $this->error('发货处理失败，'.$status['status']['status_reason'], spUrl('user', 'order'));
                        return;
                    }
                    break;
                case 'ship':
                    $this->error('你的订单未确认收货', spUrl('user','order'));
                    return;
                    break;
                case 'accept':
                case 'finish':
                    $user_lib = spClass('m_user');
                    $order_lib->updateField(array('order_code'=>$order_code), 'order_status', 'finish');
                    $user_lib->change_money( $order['user_id'], $order['order_money'] );
                    $this->success('完成充值', spUrl('user','order'));
                    break;
                default:
                    $order_lib->updateField(array('order_code'=>$order['order_code']), 'order_status', $result['status']);
                    $this->error('你的订单状态为'.$result['status'].'，无法继续操作', spUrl('user','order'));
                    return;
                    break;
            }
        } else {
            $this->error($order_info['status']['status_reason'], spUrl('user','order'));
            return;
        }
    }

    public function resend()
    {
        $user_id    = $_SESSION['user']['user_id'];
        if(!$user_id){
            $this->error('用户信息有误', spUrl('main','index'));
        }

        $user_lib   = spClass('m_user');
        $user_info = $user_lib->find(array('user_id'=>$user_id));
        if($user_info['email_chk']==1){
            $this->error('邮箱已验证过', spUrl('user','index'));
        }

        $email = $this->spArgs('email');
        $user_info['email'] = $email;

        $subject        = '欢迎注册 GFW.FUCKSPAM.IN';
        $e              = spUrl('main', 'auth', array('u'=>$userid, 'm'=>md5($user_info['email'])));
        $email_content  = <<<EOF
<p>{$user_info['username']}, 您好</p>
<p>感谢您注册 SS -- GFW，下面是邮箱验证链接，</p>
<p><a href="http://gfw.fuckspam.in{$e}">http://gfw.fuckspam.in{$e}</a></p>
<p>希望您使用愉快~</p>
EOF;
        $r = sendmail($user_info['email'], $subject, $email_content, $user_info['username']);
        $user_lib->updateField(array('user_id'=>$user_id), 'email', $email);
        $this->success('发送成功，请注意查收', spUrl('user','index'));
    }
}
