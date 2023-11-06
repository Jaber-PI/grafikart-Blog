<?php

namespace App\Table;

use App\Model\Post;
use App\Pagination\PagesHandler;
use App\Table\Exception\NotFoundException;

class PostTable extends Table
{
    protected $items = null;

    protected $paginator = null;

    public function getItems(bool $paginated = false, bool $hydrated = false): ?array
    {
        if ($this->items !== null) {
            return $this->items;
        }
        if ($paginated) {
            if ($this->paginator === null) {
                $this->setPaginator();
            }
            $this->items = $this->paginator
                ->getItems(Post::class, '', 'created_at', 'DESC');
        } else {
            $this->items = $this->db->getAll('post', Post::class);
        }
        // hydrate posts with corresponding catgories 
        if ($hydrated) {
            (new PostCategoryTable($this->db))->setPostsCategories($this->items);
        }
        return $this->items;
    }
    public function setPaginator()
    {
        $this->paginator = new PagesHandler($this->db, 'post', '', $this->perPage);
    }
    public function getItem(int $itemId, bool $withcategories = true): ?Post
    {
        $item = $this->db->query('SELECT * FROM post WHERE id = :id', ['id' => $itemId])->getObject(Post::class);
        if (!$item) {
            throw new NotFoundException('post', $itemId);
        }
        if ($withcategories) {
            (new PostCategoryTable($this->db))->setPostsCategories([$item]);
        }
        return $item;
    }
    public function addPost(Post $post): int
    {
        $params = [
            'postName' => $post->getName(false),
            'postSlug' => $post->getSlug(),
            'postContent' => $post->getContent(false),
            'image' => $post->getImage()
        ];
        $insertQuery = "INSERT INTO post SET name= :postName, slug = :postSlug, content = :postContent,image = :image, created_at = now()";
        $lastInsertedId =  $this->db->insert($insertQuery, $params);
        return $lastInsertedId;
    }
    public function updatePost(Post $post)
    {
        $params = [
            'id' => $post->getId(),
            'name' => $post->getName(),
            'content' => $post->getContent(),
            'slug' => $post->getSlug(),
            'image' => $post->getImage()
        ];
        $insertQuery = "UPDATE post SET name= :name, slug = :slug, content = :content, image = :image WHERE id = :id";
        $this->db->query($insertQuery, $params);
    }
}
