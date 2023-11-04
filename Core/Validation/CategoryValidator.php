<?php

namespace App\Validation;

use App\Validation\ValidationException;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CategoryValidator extends Validator
{
    public function validate(): bool
    {
        $this->validateName();
        $this->validateDescription();
        if (!empty($this->errors)) {
            ValidationException::throw($this->errors, $this->data);
        }
        return true;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function validateName(string $key = 'name')
    {
        $id = $this->data['id'] ?? 0;
        $violations = $this->validator->validate($this->data[$key], [
            new Length(['min' => 10]),
            new NotBlank(),
            new Unique('category', $key, $id)
        ]);

        if (0 !== count($violations)) {
            // there are errors, now you can show them
            foreach ($violations as $violation) {
                $this->errors[$key][] = $violation->getMessage();
            }
        }
    }

    public function validateDescription(string $key = 'description')
    {
        $violations = $this->validator->validate($this->data[$key], [
            new Length(['min' => 40, 'max' => 200]),
        ]);

        if (0 !== count($violations)) {
            // there are errors, now you can show them
            foreach ($violations as $violation) {
                $this->errors[$key][] = $violation->getMessage();
            }
        }
    }
}
