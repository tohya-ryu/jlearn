<?php

class AuthService implements FrameworkServiceBase {
    use FrameworkMagicGet;
    private static $magic_get_attr = array(
         'controller'
    );

    private $controller;
    private $session;
    private $user;
    private $auth_mod;

    private $csrf_mod;
    private $request;
    private $validator;
    private $db;

    public function __construct($controller)
    {
        $this->controller = $controller;
        $this->user = new User();
        $this->session = FrameworkSession::get();
        $this->csrf_mod = null;
        $this->auth_mod = null;
        $this->request = FrameworkRequest::get();
        $this->validator = new FrameworkValidator();
        $this->db = FrameworkStoreManager::get()->store();
    }

    public function attempt_login()
    {
        # attempt to match session
        //$this->auth_mod = $this->session->get_module('framework-auth-user');
        $this->auth_mod = new SessionAuth();
        $this->auth_mod->register('framework-auth-user');
        if (!is_null($this->auth_mod->userid)) {
            $sql = "SELECT * FROM `user` WHERE `id` = ?";
            $res = $this->db->pquery($sql, "i", $this->auth_mod->userid);
            if ($res->num_rows == 1) {
                $user = $res->fetch_assoc();
                if ($user['activated']) {
                    $this->user->login($user);
                    return true;
                }
            }
        }
        # attempt to match cookie
        # attempt to match credentials
        # fail
        return false;
    }

    public function form_login()
    {
        # attempt to login user with post data
        $matched = false;
        $this->validator->validate(
            $this->request->param->post('auth-email'),
            $this->request->param->post('auth-password'));
        $this->validator->required();
        $this->validator->validate_csrf_token($this->csrf_mod->token, true);
        
        $email = $this->request->param->post('auth-email')->value;
        $pswd  = $this->request->param->post('auth-password')->value;

        $sql = "SELECT * FROM `user` WHERE `email` = ?";
        $res = $this->db->pquery($sql, "s", $email);

        if ($res->num_rows == 1) {
            $user = $res->fetch_assoc();
            $matched = $this->checkpw($pswd, $user['password']);
            if ($matched)
                $matched = (bool) $user['activated'];
        }

        /*
        $this->controller->response->set_data('test', $matched);
        $this->controller->response->set_data('debug', true);
        $this->controller->response->send();
        exit();
         */

        if ($matched && $this->validator->is_valid()) {
            # login
            $this->user->login($user);
            $this->auth_mod->userid = $user['id'];
            $this->session->register_module('framework-auth-user',
                $this->auth_mod);
            $this->controller->response->set_data('state',
                FrameworkResponse::VALID_KEEP);
            $this->controller->response->set_data('notice',
                Framework::locale()->auth_valid_login());
        } else {
            # forces validator->is_valid() to output FALSE
            $this->validator->set_error('auth-email', '');
            $this->validator->set_error('auth-password', '');
            $this->controller->response->set_data('state',
                FrameworkResponse::INVALID);
            $this->controller->response->set_data('errors',
                $this->validator->get_errors());
            $this->controller->response->set_data('notice',
                Framework::locale()->auth_invalid_login());
            if (!$this->validator->csrf_token_is_valid()) {
                $this->controller->response->set_data('csrf_update',
                    $this->get_csrf_token(true));
            }
        }
    }

    public function logout()
    {
    }

    public function user_exists()
    {
        return $this->user->check_login();
    }

    public function hashpw($pswd)
    {
        return password_hash($pswd, PASSWORD_DEFAULT);
    }

    public function checkpw($pswd, $pswd_hash)
    {
        return password_verify($pswd, $pswd_hash);
    }

    public function use_csrf_prot()
    {
        if (is_null($this->csrf_mod)) {
            $this->csrf_mod = new FrameworkSessionCsrf();
            $this->csrf_mod->register('framework-auth-csrf');
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
            $this->request->param->post('auth-password')->value,
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
        $this->validator->dismiss_errors();
        $this->validator->validate($this->request->param->post('auth-email'));
        if ($this->validator->unique('user', 'email')) {
            // create user and token, send email with activation link
            $response['result'] = "email not found";
        } else {
            // user exists, send email 
            $response['result'] = "email found";
        }
        $response['state'] = 'valid-clear';
        $response['notice'] = Framework::locale()->signup_success(
            $this->request->param->post('auth-email')->value);
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
