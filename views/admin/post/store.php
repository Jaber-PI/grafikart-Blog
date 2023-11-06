<?php

use App\Attachment\PostAttachment;
use App\Database;
use App\Model\Post;
use App\Session;
use App\Table\CategoryTable;
use App\Table\PostCategoryTable;
use App\Table\PostTable;
use App\Validation\PostValidator;

require '../vendor/autoload.php';

$db = new Database();


try {
    $validator = new PostValidator(array_merge($_POST, $_FILES));
    $validator->validate();
    $categoriesList = $_POST['categoriesList'] ?? [];

    $post = new Post();

    foreach ($categoriesList as $element) {
        $category = (new CategoryTable($db))->getItem($element);
        $post->addCategory($category);
    }

    $image = PostAttachment::UploadImage($_FILES['image']) ?: '';

    $post->setName($_POST['name'])
        ->setContent($_POST['content'])
        ->setSlug($_POST['name'])
        ->setImage($image);

    $postId = (new PostTable($db))->addPost($post);

    $post->setId($postId);

    (new PostCategoryTable($db))->updatePostCatgeories($post);
} catch (\App\Validation\ValidationException $e) {
    Session::flash('errors', $e->errors);
    Session::flash('oldValues', $e->old);
    header('location: ' . $router->url('admin_post_add'));
    exit();
}

header('location: ' . $router->url('admin_post') . '?inserted');
exit();
