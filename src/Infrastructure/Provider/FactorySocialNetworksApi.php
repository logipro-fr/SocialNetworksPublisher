<?php

namespace SocialNetworksPublisher\Infrastructure\Provider;

use SocialNetworksPublisher\Application\Service\PublishPost\AbstractFactorySocialNetworksApi;
use SocialNetworksPublisher\Application\Service\PublishPost\SocialNetworksApiInterface;
use SocialNetworksPublisher\Domain\Model\Post\Exceptions\BadSocialNetworksParameterException;
use SocialNetworksPublisher\Domain\Model\Post\SocialNetworks;
use SocialNetworksPublisher\Infrastructure\Provider\Exceptions\InvalidSocialNetworks;
use SocialNetworksPublisher\Infrastructure\Provider\Facebook\Facebook;
use SocialNetworksPublisher\Infrastructure\Provider\LinkedIn\LinkedIn;
use SocialNetworksPublisher\Infrastructure\Provider\SimpleBlog\SimpleBlog;
use SocialNetworksPublisher\Infrastructure\Provider\Twitter\Twitter;

class FactorySocialNetworksApi extends AbstractFactorySocialNetworksApi
{
    public function buildApi(SocialNetworks $socialNetworks): SocialNetworksApiInterface
    {
        switch ($socialNetworks) {
            case SocialNetworks::SimpleBlog:
                return new SimpleBlog(getcwd() . "/var/simpleBlogControllerRequest.txt");
            case SocialNetworks::Facebook:
                return new Facebook();
            case SocialNetworks::LinkedIn:
                return new LinkedIn();
            case SocialNetworks::Twitter:
                return new Twitter();
            default:
                throw new BadSocialNetworksParameterException("", BadSocialNetworksParameterException::ERROR_CODE);
        }
    }
}
