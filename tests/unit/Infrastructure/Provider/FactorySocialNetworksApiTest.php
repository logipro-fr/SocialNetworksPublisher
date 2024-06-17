<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Provider;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Application\Service\PublishPost\AbstractFactorySocialNetworksApi;
use SocialNetworksPublisher\Application\Service\SocialNetworksApiInterface;
use SocialNetworksPublisher\Infrastructure\Provider\Exceptions\InvalidSocialNetworks;
use SocialNetworksPublisher\Infrastructure\Provider\FactorySocialNetworksApi;
use SocialNetworksPublisher\Infrastructure\Provider\SimpleBlog\SimpleBlog;

class FactorySocialNetworksApiTest extends TestCase
{
    public function testAbstractFactorySimpleBlog(): void
    {
        $socialNetworks = "simpleBlog";
        $sut = (new FactorySocialNetworksApi())->buildApi($socialNetworks);
        $this->assertInstanceOf(SocialNetworksApiInterface::class, $sut);
        $this->assertInstanceOf(SimpleBlog::class, $sut);
    }
    public function testAbstractFactoryFacebook(): void
    {
        $socialNetworks = "faceBook";
        $sut = (new FactorySocialNetworksApi())->buildApi($socialNetworks);
        $this->assertInstanceOf(SocialNetworksApiInterface::class, $sut);
    }

    public function testAbstractFactoryLinkedIn(): void
    {
        $socialNetworks = "LinkedIn";
        $sut = (new FactorySocialNetworksApi())->buildApi($socialNetworks);
        $this->assertInstanceOf(SocialNetworksApiInterface::class, $sut);
    }

    public function testArgumentException(): void
    {
        $this->expectException(InvalidSocialNetworks::class);
        $this->expectExceptionCode(400);
        $socialNetworks = "ddd";
        (new FactorySocialNetworksApi())->buildApi($socialNetworks);
    }
}
