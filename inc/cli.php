<?php
class cli
{
    public $__command_tpl = "/usr/bin/ssserver -p %s -k %s -d %s --pid-file /tmp/shadowsocks_%s.pid";
    public function run($ssport, $sspass){
        $command    = sprintf($this->__command_tpl, $ssport, $sspass, 'start', $ssport);
        exec($command);
    }

    public function stop($ssport, $sspass){
        $command    = sprintf($this->__command_tpl, $ssport, $sspass, 'stop', $ssport);
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
        if($output){
            return true;
        } else {
            return false;
        }
    }

    public function check_status($ssport){
        $pid        = $this->get_pid($ssport);
        return $this->chk($pid);
    }
}