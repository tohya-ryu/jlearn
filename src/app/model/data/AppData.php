<?php

class AppData {
    use FrameworkSingleton;

    private $word_types = array(
        null,                # 0
        'Noun',            # 1
        'Verb (suru)',     # 2
        'Verb (ichidan)',  # 3
        'Verb (godan)',    # 4
        'Adjective (i)',   # 5
        'Adjactive (na)',  # 6
        'Adverb',          # 7
        'Expression',      # 8
        'Conjunction',     # 9
        'Prenominal',      # 10
        'Adjactive (no)',  # 11
        'Prefix',          # 12
        'Suffix',          # 13
        'Adjactive (taru)',# 14
        'Adverb (to)',     # 15
        'Place',           # 16
        'Name'             # 17
    );

    public function get_word_types()
    {
        return $this->word_types;
    }
}
