<?php

namespace Nadi\CakePHP\Tests\Feature;

use Nadi\CakePHP\Tests\TestCase;
use Nadi\CakePHP\Transporter;

class TransporterTest extends TestCase
{
    public function test_transporter_class_exists(): void
    {
        $this->assertTrue(class_exists(Transporter::class));
    }
}
