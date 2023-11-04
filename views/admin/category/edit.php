<?php

use App\Database;
use App\HTML\Form;
use App\Table\Table;
use App\Session;
use App\Table\CategoryTable;

require '../vendor/autoload.php';

$itemId = $params['id'];
$itemSlug = $params['slug'];

$db = new Database();

$item = (new CategoryTable($db))->getItem($itemId);


if ($itemSlug !== $item->getSlug()) {
    http_response_code(301);
    header('location: ' . $router->url('admin_category_edit', ['id' => $itemId, 'slug' => $item->getSlug()]));
    exit();
}

session_start();
$errors = Session::get('errors', []);
$oldValues = Session::get('oldValues', null);
Session::flush();

$data = $oldValues ?? $item;

$form = new Form($data, $errors);

?>
<div class="container pt-5 mt-5 px-5">
    <h1 class="text-center">Edit Category</h1>
    <form action="update" method="POST" onsubmit="return confirm('do you want t continue and add post')">
        <input hidden type="text" name="id" value="<?= $item->getId() ?>">

        <div class="mb-3">
            <?= $form->input('name', 'Category Name') ?>
        </div>

        <div class="mb-3">
            <?= $form->textArea('description', 'Description', rows: 7) ?>
        </div>
        <button type="submit" class="btn btn-primary btn-sm mx-auto">Save</button>
    </form>
</div>