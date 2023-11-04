<?php

namespace App\Pagination;

use App\Database;
use App\Helper\URL;
use App\QueryBuilder;

class PagesHandler
{

    private string $table;
    private string $where;
    protected int $perPage;
    protected int $itemsCount;
    private int $currentPage;
    private int $pagesNumber;
    protected $db;
    protected array $params;
    private array $items;

    protected $posts;

    public function __construct(Database $db,  $table, $where = '', int $perPage = 12, $params = [])
    {
        $this->db = $db;
        $this->table = $table;
        $this->where = $where;
        $this->perPage = $perPage;
        $this->params = $params;
        $this->itemsCount = $this->countTotalItems();
        $this->currentPage = URL::getInt('page', 1);
        $this->pagesNumber = ceil($this->itemsCount / $this->perPage) ?: 1;
    }
    public function getItems(string $object, string $sql = '', string $oredrBy = '', string $direction = '')
    {
        $offset = (int) $this->perPage * ($this->currentPage - 1);
        if ($sql === '') {
            $query = (new QueryBuilder())->from($this->table)->limit($this->perPage)->offset($offset)->orderBy($oredrBy, $direction);
            $sql = $query->toSQL();
        } else {
            $sql .= " LIMIT {$this->perPage} OFFSET {$offset}";
        }

        $this->items = $this->db
            ->query($sql)
            ->getObjects($object);

        return $this->items;
    }
    public function pagesControl()
    {
        $link = explode('?', $_SERVER['REQUEST_URI'])[0];
        $get = $_GET;
        $get['page'] = $this->currentPage - 1;
        $previousLink = $link . '?' . http_build_query($get);
        $get['page'] = $this->currentPage + 1;
        $nextLink = $link . '?' . http_build_query($get);
        ob_start();
        echo <<<HTML
        <div class="pages-control d-flex gap-2 px-1 py-4">
            <div class="me-auto">
        HTML;

        if ($this->currentPage > 1) {
            echo <<<HTML
            <a class="btn btn-sm btn-primary" href="{$previousLink}">
                <i class="fa-solid fa-chevron-left"></i> Previous Page
            </a>
            HTML;
        }
        echo <<<HTML
        </div>
        <div class="page-description">
            {$this->currentPage}   /   {$this->pagesNumber}
        </div>
        <div class="ms-auto">
        HTML;

        if ($this->currentPage  < $this->pagesNumber) {
            echo <<<HTML
            <a class="btn btn-sm btn-primary" href="{$nextLink}">
                Next Page <i class="fa-solid fa-chevron-right"></i></a>
            HTML;
        }
        echo <<<HTML
            </div>
        </div>
        HTML;
        $control = ob_get_clean();
        return $control;
    }

    public function setPerPage(int $numberOfItemsPerPage = 12): self
    {
        $this->perPage = $numberOfItemsPerPage;
        return $this;
    }

    public function countTotalItems(): int
    {
        $this->itemsCount = $this->db->count($this->table, $this->where, $this->params) ?? 0;
        return $this->itemsCount;
    }
}
