<?php

namespace SocialNetworksPublisher\Tests\Domain;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Content;
use SocialNetworksPublisher\Domain\Exceptions\EmptyContentException;

class ContentTest extends TestCase
{
    private const TEXT =
     "Lorem ipsum dolor sit amet, consectetur adipiscing elit.";
    public function testValidTextContent(): void
    {
        $content = new Content(self::TEXT);
        $this->assertInstanceOf(Content::class, $content);
        $this->assertEquals(self::TEXT, $content);
    }

    public function testBadTextContentException(): void
    {
        $this->expectException(EmptyContentException::class);
        $this->expectExceptionMessage(EmptyContentException::MESSAGE);
        new Content("");
    }
}
