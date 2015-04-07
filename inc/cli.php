<?php
class cli
{
    public $__command_tpl = "/usr/bin/ssserver -p %s -k %s -d %s --pid-file /tmp/shadowsocks_%s.pid";
    public function run($ssport, $sspass){
        $command    = sprintf($this->__command_tpl, $ssport, $sspass, 'start', $ssport);

    }

    public function get_pid($ssport){
        $command    = 'cat /tmp/shadowsocks_' . $ssport . '.pid';

    }
}