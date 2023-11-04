<?php

declare(strict_types=1);

use App\Model\Post;
use App\Validation\PostValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Exception\ValidatorException;

class PostValidatorTest extends TestCase
{
    protected function createValidator($post): PostValidator
    {
        return new PostValidator($post);
    }

    public function testNullIsValid(): void
    {
        $post = new Post();
        $post->setName('jaber');

        $this->expectException(ValidatorException::class);

        $validator = $this->createValidator($post);
        $validator->validate($post);
    }
}
