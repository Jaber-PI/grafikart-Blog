<?php

use App\Database;
use App\HTML\Form;
use App\Model\Category;
use App\Session;
use App\Table\CategoryTable;
use App\Table\PostTable;

require '../vendor/autoload.php';

$postId = $params['id'];
$postSlug = $params['slug'];

$db = new Database();

$post = (new PostTable($db))->getItem($postId);

$allCategoriesList = Category::categoryToArray((new CategoryTable($db))->getItems());

if ($postSlug !== $post->getSlug()) {
    http_response_code(301);
    header('location: ' . $router->url('admin_post_edit', ['id' => $postId, 'slug' => $post->getSlug()]));
    exit();
}

session_start();
$errors = Session::get('errors', []);
$oldValues = Session::get('oldValues', null);
Session::unflash();

$data = $oldValues ?? $post;

$form = new Form($data, $errors);

?>
<div class="container pt-5 mt-5 px-5">
    <form action="update" method="POST" onsubmit="return confirm('do you want to continue and add post')">
        <input hidden type="text" name="id" value="<?= $post->getId() ?>">

        <div class="mb-3">
            <?= $form->input('name', 'Post name') ?>
        </div>

        <div class="mb-3">
            <?= $form->textArea('content', 'Post Content', rows: 7) ?>
        </div>
        <div class="mb-3">
            <?= $form->select('categoriesList', 'Categories', $allCategoriesList, multiple: true) ?>
        </div>
        <button type="submit" class="btn btn-primary btn-sm mx-auto">Save</button>
    </form>
</div>