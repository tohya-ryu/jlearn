<?php

class PracticeService implements FrameworkServiceBase {
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
    //private $user_id;
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
        $this->query_param_types = "";
        $this->query_params = array();
        $this->continue = true;
    }

    public function at_end()
    {
        if (!$this->continue) {
            $session = new SessionPractice();
            $session->register('practice');
            $session->reset();
            $session->save();
        }
        return !$this->continue;
    }

    public function update($type)
    {
        $id = $this->request->param->post('id')->value;
        $success = $this->request->param->post('success')->value;
        $miss = $this->request->param->post('miss')->value;
        $counter = $this->request->param->post('counter')->value;
        $update_type = $this->request->param->post('update_type')->value;

        switch($update_type) {
        case 2:
            $counter++;
            $success++;
            $success_rate = $this->calc_success_rate($success, $counter);
            $time = (new DateTime())->getTimestamp();
            $sql = "UPDATE `$type` SET `success_counter` = ?, ".
                "`counter` = ?, `success_rate` = ?, ".
                "`update_datetime` = ? WHERE `id` = ?;";
            $this->db->pquery($sql, 'iidii', $success, $counter,
                $success_rate, $time, $id);
            break;
        case 3:
            $counter++;
            $miss++;
            $success_rate = $this->calc_success_rate($success, $counter);
            $time = (new DateTime())->getTimestamp();
            $sql = "UPDATE `$type` SET `miss_counter` = ?, ".
                "`counter` = ?, `success_rate` = ?, ".
                "`update_datetime` = ? WHERE `id` = ?;";
            $this->db->pquery($sql, 'iidii', $miss, $counter,
                $success_rate, $time, $id);
            break;
        case 1:
        default:
            # do nothing
        }
    }

    public function vocab()
    {
        $this->collect_formdata('vocab');
        if ($this->formdata->get_practice_start())
            $this->construct_query('vocab');
        $this->prepare_session('vocab');
    }

    public function kanji()
    {
        $this->collect_formdata('kanji');
        if ($this->formdata->get_practice_start())
            $this->construct_query('kanji');
        $this->prepare_session('kanji');
    }

    private function calc_success_rate($a, $b)
    {
        $c = ($a / $b) * 100;
        $c = round($c, 2);
        return $c;
    }

    private function collect_formdata($type)
    {
        $this->formdata = new PracticeFormData($type);
        $this->formdata->setup();
    }

    public function validate_formdata($type)
    {
        if (!$this->request->param->post('practice-start')->value) {
            # these only require validation when session is in progress
            $this->validator->validate($this->request->param->post('id'));
            $this->validator->validate($this->request->param->post('counter'));
            $this->validator->validate($this->request->param->post('success'));
            $this->validator->validate($this->request->param->post('miss'));
            $this->validator->validate(
                $this->request->param->post('query_counter'));
            $this->validator->validate(
                $this->request->param->post('current_word_counter'));
            $this->validator->required();
            $this->validator->regex_match('/^[0-9]*$/', 'Requires integer.');

            $this->validator->validate(
                $this->request->param->post('update_type'));
            $this->validator->required();
            $this->validator->regex_match('/^(1|2|3){1}$/',
                'Requires integer (1-3).');
        }
        $this->validator->validate($this->request->param->post('order_rule'));
        $this->validator->required();
        $this->validator->regex_match('/^(0|1|2|3|4){1}$/',
            'Requires integer (0-4).');

        $this->validator->validate(
            $this->request->param->post('ignore_latest'));
        $this->validator->validate(
            $this->request->param->post('counter_limit'));
        $this->validator->validate(
            $this->request->param->post('min_success_limit'));
        $this->validator->validate(
            $this->request->param->post('max_success_limit'));
        $this->validator->required();
        $this->validator->regex_match('/^[0-9]*$/', 'Requires integer.');

        $this->validator->validate(
            $this->request->param->post('timespace_type'));
        $this->validator->required();
        $this->validator->regex_match('/^(1|2){1}$/',
            'Requires integer (1-2).');

        if (!strtotime($this->request->param->post('timespace_start')->value))
            $this->validator->set_error('timespace_start', 'Invalid input');

        if (!strtotime($this->request->param->post('timespace_end')->value))
            $this->validator->set_error('timespace_end', 'Invalid input');

        $this->validator->validate($this->request->param->post('jlpt'));
        $this->validator->required();
        $this->validator->regex_match('/^(0|1|2|3|4|5){1}$/',
            'Requires integer (0-5).');

        if ($type == 'vocab') {
            $this->validator->validate($this->request->param->post('type'));
            $this->validator->required();
            if ($this->validator->regex_match(
                '/^[0-9]*$/', 'Requires integer.'))
            {
                if ($this->request->param->post('type')->value > 17)
                    $this->validator->set_error('type', 'allowed range 0-17');
            }
            $this->validator->validate(
                $this->request->param->post('transitivity'));
            $this->validator->required();
            $this->validator->regex_match('/^(0|1|2|3){1}$/',
                'Requires integer (0-3).');
        }
        if ($type == "kanji") {
            $this->validator->validate(
                $this->request->param->post('min_words'));
            $this->validator->validate(
                $this->request->param->post('min_words'));
            $this->validator->required();
            if ($this->validator->regex_match(
                '/^[0-9]*$/', 'Requires integer.'))
            {
                if ($this->request->param->post('min_words')->value >=
                    $this->request->param->post('max_words')->value)
                {
                    $this->validator->set_error('min_words',
                        'Has to be lower value than max_words.');
                }
            }
        }

        if ($this->request->param->post('custom')->value) {
            $this->validator->validate($this->request->param->post('custom'));
            $this->validator->maxlen(255);
        }

        $this->validator->validate_csrf_token(
            $this->controller->auth->csrf_mod->token, true,
            'jlearn');

        return $this->validator->is_valid();

    }

    private function prepare_session($type)
    {
        $session = new SessionPractice();
        $session->register('practice');
        if ($this->formdata->get_practice_start()) {
            $sql = "SELECT * ".$this->sqltmp;
            $res = $this->db->pquery($sql, $this->query_param_types,
                ...$this->query_params);
            $set = array();
            while ($row = $res->fetch_assoc())
                array_push($set, $row);
            $this->formdata->set_query_counter(count($set));
            $session->set($set);
            $session->save();
            //$sql = "SELECT COUNT(`id`) ".$this->sqltmp;
            //$this->formdata->set_query_counter($this->db->pquery($sql,
            //    $this->query_param_types,
            //    ...$this->query_params)->fetch_row()[0]);
        }
        $index = $this->formdata->get_current_word_counter();
        //$n = $this->db->escape($this->formdata->get_current_word_counter());
        //$sql = "SELECT * ".$this->sqltmp;
        //$sql .= "LIMIT 1 OFFSET $n";
        //var_dump($sql);
        //$res = $this->db->pquery($sql, $this->query_param_types,
        //    ...$this->query_params);
        if ($index >= count($session->set)) {
        //if ($res->num_rows === 0) {
            $this->continue = false;
        } else {
            $row = $session->set[$index];
            //$row = $res->fetch_assoc();
            if ($type == 'vocab')
                $this->data_obj = new VocabData($row);
            if ($type == 'kanji')
                $this->data_obj = new KanjiData($row);
            $this->formdata->inc_current_word_counter();
            $this->formdata->id = $this->data_obj->id;
        }
    }

    private function construct_query($type)
    {
        $this->sqltmp = "FROM `$type` WHERE `user_id` = ? ";
        $this->setqp('i', $this->controller->auth->get_user_id());
        # apply search by kanji
        if ($this->formdata->get_search_kanji()) {
            if ($type === 'kanji') {
                $this->sqltmp .= "AND `kanji` = ? ";
                $this->setqp('s', $this->formdata->get_search_kanji());
            } else {
                $this->sqltmp .= "AND `kanji_name` LIKE ? ";
                $this->setqp('s', "%".$this->formdata->get_search_kanji()."%");
            }
        }
        # apply search by tags
        if ($this->formdata->get_tags()) {
            $i = 0;
            foreach ($this->formdata->get_tags() as $tag) {
                if ($i == 0) {
                    $this->sqltmp .= "AND (`tags` LIKE ? ";
                } else {
                    $this->sqltmp .= "OR `tags` LIKE ? ";
                }
                $this->setqp('s', "%$tag%");
                $i++;
            }
            $this->sqltmp .= ") ";
        }
        # apply search by default/custom time interval
        if ($this->formdata->get_custom_timespace() != 'accept') {
            $this->sqltmp .= "AND `update_datetime` < ? ";
            $this->setqp('i', $this->formdata->get_ignore_latest());
        } else {
            $column = "";
            switch ($this->formdata->get_timespace_type()) {
            case 1:
                $column = "creation_datetime";
                break;
            case 2:
                $column = "update_datetime";
                break;
            }
            $this->sqltmp .= "AND (`$column` BETWEEN ? AND ?) ";
            $this->setqp('i',
                strtotime($this->formdata->get_timespace_start()));
            $this->setqp('i', strtotime($this->formdata->get_timespace_end()));
        }
        # apply search by counter
        $this->sqltmp .= "AND `counter` <= ? ";
        $this->setqp('i', $this->formdata->get_counter_limit());
        # apply search by success rate
        $this->sqltmp .= "AND (`success_rate` BETWEEN ? AND ?) ";
        $this->setqp('d', $this->formdata->get_min_success_limit());
        $this->setqp('d', $this->formdata->get_max_success_limit());
        # apply search by jlpt level
        if ($this->formdata->get_jlpt()) {
            $this->sqltmp .= "AND `jlpt` = ? ";
            $this->setqp('i', $this->formdata->get_jlpt());
        }
        # apply search by word type (vocab only)
        if ($this->formdata->get_type() && $type == 'vocab') {
            $this->sqltmp .= "AND (`wtype1` = ? OR `wtype2` = ? OR "
                ."`wtype3` = ? "
                ."OR `wtype4` = ? OR `wtype5` = ? OR `wtype6` = ? "
                ."OR `wtype7` = ?) ";
            $this->setqp('s', $this->formdata->get_type());
            $this->setqp('s', $this->formdata->get_type());
            $this->setqp('s', $this->formdata->get_type());
            $this->setqp('s', $this->formdata->get_type());
            $this->setqp('s', $this->formdata->get_type());
            $this->setqp('s', $this->formdata->get_type());
            $this->setqp('s', $this->formdata->get_type());
        }
        # apply search by transitivity (vocab only)
        if ($this->formdata->get_transitivity() && $type == 'vocab') {
            $this->sqltmp .= "AND `transitivity` = ? ";
            $this->setqp('i', $this->formdata->get_transitivity());
        }
        # apply search by number of vocabs associated with kanji
        if ($type == 'kanji') {
            $this->sqltmp .= "AND (`word_count` BETWEEN ? AND ?) ";
            $this->setqp('i', $this->formdata->get_min_words());
            $this->setqp('i', $this->formdata->get_max_words());
        }
        # apply order rule
        $session_id = $this->db->escape((FrameworkSession::get())->get_id());
        switch ($this->formdata->get_order_rule()) {
        case 0:
            $this->sqltmp .= "ORDER BY RAND('$session_id') ";
            break;
        case 1:
            $this->sqltmp .= "ORDER BY `success_rate` ASC, `counter` DESC ";
            break;
        case 2:
            $this->sqltmp .= "ORDER BY `update_datetime` ASC ";
            break;
        case 3:
            $this->sqltmp .= "ORDER BY `creation_datetime` ASC ";
            break;
        case 4:
            $this->sqltmp .= "ORDER BY `update_datetime` DESC ";
            break;
        default:
            $this->sqltmp .= "ORDER BY RAND('$session_id') ";
        }
    }

    private function setqp($type, $param)
    {
        $this->query_param_types .= $type;
        array_push($this->query_params, $param);
    }

    public function get_formdata()
    {
        return $this->formdata;
    }

    public function get_data()
    {
        return $this->data_obj;
    }


}
