<?php

class User {

    private $login_state;
    private $login_tokens;

    public function __construct()
    {
        $this->login_state = false;
        $this->login_tokens = array();
    }

    public function check_login()
    {
        return $this->login_state;
    }
}
