<?php

use App\Database;
use App\Table\PostTable;

[$postSlug, $postId] = [$params['slug'], (int)$params['id']];

$db  = new Database();

$post = (new PostTable($db))->getItem($postId);

if ($post->getSlug() !== $postSlug) {
    $url = $router->url('post', ['id' => $postId, 'slug' => $post->getSlug()]);
    http_response_code(301);
    header('location: ' . $url);
    exit();
}

$title = $post->getName();
?>

</div>

<seciton class="articles" id="articles">
    <div class="container">
        <?= $post->render($router) ?>
    </div>
</seciton>
<div class="spikes"></div>