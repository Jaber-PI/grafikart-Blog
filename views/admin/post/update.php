<?php

use App\Database;
use App\Session;
use App\Table\CategoryTable;
use App\Table\PostCategoryTable;
use App\Table\PostTable;
use App\Validation\PostValidator;

require '../vendor/autoload.php';

$db = new Database();

$postId = $_POST['id'];
$categoriesList = $_POST['categoriesList'];

$post = (new PostTable($db))->getItem($postId);
$params = ['id' => $postId, 'slug' => $post->getSlug()];

session_start();

try {
    $validator = new PostValidator($_POST);
    $validator->validate();

    $categoriesList = $_POST['categoriesList'];
    $post->setCategories([]);

    foreach ($categoriesList as $element) {
        $category = (new CategoryTable($db))->getItem($element);
        $post->addCategory($category);
    }

    $post->setName($_POST['name']);
    $post->setContent($_POST['content']);
    $post->setSlug($_POST['name']);

    (new PostTable($db))->updatePost($post);
    (new PostCategoryTable($db))->updatePostCatgeories($post);
} catch (\App\Validation\ValidationException $e) {
    Session::flash('errors', $e->errors);
    Session::flash('oldValues', $e->old);

    header('location: ' . $router->url('admin_post_edit', $params));
    exit();
}

header('location: ' . $router->url('admin_posts') . '?updated');
exit();
