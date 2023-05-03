<?php

FrameworkRoute::set('get', '/', 'main#index');

require 'app/config/auth-routes.conf.php';


#FrameworkRoute::set('get', 'page/data/:id{[0-9]+}', 'main#data');
#
FrameworkRoute::set_default('main#index'); // 404 goes here
