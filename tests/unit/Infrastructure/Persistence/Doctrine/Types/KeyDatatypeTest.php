<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\SqlitePlatform;
use PHPUnit\Framework\TestCase;
use Safe\DateTimeImmutable;
use SocialNetworksPublisher\Domain\Model\Key\TwitterKeyData;
use SocialNetworksPublisher\Domain\Model\Page\Post;
use SocialNetworksPublisher\Domain\Model\Page\PostId;
use SocialNetworksPublisher\Domain\Model\Page\PostStatus;
use SocialNetworksPublisher\Infrastructure\Persistence\Doctrine\Types\KeyDataType;
use SocialNetworksPublisher\Infrastructure\Persistence\Doctrine\Types\PostsType;

use function Safe\json_encode;

class KeyDataTypeTest extends TestCase
{
    public function testConvertToDatabaseValue(): void
    {
        $keyData = new TwitterKeyData(
            "a",
            "b"
        );

        $expectedOuptut = json_encode([
            "socialNetwork" => "Twitter",
            "bearerToken" => "a",
            "refreshToken" => "b"
        ]);
        $type = new KeyDataType();
        $this->assertEquals($expectedOuptut, $type->convertToDatabaseValue($keyData, new SqlitePlatform()));
    }

    public function testConvertToPHPValue(): void
    {
        $input = json_encode([
            "socialNetwork" => "Twitter",
            "bearerToken" => "a",
            "refreshToken" => "b"
        ]);

        $expectedOuptut = new TwitterKeyData(
            "a",
            "b"
        );

        $type = new KeyDataType();

        $this->assertEquals($expectedOuptut, $type->convertToPHPValue($input, new SqlitePlatform()));
    }

    public function testGetName(): void
    {
        $this->assertEquals('key_data', (new KeyDataType())->getName());
    }
}
