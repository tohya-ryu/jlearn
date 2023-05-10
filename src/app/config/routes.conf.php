<?php

FrameworkRoute::set('get', '/', 'main#index');
FrameworkRoute::set('get', 'update/counters', 'main#count_words');

FrameworkRoute::set('get', 'new/vocab', 'vocab#new_form');
FrameworkRoute::set('post', 'new/vocab', 'vocab#new_submit');
FrameworkRoute::set('post', 'practice/vocab', 'vocab#practice');

FrameworkRoute::set('get', 'new/kanji', 'kanji#new_form');
FrameworkRoute::set('post', 'new/kanji', 'kanji#new_submit');
FrameworkRoute::set('post', 'practice/kanji', 'kanji#practice');

FrameworkRoute::set('get', 'fetch/vocab/:search', 'main#fetch_vocab');
FrameworkRoute::set('get', 'fetch/kanji/:search', 'main#fetch_kanji');
FrameworkRoute::set('get', 'get/vocab/:id', 'main#vocab');
FrameworkRoute::set('get', 'get/kanji/:id', 'main#kanji');
FrameworkRoute::set('get', 'find', 'main#find_data');
FrameworkRoute::set('patch', 'update/vocab', 'main#update_vocab');
FrameworkRoute::set('patch', 'update/kanji', 'main#update_kanji');

require 'app/config/auth-routes.conf.php';


#FrameworkRoute::set('get', 'page/data/:id{[0-9]+}', 'main#data');
#
FrameworkRoute::set_default('main#index'); // 404 goes here
