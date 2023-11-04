<?php

use App\Database;
use App\Table\CategoryTable;
use App\Table\PostCategoryTable;

define('ARTICLE_PERPAGE', 12);

[$categorySlug, $categoryId] = array_values($params);

$db  = new Database();

$params = compact('categoryId', 'categorySlug');

$category = (new CategoryTable($db, $params))->getItem($categoryId);

/** @var PostCategoryTable */
$table = (new PostCategoryTable($db))->setPerPage(4);
$posts = $table->getCategoryPosts($categoryId);

$title = $category->getName();

?>

</div>

<seciton class="articles" id="articles">
    <div class="container">
        <h2 class="main-title"><?= $category->getName() ?></h2>
        <p class="text-muted mb-4">
            <em>Category Description : </em>
            <?= $category->getDescription() ?>
        </p>
        <div class="content">
            <?php if ($posts) : ?>

                <!-- begin card  -->
                <?php foreach ($posts as $post) : ?>
                    <?= $post->card($router) ?>
                <?php endforeach ?>
                <!-- end card  -->
            <?php else : ?>
                <div class="alert alert-info">No available posts</div>
            <?php endif ?>


        </div>
        <!-- begin pages control  -->
        <?= $table->pagesControl() ?>
        <!-- end pages control  -->
    </div>
</seciton>
<div class="spikes"></div>