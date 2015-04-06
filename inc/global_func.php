<?php
/**
 * 模板输出函数 全局
 */
function tpl_display($obj=null, $tpl_name=null, $css_js=array()){
    if(!$obj || !$tpl_name)die('err in tpl_display');
    $obj->head_js = $css_js['head_js']?$css_js['head_js']:array();
    $obj->head_css = $css_js['head_css']?$css_js['head_css']:array();
    $obj->foot_js = $css_js['foot_js']?$css_js['foot_js']:array();
    $obj->bootstrap_responsive = $css_js['bootstrap_responsive']?$css_js['bootstrap_responsive']:false;
    $obj->display('header.html');
    if(is_array($tpl_name)){
        foreach($tpl_name as $k=>$v){
            $obj->display($v);
        }
    } else {
        $obj->display($tpl_name);
    }
    $obj->display('footer.html');
}

function admin_tpl_display($obj=null, $tpl_name=null, $css_js=array()){
    $obj->_admin_tpl_switch = 1;
    $tpl = array('admin/nav.html');
    foreach ($tpl_name as $k => $v) {
        array_push($tpl, $v);
    }
    array_push($tpl, 'admin/foot_nav.html');
    tpl_display($obj, $tpl, $css_js);
}

/**
 * ajax输出函数 全局
 */
function ajax_output($status=0, $msg=null, $info=array()) {
    header('Content-Type: application/json; charset=utf-8');
    $arr['status'] = $status;
    $arr['msg'] = $msg;
    $arr['info'] = $info;
    echo json_encode($arr);
    die();
}

/**
 * 检查是否已登录
 */
function checkHasLogin(){
    if(isset($_SESSION['user']) && $_SESSION['user']['userid']>0){
        return true;
    }
    return false;
}

/**
 * 生成随机字符串
 */
function createStr($len = 5)
{  
    $rand_str = '';  
    for ($i = 0; $i < $len; $i++)  
    {
        $t  = mt_rand(65, 122);
        while($t>90&&$t<97){
            $t  = mt_rand(65, 122);
        }
        $rand_str .= chr($t);
    }
    return $rand_str;  
}

/**
 * 发送邮件
 */
function sendmail($to_user, $subject, $content, $user_name){
    if(!$to_user||!$subject||!$content||!$user_name)return false;
    import('class.phpmailer.php');
    $mail = spClass('PHPMailer');

    $mail->IsSMTP(); // 使用SMTP方式发送
    $mail->Host = "smtp.qq.com"; // 您的企业邮局域名
    $mail->SMTPAuth = true; // 启用SMTP验证功能
    $mail->Username = "gfw@ohshit.cc"; // 邮局用户名(请填写完整的email地址)
    $mail->Password = ""; // 邮局密码
    $mail->Port=25;
    $mail->From = "gfw@ohshit.cc"; //邮件发送者email地址
    $mail->FromName = "GFW.OHSHIT.CC";
    $mail->CharSet  = "utf-8";


    $mail->AddAddress($to_user, $user_name);//收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名")
    $mail->IsHTML(true); // set email format to HTML //是否使用HTML格式
     
    $mail->Subject = $subject; //邮件标题
    $mail->Body = $content; //邮件内容
    //$mail->AltBody = "This is the body in plain text for non-HTML mail clients"; //附加信息，可以省略
    if(!$mail->Send())
    {
        var_dump($mail->ErrorInfo);
        return $mail->ErrorInfo;
    } else {
        return true;
    }
}

/**
 * 找回密码邮件内容
 */
function findPassEmailContent($user_name, $pass){
    if(!$user_name||!$pass)return;
    $html_content   = '<p>'.$user_name.'，您好</p>';
    $html_content   .= '<p>这是您的新密码，请尽快登录修改</p>';
    $html_content   .= '<p>'.$pass.'</p>';
    $html_content   .= '<p>祝您使用愉快！</p>';
    return $html_content;
}

function is_mobile_request()  
{  
    $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';  
    $mobile_browser = '0';  
    if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))  
        $mobile_browser++;  
    if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false))  
        $mobile_browser++;  
    if(isset($_SERVER['HTTP_X_WAP_PROFILE']))  
        $mobile_browser++;  
    if(isset($_SERVER['HTTP_PROFILE']))  
        $mobile_browser++;  
    $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));  
    $mobile_agents = array(  
        'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',  
        'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',  
        'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',  
        'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',  
        'newt','noki','oper','palm','pana','pant','phil','play','port','prox',  
        'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',  
        'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',  
        'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',  
        'wapr','webc','winw','winw','xda','xda-'
    );  
    if(in_array($mobile_ua, $mobile_agents))  
        $mobile_browser++;  
    if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)  
        $mobile_browser++;  
    // Pre-final check to reset everything if the user is on Windows  
    if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)  
        $mobile_browser=0;  
    // But WP7 is also Windows, with a slightly different characteristic  
    if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)  
        $mobile_browser++;  
    if($mobile_browser>0)  
        return true;  
    else
        return false;
}
