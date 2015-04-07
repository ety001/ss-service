<?php
class test extends spController
{
    public function tt(){
        spClass('m_port_pool')->updateField(array('port'=>10000), 'status', 1);
    }
}