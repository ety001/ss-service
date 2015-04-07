<?php
class m_port_pool extends spModel
{
    var $pk = "port"; // 数据表的主键
    var $table = "port_pool"; // 数据表的名称

    public function get_ss_port(){
        $port   = $this->find(array('status'=>0));
        return $port['port'];
    }

    public function change_status($port=0, $status=0){
        return $this->updateField(array('port'=>$port), 'status', $status);
    }
}
