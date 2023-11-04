<?php

use App\Database;
use App\Model\Category;
use App\Table\Table;
use App\Session;
use App\Table\CategoryTable;
use App\Validation\PostValidator;

require '../vendor/autoload.php';

$db = new Database();

session_start();

try {
    $validator = new PostValidator($_POST);
    $validator->validate();

    $item = new Category();

    $item->setName($_POST['name'])
        ->setDescription($_POST['content'])
        ->setSlug($_POST['name']);

    $itemId = (new CategoryTable($db))->addItem($item);
    $item->setId($itemId);
} catch (\App\Validation\ValidationException $e) {
    Session::flash('errors', $e->errors);
    Session::flash('oldValues', $e->old);
    header('location: ' . $router->url('admin_category_add'));
    exit();
}

header('location: ' . $router->url('admin_category') . '?inserted');
exit();
