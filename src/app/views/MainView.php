<?php

class MainView extends FrameworkViewBase {

    public $title;
    public $csrf_token;

    public $username;

    public function index()
    {
        $this->set_token();
        $this->title = 'jlearn 2.0';
        $this->username = $this->enc(
            $this->controller->auth->get_user_name());
        $this->set_layout('layout.html.php');
        $this->render('landing.html.php');
    }


    private function set_token()
    {
        $this->csrf_token = $this->controller->auth->get_csrf_token(true);
    }

}
