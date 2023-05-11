<?php

class VocabData {

    public $id;
    public $user_id;
    public $kanji_name;
    public $hiragana_name;
    public $meanings;
    public $creation_datetime;
    public $update_datetime;
    public $counter;
    public $success_counter;
    public $miss_counter;
    public $success_rate;
    public $wtype1;
    public $wtype2;
    public $wtype3;
    public $wtype4;
    public $wtype5;
    public $wtype6;
    public $wtype7;
    public $jlpt;
    public $tags;
    public $transitivity;

    public function __construct($assoc)
    {
        foreach ($assoc as $k => $v) {
            $this->$k = $v;
        }
    }

    public function to_html()
    {
        $this->kanji_name = HtmlUtil::escape($this->kanji_name);
        $this->hiragana_name = HtmlUtil::escape($this->hiragana_name);
        $this->tags = HtmlUtil::escape($this->tags);
        $this->meanings = HtmlUtil::escape($this->meanings);
        $this->meanings = nl2br($this->meanings);
    }

}
