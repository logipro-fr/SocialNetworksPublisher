<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Provider\Twitter;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Application\Service\PublishPost\PostFactory;
use SocialNetworksPublisher\Application\Service\PublishPost\PublishPostRequest;
use SocialNetworksPublisher\Infrastructure\Provider\Twitter\Twitter;
use SocialNetworksPublisher\Infrastructure\Shared\CurrentWorkDirPath;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

use function Safe\file_get_contents;

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
        var_dump(CurrentWorkDirPath::getPath() . '/tests/unit/ressources/TwitterResponseTweet');

        $twitterResponse = [
            new MockResponse(file_get_contents(CurrentWorkDirPath::getPath() . '/tests/unit/ressources/TwitterResponseTweet.json'), ['http_code' => 200]),
        ];
        $client = new MockHttpClient($twitterResponse, 'https://api.twitter.com/2/tweets');

        $twitter = new Twitter($client);

        $response = $twitter->postApiRequest($post);
        $this->assertTrue($response->success);
    }

}
