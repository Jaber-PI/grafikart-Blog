<?php

use App\Authenticator;

if (!Authenticator::isLogged()) {
    header('location: ' . $router->url('login'));
    exit();
}

header('location: ' . $router->url('admin_post'));
exit();
