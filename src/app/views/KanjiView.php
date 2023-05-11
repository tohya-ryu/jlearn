<?php

class KanjiView extends FrameworkViewBase {

    public $title;
    public $csrf_token;

    public $username;
    public $formdata;
    public $kanji;

    public function new()
    {
        $this->init();
        $this->csrf_token = $this->controller->auth->get_csrf_token(true,
            'jlearn/kanji');
        $this->formdata = array(
            'kanji'    => '',
            'onyomi'   => '',
            'kunyomi'  => '',
            'meanings' => '',
            'jlpt'     => 0,
            'tags'     => ''
        );
        $this->render('new_kanji.html.php');
    }

    public function invalid_id()
    {
        $this->init();
        $this->render('edit_invalid_id.html.php');
    }

    public function edit()
    {
        $this->init();
        $this->csrf_token = $this->controller->auth->get_csrf_token(true,
            'jlearn/kanji');
        $this->formdata = array(
            'kanji'    => $this->kanji->kanji,
            'onyomi'   => $this->kanji->onyomi,
            'kunyomi'  => $this->kanji->kunyomi,
            'meanings' => $this->kanji->meanings,
            'jlpt'     => $this->kanji->jlpt,
            'tags'     => $this->kanji->tags
        );
        $this->render('edit_kanji.html.php');
    }

    public function practice_end()
    {
        $this->init();
        $this->render('practice_end.html.php');
    }

    public function practice()
    {
        $this->csrf_token = $this->controller->auth->get_csrf_token(true,
            'jlearn');
        $this->init();

        $this->formdata = $this->controller->practice->get_formdata();
        $this->kanji = $this->controller->practice->get_data();
        $this->kanji->to_html();

        $data = array(
            'min_words' => $this->enc($this->formdata->get_min_words()),
            'max_words' => $this->enc($this->formdata->get_max_words()),
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

        $this->render('practice_kanji.html.php', $data);
    }

    private function init()
    {
        $this->title = 'jlearn 2.0';
        $this->username = $this->enc(
            $this->controller->auth->get_user_name());
        $this->set_layout('layout.html.php');
    }

}
