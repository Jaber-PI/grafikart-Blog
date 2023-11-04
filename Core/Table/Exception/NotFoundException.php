<?php

namespace App\Table\Exception;

use Exception;

class NotFoundException extends Exception
{
    public function __construct(string $itemType, int $id)
    {
        $this->message = "No {$itemType} is found for this id: {$id}";
    }
}
