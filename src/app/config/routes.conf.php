<?php

FrameworkRoute::set('get', '/', 'main#index');

FrameworkRoute::set('post', 'count-words', 'main#count_words');
FrameworkRoute::set('get', 'add/vocab', 'main#add_vocab');
FrameworkRoute::set('get', 'add/kanji', 'main#add_kanji');
FrameworkRoute::set('get', 'fetch/vocab/:search', 'main#fetch_vocab');
FrameworkRoute::set('get', 'fetch/kanji/:search', 'main#fetch_kanji');
FrameworkRoute::set('get', 'get/vocab/:id', 'main#vocab');
FrameworkRoute::set('get', 'get/kanji/:id', 'main#kanji');
FrameworkRoute::set('get', 'list/vocab/:page', 'main#list_vocab');
FrameworkRoute::set('get', 'list/kanji/:page', 'main#list_kanji');
FrameworkRoute::set('patch', 'update/vocab', 'main#update_vocab');
FrameworkRoute::set('patch', 'update/kanji', 'main#update_kanji');
FrameworkRoute::set('post', 'practice/kanji', 'main#practice_kanji');
FrameworkRoute::set('post', 'practice/vocab', 'main#practice_vocab');
FrameworkRoute::set('post', 'add/vocab', 'main#add_vocab_submit');
FrameworkRoute::set('post', 'add/kanji', 'main#add_kanji_submit');

require 'app/config/auth-routes.conf.php';


#FrameworkRoute::set('get', 'page/data/:id{[0-9]+}', 'main#data');
#
FrameworkRoute::set_default('main#index'); // 404 goes here
