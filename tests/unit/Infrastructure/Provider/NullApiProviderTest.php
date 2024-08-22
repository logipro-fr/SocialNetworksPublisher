<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Provider;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Application\Service\PublishPost\SocialNetworksApiInterface;
use SocialNetworksPublisher\Domain\Model\Page\Post;
use SocialNetworksPublisher\Domain\Model\Page\PostStatus;
use SocialNetworksPublisher\Infrastructure\Provider\NullApiProvider;

class NullApiProviderTest extends TestCase
{
    public function testCreateNullProvider(): void
    {
        $sut = new NullApiProvider();
        $this->assertInstanceOf(SocialNetworksApiInterface::class, $sut);
    }

    public function testNullPostApiRequest(): void
    {
        $sut = new NullApiProvider();
        $response = $sut->postApiRequest(new Post("test", PostStatus::READY));
        $this->assertFalse($response->success);
    }
}
