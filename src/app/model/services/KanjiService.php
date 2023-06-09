<?php

class KanjiService implements FrameworkServiceBase {
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

    private $lookup_result;

    public function __construct($controller)
    {
        $this->controller = $controller;
        $this->session = FrameworkSession::get();
        $this->request = FrameworkRequest::get();
        $this->validator = new FrameworkValidator();
        $this->db = FrameworkStoreManager::get()->store();
    }

    public function update_word_count()
    {
        $vserv = new VocabService($this->controller);
        $sql = "SELECT `id`, `kanji`, `word_count` ".
            "FROM `kanji` WHERE `user_id` = ?";
        $res = $this->db->pquery($sql, 'i',
            $this->controller->auth->get_user_id());
        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $vserv->lookup('kanji_name', 'LIKE', $row['kanji']); 
                $words = $vserv->get_lookup_result();
                if ($words) {
                    $count = count($words);
                    if ($count > $row['word_count']) {
                        $this->db->update('kanji');
                        $this->db->set('word_count', 'i', $count);
                        $this->db->where('id', '=', 'i', $row['id']);
                        $this->db->run();
                    }
                }
            }

        }
    }

    public function fetch($id)
    {
        if ($this->data_obj)
            return $this->data_obj;
        $sql = "SELECT * FROM `kanji` WHERE `id` = ? AND `user_id` = ?";
        $res = $this->db->pquery($sql, 'ii', $id,
            $this->controller->auth->get_user_id());
        $row = $res->fetch_assoc();
        if (is_null($row))
            $this->data_obj = null;
        else
            $this->data_obj = new KanjiData($row);
        return $this->data_obj;
    }

    public function lookup($column, $op, $search)
    {
        $search = trim($search);
        if ($op === 'LIKE')
            $search = "%$search%";
        $sql = "SELECT * FROM `kanji` WHERE `user_id` = ? AND `$column` $op ?";
        $res = $this->db->pquery($sql, 'is',
            $this->controller->auth->get_user_id(), $search);
        if ($res->num_rows < 1) {
            $this->lookup_result = null;
        } else {
            $ar = array();
            while ($row = $res->fetch_assoc()) {
                $obj = new KanjiData($row);
                $obj->to_html();
                array_push($ar, $obj);
            }
            $this->lookup_result = $ar;
        }

    }

    public function get_lookup_result()
    {
        return $this->lookup_result;
    }

    public function check_kanji()
    {
        $request = FrameworkRequest::get();
        $obj = $this->fetch($request->param->post('id')->value);
        return ($obj->kanji == $request->param->post('kanji')->value);
    }

    public function update()
    {
        $rp = $this->request->param;
        $this->db->update('kanji');
        $this->db->set('kanji', 's', $rp->post('kanji')->value);
        $this->db->set('onyomi', 's', trim($rp->post('onyomi')->value));
        $this->db->set('kunyomi', 's', trim($rp->post('kunyomi')->value));
        $this->db->set('meanings', 's', $rp->post('meanings')->value);
        $this->db->set('jlpt', 'i', $rp->post('jlpt')->value);
        $this->db->set('tags', 's', trim($rp->post('tags')->value));
        $this->db->where('`id`', '=', 'i', $rp->post('id')->value);
        $this->db->where('`user_id`', '=', 'i',
            $this->controller->auth->get_user_id());
        $this->db->run();
    }

    public function insert_new()
    {
        $request = FrameworkRequest::get();
        $time = (new DateTime())->getTimestamp();
        $this->db->insert_into('kanji');
        $this->db->set('user_id', 'i',
            $this->controller->auth->get_user_id());
        $this->db->set('kanji', 's', $request->param->post('kanji')->value);
        $this->db->set('onyomi', 's',
            trim($request->param->post('onyomi')->value));
        $this->db->set('kunyomi', 's',
            trim($request->param->post('kunyomi')->value));
        $this->db->set('meanings', 's',
            $request->param->post('meanings')->value);
        $this->db->set('creation_datetime', 'i', $time);
        $this->db->set('update_datetime', 'i', $time);
        $this->db->set('counter', 'i', 1);
        $this->db->set('success_counter', 'i', 0);
        $this->db->set('miss_counter', 'i', 1);
        $this->db->set('success_rate', 'd', 0.00);
        $this->db->set('jlpt', 'i', $request->param->post('jlpt')->value);
        $this->db->set('tags', 's',
            trim($request->param->post('tags')->value));
        $this->new_id = $this->db->run();
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
            $sql = "SELECT COUNT(`id`) FROM `kanji` WHERE `kanji` = ?";
            $res = $this->db->pquery($sql, 's', $kanji)->fetch_row()[0];
            if ($res >= 1) {
                # duplicate found
                $this->validator->set_error('kanji', 'Duplicate entry: '.
                    'this kanji already exists.');
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
        if (mb_strlen($this->request->param->post('kanji')->value, 'UTF-8')>1){
            $this->validator->set_error('kanji', 'String exceeds limit (1)');
        }
        # onyomi
        $this->validator->validate($this->request->param->post('onyomi'));
        $this->validator->required();
        $this->validator->maxlen(255);
        # kunyomi
        $this->validator->validate($this->request->param->post('kunyomi'));
        $this->validator->required();
        $this->validator->maxlen(255);

        # meanings
        $this->validator->validate($this->request->param->post('meanings'));
        $this->validator->required();
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
            $this->controller->auth->csrf_mod->token, true, 'jlearn/kanji');
        return $this->validator->is_valid();
    }

    public function handle_valid_response($response)
    {
        $id = $this->new_id;
        $str = '<a href="'.
            $this->controller->base_uri("edit/kanji/$id").'">'.$this->new_str.
            '</a>';
        $response->set_data('state', FrameworkResponse::VALID_CLEAR);
        $response->set_data('notice', "Successfully added $str to database.".
            " You may keep entering data.");
    }

    public function handle_valid_update_response($response)
    {
        $response->set_data('select_defaults', array(
            'jlpt' => $this->request->param->post('jlpt')->value));
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
                $this->controller->auth->get_csrf_token(true, 'jlearn/kanji'));
        }
    }

}
