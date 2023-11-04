<?php

namespace App\Validation;

use Symfony\Component\Validator\Validation;

class Validator
{
    protected $data;
    protected $db;
    protected $validator;
    protected $errors;

    public function __construct($data)
    {
        $this->data = $data;
        $this->validator = Validation::createValidator();
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
