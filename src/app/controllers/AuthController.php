<?php

class AuthController extends FrameworkControllerBase {

    public $auth;

    public function __construct()
    {
        $this->auth = new AuthService($this);
        $this->init_language();
    }

    public function login()
    {
        # GET / POST | Login Form | Redirect to Index on success
        $this->auth->use_csrf_prot();
        $this->auth->login();
        $view = new AuthView($this);
        $view->login();
        //var_dump($this->auth->user->check_login());
    }

    public function logout()
    {
        # GET | Logout and Redirect to Index

    }

    public function signup()
    {
        # GET | Sign Up Form | Sends Confirmation Mail on success
        $this->auth->use_csrf_prot();
        $view = new AuthView($this);
        $view->signup();
    }

    public function signup_submit()
    {
        # POST | Validates Form Data
        $this->response->set_type(FrameworkResponse::JSON);
        $this->auth->use_csrf_prot();
        $this->auth->validate_signup();
        $response = $this->auth->handle_signup();
        echo json_encode($response);
    }

    public function signup_confirm()
    {
        # GET | Sign Up confirmation | Activates account linked to :token
    }

    public function pwreset_apply()
    {
        # GET / POST | E-Mail Form
    }

    public function pwreset_confirm()
    {
        # GET / POST | Password Form linked to :token
    }

}
