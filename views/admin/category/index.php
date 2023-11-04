<?php

use App\Database;
use App\Table\CategoryTable;

define('ARTICLE_PERPAGE', 12);
$title = 'Posts';

$db  = new Database();
$table = new CategoryTable($db);
$categories =  $table->setPerPage(8)->getItems(true);

?>

</div>

<seciton class="articles" id="articles">
    <div class="container">
        <h2 class="main-title">Manage Categories</h2>
        <!-- message  -->
        <?php if (isset($_GET['deleted'])) : ?>
            <div class="alert alert-success message">
                Item Deleted
            </div>
        <?php endif ?>

        <div class="">
            <?php $itemCreateLink = $router->url('admin_category_add') ?>
            <a class="btn btn-sm btn-info" href="<?= $itemCreateLink ?>"><i class="fa-solid fa-plus"></i> Create New Category</a>

            <div class="table-responsive">
                <table id="manageTable" class="mb-4">
                    <tr>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Control</th>
                    </tr>
                    <?php if (!empty($categories)) : ?>
                        <?php foreach ($categories as $category) : ?>
                            <?php $categoryParams = ['id' => $category->getId(), 'slug' => $category->getSlug()] ?>
                            <tr>
                                <td>
                                    <h4>
                                        <?php $categoryLink = $router->url('category', $categoryParams) ?>
                                        <a href="<?= $categoryLink ?>">
                                            <?= $category->getName() ?>
                                        </a>
                                    </h4>
                                </td>
                                <td>
                                    <p>
                                        <?= htmlentities($category->getDescription()) ?>
                                    </p>
                                </td>
                                <td>
                                    <?php $categoryEditLink = $router->url('admin_category_edit', $categoryParams) ?>
                                    <a href="<?= $categoryEditLink ?>" class="btn btn-sm btn-primary">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        Edit
                                    </a>
                                    <?php $categoryDeleteLink = $router->url('admin_category_del', $categoryParams) ?>
                                    <form action="<?= $categoryDeleteLink ?>" class="d-inline-block" method="POST" onsubmit="return confirm('Are you sure to delete?')">
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fa-solid fa-user-slash"></i>
                                            Delete
                                        </button>
                                    </form>
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