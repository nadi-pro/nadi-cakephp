<?php

namespace Nadi\CakePHP\Tests\Feature;

use Nadi\CakePHP\Handler\Base;
use Nadi\CakePHP\Handler\HandleExceptionEvent;
use Nadi\CakePHP\Handler\HandleHttpRequestEvent;
use Nadi\CakePHP\Handler\HandleQueryEvent;
use Nadi\CakePHP\Tests\TestCase;

class HandlerTest extends TestCase
{
    public function test_handler_classes_exist(): void
    {
        $this->assertTrue(class_exists(Base::class));
        $this->assertTrue(class_exists(HandleExceptionEvent::class));
        $this->assertTrue(class_exists(HandleHttpRequestEvent::class));
        $this->assertTrue(class_exists(HandleQueryEvent::class));
    }

    public function test_base_handler_hash(): void
    {
        // We can't call store without the singleton, but hash is standalone
        $base = new class extends Base {
            public function testHash(string $value): string
            {
                return $this->hash($value);
            }
        };

        $this->assertEquals(sha1('test'), $base->testHash('test'));
    }
}
