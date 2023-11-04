<?php

use App\Database;
use App\Table\CategoryTable;

include '../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('location: ' . $router->url('admin_categories'));
    exit();
}

$db = new Database();

$itemId = (int) $params['id'];

(new CategoryTable($db))->deleteItem($itemId);


$http_referer = $_SERVER['HTTP_REFERER'];
$urlParts = explode('?', $http_referer);
$url_has_query = count($urlParts) === 2;

if ($url_has_query) {
    $get = 0;
}

$prevLink = $http_referer . ($url_has_query ? '&deleted=done' : '?deleted=done');

header('location: ' . $prevLink);
exit();
