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
        $hashTag = new HashTag("test");
        $hashTag2 = new HashTag("#test");

        $this->assertEquals('#test', $hashTag->getHashtag());
        $this->assertEquals('#test', $hashTag2->getHashtag());
    }


    public function testBadHashtagFormat(): void
    {
        $this->expectException(BadHashtagFormatException::class);
        $this->expectExceptionCode(BadHashtagFormatException::ERROR_CODE);
        $this->expectExceptionMessage(BadHashtagFormatException::MESSAGE);
        new HashTag("te st");
    }
}
