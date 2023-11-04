<?php

declare(strict_types=1);

use App\Validation\Unique;
use App\Validation\UniqueValidator;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class UniqueValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): ConstraintValidatorInterface
    {
        return new UniqueValidator();
    }

    public function testNullIsValid(): void
    {
        $this->validator->validate(null, new Unique());

        $this->assertNoViolation();
    }

    /**
     * @dataProvider provideInvalidConstraints
     */
    public function testTrueIsInvalid(Unique $constraint): void
    {
        $this->validator->validate('...', $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ string }}', '...')
            ->assertRaised();
    }

    public function provideInvalidConstraints(): iterable
    {
        yield [new Unique(message: 'myMessage')];
        // ...
    }
}
