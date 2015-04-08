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
        $page               = array(
            'title'     => 'SS',
            'tag'       => 'reg'
        );
        $css_js['head_css'] = array('res/css/global.css');
        $this->page         = $page;
        tpl_display($this, 'main/reg.html', $css_js);
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
        if(checkHasLogin()){
            $this->jump(spUrl('user','index'));
        }
        $post_data          = $this->spArgs(null, false, 'post');
        unset($post_data['/main-regSave_html']);
        $tmp                = array(
            'username'      => '用户名不能为空',
            'password'      => '密码不能为空',
            'repassword'    => '密码不能为空',
            'email'         => 'email不能为空',
            'sspass'       => 'Shadowsocks密码不能为空'
        );
        //检查是否为空
        foreach ($post_data as $k => $v) {
            if(empty($v)){
                $this->error($tmp[$k], spUrl('main','reg'));
            }
        }
        
        $user_lib           = spClass('m_user');
        $user_info          = $user_lib->find(array('username'=>$post_data['username']));
        if($user_info){
            $this->error('用户名已存在',spUrl('main','reg'));
        }
        $user_info          = $user_lib->find(array('email'=>$post_data['email']));
        if($user_info){
            $this->error('邮箱已存在',spUrl('main','reg'));
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
        $u  = (int)$this->spArgs('u');
        $m  = $this->spArgs('m');
        $user   = spClass('m_user')->find(array('user_id', $u));
        if(md5($user['email']) == $m){
            spClass('m_user')->updateField(array('user_id', $u), 'email_chk', 1);
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
}