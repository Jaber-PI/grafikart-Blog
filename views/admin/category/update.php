<?php

use App\Database;
use App\Model\Category;
use App\Session;
use App\Table\CategoryTable;
use App\Validation\CategoryValidator;

require '../vendor/autoload.php';

$db = new Database();

$itemId = (int) $_POST['id'];

/** @var Post|Category */
$item = (new CategoryTable($db))->getItem($itemId);

$params = ['id' => $itemId, 'slug' => $item->getSlug()];

session_start();

try {
    $validator = new CategoryValidator($_POST);
    $validator->validate();

    $item->setName($_POST['name']);
    $item->setDescription($_POST['description']);
    $item->setSlug($_POST['name']);

    (new CategoryTable($db))->updateCategory($item);
} catch (\App\Validation\ValidationException $e) {
    Session::flash('errors', $e->errors);
    Session::flash('oldValues', $e->old);

    header('location: ' . $router->url('admin_category_edit', $params));
    exit();
}

header('location: ' . $router->url('admin_category', $params));
exit();
