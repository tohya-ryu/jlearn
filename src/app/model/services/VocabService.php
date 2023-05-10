<?php

class VocabService implements FrameworkServiceBase {
    use FrameworkMagicGet;
    private static $magic_get_attr = array(
         'controller', 'validator'
    );

    private $controller;
    private $session;
    private $user;

    private $request;
    private $validator;
    private $db;

    private $formdata;
    private $sqltmp;
    private $query_param_types;
    private $query_params;

    private $data_obj;

    private $continue;

    public function __construct($controller)
    {
        $this->controller = $controller;
        $this->session = FrameworkSession::get();
        $this->request = FrameworkRequest::get();
        $this->validator = new FrameworkValidator();
        $this->db = FrameworkStoreManager::get()->store();
    }

    public function validate()
    {
        # kanji
        $this->validator->validate($this->request->param->post('kanji'));
        $this->validator->required();
        $this->validator->maxlen(120);
        # hiragana
        $this->validator->validate($this->request->param->post('hiragana'));
        $this->validator->required();
        $this->validator->maxlen(120);
        # meanings
        $this->validator->validate($this->request->param->post('meanings'));
        $this->validator->required();
        # word types
        $this->validator->validate($this->request->param->post('wtype1'),
            $this->request->param->post('wtype2'),
            $this->request->param->post('wtype3'),
            $this->request->param->post('wtype4'),
            $this->request->param->post('wtype5'),
            $this->request->param->post('wtype6'),
            $this->request->param->post('wtype7'));
        $this->validator->required();
        if ($this->validator->regex_match(
            '/^[0-9]*$/', 'Requires integer.'))
        {
            if ($this->request->param->post('wtype1')->value > 17)
                $this->validator->set_error('wtype1', 'allowed range 0-17');
            if ($this->request->param->post('wtype2')->value > 17)
                $this->validator->set_error('wtype2', 'allowed range 0-17');
            if ($this->request->param->post('wtype3')->value > 17)
                $this->validator->set_error('wtype3', 'allowed range 0-17');
            if ($this->request->param->post('wtype4')->value > 17)
                $this->validator->set_error('wtype4', 'allowed range 0-17');
            if ($this->request->param->post('wtype5')->value > 17)
                $this->validator->set_error('wtype5', 'allowed range 0-17');
            if ($this->request->param->post('wtype6')->value > 17)
                $this->validator->set_error('wtype6', 'allowed range 0-17');
            if ($this->request->param->post('wtype7')->value > 17)
                $this->validator->set_error('wtype7', 'allowed range 0-17');
            if ($this->request->param->post('wtype1')->value == 0)
                $this->validator->set_error('wtype1',
                    'Actual typre required.');
        }
        # transitivity
        $this->validator->validate(
            $this->request->param->post('transitivity'));
        $this->validator->required();
        $this->validator->regex_match('/^(0|1|2|3){1}$/',
            'Requires integer (0-3).');
        # jlpt
        $this->validator->validate($this->request->param->post('jlpt'));
        $this->validator->required();
        $this->validator->regex_match('/^(0|1|2|3|4|5){1}$/',
            'Requires integer (0-5).');
        # tags
        if ($this->request->param->post('tags')->value) {
            $this->validator->validate($this->request->param->post('tags'));
            $this->validator->maxlen(255);
        }
        # csrf
        $this->validator->validate_csrf_token(
            $this->controller->auth->csrf_mod->token, true);
        return $this->validator->is_valid();
    }

}
