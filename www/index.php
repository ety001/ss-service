<?php
require('../config.php');
$spConfig['mode'] = getenv('GFW_MODE');
require(SP_PATH."/SpeedPHP.php");
import(APP_PATH.'/inc/global_func.php');//载入自定义函数库
spRun();