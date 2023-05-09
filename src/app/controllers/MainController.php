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

    public function practice_vocab()
    {
        # POST | 
        $this->response->set_type(FrameworkResponse::HTML);
        $this->auth->use_csrf_prot();
        if ($this->auth->attempt_login()) {
            # validate
            if (!$this->practice->validate_formdata('vocab')) {
                echo "<p><b>validation errors</b></p>";
                var_dump($this->practice->validator->get_errors());
                exit();
                $this->redirect($this->base_uri(''));
            }
            # update last vocab if not first request of a practice session
            if (!$this->request->param->post('practice-start'))
                $this->practice->update('vocab');
            # session handling
            $this->practice->vocab();

            $view = new MainView($this);
            if ($this->practice->at_end())
                $view->practice_end();
            else
                $view->practice_vocab();
            $this->response->send();
        } else {
            $this->redirect($this->base_uri('auth/login'));
        }
    }

    public function practice_kanji()
    {
        # POST | 
        $this->response->set_type(FrameworkResponse::HTML);
        $this->auth->use_csrf_prot();
        if ($this->auth->attempt_login()) {
            # validate
            if (!$this->practice->validate_formdata('kanji')) {
                echo "<p><b>validation errors</b></p>";
                var_dump($this->practice->validator->get_errors());
                exit();
                $this->redirect($this->base_uri(''));
            }
            # update last kanji if not first request of a practice session
            $this->practice->update('kanji');
            # session handling
            $this->practice->kanji();
            $view = new MainView($this);
            if ($this->practice->at_end())
                $view->practice_end();
            else
                $view->practice_kanji();
            $this->response->send();
        } else {
            $this->redirect($this->base_uri('auth/login'));
        }
    }

    public function test()
    {
        //$db = FrameworkStoreManager::get()->store();
        //$this->auth->use_csrf_prot();
        $auth = new FrameworkSessionCsrf();
        $auth->register('test');
        echo $auth->token->to_hash();
        echo "<br>";
        $auth = new SessionAuth();
        $auth->register('test-2');
        echo "<br>";
        $auth = new FrameworkSessionCsrf();
        $auth->register('test');
        echo $auth->token->to_hash();
        echo "<br>";

    }

}
