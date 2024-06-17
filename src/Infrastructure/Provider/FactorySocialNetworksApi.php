<?php

namespace SocialNetworksPublisher\Infrastructure\Provider;

use SocialNetworksPublisher\Application\Service\PublishPost\AbstractFactorySocialNetworksApi;
use SocialNetworksPublisher\Application\Service\SocialNetworksApiInterface;
use SocialNetworksPublisher\Infrastructure\Provider\Exceptions\InvalidSocialNetworks;
use SocialNetworksPublisher\Infrastructure\Provider\Facebook\Facebook;
use SocialNetworksPublisher\Infrastructure\Provider\LinkedIn\LinkedIn;
use SocialNetworksPublisher\Infrastructure\Provider\SimpleBlog\SimpleBlog;

class FactorySocialNetworksApi extends AbstractFactorySocialNetworksApi
{
    public function buildApi(string $socialNetworks): SocialNetworksApiInterface
    {
        $socialNetworks = strtolower($socialNetworks);
        switch ($socialNetworks) {
            case 'simpleblog':
                return new SimpleBlog(getcwd() . "/var/simpleBlogControllerRequest.txt");
            case 'facebook':
                return new Facebook();
            case 'linkedin':
                return new LinkedIn();
            default:
                throw new InvalidSocialNetworks("Unsupported social network: $socialNetworks", 400);
        }
    }
}
