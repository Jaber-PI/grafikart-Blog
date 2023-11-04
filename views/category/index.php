<?php

use App\Database;
use App\Table\CategoryTable;

$title = 'My Categories';

$db  = new Database();
$table = new CategoryTable($db);
$items =  $table->getItems(true);

?>

</div>

<seciton class="articles" id="articles">
    <div class="container">
        <h2 class="main-title">Categories</h2>
        <div class="content">
            <?php foreach ($items as $item) : ?>
                <?= $item->card($router) ?>
            <?php endforeach ?>
        </div>
        <!-- begin pages control  -->
        <?= $table->pagesControl() ?>
        <!-- end pages control  -->
    </div>
</seciton>
<div class="spikes"></div>