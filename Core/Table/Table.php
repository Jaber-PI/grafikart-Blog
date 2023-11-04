<?php

namespace App\Table;

use App\Database;

class Table
{

    /** @var Database */
    protected $db;

    protected $paginator = null;

    /** Number Of Items to Show per Page default :12 */
    protected $perPage = 12;

    protected $params;

    public function __construct(Database $db, array $params = [])
    {
        $this->db = $db;
        $this->params = $params;
    }


    public function getItems()
    {
    }
    public function deleteItem(int $itemId)
    {
    }

    public function setPerPage(int $numberOfItemsPerPage = 12): self
    {
        $this->perPage = $numberOfItemsPerPage;
        return $this;
    }

    public function pagesControl()
    {
        if ($this->paginator) {
            return $this->paginator->pagesControl();
        }
    }
}
