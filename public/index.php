<?php

use App\Router;

define('VIEW_PATH', dirname(__DIR__) . '/views/');
define('UPLOADS_PATH', './upload');

include '../vendor/autoload.php';

define('DEBUG_TIME', microtime(true));

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

if (isset($_GET['page']) && $_GET['page'] === '1') {
    $url = explode('?', $_SERVER["REQUEST_URI"])[0];
    $queries = $_GET;
    unset($queries['page']);
    http_response_code(301);
    $url =  $url . '?' . http_build_query($queries);
    header('location: ' . $url);
    exit();
}



$router = new Router(VIEW_PATH);
$router
    ->get('/', 'post/index', 'home')
    // auth 
    ->match('/login', 'auth/login', 'login')
    ->post('/logout', 'auth/destroy', 'logout')
    // blog 
    ->get('/blog', 'post/index', 'posts')
    ->get('/blog/post', 'post/index', 'posts_')
    ->get('/blog/category/[*:slug]-[i:id]', 'category/show', 'category')
    ->get('/blog/[*:slug]-[i:id]', 'post/show', 'post')
    ->get('/blog/category', 'category/index', 'categories')
    // admin 
    ->get('/admin', 'admin/index', 'admin')
    // admin post 
    ->get('/admin/post', 'admin/post/index', 'admin_post')
    ->get('/admin/post/[*:slug]-[i:id]', 'admin/post/edit', 'admin_post_edit')
    ->post('/admin/post/update', 'admin/post/update', 'admin_post_update')
    ->post('/admin/post/[*:slug]-[i:id]/delete', 'admin/post/destroy', 'admin_post_del')
    ->get('/admin/post/add', 'admin/post/create', 'admin_post_add')
    ->post('/admin/post/store', 'admin/post/store', 'admin_post_store')
    // admin category 
    ->get('/admin/category', 'admin/category/index', 'admin_category')
    ->get('/admin/category/[*:slug]-[i:id]', 'admin/category/edit', 'admin_category_edit')
    ->post('/admin/category/update', 'admin/category/update', 'admin_category_update')
    ->post('/admin/category/[*:slug]-[i:id]/delete', 'admin/category/destroy', 'admin_category_del')
    ->get('/admin/category/add', 'admin/category/create', 'admin_category_add')
    ->post('/admin/category/store', 'admin/category/store', 'admin_category_store');


$router->run();
