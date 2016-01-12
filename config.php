<?php
define("APP_PATH",dirname(__FILE__));
define("SP_PATH",dirname(__FILE__).'/SpeedPHP');
$spConfig = array(
    'db' => array( // 数据库设置
        'driver' => 'mysqli',
        'host' => getenv('SS_HOST'),  // 数据库地址
        'login' => getenv('SS_USER'), // 数据库用户名
        'password' => getenv('SS_PASS'), // 数据库密码
        'database' => getenv('SS_DB'), // 数据库的库名称
        'prefix' => getenv('SS_PREFIX') // 表前缀
    ),
    'sp_cache' => APP_PATH.'/tmp',
    'view' => array( // 视图配置
        'enabled' => TRUE, // 开启视图
        'config' =>array(
            'template_dir' => APP_PATH.'/tpl', // 模板目录
        ),
        'engine_name' => 'speedy', // 模板引擎的类名称
        'engine_path' => SP_PATH.'/Drivers/speedy.php', // 模板引擎主类路径
    ),
    'launch' => array( // 加入挂靠点，以便开始使用Url_ReWrite的功能
        'router_prefilter' => array( 
            array('spUrlRewrite', 'setReWrite'),  // 对路由进行挂靠，处理转向地址
        ),
         'function_url' => array(
            array("spUrlRewrite", "getReWrite"),  // 对spUrl进行挂靠，让spUrl可以进行Url_ReWrite地址的生成
        ),
    ),
    'ext' => array(
        'spUrlRewrite' => array(
            'suffix' => '.html', 
            'sep' => '-', 
            'map' => array(
            ),
            'args' => array(
                
            )
        )
    ),
    'include_path' => array(
        APP_PATH.'/inc',
        APP_PATH.'/inc/phpmailer',
        APP_PATH.'/inc/alipay'
    ),
    'siteconfig' => array(
    ),
    'mail' => array(
        'pass' => getenv('SS_EMAIL_PASS')
    ),
    'service_limit' => getenv('SS_LIMIT')?getenv('SS_LIMIT'):8,
    'alipay' => array(
        'cacert' => APP_PATH.'/inc/alipay/' . 'cacert.pem',
        'sign_type' => strtoupper('MD5'),
        'input_charset' => strtolower('utf-8'),
        'transport' => 'http',
        'seller_email' => getenv('SELLER_EMAIL'),
        'partner' => getenv('PARTNER'),//合作身份者id，以2088开头的16位纯数字
        'key' => getenv('PARTNER_KEY'),//安全检验码，以数字和字母组成的32位字符
    ),
    'weidian' => array(
        'key' => getenv('WEIDIAN_KEY'),
        'secret' => getenv('WEIDIAN_SECRET')
    )
);