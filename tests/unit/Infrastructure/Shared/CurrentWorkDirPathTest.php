<?php

namespace SocialNetworksPublisher\Tests\Infrastructure;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use SocialNetworksPublisher\Infrastructure\Exceptions\NoPWDException;
use SocialNetworksPublisher\Infrastructure\Shared\CurrentWorkDirPath;

class CurrentWorkDirPathTest extends TestCase
{
    public function testGetFullPath(): void
    {
        $currentDir = dirname(__DIR__, 4);
        $path = CurrentWorkDirPath::getPath();
        $this->assertEquals($currentDir, $path);
    }

    public function testNoPWDException(): void
    {

        $this->expectException(NoPWDException::class);
        $this->expectExceptionMessage("Environment variable PWD not found");

        CurrentWorkDirPath::getPath('');
    }
}
