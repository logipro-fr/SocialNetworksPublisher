<?php

namespace SocialNetworksPublisher\Tests\Domain;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\HashTag;
use SocialNetworksPublisher\Domain\Exceptions\BadHashtagFormatException;


class HashtagTest extends TestCase
{
    public function testValidHashtag(): void
    {
        $hashTag = new HashTag("#test");
        $this->assertEquals('#test', $hashTag->getHashtag()[0]);
    }

    public function testValidHashtagArray(): void
    {
        $multipleHashTag = "#test1, #test2, #test3";
        $hashTag = new HashTag($multipleHashTag);

        $this->assertEquals("#test1", $hashTag->getHashtag()[0]);
        $this->assertEquals("#test2", $hashTag->getHashtag()[1]);
        $this->assertEquals("#test3", $hashTag->getHashtag()[2]);
    }

    public function testBadHashtagFormat(): void
    {
        $this->expectException(BadHashtagFormatException::class);
        $this->expectExceptionMessage(BadHashtagFormatException::MESSAGE);
        new HashTag("test, tesi");
    }
}
