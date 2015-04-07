<?php
class test extends spController
{
    public function tt(){
        spClass('m_port_pool')->updateField(array('port'=>10000), 'status', 1);
    }

    public function email(){
        sendmail('ety001@domyself.me', 'test', 'test123', 'ety001');
    }
}