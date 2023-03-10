<?php

namespace Tests\Unit;

use Tests\TestCase;

class GlobalTest extends TestCase
{
    public function testClassBasename()
    {
        $this->assertEquals('GlobalTest', class_basename($this));
    }

    public function testConfig()
    {
        $this->assertEquals('testing', config('app.env'));
    }
}
