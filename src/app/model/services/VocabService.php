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
    private $data_obj;

    public function __construct($controller)
    {
        $this->controller = $controller;
        $this->session = FrameworkSession::get();
        $this->request = FrameworkRequest::get();
        $this->validator = new FrameworkValidator();
        $this->db = FrameworkStoreManager::get()->store();
    }

    public function fetch($id)
    {
        if ($this->data_obj)
            return $this->data_obj;
        $sql = "SELECT * FROM `vocab` WHERE `id` = ? AND `user_id` = ?";
        $res = $this->db->pquery($sql, 'ii', $id,
            $this->controller->auth->get_user_id());
        $row = $res->fetch_assoc();
        if (is_null($row))
            $this->data_obj = null;
        else
            $this->data_obj = new VocabData($row);
        return $this->data_obj;
    }

    public function check_names()
    {
        $request = FrameworkRequest::get();
        $obj = $this->fetch($request->param->post('id')->value);
        return ($obj->kanji_name == 
            trim($request->param->post('kanji')->value) &&
            $obj->hiragana_name == 
            trim($request->param->post('hiragana')->value));
    }

    public function update()
    {
        $sql = "UPDATE `vocab` SET `kanji_name` = ?, `hiragana_name` = ?, ".
            "`meanings` = ?, `wtype1` = ?, `wtype2` = ?, `wtype3` = ?, ".
            "`wtype4` = ?, `wtype5` = ?, `wtype6` = ?, `wtype7` = ?, ".
            "`jlpt` = ?, `transitivity` = ?, `tags` = ? WHERE ".
            "`id` = ? AND `user_id` = ?";
        $this->db->pquery($sql, 'sssiiiiiiiiisii',
            trim($this->request->param->post('kanji')->value),
            trim($this->request->param->post('hiragana')->value),
            $this->request->param->post('meanings')->value,
            $this->request->param->post('wtype1')->value,
            $this->request->param->post('wtype2')->value,
            $this->request->param->post('wtype3')->value,
            $this->request->param->post('wtype4')->value,
            $this->request->param->post('wtype5')->value,
            $this->request->param->post('wtype6')->value,
            $this->request->param->post('wtype7')->value,
            $this->request->param->post('jlpt')->value,
            $this->request->param->post('transitivity')->value,
            trim($this->request->param->post('tags')->value),
            $this->request->param->post('id')->value,
            $this->controller->auth->get_user_id());
    }

    public function insert_new()
    {
        $request = FrameworkRequest::get();
        $time = (new DateTime())->getTimestamp();
        $this->db->assoc('user_id', 'i',
            $this->controller->auth->get_user_id());
        $this->db->assoc('kanji_name', 's',
            trim($request->param->post('kanji')->value));
        $this->db->assoc('hiragana_name', 's',
            trim($request->param->post('hiragana')->value));
        $this->db->assoc('meanings', 's',
            $request->param->post('meanings')->value);
        $this->db->assoc('creation_datetime', 'i', $time);
        $this->db->assoc('update_datetime', 'i', $time);
        $this->db->assoc('counter', 'i', 1);
        $this->db->assoc('success_counter', 'i', 0);
        $this->db->assoc('miss_counter', 'i', 1);
        $this->db->assoc('success_rate', 'd', 0.00);
        $this->db->assoc('wtype1', 'i',$request->param->post('wtype1')->value);
        $this->db->assoc('wtype2', 'i',$request->param->post('wtype2')->value);
        $this->db->assoc('wtype3', 'i',$request->param->post('wtype3')->value);
        $this->db->assoc('wtype4', 'i',$request->param->post('wtype4')->value);
        $this->db->assoc('wtype5', 'i',$request->param->post('wtype5')->value);
        $this->db->assoc('wtype6', 'i',$request->param->post('wtype6')->value);
        $this->db->assoc('wtype7', 'i',$request->param->post('wtype7')->value);
        $this->db->assoc('jlpt', 'i', $request->param->post('jlpt')->value);
        $this->db->assoc('tags', 's',
            trim($request->param->post('tags')->value));
        $this->db->assoc('transitivity', 'i',
            $request->param->post('transitivity')->value);
        $this->new_id = $this->db->insert('vocab');
        $this->new_str = HtmlUtil::escape(
            $this->request->param->post('kanji')->value);
    }

    public function validate_update()
    {
        if ($this->validate()) {
            $this->validator->validate($this->request->param->post('id'));
            $this->validator->required();
            $this->validator->regex_match('/^[0-9]+$/', 'Requires integer.');
            return $this->validator->is_valid();
        }
        return false;
    }

    public function valid_dup()
    {
        # duplicate entry check
        if (!$this->request->param->post('allow-duplicate')->value)
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
                return false;
            }
        }
        # no duplicates found
        return true;
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
            $this->controller->auth->csrf_mod->token, true, 'jlearn/vocab');
        return $this->validator->is_valid();
    }

    public function handle_valid_response($response)
    {
        $id = $this->new_id;
        $str = '<a href="'.
            $this->controller->base_uri("edit/vocab/$id").'">'.$this->new_str.
            '</a>';
        $response->set_data('state', FrameworkResponse::VALID_CLEAR);
        $response->set_data('notice', "Successfully added $str to database.".
            " You may keep entering data.");
    }

    public function handle_valid_update_response($response)
    {
        $response->set_data('select_defaults', array(
            'wtype1' => $this->request->param->post('wtype1')->value,
            'wtype2' => $this->request->param->post('wtype2')->value,
            'wtype3' => $this->request->param->post('wtype3')->value,
            'wtype4' => $this->request->param->post('wtype4')->value,
            'wtype5' => $this->request->param->post('wtype5')->value,
            'wtype6' => $this->request->param->post('wtype6')->value,
            'wtype7' => $this->request->param->post('wtype7')->value,
            'jlpt' => $this->request->param->post('jlpt')->value,
            'transitivity' => $this->request->param->post(
                'transitivity')->value
        ));
        $response->set_data('state', FrameworkResponse::VALID_KEEP);
        $response->set_data('notice', 'Resource update successful.');
    }

    public function handle_invalid_response($response)
    {
        $response->set_data('state', FrameworkResponse::INVALID);
        $response->set_data('errors', $this->validator->get_errors());
        $response->set_data('notice',
            'Invalid data. Please correct where applicable.');
        if (!$this->validator->csrf_token_is_valid()) {
            $response->set_data('csrf_update',
                $this->controller->auth->get_csrf_token(true, 'jlearn/vocab'));
        }
    }

}
