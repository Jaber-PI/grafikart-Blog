<?php

namespace App\Table;

use App\Model\Category;
use App\Pagination\PagesHandler;
use App\Table\Exception\NotFoundException;

class CategoryTable extends Table
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
                ->getItems(Category::class, '', 'id', 'DESC');
        } else {
            $this->items = $this->db->getAll('category', Category::class);
        }
        // hydrate posts with corresponding catgories 
        if ($hydrated) {
            (new PostCategoryTable($this->db))->setPostsCategories($this->items);
        }
        return $this->items;
    }

    public function addItem(Category $item): int
    {
        $params = [
            'name' => $item->getName(false),
            'slug' => $item->getSlug(),
            'description' => $item->getDescription(false)
        ];
        $insertQuery = "INSERT INTO category SET name= :name, slug = :slug, description =:description";
        $lastInsertedId =  $this->db->insert($insertQuery, $params);
        return $lastInsertedId;
    }

    public function deleteItem(int $itemId)
    {
        $this->db->delete('category', 'id = ?', [$itemId]);
    }
    // set paginator 
    public function setPaginator()
    {
        $this->paginator = new PagesHandler($this->db, 'category', '', $this->perPage);
    }
    public function updateCategory(Category $category)
    {
        $params = [
            'id' => $category->getId(),
            'name' => $category->getName(),
            'description' => $category->getDescription(),
            'slug' => $category->getSlug()
        ];
        $updateQuery = "UPDATE category SET name= :name, slug = :slug, description = :description WHERE id = :id";
        $this->db->query($updateQuery, $params);
    }

    public function getItem(int $categoryId): ?Category
    {
        $category = $this->db->query('SELECT * FROM category WHERE id = :id', ['id' => $categoryId])
            ->getObject(Category::class);
        if (!$category) {
            throw new NotFoundException('Category', $categoryId);
        }
        return $category;
    }

    public function getCategoryPosts(int $categoryId)
    {
    }
}
