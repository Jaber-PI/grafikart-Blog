<?php

namespace App\Table;

use App\Model\Category;
use App\Model\Post;
use App\Pagination\PagesHandler;

class PostCategoryTable extends Table
{
    protected $posts = null;
    protected $items;

    /**
     * set categories property to every Post Object
     * @param Post[] $posts
     * @return Post[]
     */
    public function setPostsCategories($posts): void
    {
        $postsByIds = [];
        foreach ($posts as $post) {
            $postsByIds[$post->getId()] = $post;
            $post->setCategories([]);
        }

        $query = "SELECT c.*, pc.post_id FROM post_category pc LEFT JOIN category c ON pc.category_id = c.id LEFT JOIN post p ON pc.post_id = p.id WHERE p.id IN (" . implode(',', array_keys($postsByIds)) . ")";
        $postCategories = $this->db->query($query)->getObjects(Category::class);

        foreach ($postCategories as $category) {
            $postsByIds[$category->getPostId()]->addCategory($category);
        }
    }

    public function updatePostCatgeories(Post $post)
    {
        $postId = $post->getId();
        $categories = $post->getCategoriesList();
        $this->db->connection->beginTransaction();

        $this->db->query("DELETE FROM post_category WHERE post_id = {$postId}");
        foreach ($categories as $cat) {
            $insertQuery = "INSERT INTO post_category SET post_id = {$postId},  category_id = ?";
            $this->db->query($insertQuery, [$cat['id']]);
        }
        $this->db->connection->commit();
    }
    // set paginator 
    public function setPaginator(string $table, string $where = '', $params = [])
    {
        $this->paginator = new PagesHandler($this->db, $table, $where, $this->perPage, $params);
    }

    public function getCategoryPosts(int $categoryId, bool $paginated = true)
    {
        if ($this->items !== null) {
            return $this->items;
        }
        if ($paginated) {
            $this->setPaginator("post_category", "category_id = {$categoryId}");

            $sql = "SELECT p.* FROM post_category pc LEFT JOIN post p ON pc.post_id = p.id WHERE category_id = {$categoryId}";

            $this->items = $this->paginator->getItems(Post::class, $sql);
            // 
        } else {
            $this->items = $this->db->getAll('category', Category::class);
        }
        // // hydrate posts with corresponding catgories 
        // if ($hydrated) {
        //     (new PostCategoryTable($this->db))->setPostsCategories($this->items);
        // }
        return $this->items;
    }
}
