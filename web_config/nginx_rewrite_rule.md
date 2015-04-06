'''
location / {
    if ( !-e $request_filename ) {
        rewrite (.*) /index.php?$1 last;
    }
}
'''

'''
'launch' => array( // 加入挂靠点，以便开始使用Url_ReWrite的功能
    'router_prefilter' => array( 
        array('spUrlRewrite', 'setReWrite'),  // 对路由进行挂靠，处理转向地址
        array('spAcl','mincheck') // 开启有限的权限控制
    ),
     'function_url' => array(
        array("spUrlRewrite", "getReWrite"),  // 对spUrl进行挂靠，让spUrl可以进行Url_ReWrite地址的生成
    ),
),
'ext' => array(
    'spUrlRewrite' => array(
        'suffix' => '.html', 
        'sep' => '_', 
        'map' => array(
        ),
        'args' => array(
            
        )
    )
),
'''