<?php

class PracticeFormData {

    private $form_type;

    # used by vocab and kanji practice
    public $id;
    private $counter;
    private $success;
    private $miss;
    private $update_type;

    private $practice_start;
    private $search_kanji;
    private $tags;
    private $order_rule;
    private $custom_timespace;
    private $timespace_type;
    private $timespace_start;
    private $timespace_end;
    private $ignore_latest;
    private $counter_limit;
    private $success_limit;
    private $jlpt;
    private $query_counter;
    private $current_word_counter; # word = vocab/kanji

    # used by vocab practice exclusively
    private $type;
    private $transitivity;
    
    # used by kanji practice exclusively
    private $min_words;
    private $max_words;

    public function __construct($type)
    {
        $this->form_type = $type;
        $this->current_word_counter = 0;
    }

    public function setup()
    {
        $request = FrameworkRequest::get();
        $this->practice_start = $request->param->post('practice-start')->value;
        $this->search_kanji = 
            $request->param->post('search_by_kanji')->value;
        $tags = $request->param->post('tags')->value;
        if ($tags)
            $this->tags = explode('|', $tags);
        $this->order_rule =
            $request->param->post('order_rule')->value;
        $this->custom_timespace =
            $request->param->post('custom_timespace')->value;
        $this->timespace_type =
            $request->param->post('timespace_type')->value;
        $this->timespace_start =
            $request->param->post('timespace_start')->value;
        $this->timespace_end =
            $request->param->post('timespace_end')->value;
        $this->ignore_latest =
            $request->param->post('ignore_latest')->value;
        $this->counter_limit =
            $request->param->post('counter_limit')->value;
        $this->success_limit =
            $request->param->post('success_limit')->value;
        $this->jlpt = $request->param->post('jlpt')->value;
        $this->query_counter =
            $request->param->post('query_counter')->value;
        if ($request->param->post('current_word_counter')->value) {
            $this->current_word_counter =
                $request->param->post('current_word_counter')->value;
        }
        switch($this->form_type) {
        case 'vocab':
            $this->type = $request->param->post('type')->value;
            $this->transitivity = $request->param->post('transitivity')->value;
            break;
        case 'kanji':
            $this->min_words = $request->param->post('min_words')->value;
            $this->max_words = $request->param->post('max_words')->value;
            break;
        }

        $this->id = $request->param->post('id')->value;
        $this->counter = $request->param->post('counter')->value;
        $this->success = $request->param->post('success')->value;
        $this->miss = $request->param->post('miss')->value;
        $this->update_type = $request->param->post('update_type')->value;
    }

    public function get_search_kanji()
    {
        return $this->search_kanji;
    }

    public function get_tags()
    {
        return $this->tags;
    }

    public function get_tags_string()
    {
        if (!is_null($this->tags)) {
            return implode('|', $this->tags);
        }
        return "";
    }

    public function get_order_rule()
    {
        return $this->order_rule;
    }

    public function get_custom_timespace()
    {
        return $this->custom_timespace;
    }

    public function get_timespace_type()
    {
        return $this->timespace_type;
    }

    public function get_timespace_start()
    {
        return $this->timespace_start;
    }

    public function get_timespace_end()
    {
        return $this->timespace_end;
    }

    public function get_ignore_latest()
    {
        return $this->ignore_latest;
    }

    public function get_counter_limit()
    {
        return $this->counter_limit;
    }

    public function get_success_limit()
    {
        return $this->success_limit;
    }

    public function get_jlpt()
    {
        return $this->jlpt;
    }

    public function get_query_counter()
    {
        return $this->query_counter;
    }

    public function get_current_word_counter()
    {
        return $this->current_word_counter;
    }

    public function get_transitivity()
    {
        return $this->transitivity;
    }

    public function get_min_words()
    {
        return $this->min_words;
    }

    public function get_max_words()
    {
        return $this->max_words;
    }

    public function get_practice_start()
    {
        return $this->practice_start;
    }

    public function get_type()
    {
        return $this->type;
    }

    public function set_query_counter($n)
    {
        $this->query_counter = $n;
    }

    public function inc_current_word_counter()
    {
        $this->current_word_counter++;
    }

    public function get_id()
    {
        return $this->id;
    }

    public function get_counter()
    {
        return $this->counter;
    }

    public function get_success()
    {
        return $this->success;
    }

    public function get_miss()
    {
        return $this->miss;
    }

    public function get_update_type()
    {
        return $this->update_type;
    }

}
