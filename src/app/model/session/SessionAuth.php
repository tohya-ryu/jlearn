<?php

class SessionAuth {
    use FrameworkMagicGet;
    private static $magic_get_attr = array(
        'userid'
    );

    private $key;
    private $userid;

    public function __construct($key)
    {
        $this->key = $key;
    }



}
