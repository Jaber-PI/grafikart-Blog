<?php

use App\Attachment\PostAttachment;
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

try {
    $validator = new PostValidator(array_merge($_POST, $_FILES));
    $validator->validate();

    $categoriesList = $_POST['categoriesList'];
    $post->setCategories([]);

    foreach ($categoriesList as $element) {
        $category = (new CategoryTable($db))->getItem($element);
        $post->addCategory($category);
    }

    if ($_FILES['image']['size'] !== 0) {
        $oldImage = $post->getImage();
        $image = PostAttachment::UploadImage($_FILES['image']) ?: '';
        PostAttachment::deleteImage($oldImage);
        $post->setImage($image);
    }

    $post->setName($_POST['name'])
        ->setContent($_POST['content'])
        ->setSlug($_POST['name']);

    (new PostTable($db))->updatePost($post);
    (new PostCategoryTable($db))->updatePostCatgeories($post);
} catch (\App\Validation\ValidationException $e) {
    Session::flash('errors', $e->errors);
    Session::flash('oldValues', $e->old);

    header('location: ' . $router->url('admin_post_edit', $params));
    exit();
}

header('location: ' . $router->url('admin_post') . '?updated');
exit();
