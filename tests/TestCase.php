<?php declare(strict_types=1);

namespace Nalognl\MegaplanModule\Tests;

use Nalognl\MegaplanModule\Config;
use PHPUnit\Framework\TestCase as PHPUnit;
use ReflectionClass;

class TestCase extends PHPUnit
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    protected function execMethod(string $class, string $method, object $object, $method_args = [])
    {
        $reflection = new ReflectionClass($class);
        $new_method = $reflection->getMethod($method);
        $new_method->setAccessible(true);
        return $new_method->invoke($object, ...$method_args);
    }
}