<?php

class VocabController extends FrameworkControllerBase {

    public $auth;
    public $practice;

    public function __construct()
    {
        $this->auth = new AuthService($this);
        $this->practice = new PracticeService($this);
        $this->init_language();
        $this->service = new VocabService($this);
    }

    public function new_form()
    {
        # GET | Returns submission form
        $this->response->set_type(FrameworkResponse::HTML);
        $this->auth->use_csrf_prot();
        if ($this->auth->attempt_login()) {
            $view = new VocabView($this);
            $view->new();
        } else {
            $this->redirect($this->base_uri('auth/login'));
        }
    }

    public function edit_form()
    {
        # GET |
        $this->response->set_type(FrameworkResponse::HTML);
        $this->auth->use_csrf_prot();
        if ($this->auth->attempt_login()) {
            $view = new VocabView($this);
            $view->vocab = $this->service->fetch($this->request->param->uri(
                'id')->value);
            if (is_null($view->vocab))
                $view->invalid_id();
            else
                $view->edit();
        } else {
            $this->redirect($this->base_uri('auth/login'));
        }
    }

    public function new_submit()
    {
        # POST | Handles form submission
        $this->response->set_type(FrameworkResponse::JSON);
        $this->auth->use_csrf_prot();
        if ($this->auth->attempt_login()) {
            if ($this->service->validate() &&$this->service->valid_dup()) {
                $this->service->insert_new();
                $this->service->handle_valid_response($this->response);
            } else {
                $this->service->handle_invalid_response($this->response);
            }
        } else {
            $this->response->set_data('redirect', $this->base_uri());
        }
        $this->response->send();
    }

    public function edit_submit()
    {
        # PATCH |
        $this->response->set_type(FrameworkResponse::JSON);
        $this->auth->use_csrf_prot();
        if ($this->auth->attempt_login()) {
            if ($this->service->validate_update()) {
                if (!$this->service->check_names() &&
                    !$this->service->valid_dup())
                {
                    $this->service->handle_invalid_response($this->response);
                    $this->response->send();
                    return;
                }
                $this->service->update();
                $this->service->handle_valid_update_response($this->response);
            } else {
                $this->service->handle_invalid_response($this->response);
            }
        } else {
            $this->response->set_data('redirect', $this->base_uri());
        }
        $this->response->send();
    }

    public function practice()
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

            $view = new VocabView($this);
            if ($this->practice->at_end())
                $view->practice_end();
            else
                $view->practice();
            $this->response->send();
        } else {
            $this->redirect($this->base_uri('auth/login'));
        }
    }

}
