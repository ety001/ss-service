<?php
class main extends spController
{
	public function index(){
        $page               = array(
            'title'     => '私人定制',
            'tag'       => 'index'
        );
        $css_js['head_css'] = array('res/css/global.css');
        $this->page         = $page;
        tpl_display($this, 'index.html', $css_js);
    }

    public function reg(){
        $page               = array(
            'title'     => '注册私人定制',
            'tag'       => 'reg'
        );
        $css_js['head_css'] = array('res/css/global.css');
        $this->page         = $page;
        tpl_display($this, 'main/reg.html', $css_js);
    }

    public function login(){
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
        $post_data          = $this->spArgs(null, false, 'post');
        unset($post_data['/main-regSave_html']);
        $tmp                = array(
            'username'      => '用户名不能为空',
            'password'      => '密码不能为空',
            'repassword'    => '密码不能为空',
            'email'         => 'email不能为空',
            'vpnname'       => 'vpn的登录名不能为空',
            'vpnpass'       => 'vpn的登录密码不能为空'
        );
        //检查是否为空
        foreach ($post_data as $k => $v) {
            if(empty($v)){
                $this->error($tmp[$k], spUrl('main','reg'));
            }
        }
        
        $user_lib           = spClass('m_users');
        $user_info          = $user_lib->find(array('username'=>$post_data['username']));
        if($user_info){
            $this->error('用户名已存在',spUrl('main','reg'));
        }
        if($post_data['password']!==$post_data['repassword']){
            $this->error('两次密码不一样',spUrl('main','reg'));
        }

        $data               = array(
            'username'      => $post_data['username'],
            'password'      => md5($post_data['password']),
            'email'         => $post_data['email'],
            'vpnname'       => $post_data['vpnname'],
            'vpnpass'       => $post_data['vpnpass']
        );

        if($userid = $user_lib->create($data)){
/*            $subject        = '欢迎注册 GFW.OHSHIT.CC';
            $e              = spUrl('main', 'auth', array('u'=>$userid, 'm'=>md5($post_data['email'])));
            $email_content  = <<<EOF
{$post_data['username']}, 您好
感谢您注册私人定制GFW，下面是邮箱验证链接，
<a href="http://gfw.ohshit.cc{$e}">http://gfw.ohshit.cc{$e}</a>
希望您使用愉快~
EOF;
            sendmail($post_data['email'], $subject, $email_content, $post_data['username']);*/
            $this->success('注册成功', spUrl('main', 'login'));
        } else {
            $this->error('注册失败，请联系管理员', spUrl('main','reg'));
        }
    }

    public function logAuth(){
        $post_data          = $this->spArgs(null, false, 'post');
        $user_lib           = spClass('m_users');
        $user_info          = $user_lib->find(array('username'=>$post_data['username']));
        if(!$user_info || $user_info['password']!= md5($post_data['password']) ){
            $this->error('用户名或密码错误', spUrl('main', 'login'));
        }
        $_SESSION['user']   = $user_info;
        $this->success('登录成功', spUrl('user', 'index'));
    }

    public function auth(){

    }

    public function test(){
        sendmail('ety001@domyself.me', 'test', 'test123', 'ety001');
    }
}