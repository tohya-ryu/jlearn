<?php

class AuthService implements FrameworkServiceBase {
    use FrameworkMagicGet;
    private static $magic_get_attr = array(
        'user', 'controller'
    );

    private $controller;
    private $session;
    private $user;

    private $csrf_mod;
    private $request;
    private $validator;

    public function __construct($controller)
    {
        $this->controller = $controller;
        $this->user = new User();
        $this->session = FrameworkSession::get();
        $this->csrf_mod = null;
        $this->request = FrameworkRequest::get();
        $this->validator = new FrameworkValidator();
    }

    public function login()
    {
        # Attempt to match Session
        //if ($this->session->check_module('auth')) {
        //}
        # Attempt to match Cookie
        # Attempt to match Credentials
        # Fail
    }

    public function logout()
    {
    }

    public function use_csrf_prot()
    {
        if (is_null($this->csrf_mod)) {
            $this->csrf_mod = new FrameworkSessionCsrf();
            $this->csrf_mod->register('auth-csrf');
        } else {
            debug_print_backtrace(0,1);
            trigger_error("CSRF Protection already initialized", E_USER_ERROR);
        }
    }

    public function disable_csrf_prot()
    {
        if (is_null($this->csrf_mod)) {
            debug_print_backtrace(0,1);
            trigger_error("CSRF Protection not initialized", E_USER_ERROR);
        } else {
            $this->csrf_mod->unregister();
            $this->csrf_mod = null;
        }
    }

    public function get_csrf_token($secure = false)
    {
        if (is_null($this->csrf_mod)) {
            return false;
        } else {
            if ($secure) {
                return $this->csrf_mod->token->to_hash();
            } else {
                return $this->csrf_mod->token->code;
            }
        }
    }

    public function validate_signup()
    {
        $this->validator->validate(
            $this->request->param->post('auth-name'),
            $this->request->param->post('auth-email'),
            $this->request->param->post('auth-password'),
            $this->request->param->post('auth-password-check'));
        $this->validator->required();

        $this->validator->validate($this->request->param->post('auth-name'));
        $this->validator->minlen(3);
        $this->validator->maxlen(16);
        $this->validator->regex_match('/^[a-zA-Z]+([_][a-zA-Z]+)*[0-9]*$/',
            Framework::locale()->username_regex_failed());

        $this->validator->validate($this->request->param->post('auth-email'));
        $this->validator->email();

        $this->validator->validate(
            $this->request->param->post('auth-password'));
        $this->validator->minlen(6);
        $this->validator->maxlen(64);

        $this->validator->validate(
            $this->request->param->post('auth-password-check'));
        $this->validator->match_input(
            $this->request->param->post('auth-password'),
            Framework::locale()->inp_password());

        $this->validator->validate_csrf_token($this->csrf_mod->token, true);

    }

    public function handle_signup()
    {
        $response = null;
        if ($this->validator->is_valid()) {
            $response = $this->handle_valid_signup();
        } else {
            $response = $this->handle_invalid_signup();
        }
        return $response;
    }

    private function handle_valid_signup()
    {
        $response = array();
        $this->validator->validate($this->request->param->post('auth-email'));
        if ($this->validator->unique('user', 'email')) {
            // create user and token, send email with activation link
        } else {
            // user exists, send email 
        }
        $response['state'] = 'valid-clear';
        $response['notice'] = FrameworkLocale()->signup_success(
            $this->request->param->post('auth-email'));
        return $response;
    }

    private function handle_invalid_signup()
    {
        $response = array();
        $response['state'] = 'invalid';
        $response['errors'] = $this->validator->get_errors();
        $response['notice'] =
            Framework::locale()->validation_errors_notice();
        if (!$this->validator->csrf_token_is_valid()) {
            $response['csrf_update'] =
                $this->get_csrf_token(true);
        }
        return $response;
    }

}
