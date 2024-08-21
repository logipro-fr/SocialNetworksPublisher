<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Provider\LinkedIn;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Application\Service\PublishPost\PostFactory;
use SocialNetworksPublisher\Application\Service\PublishPost\PublishPostRequest;
use SocialNetworksPublisher\Infrastructure\Provider\LinkedIn\LinkedIn;
use SocialNetworksPublisher\Application\Service\PublishPost\ProviderResponse;
use SocialNetworksPublisher\Domain\Model\Page\Post;
use SocialNetworksPublisher\Domain\Model\Page\PostStatus;

class LinkedInTest extends TestCase
{
    private const TEXT_CONTENT =
    "Following a prediction made at 10:00, an accident occurred on N02 at 10:35. 
    This underscores the importance of accident prediction in prevention.";
    protected function setUp(): void
    {
    }
    public function testApiPostRequestWithCustomPostId(): void
    {
        $sut = new LinkedIn();
        $response = $sut->postApiRequest(new Post(
            self::TEXT_CONTENT,
            PostStatus::READY
        ));
        $this->assertInstanceOf(ProviderResponse::class, $response);
        $this->assertFalse($response->success);
    }
    public function testApiPostRequest(): void
    {
        $sut = new LinkedIn();
        $response = $sut->postApiRequest($post = new Post(
            self::TEXT_CONTENT,
            PostStatus::READY
        ));
        $this->assertInstanceOf(ProviderResponse::class, $response);
        $this->assertFalse($response->success);
    }
}
