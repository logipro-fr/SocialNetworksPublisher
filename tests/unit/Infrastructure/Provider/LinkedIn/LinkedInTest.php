<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Provider\LinkedIn;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Application\Service\PublishPost\PostFactory;
use SocialNetworksPublisher\Application\Service\PublishPost\PublishPostRequest;
use SocialNetworksPublisher\Domain\Model\Post\Post;
use SocialNetworksPublisher\Infrastructure\Provider\Facebook\Facebook;
use SocialNetworksPublisher\Infrastructure\Provider\LinkedIn\LinkedIn;
use SocialNetworksPublisher\Application\Service\PublishPost\ProviderResponse;

class LinkedInTest extends TestCase
{
    private const TEXT_CONTENT =
    "Following a prediction made at 10:00, an accident occurred on N02 at 10:35. 
    This underscores the importance of accident prediction in prevention.";
    private PublishPostRequest $request;
    protected function setUp(): void
    {
        $this->request = new PublishPostRequest(
            "LinkedIn",
            "1a84fvb",
            "5adf78bfdsg",
            self::TEXT_CONTENT,
            "#PEdro",
        );
    }
    public function testApiPostRequestWithCustomPostId(): void
    {
        $sut = new LinkedIn();
        $response = $sut->postApiRequest((new PostFactory())->buildPostFromRequest($this->request, "test"));
        $this->assertInstanceOf(ProviderResponse::class, $response);
        $this->assertFalse( $response->success);
    }
    public function testApiPostRequest(): void
    {
        $sut = new LinkedIn();
        $response = $sut->postApiRequest((new PostFactory())->buildPostFromRequest($this->request));
        $this->assertInstanceOf(ProviderResponse::class, $response);
        $this->assertFalse( $response->success);
    }
}
