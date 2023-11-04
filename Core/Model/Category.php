<?php

namespace App\Model;

use App\Helper\Text;

class Category
{
    protected $id;
    protected $name;
    protected $description;
    protected $slug;
    protected $post_id;

    public function getId(): int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }
    public function getName(): string
    {
        return htmlentities($this->name);
    }
    public function setName(string $value): self
    {
        $this->name = $value;
        return $this;
    }
    public function getSlug(): string
    {
        return htmlentities($this->slug);
    }
    public function setSlug(string $value): self
    {
        $slug = strtolower(preg_replace(['/\'/', '/\#/'], '', preg_replace('/\s/', '-', $value)));
        $this->slug = $slug;
        return $this;
    }
    public function getPostId(): int
    {
        return (int) $this->post_id;
    }
    public function setPostId($post_id): void
    {
        $this->post_id =  $post_id;
    }
    public function getDescription(): ?string
    {
        if ($this->description === null) {
            return null;
        }
        return htmlentities($this->description);
    }
    public function setDescription(string $value): self
    {
        $this->description = $value;
        return $this;
    }
    public function excerpt(): ?string
    {
        if ($this->description === null) {
            return null;
        }
        return Text::excerpt(htmlentities($this->description));
    }
    public function card($router)
    {
        $imgLink = $this->getId() % 8 + 1;
        $categoryLink = $router->url('category', ['slug' => $this->getSlug(), 'id' => $this->getId()]);
        return <<<HTML
        <div class="card">
            <img src="../includes/images/cat-0{$imgLink}.jpg" alt="test 1">
            <div class="discription">
                <h3>
                    {$this->getName()}
                </h3>
                <p>
                    {$this->excerpt()}
                </p>
            </div>
            <div class="info mt-auto">
                <a href="{$categoryLink}">
                    Read More
                </a>
                <i class="fas fa-long-arrow-alt-right"></i>
            </div>
        </div>
        HTML;
    }
    public function toArray()
    {
        $list = [];
        $list['id'] = $this->getId();
        $list['name'] = $this->getName();
        $list['description'] = $this->getDescription();

        return $list;
    }
    public static function categoryToArray(Category|array $categories): array
    {
        if (!is_array($categories)) {
            return $categories->toArray();
        }
        return array_map(
            function ($cat) {
                return $cat->toArray();
            },
            $categories
        );
    }
}
