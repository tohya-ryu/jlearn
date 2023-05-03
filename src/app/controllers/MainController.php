<?php

class MainController extends FrameworkControllerBase {

    public $auth;

    public function __construct()
    {
        $this->auth = new AuthService($this);
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
        $db = FrameworkStoreManager::get()->store();
        #$sql = "INSERT INTO `user` (`name`, `email`, `password`, `activated`)".
        #    " VALUES (?,?,?,1)";
        #$db->pquery($sql, "sss", 'tim', 'tim@tom.com', 'abcdefg');
        
        #$db->prepare($sql);
        #$db->bind("sss", $name, $email, $pswd);

        #$name = 'User 1';
        #$email = 'User1@users.com';
        #$pswd = 'pw_user1';
        #$db->exec();

        #$name = 'User 2';
        #$email = 'User2@users.com';
        #$pswd = 'pw_user2';
        #$db->exec();

        $sql = "SELECT COUNT(`id`) FROM `user` WHERE `email` = ?";
        $email = 'tim@tom.com';
        $res = $db->pquery($sql, "s", $email);
        var_dump($res->fetch_row());

        $sql = "SELECT COUNT(`id`) FROM `user` WHERE `email` = ?";
        $email = 'tam@tom.com';
        $res = $db->pquery($sql, "s", $email);
        var_dump($res->fetch_row());

    }

}
