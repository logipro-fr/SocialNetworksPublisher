<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Provider;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Application\Service\PublishPost\SocialNetworksApiInterface;
use SocialNetworksPublisher\Domain\Model\Shared\SocialNetworks;
use SocialNetworksPublisher\Infrastructure\Provider\Facebook\Facebook;
use SocialNetworksPublisher\Infrastructure\Provider\FactorySocialNetworksApi;
use SocialNetworksPublisher\Infrastructure\Provider\LinkedIn\LinkedIn;
use SocialNetworksPublisher\Infrastructure\Provider\SimpleBlog\SimpleBlog;
use SocialNetworksPublisher\Infrastructure\Provider\Twitter\TwitterClient;
use SocialNetworksPublisher\Infrastructure\Shared\CurrentWorkDirPath;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

use function Safe\file_get_contents;

class FactorySocialNetworksApiTest extends TestCase
{
    public function testAbstractFactorySimpleBlog(): void
    {
        $socialNetworks = SocialNetworks::SimpleBlog;
        $sut = (new FactorySocialNetworksApi())->buildApi($socialNetworks);
        $this->assertInstanceOf(SocialNetworksApiInterface::class, $sut);
        $this->assertInstanceOf(SimpleBlog::class, $sut);
    }
    public function testAbstractFactoryFacebook(): void
    {
        $socialNetworks = SocialNetworks::Facebook;
        $sut = (new FactorySocialNetworksApi())->buildApi($socialNetworks);
        $this->assertInstanceOf(Facebook::class, $sut);
    }

    public function testAbstractFactoryLinkedIn(): void
    {
        $socialNetworks = SocialNetworks::LinkedIn;
        $sut = (new FactorySocialNetworksApi())->buildApi($socialNetworks);
        $this->assertInstanceOf(LinkedIn::class, $sut);
    }

    public function testAbstractFactoryTwitter(): void
    {
        $twitterResponse = [
            new MockResponse(
                file_get_contents(
                    CurrentWorkDirPath::getPath() .
                    '/tests/unit/ressources/TwitterResponseRefreshFirst.json'
                ),
                ['http_code' => 200]
            ),
        ];
        $client = new MockHttpClient($twitterResponse, 'https://api.twitter.com/2/oauth2/token');
        $socialNetworks = SocialNetworks::Twitter;
        $sut = (new FactorySocialNetworksApi($client))->buildApi($socialNetworks);
        $this->assertInstanceOf(TwitterClient::class, $sut);
    }
}
