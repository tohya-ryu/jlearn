<?php

class MainController extends FrameworkControllerBase {

    public $auth;
    public $practice;

    public function __construct()
    {
        $this->auth = new AuthService($this);
        $this->practice = new PracticeService($this);
        $this->init_language();
    }

    public function index()
    {
        # GET | Home (requires login)
        $this->response->set_type(FrameworkResponse::HTML);
        $this->auth->use_csrf_prot();
        if ($this->auth->attempt_login()) {
            $view = new MainView($this);
            $view->index();
            $this->response->send();
        } else {
            $this->redirect($this->base_uri('auth/login'));
        }
    }

    public function test()
    {
        //$db = FrameworkStoreManager::get()->store();
    }

}
