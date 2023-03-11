<?php

namespace Tests\Unit;

use Tests\TestCase;

class PathsTest extends TestCase
{
    public function testEnv()
    {
        $this->assertEquals('testing', env('APP_ENV'));
    }

    public function testBasePath()
    {
        $envExample = file_get_contents(base_path('.env.example'));

        $this->assertStringContainsString('APP_ENV=local', $envExample);
    }

    public function testConfigPath()
    {
        $this->assertFileExists(config_path('app.php'));
    }

    public function testResourcePath()
    {
        $this->assertFileExists(resource_path('views/index.html'));
    }

    public function testDatabasePath()
    {
        $this->assertFileExists(database_path('db_tables.php'));
    }

    public function testPublicPath()
    {
        $this->assertFileExists(public_path('index.php'));
    }

    public function testRoutesPath()
    {
        $this->assertFileExists(routes_path('web.php'));
    }

    public function testStoragePath()
    {
        $this->assertFileExists(storage_path('logs/slim.log'));
    }
}
