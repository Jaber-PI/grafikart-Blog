<?php

declare(strict_types=1);

use App\Container;
use App\Database;
use PHPUnit\Framework\TestCase;

final class ContainerTest extends TestCase
{
    public function testbindString()
    {
        $container = new Container();
        $container->bind('Database', function () {
            return new Database('sqlite::memory:', null, null);
        });

        $db = $container->resolve('Database');

        $this->assertInstanceOf(Database::class, $db);
    }
    public function testbindwithClassName()
    {
        $container = new Container();
        $container->bind(Database::class, function () {
            return new Database('sqlite::memory:', null, null);
        });

        $db = $container->resolve(Database::class);

        $this->assertInstanceOf(database::class, $db);
    }
}
