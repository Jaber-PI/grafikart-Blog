<?php

namespace App\Validation;

use Symfony\Component\Validator\Constraint;

class Unique extends Constraint
{
    public string $message = 'This  "{{ string }}" already exists';
    public string $mode = 'strict';
    public string $table;
    public string $column;
    public int $id;

    // all configurable options must be passed to the constructor
    public function __construct(string $table, string $column, int $id, string $mode = null, string $message = null, array $groups = null, $payload = null)
    {
        parent::__construct([], $groups, $payload);
        $this->table = $table;
        $this->column = $column;
        $this->id = $id;
        $this->mode = $mode ?? $this->mode;
        $this->message = $message ?? $this->message;
    }
}
