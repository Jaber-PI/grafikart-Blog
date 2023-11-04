<?php

use App\Database;
use App\Table\PostTable;


define('ARTICLE_PERPAGE', 12);
$title = 'Posts';

$get = $_GET;
if (isset($get['deleted'])) {
    unset($_GET['deleted']);
}

$db  = new Database();

$table = new PostTable($db);
$posts =  $table->setPerPage(5)
    ->getItems(true);

?>

<seciton class="articles" id="articles">
    <div class="container">
        <h2 class="main-title">Manage Posts</h2>

        <!-- message  -->
        <?php if (isset($get['deleted'])) : ?>
            <div class="alert alert-success message">
                Item Deleted
            </div>
        <?php endif ?>
        <?php if (isset($get['inserted'])) : ?>
            <div class="alert alert-success message">
                Item Added
            </div>
        <?php endif ?>
        <?php if (isset($get['updated'])) : ?>
            <div class="alert alert-sm alert-success message">
                Item Updated
            </div>
        <?php endif ?>

        <div class="">
            <?php $postCreateLink = $router->url('admin_post_add') ?>
            <a class="btn btn-sm btn-info" href="<?= $postCreateLink ?>"><i class="fa-solid fa-plus"></i> Create New Post</a>

            <div class="table-responsive">
                <table id="manageTable" class="mb-4">
                    <tr>
                        <th>#id</th>
                        <th>Post</th>
                        <th>Adding Date</th>
                        <th>Control</th>
                    </tr>
                    <?php if (!empty($posts)) : ?>
                        <?php foreach ($posts as $post) : ?>
                            <?php $postParams = ['id' => $post->getId(), 'slug' => $post->getSlug()] ?>
                            <tr>
                                <td>
                                    <?= htmlentities($post->getId()) ?>
                                </td>
                                <td>
                                    <h4>
                                        <?php $postLink = $router->url('post', $postParams) ?>
                                        <a href="<?= $postLink ?>">
                                            <?= $post->getName() ?>
                                        </a>
                                    </h4>
                                    <p>
                                        <?= htmlentities($post->excerpt()) ?>
                                    </p>
                                </td>
                                <td><?= htmlentities($post->getCreatedAt()->format('d F Y')) ?></td>
                                <td>
                                    <?php $postEditLink = $router->url('admin_post_edit', $postParams) ?>
                                    <a href="<?= $postEditLink ?>" class="btn btn-sm btn-primary">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        Edit
                                    </a>
                                    <?php $postDeleteLink = $router->url('admin_post_del', $postParams) ?>
                                    <form action="<?= $postDeleteLink ?>" class="d-inline-block" method="POST" onsubmit="return confirm('Are you sure to delete?')">
                                        <input type="text" hidden name="id" value="<?= $post->getId() ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fa-solid fa-user-slash"></i>
                                            Delete
                                        </button>
                                    </form>
                                    <!-- <?php if (1 != 1) : ?>
                                        <a href="items.php?do=Approve&id=<?= $post->getId() ?>" class="btn btn-sm btn-success" onclick="return confirm('Are you sure to Approve this Item?')"><i class="fa-solid fa-check"></i> Approve </a>
                                    <?php endif; ?> -->
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6">No AVAILABLE DATA</td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>

        <!-- begin pages control  -->
        <?= $table->pagesControl() ?>
        <!-- end pages control  -->
    </div>
</seciton>
<div class="spikes"></div>