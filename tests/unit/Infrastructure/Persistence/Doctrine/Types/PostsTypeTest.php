<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\SqlitePlatform;
use PHPUnit\Framework\TestCase;
use Safe\DateTimeImmutable;
use SocialNetworksPublisher\Domain\Model\Page\Post;
use SocialNetworksPublisher\Domain\Model\Page\PostId;
use SocialNetworksPublisher\Domain\Model\Page\PostStatus;
use SocialNetworksPublisher\Infrastructure\Persistence\Doctrine\Types\PostsType;

use function Safe\json_encode;

class PostsTypeTest extends TestCase
{
    private PostsType $postsType;
    private AbstractPlatform $platform;
    private string $dbValue;
    /** @var ArrayCollection<int, Post> */
    private ArrayCollection $phpValue;
    private DateTimeImmutable $date;
    protected function setUp(): void
    {
        $this->postsType = new PostsType();
        $this->platform = new SqlitePlatform();
        $this->date = new DateTimeImmutable();

        $this->phpValue = new ArrayCollection(
            [
                new Post(
                    "content 1",
                    PostStatus::READY,
                    new PostId("post_1"),
                    $this->date
                ),
            ]
        );
        $this->dbValue = json_encode([
            [
                "id" => (new PostId("post_1"))->__toString(),
                "content" => "content 1",
                "status" => PostStatus::READY,
                "createdAt" => $this->date

            ]
        ]);
    }

    public function testConvertToDatabaseValue(): void
    {

        $actual = $this->postsType->convertToDatabaseValue($this->phpValue, $this->platform);
        $this->assertEquals($this->dbValue, $actual);
    }

    public function testConvertToPHPValue(): void
    {
        /** @var ArrayCollection<int, Post> */
        $actual = $this->postsType->convertToPHPValue($this->dbValue, $this->platform);
        $this->assertEquals("content 1", $actual[0]->getContent());
        $this->assertEquals(PostStatus::READY, $actual[0]->getStatus());
        $this->assertEquals("post_1", $actual[0]->getPostId());
        $this->assertTrue($this->date->diff($actual[0]->getCreatedAt(), true)->s < 2);
    }

    public function testGetName(): void
    {
        $this->assertEquals('posts', $this->postsType->getName());
    }
}
