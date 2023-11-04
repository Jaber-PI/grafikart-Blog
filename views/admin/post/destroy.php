<?php

use App\Database;

include '../vendor/autoload.php';

if (($_SERVER['REQUEST_METHOD'] !== 'POST') || empty($_POST)) {
    header('location: ' . $router->url('admin_posts'));
    exit();
}

$db = new Database();

$postId = (int) $_POST['id'];

$db->delete('post', 'id = ?', [$postId]);

$http_referer = $_SERVER['HTTP_REFERER'];
$urlParts = explode('?', $http_referer);
$url_has_query = count($urlParts) === 2;

if ($url_has_query) {
    $get = 0;
}

$prevLink = $http_referer . ($url_has_query ? '&deleted=done' : '?deleted=done');

header('location: ' . $prevLink);
exit();
