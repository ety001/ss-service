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
        $user_lib           = spClass('m_users');
        $this->user_info    = $user_lib->find(array('userid'=>$_SESSION['user']['userid']));
        $page               = array(
            'title'     => '管理',
            'tag'       => 'index'
        );
        $css_js['head_css'] = array('res/css/global.css');
        $this->page         = $page;
        tpl_display($this, 'user/index.html', $css_js);
    }

    public function order(){
        $order_lib          = spClass('m_order');
        $this->order_info   = $order_lib->findAll(array('userid'=>$_SESSION['user']['userid']));
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
        $userid             = $_SESSION['user']['userid'];
        $order_lib          = spClass('m_order');
        if($order_lib->find(array('order_id'=>$order_id))){
            $this->error('订单已存在', spUrl('user', 'order'));
        } else {
            $info           = array(
                'order_id'  => $order_id,
                'userid'    => $userid
            );
            $order_lib->create($info);
            $this->success('添加订单成功，请等待管理员审核', spUrl('user', 'order'));
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

    public function admin(){
        if($_SESSION['user']['username']!='ety001'){
            $this->jump(spUrl('user','index'));
        }
        $user_lib           = spClass('m_users');
        $order_lib          = spClass('m_order');
        $this->user_info    = $user_lib->findAll();
        $this->order_info   = $order_lib->spLinker()->findAll();
        $page               = array(
            'title'     => 'admin',
            'tag'       => 'admin'
        );
        $css_js['head_css'] = array('res/css/global.css');
        $this->page         = $page;
        tpl_display($this, 'user/admin.html', $css_js);
    }

    public function vip(){
        if($_SESSION['user']['username']!='ety001'){
            $this->jump(spUrl('user','index'));
        }
        $user_id            = $this->spArgs('user_id');
        $user_lib           = spClass('m_users');
        $user_info          = $user_lib->find(array('userid'=>$user_id));
        $vip                = $this->spArgs('vip');
        if($vip==1){
            if(time()>$user_info['end_time']){
                $info   = array(
                    'user_type' => 0,
                    'status'    => 0
                );
            } else {
                $info   = array(
                    'user_type' => 0,
                );
            }
        } elseif($vip==0){
            $info   = array(
                'user_type' => 1,
                'status'    => 1
            );
        }
        $user_lib->update(array('userid'=>$user_id), $info);
        $this->jump(spUrl('user','admin'));
    }

    public function chgstatus(){
        if($_SESSION['user']['username']!='ety001'){
            $this->jump(spUrl('user','index'));
        }
        $user_id            = $this->spArgs('user_id');
        $user_lib           = spClass('m_users');
        $user_info          = $user_lib->find(array('userid'=>$user_id));
        $status             = $this->spArgs('status');
        if($status==1){
            $info   = array(
                'start_time'    => 0,
                'end_time'      => 0,
                'status'        => 0
            );
        } elseif($status==0){
            $now    = time();
            $info   = array(
                'start_time'    => $now,
                'end_time'      => $now + 365*24*3600,
                'status'    => 1
            );
        }
        $user_lib->update(array('userid'=>$user_id), $info);
        $this->jump(spUrl('user','admin'));
    }

    public function addoneyear(){
        if($_SESSION['user']['username']!='ety001'){
            $this->jump(spUrl('user','index'));
        }
        $user_id            = $this->spArgs('user_id');
        $user_lib           = spClass('m_users');
        $user_info          = $user_lib->find(array('userid'=>$user_id));
        $info               = array(
            'end_time'  => $user_info['end_time']+365*24*3600
        );
        $user_lib->update(array('userid'=>$user_id), $info);
        $this->jump(spUrl('user','admin'));
    }

    public function verifyorder(){
        if($_SESSION['user']['username']!='ety001'){
            $this->jump(spUrl('user','index'));
        }
        $id            = $this->spArgs('id');
        $order_lib          = spClass('m_order');
        $info               = array(
            'status'    => 1
        );
        $order_lib->update(array('id'=>$id), $info);
        $this->jump(spUrl('user','admin'));
    }

    public function port(){
        if($_SESSION['user']['username']!='ety001'){
            $this->jump(spUrl('user','index'));
        }
        $user_id      = $this->spArgs('u');
        $p            = $this->spArgs('p');
        $user_lib           = spClass('m_users');
        $info   = array(
            'port'=> $p
        );
        $user_lib->update(array('userid'=>$user_id), $info);
    }

}