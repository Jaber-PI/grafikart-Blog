<?php


namespace App\Validation;

use App\Validation\ValidationException;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PostValidator extends Validator
{
    public function validate(): bool
    {
        $this->validateName();
        $this->validateContent();
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
            new Unique('post', $key, $id)
        ]);

        if (0 !== count($violations)) {
            // there are errors, now you can show them
            foreach ($violations as $violation) {
                $this->errors[$key][] = $violation->getMessage();
            }
        }
    }

    public function validateContent(string $key = 'content')
    {
        $violations = $this->validator->validate($this->data[$key], [
            new Length(['min' => 100]),
        ]);

        if (0 !== count($violations)) {
            // there are errors, now you can show them
            foreach ($violations as $violation) {
                $this->errors[$key][] = $violation->getMessage();
            }
        }
    }
    public function validateCategories(array $categories)
    {
        $key = 'categoriesList';
        dd($categories, $this->data[$key]);
        $this->data[$key];

        $this->errors[$key][] = "Choice is not valide";
    }
}
