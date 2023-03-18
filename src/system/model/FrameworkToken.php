<?php

class FrameworkToken {
    use FrameworkMagicGet;
    private static $magic_get_attr = array('code');

    const BYTES = 32;
    private $code;

    public function __construct($code = false)
    {
        if ($code) {
            $this->code = $code;
        } else {
            $this->code = bin2hex(random_bytes(self::BYTES));
        }
    }

    public function to_hash()
    {
        return hash_hmac('sha256', FrameworkRequest::get()->uri, $this->code);
    }

}
