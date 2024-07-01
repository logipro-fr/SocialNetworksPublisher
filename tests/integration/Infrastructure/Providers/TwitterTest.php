<?php

namespace SocialNetworksPublisher\Tests\Integration\Infrastructure\Provider;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Application\Service\PublishPost\PostFactory;
use SocialNetworksPublisher\Application\Service\PublishPost\PublishPostRequest;
use SocialNetworksPublisher\Infrastructure\Provider\Twitter\Twitter;

class TwitterTest extends TestCase
{
    private const TEXT_CONTENT = "Hello world !";
    private PublishPostRequest $request;
    protected function setUp(): void
    {
        $this->request = new PublishPostRequest(
            "Twitter",
            "1a84fvb",
            "5adf78bfdsg",
            self::TEXT_CONTENT,
            "#PEdro",
        );
    }
    public function testTwitterRequest(): void
    {
        $post = (new PostFactory())->buildPostFromRequest($this->request);
        $twitter = new Twitter();

        $response = $twitter->postApiRequest($post);
        $this->assertTrue($response->success);
    }

}
