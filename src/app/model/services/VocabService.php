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

    public function insert_new()
    {
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

        # duplicate entry check
        if ($this->validator->is_valid() &&
            !$this->request->param->post('allow-duplicate')->value)
        {
            $kanji = trim($this->request->param->post('kanji')->value);
            $kana = trim($this->request->param->post('hiragana')->value);
            $sql = "SELECT COUNT(`id`) FROM `vocab` WHERE `kanji_name` = ? "
                ."AND `hiragana_name` = ?";
            $res = $this->db->pquery($sql, 'ss', $kanji,
                $kana)->fetch_row()[0];
            if ($res >= 1) {
                # duplicate found
                $this->validator->set_error('kanji', 'Duplicate entry: '.
                    'a word with this kanji and hiragana combination already '.
                    'exists.');
                $this->validator->set_error('hiragana', ' ');
            }
        }
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
                    'Actual type required.');
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
        $this->validator->validate($this->request->param->post('tags'));
        $this->validator->maxlen(255);
        # csrf
        $this->validator->validate_csrf_token(
            $this->controller->auth->csrf_mod->token, true);
        return $this->validator->is_valid();
    }

    public function handle_valid_response($response)
    {
        $response->set_data('state', FrameworkResponse::VALID_CLEAR);
        $response->set_data('notice', 'Database submission successful.');
    }

    public function handle_invalid_response($response)
    {
        $response->set_data('state', FrameworkResponse::INVALID);
        $response->set_data('errors', $this->validator->get_errors());
        $response->set_data('notice',
            'Invalid data. Please correct where applicable.');
        if (!$this->validator->csrf_token_is_valid()) {
            $response->set_data('csrf_update',
                $this->controller->auth->get_csrf_token(true));
        }
    }

}
