<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Provider\Twitter;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Application\Service\PublishPost\PostFactory;
use SocialNetworksPublisher\Application\Service\PublishPost\PublishPostRequest;
use SocialNetworksPublisher\Infrastructure\Provider\Twitter\Twitter;

class TwitterTest extends TestCase
{
    private const TEXT_CONTENT = "Ceci est un test";
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
    public function testTwitter(): void
    {
        $post = (new PostFactory())->buildPostFromRequest($this->request);
        $this->assertTrue((new Twitter())->postApiRequest($post)->success);
    }
}
