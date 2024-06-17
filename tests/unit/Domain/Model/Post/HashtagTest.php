<?php

namespace SocialNetworksPublisher\Tests\Domain\Model\Post;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Post\HashTag;
use SocialNetworksPublisher\Domain\Model\Post\Exceptions\BadHashtagFormatException;
use SocialNetworksPublisher\Domain\Model\Post\HashTagArray;

class HashtagTest extends TestCase
{
    public function testValidHashtag(): void
    {
        $hashTag = new HashTag("t");
        $hashTag2 = new HashTag("#test");

        $this->assertEquals('#t', $hashTag->getHashtag());
        $this->assertEquals('#test', $hashTag2->getHashtag());
    }


    public function testBadHashtagFormat(): void
    {
        $this->expectException(BadHashtagFormatException::class);
        $this->expectExceptionCode(BadHashtagFormatException::ERROR_CODE);
        $this->expectExceptionMessage(BadHashtagFormatException::MESSAGE);
        new HashTag("te st");

        $this->expectException(BadHashtagFormatException::class);
        $this->expectExceptionCode(BadHashtagFormatException::ERROR_CODE);
        $this->expectExceptionMessage(BadHashtagFormatException::MESSAGE);
        new HashTag(" test");

        $this->expectException(BadHashtagFormatException::class);
        $this->expectExceptionCode(BadHashtagFormatException::ERROR_CODE);
        $this->expectExceptionMessage(BadHashtagFormatException::MESSAGE);
        new HashTag("# test");
    }

    public function testValidUTF8Hashtag(): void
    {
        $hashTag = new HashTag("例子");
        $this->assertEquals("#例子", $hashTag->getHashtag());
    }

    public function testInvalidUTF8Hashtag(): void
    {
        $this->expectException(BadHashtagFormatException::class);
        new HashTag("例@子");
    }

    public function testToArray(): void
    {
        $hashTag = new HashTag('dd');
        $this->assertEquals(['#dd'], $hashTag->toArray());
    }

    public function testFromArray(): void
    {
        $array = ["#test"];
        $this->assertEquals('#test', HashTag::fromArray($array));
    }
}
