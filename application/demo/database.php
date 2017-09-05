<?php
//配置文件
return [
    // 数据库名
    'database'    => 'testdb',
    // 数据库类型
	'type' => 'mysql',
    // 数据库表前缀
    'prefix'      => '',
    // 数据库连接参数
    'params' => [
        // 使用长连接
        \PDO::ATTR_PERSISTENT => true,
    ],    
];