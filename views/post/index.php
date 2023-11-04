<?php

use App\Database;
use App\Table\PostTable;

define('ARTICLE_PERPAGE', 12);
$title = 'My Posts';

$db  = new Database();
$table = new PostTable($db);
$posts =  $table->getItems(true, true);

?>

</div>

<seciton class="articles" id="articles">
    <div class="container">
        <h2 class="main-title">Articles</h2>
        <div class="content">
            <?php foreach ($posts as $post) : ?>
                <?= $post->card($router) ?>
            <?php endforeach ?>
        </div>
        <!-- begin pages control  -->
        <?= $table->pagesControl() ?>
        <!-- end pages control  -->
    </div>
</seciton>
<div class="spikes"></div>