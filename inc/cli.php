<?php
class cli
{
    public $__command_tpl = "/usr/local/bin/ss-server -p %s -k %s --user www -t 600 -f /tmp/shadowsocks_%s.pid";
    //public $__command_tpl = "/usr/bin/ssserver -p %s -k %s -d %s --user www --log-file /tmp/shadowsocks.log --pid-file /tmp/shadowsocks_%s.pid";
    public function run($ssport, $sspass){
        $command    = sprintf($this->__command_tpl, $ssport, $sspass, $ssport);
        exec($command);
    }

    public function stop($ssport, $sspass){
        /*$command    = sprintf($this->__command_tpl, $ssport, $sspass, 'stop', $ssport);
        exec($command);*/
        $pid            = $this->get_pid($ssport);
        $command        = "kill {$pid}";
        exec($command);
    }

    public function get_pid($ssport){
        $command    = 'cat /tmp/shadowsocks_' . $ssport . '.pid';
        exec($command, $output, $status);
        return $output[0];
    }

    public function chk($pid){
        $command    = sprintf("ps aux |awk  -F ' '  '{print $2}'| grep %s", $pid);
        exec($command, $output, $status);
        if((int)$output[0]>0){
            return true;
        } else {
            return false;
        }
    }

    public function check_status($ssport){
        $pid        = $this->get_pid($ssport);
        return $this->chk($pid);
    }

    public function list_all(){
        $command    = sprintf("ps aux | grep -v 'grep' | grep ss-server | awk -F ' ' '{print $2}'");
        //$command    = sprintf("ps aux | grep -v 'grep' | grep ssserver | awk -F ' ' '{print $2}'");
        exec($command, $out, $status);
        return $out;
    }
}
