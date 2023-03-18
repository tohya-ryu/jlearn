<?php

$config['name'] = 'jlearn';
$config['key'] = 'db';
$config['type'] = 'mysql/mariadb';
$config['engine'] = 'InnoDB';
$config['charset'] = 'utf8mb4';
$config['collate'] = 'utf8mb4_unicode_ci';
$config['default'] = true;

# Basic connection
$config['connections']['conn1'] = array(
        'key'      => 'conn1',
        'host'     => 'localhost',
        'port'     => '3306',
        'user'     => 'admin',
        'pswd'     => 'mariadb',
        'default'  => true,
        'ssl'      => array(
            'active'        => false,
            'key'         => null,
            'cert'        => null,
            'cacert'      => 'path',
            'capath'      => null,
            'cipheralgos' => null
        ),
        'options' => array(
            MYSQLI_OPT_CONNECT_TIMEOUT => 10,
            MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, false
        ),
    );

// You can configure multiple connections by setting the same keys
// in another array in $config['connections']
// $config['connections']['conn2'] = array(
// 'name' = 'Alternative Connection';
// ...
// );
