<?php
class admin extends spController
{
    public function __construct(){
        parent::__construct();
        if(!checkHasLogin()){
            $this->error('您还未登录，请先登录！',spUrl('main','login'));
            return;
        }
        if($_SESSION['user']['username']!='ety001'){
            $this->jump(spUrl('user','index'));
        }
    }

    public function index(){
        $buyservice_lib         = spClass('m_buyservice');
        $user_lib               = spClass('m_user');
        $order_lib              = spClass('m_order');


        $user_info              = $user_lib->findAll();

        foreach ($user_info as $k => $user) {
            $tt                 = $buyservice_lib->get_current_service($user['user_id']);
            $user_info[$k]['start_time']    = $tt['buy_time'];
            $user_info[$k]['end_time']      = $tt['end_time'];
            $user_info[$k]['service_info']  = $tt;
        }

        $this->user_info        = $user_info;
        $this->order_info       = $order_lib->spLinker()->findAll();

        $page               = array(
            'title'     => 'admin',
            'tag'       => 'admin'
        );
        $css_js['head_css'] = array('res/css/global.css');
        $this->page         = $page;
        tpl_display($this, 'admin/index.html', $css_js);
    }

    public function vip(){
        $user_id            = $this->spArgs('user_id');
        $user_lib           = spClass('m_user');
        $user_info          = $user_lib->find(array('user_id'=>$user_id));
        $vip                = $this->spArgs('vip');
        if($vip==1){
            $info   = array(
                'user_type' => 0,
            );
        } elseif($vip==0){
            $info   = array(
                'user_type' => 1
            );
        }
        $user_lib->update(array('user_id'=>$user_id), $info);
        $this->jump(spUrl('admin','index'));
    }

    public function verifyorder(){
        $id            = $this->spArgs('id');
        $order_lib          = spClass('m_order');
        $info               = array(
            'status'    => 1
        );
        $order_lib->update(array('id'=>$id), $info);
        $this->jump(spUrl('user','admin'));
    }
}