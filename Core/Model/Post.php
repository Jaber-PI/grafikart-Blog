<?php

namespace App\Model;


use App\Helper\Text;
use DateTime;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class Post
{
    protected $id;

    protected string $name;

    protected string $content;

    protected  $image;
    protected  $oldImage;

    protected $created_at;
    protected $slug;
    /** @var Category[] */
    private $categories = [];

    public function getId(): int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }
    public function getCategories(bool $asList = false): array
    {
        if ($asList) {
            return Category::categoryToArray($this->categories);
        }
        return $this->categories;
    }
    public function getCategoriesList(): array
    {
        return $this->getCategories(true);
    }
    public function setCategories(array $categories = []): self
    {
        $this->categories = $categories;
        return $this;
    }

    public function addCategory(Category $category): void
    {
        $this->categories[] = $category;
    }
    public function getName(bool $escaped = true): string
    {
        return $escaped ? htmlentities($this->name) : $this->name;
    }
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }
    public function getSlug(): string
    {
        return htmlentities($this->slug);
    }
    public function setSlug(string $name): self
    {
        $slug = strtolower(preg_replace('/\'/', '', preg_replace('/\s/', '-', $name)));
        $this->slug = $slug;
        return $this;
    }
    public function getImage(): string
    {
        return $this->image ?? 'cat-01.jpg';
    }
    public function setImage(string $path): self
    {
        if ($path === '') {
            return $this;
        }
        $this->image = $path;
        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return new DateTime($this->created_at);
    }
    public function excerpt(): ?string
    {
        if ($this->content === null) {
            return null;
        }
        return Text::excerpt(htmlentities($this->content));
    }
    public function getContent(bool $escaped = true): ?string
    {
        if ($this->content === null) {
            return null;
        }
        return $escaped ? htmlentities($this->content) : $this->content;
    }
    public function card($router)
    {
        $postLink = $router->url('post', ['slug' => $this->getSlug(), 'id' => $this->getId()]);
        ob_start();
        echo <<<HTML
        <div class="card">
            <img src="upload/post/{$this->getImage()}" alt="test 1">
            <div class="discription">
                <h3>
                    {$this->getName()}
                </h3>
                <p>
                    {$this->excerpt()}
                </p>
            </div>
            <div class="categories p-2">
        HTML;
        foreach ($this->categories as $category) {
            $catLink = $router->url('category', ['slug' => $category->getSlug(), 'id' => $category->getId()]);
            echo <<<HTML
                <a href="{$catLink}" class="btn btn-outline-info btn-sm m-1">
                    {$category->getName()}
                </a>
                HTML;
        }
        echo <<<HTML
            </div>
            <div class="info mt-auto">
                <a href="{$postLink}">
                    Read More {$this->getId()}
                </a>
                <i class="fas fa-long-arrow-alt-right"></i>
            </div>
        </div>
        HTML;
        $content = ob_get_clean();
        return $content;
    }
    public function render($router)
    {
        ob_start();
        echo <<<HTML
            <div class="post">
                <div class="discription">
                    <h3>
                        {$this->getName()}
                    </h3>
                    <div class="categories">
            HTML;
        foreach ($this->getCategories() as $category) {
            $link = $router->url('category', ['slug' => $category->getSlug(), 'id' => $category->getId()]);
            echo <<<HTML
                    <a href="{$link}" class="btn btn-primary btn-sm">
                        {$category->getName()}
                    </a>
                    HTML;
        }
        $content = nl2br($this->getContent());
        echo <<<HTML
                </div>
                <div class="text-muted mb-2">
                    {$this->getCreatedAt()->format('d F Y')}
                </div>
                <!-- <img src="includes/images/cat-0 -->
                <!--  this->getId() % 8 + 1  -->
                <!-- .jpg" alt="test 1"> -->
                <p>
                    {$content}
                </p>
            </div>
            <div class="info mt-auto">
                Leave a comment
                <i class="fas fa-long-arrow-alt-right"></i>
            </div>
        </div>
        HTML;
        $content = ob_get_clean();
        return $content;
    }
}
