<?php

class VocabView extends FrameworkViewBase {

    public $title;
    public $csrf_token;

    public $username;
    public $formdata;
    public $vocab;
    public $kanji;

    public function new()
    {
        $this->init();
        $this->render('new_vocab.html.php');
    }

    public function practice_end()
    {
        $this->init();
        $this->render('practice_end.html.php');
    }

    public function practice()
    {
        $this->init();

        $this->csrf_token = $this->controller->auth->get_csrf_token(true,
            'jlearn');

        $this->formdata = $this->controller->practice->get_formdata();
        $this->vocab = $this->controller->practice->get_data();
        $this->vocab->to_html();

        $word_types = array();
        array_push($word_types, $this->vocab->wtype1);
        array_push($word_types, $this->vocab->wtype2);
        array_push($word_types, $this->vocab->wtype3);
        array_push($word_types, $this->vocab->wtype4);
        array_push($word_types, $this->vocab->wtype5);
        array_push($word_types, $this->vocab->wtype6);
        array_push($word_types, $this->vocab->wtype7);
        $word_type_text = "";
        $word_types_counter = 1;

        $word_trans = "";

        $wtdata = (AppData::get())->get_word_types();
        foreach ($word_types as $word_type) {
            if ($word_type > 0) {
                if ($word_types_counter > 1)
                    $word_type_text .= " / ";
                $word_type_text .= $wtdata[$word_type];
                $word_types_counter++;
            }
        }
        switch ($this->vocab->transitivity) {
        case 1:
            $word_trans = " (Transitive)";
            break;
        case 2:
            $word_trans = " (Intransitive)";
            break;
        case 3:
            $word_trans = " (Transitive & Intransitive)";
            break;
        }
        $data = array(
            'word_type_text' => $word_type_text,
            'word_trans' => $word_trans,
            'search_kanji' => $this->enc($this->formdata->get_search_kanji()),
            'tags' => $this->enc($this->formdata->get_tags_string()),
            'custom_timespace' => $this->enc(
                $this->formdata->get_custom_timespace()),
            'timespace_type' => $this->enc(
                $this->formdata->get_timespace_type()),
            'timespace_start' => $this->enc(
                $this->formdata->get_timespace_start()),
            'timespace_end' => $this->enc(
                $this->formdata->get_timespace_end()),
            'order_rule' => $this->enc($this->formdata->get_order_rule()),
            'ignore_latest' => $this->enc(
                $this->formdata->get_ignore_latest()),
            'counter_limit' => $this->enc(
                $this->formdata->get_counter_limit()),
            'success_limit' => $this->enc(
                $this->formdata->get_success_limit()),
            'jlpt' => $this->enc($this->formdata->get_jlpt()),
            'type' => $this->enc($this->formdata->get_type()),
            'transitivity' => $this->enc($this->formdata->get_transitivity()),
            'query_counter' => $this->enc(
                $this->formdata->get_query_counter()),
            'current_word_counter' => $this->enc(
                $this->formdata->get_current_word_counter()),
        );

        $this->render('practice_vocab.html.php', $data);
    }

    private function init()
    {
        $this->title = 'jlearn 2.0';
        $this->username = $this->enc(
            $this->controller->auth->get_user_name());
        $this->set_layout('layout.html.php');
    }

}
