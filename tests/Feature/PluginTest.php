<?php

namespace Nadi\CakePHP\Tests\Feature;

use Nadi\CakePHP\NadiPlugin;
use Nadi\CakePHP\Tests\TestCase;

class PluginTest extends TestCase
{
    public function test_plugin_class_exists(): void
    {
        $this->assertTrue(class_exists(NadiPlugin::class));
    }
}
