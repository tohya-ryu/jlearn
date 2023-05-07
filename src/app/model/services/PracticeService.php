<?php

class PracticeService implements FrameworkServiceBase {
    use FrameworkMagicGet;
    private static $magic_get_attr = array(
         'controller'
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
        return !$this->continue;
    }

    public function update($type)
    {
        $this->validator->dismiss_errors();
        $this->validator->validate($this->request->param->post('id'));
        $this->validator->required();
        $this->validator->regex_match('/^[0-9]*$/','');
        if ($this->validator->is_valid()) {
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
                $success++;
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
    }

    public function vocab()
    {
        $this->collect_formdata('vocab');
        $this->construct_query('vocab');
        $this->prepare_session('vocab');
    }

    public function kanji()
    {
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

    private function prepare_session($type)
    {
        if ($this->formdata->get_practice_start()) {
            $sql = "SELECT COUNT(`id`) ".$this->sqltmp;
            $this->formdata->set_query_counter($this->db->pquery($sql,
                $this->query_param_types,
                ...$this->query_params)->fetch_row()[0]);
        }
        $n = $this->db->escape($this->formdata->get_current_word_counter());
        $sql = "SELECT * ".$this->sqltmp;
        $sql .= "LIMIT 1 OFFSET $n";
        $res = $this->db->pquery($sql, $this->query_param_types,
            ...$this->query_params);
        if ($res->num_rows === 0) {
            $this->continue = false;
        } else {
            $row = $res->fetch_assoc();
            $this->data_obj = new VocabData($row);
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
            $this->sqltmp .= "AND `kanji_name` LIKE ? ";
            $this->setqp('s', "%".$this->formdata->get_search_kanji()."%");
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
        $this->sqltmp .= "AND `success_rate` <= ? ";
        $this->setqp('d', $this->formdata->get_success_limit());
        # apply search by jlpt level
        if ($this->formdata->get_jlpt()) {
            $this->sqltmp .= "AND `jlpt` = ? ";
            $this->setqp('i', $this->formdata->get_jlpt());
        }
        # apply search by word type (vocab only)
        if ($this->formdata->get_type()) {
            $this->sqltmp .= "AND (`wtype1` = ? OR `wtype2` = ? OR `wtype3` = ? "
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
        if ($this->formdata->get_transitivity()) {
            $this->sqltmp .= "AND `transitivity` = ? ";
            $this->setqp('i', $this->formdata->get_transitivity());
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
