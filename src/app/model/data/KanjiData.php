<?php

class KanjiData {

    public $id;
    public $user_id;
    public $kanji;
    public $onyomi;
    public $kunyomi;
    public $meanings;
    public $creation_datetime;
    public $update_datetime;
    public $counter;
    public $success_counter;
    public $miss_counter;
    public $success_rate;
    public $jlpt;
    public $tags;
    public $word_count;

    public function __construct($assoc)
    {
        foreach ($assoc as $k => $v) {
            $this->$k = $v;
        }
    }

    public function to_html()
    {
        $this->kanji = HtmlUtil::escape($this->kanji);
        $this->onyomi = HtmlUtil::escape($this->onyomi);
        $this->kunyomi = HtmlUtil::escape($this->kunyomi);
        $this->meanings = HtmlUtil::escape($this->meanings);
        $this->meanings = nl2br($this->meanings);
    }

}
