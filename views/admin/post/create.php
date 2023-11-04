<?php

use App\Database;
use App\HTML\Form;
use App\Model\Category;
use App\Session;
use App\Table\CategoryTable;

require '../vendor/autoload.php';

session_start();
$errors = Session::get('errors', []);
$oldValues = Session::get('oldValues', null);
Session::flush();

$db = new Database();

$allCategoriesList = Category::categoryToArray(
    (new categoryTable($db))->getItems()
);

$data = $oldValues ?? [];

$form = new Form($data, $errors);

?>

<div class="container pt-5 mt-5 px-5">
    <form action="store" method="POST" onsubmit="return confirm('do you want t continue and add post')">
        <div class="mb-3">
            <?= $form->input('name', 'Post name') ?>
        </div>
        <div class="mb-3">
            <?= $form->textArea('content', 'Post Content', rows: 9) ?>
        </div>
        <div class="mb-3">
            <?= $form->select('categoriesList', 'Categories', $allCategoriesList, multiple: true) ?>
        </div>
        <button type="submit" class="btn btn-primary btn-sm mx-auto">Save</button>
    </form>
</div>