<?php

namespace SocialNetworksPublisher\Tests\Domain\Model\Post;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Post\Exceptions\EmptyParametersException;
use SocialNetworksPublisher\Domain\Model\Post\HashTag;
use SocialNetworksPublisher\Domain\Model\Post\HashTagArray;
use SocialNetworksPublisher\Domain\Model\Post\HashTagArrayFactory;

class HashTagArrayTest extends TestCase
{
    public function testValidHashtags(): void
    {
        $hashTag1 = new HashTag("#test1");
        $hashTag2 = new HashTag("#test2");
        $hashTag3 = new HashTag("#test3");
        $hashTags = new HashTagArray($hashTag1);
        $hashTags->add($hashTag2);
        $hashTags->add($hashTag3);

        $this->assertEquals("#test1", $hashTags->getHashtags()[0]);
        $this->assertEquals("#test2", $hashTags->getHashtags()[1]);
        $this->assertEquals("#test3", $hashTags->getHashtags()[2]);
    }


    public function testHashTagsToString(): void
    {
        $hashTag1 = new HashTag("#test1");
        $hashTag2 = new HashTag("#test2");
        $hashTag3 = new HashTag("#test3");
        $hashTags = new HashTagArray($hashTag1);

        $hashTags->add($hashTag2);
        $hashTags->add($hashTag3);

        $this->assertEquals("#test1 #test2 #test3 ", $hashTags->__toString());
    }

    public function testNullHashTagArray(): void
    {
        $hashTags = new HashTagArray();
        $this->assertEmpty($hashTags->getHashTags());
    }

    public function testHashTagArrayFactoryBuildFromArray(): void
    {
        $factory = new HashTagArrayFactory();

        $hashTags  = $factory->buildHashTagArrayFromArray([new HashTag("#1"), new HashTag("#2")]);

        $this->assertEquals(new HashTag("#2"), $hashTags->getHashTags()[1]);
    }

    public function testHashTagArrayFactoryBuildFromSentenceWithDelimiter(): void
    {
        $factory = new HashTagArrayFactory();

        $hashTags  = $factory->buildHashTagArrayFromSentence("#1, #2, #3", ", ");

        $this->assertEquals(new HashTag("#2"), $hashTags->getHashTags()[1]);
    }

    public function testEmptyStringException(): void
    {
        $factory = new HashTagArrayFactory();

        $hashTags  = $factory->buildHashTagArrayFromSentence("", ", ");

        $this->assertEmpty($hashTags->getHashTags());
    }
}
