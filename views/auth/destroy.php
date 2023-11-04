<?php

use App\Authenticator;

Authenticator::logout();

header('location: ' . $router->url('home'));
