<?php
class main extends spController
{
	public function index(){
        if(checkHasLogin()){
            $this->jump(spUrl('user','index'));
        }
        $page               = array(
            'title'     => 'SS',
            'tag'       => 'index'
        );
        $css_js['head_css'] = array('res/css/global.css');
        $this->page         = $page;
        tpl_display($this, 'index.html', $css_js);
    }

    public function reg(){
        if(checkHasLogin()){
            $this->jump(spUrl('user','index'));
        }
        //获取邀请用户的信息
        $i                  = (int)$this->spArgs('i');
        if(!$i){
            $i  = $_COOKIE['invite_user_id'];
        }
        if($i){
            $this->invite_user  = spClass('m_user')->find(array('user_id'=>$i));
            if(!$_COOKIE['invite_user_id']){
                setcookie ( 'invite_user_id', $i, time()+3600 );
            }
        }

        $page               = array(
            'title'     => 'SS',
            'tag'       => 'reg'
        );
        $css_js['head_css'] = array('res/css/global.css');
        $this->page         = $page;
        tpl_display($this, 'main/reg.html', $css_js);
    }

    public function advise(){
        $page               = array(
            'title'     => '建议',
            'tag'       => 'advise'
        );
        $css_js['head_css'] = array('res/css/global.css');
        $this->page         = $page;
        tpl_display($this, 'main/advise.html', $css_js);
    }

    public function login(){
        if(checkHasLogin()){
            $this->jump(spUrl('user','index'));
        }
        $page               = array(
            'title'     => '登录',
            'tag'       => 'login'
        );
        $css_js['head_css'] = array('res/css/global.css');
        $this->page         = $page;
        tpl_display($this, 'main/login.html', $css_js);
    }

    public function logout(){
        unset( $_SESSION['user'] );
        $this->success('退出成功', spUrl('main', 'index'));
    }

    public function regSave(){
        global $spConfig;
        if(checkHasLogin()){
            $this->jump(spUrl('user','index'));
        }
        $post_data          = $this->spArgs(null, false, 'post');
        unset($post_data['/main-regSave_html']);

        $regin_lib = spClass('m_regin');
        $regin = $regin_lib->find( array('regin_key'=>$post_data['regin']) );
        if(empty($regin) || $regin['status']==1){
            $this->error('邀请码不正确或者已经使用', spUrl('main','reg'));
            return;
        }
        $regin_data = $post_data['regin'];
        unset($post_data['regin']);
        
        $user_lib           = spClass('m_user');
        $verifier           = $user_lib->spVerifier($post_data);
        if( $verifier ){
            $msg    = array_pop($verifier);
            $this->error($msg[0], spUrl('main', 'reg'));
        }
        
        if($post_data['password']!==$post_data['repassword']){
            $this->error('两次密码不一样',spUrl('main','reg'));
        }

        $port_lib           = spClass('m_port_pool');
        $ssport             = $port_lib->get_ss_port();
        if(!$ssport){
            $this->error('服务器用户已达上限', spUrl('main','index'));
        }

        $data               = array(
            'username'      => $post_data['username'],
            'password'      => md5($post_data['password']),
            'email'         => $post_data['email'],
            'sspass'        => $post_data['sspass'],
            'create_time'   => time(),
            'ssport'        => $ssport
        );

        if($userid = $user_lib->create($data)){
            $regin_lib->updateField(array('regin_key'=>$regin_data), 'status', 1);
            $port_lib->change_status($ssport, 1);

            $subject        = '欢迎注册 GFW.FUCKSPAM.IN';
            $e              = spUrl('main', 'auth', array('u'=>$userid, 'm'=>md5($post_data['email'])));
            $email_content  = <<<EOF
<p>{$post_data['username']}, 您好</p>
<p>感谢您注册 SS -- GFW，下面是邮箱验证链接，</p>
<p><a href="http://gfw.fuckspam.in{$e}">http://gfw.fuckspam.in{$e}</a></p>
<p>希望您使用愉快~</p>
EOF;
            sendmail($post_data['email'], $subject, $email_content, $post_data['username']);

            //邀请用户信息保存
            if($post_data['invite_uid']){
                spClass('m_invite')->save_invite($post_data['invite_uid'], $userid);
            }
            
            $this->success('注册成功', spUrl('main', 'login'));
        } else {
            $this->error('注册失败，请联系管理员', spUrl('main','reg'));
        }
    }

    public function logAuth(){
        if(checkHasLogin()){
            $this->jump(spUrl('user','index'));
        }
        $post_data          = $this->spArgs(null, false, 'post');
        $user_lib           = spClass('m_user');
        $user_info          = $user_lib->find(array('username'=>$post_data['username']));
        if(!$user_info || $user_info['password']!= md5($post_data['password']) ){
            $this->error('用户名或密码错误', spUrl('main', 'login'));
        }
        $_SESSION['user']   = $user_info;
        $this->success('登录成功', spUrl('user', 'index'));
    }

    public function auth(){
        $u          = (int)$this->spArgs('u');
        $m          = $this->spArgs('m');
        $user_lib   = spClass('m_user');
        $user   = $user_lib->find(array('user_id'=>$u));
        if(!$user){
            $this->error('用户不存在', spUrl('main', 'login'));
        }
        if($user['email_chk']){
            $this->success('邮箱已经验证通过了', spUrl('main','index'));
        }
        if(md5($user['email']) == $m){
            $user_lib->update(array('user_id'=>$u), array('email_chk'=> 1,'money_amount'=> ($user['money_amount']+5) ) );
            $this->success('验证通过', spUrl('main', 'login'));
        } else {
            $this->error('验证失败', spUrl('main', 'index'));
        }
    }

    /**
     * 淘宝回调接口
     */
    public function cb(){
        $arr['top_appkey']             = $this->spArgs('top_appkey');
        $arr['top_parameters']         = $this->spArgs('top_parameters');
        $arr['top_session']            = $this->spArgs('top_session');
        $arr['top_sign']               = $this->spArgs('top_sign');

        foreach ($arr as $k => $v) {
            $arr[$k]    = urldecode($v);
        }

        $p              = base64_decode($arr['top_parameters']);
        $params         = array();
        $t1             = explode('&', $p);
        foreach ($t1 as $val) {
            $tmp    = explode('=', $val);
            $params[ $tmp[0] ] = $tmp[1];
        }

        $key_lib        = spClass('m_key');
        $app            = $key_lib->findAll();

        $tmp_sign       = $app[0]['appkey'] . $arr['top_parameters'] . $arr['top_session'] . $app[0]['appsecret'];
        
        $mysign         = base64_encode( md5($tmp_sign,true) );

        if($mysign == $arr['top_sign']){
            $info       = array(
                'refresh'       => $params['refresh_token'],
                'sessionkey'    => $arr['top_session']
            );
            $key_lib->update(array('appkey'=>$app[0]['appkey']), $info);
            $this->jump(spUrl('admin','index'));
        } else {
            $this->error('验证失败', spUrl('main','index'));
        }
    }

    public function weidian()
    {
        logResult(var_export($_POST, true));

        $content = $_POST['content'];
        $content = json_decode($content, true);

        $message = $content['message'];
        $order_code = $message['order_id'];

        $order_lib = spClass('m_order');
        $order = $order_lib->find(array('order_code'=>$order_code));
        if($order) {
            switch($message['status']) {
                case 'pay':
                    $status = send_weidian_order($order_code);
                    if($status['status']['status_reason']=='success'){
                        $order_lib->updateField(array('order_code'=>$order_code), 'order_status', 'ship');
                        return;
                    }
                    break;
                case 'accept':
                case 'finish':
                    $user_lib = spClass('m_user');
                    $order_lib->updateField(array('order_code'=>$order_code), 'order_status', 'finish');
                    $user_lib->change_money( $order['user_id'], $order['order_money'] );
                    break;
            }
        }
        return;
    }

    public function regin()
    {
        $page               = array(
            'title'     => 'SS',
            'tag'       => 'reg'
        );
        $css_js['head_css'] = array('res/css/global.css');
        $this->page         = $page;
        tpl_display($this, 'main/regin.html', $css_js);
    }
}