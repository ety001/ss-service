<?php
class m_order extends spModel
{
    var $pk = "id"; // 数据表的主键
    var $table = "order"; // 数据表的名称
    var $linker = array(
        array(
            'type' => 'hasone',   // 关联类型，这里是一对一关联
            'map' => 'user_info',    // 关联的标识
            'mapkey' => 'userid', // 本表与对应表关联的字段名
            'fclass' => 'm_users', // 对应表的类名
            'fkey' => 'userid',    // 对应表中关联的字段名
            'enabled' => true     // 启用关联
        )
    );
}
