<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Provider;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Application\Service\PublishPost\AbstractFactorySocialNetworksApi;
use SocialNetworksPublisher\Application\Service\PublishPost\SocialNetworksApiInterface;
use SocialNetworksPublisher\Domain\Model\Post\SocialNetworks;
use SocialNetworksPublisher\Infrastructure\Provider\Exceptions\InvalidSocialNetworks;
use SocialNetworksPublisher\Infrastructure\Provider\Facebook\Facebook;
use SocialNetworksPublisher\Infrastructure\Provider\FactorySocialNetworksApi;
use SocialNetworksPublisher\Infrastructure\Provider\LinkedIn\LinkedIn;
use SocialNetworksPublisher\Infrastructure\Provider\SimpleBlog\SimpleBlog;
use SocialNetworksPublisher\Infrastructure\Provider\Twitter\Twitter;

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
        $socialNetworks = SocialNetworks::Twitter;
        $sut = (new FactorySocialNetworksApi())->buildApi($socialNetworks);
        $this->assertInstanceOf(Twitter::class, $sut);
    }
}
