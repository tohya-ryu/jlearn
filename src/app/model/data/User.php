<?php

class User {

    private $login_state;
    private $login_tokens;

    private $id;
    private $name;
    private $email;
    private $password;
    private $token;
    private $activated;
    private $tag;
    private $lang_tag;
    private $token_datetime;
    private $creation_datetime;

    public function __construct()
    {
        $this->login_state = false;
        $this->login_tokens = array();
    }

    public function check_login()
    {
        return $this->login_state;
    }

    public function login($data)
    {
        foreach ($data as $k => $v) {
            $this->$k = $v;
        }
        $this->login_state = true;
    }
}
