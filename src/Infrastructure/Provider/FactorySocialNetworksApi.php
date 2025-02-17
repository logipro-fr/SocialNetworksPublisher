<?php

namespace SocialNetworksPublisher\Infrastructure\Provider;

use SocialNetworksPublisher\Application\Service\PublishPost\AbstractFactorySocialNetworksApi;
use SocialNetworksPublisher\Application\Service\PublishPost\SocialNetworksApiInterface;
use SocialNetworksPublisher\Domain\Model\Shared\SocialNetworks;
use SocialNetworksPublisher\Infrastructure\Provider\Facebook\Facebook;
use SocialNetworksPublisher\Infrastructure\Provider\LinkedIn\LinkedIn;
use SocialNetworksPublisher\Infrastructure\Provider\SimpleBlog\SimpleBlog;
use SocialNetworksPublisher\Infrastructure\Provider\Twitter\TwitterBearerToken;
use SocialNetworksPublisher\Infrastructure\Provider\Twitter\TwitterClient;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FactorySocialNetworksApi extends AbstractFactorySocialNetworksApi
{
    public function __construct(private HttpClientInterface $client = new CurlHttpClient())
    {
    }
    public function buildApi(SocialNetworks $socialNetworks): SocialNetworksApiInterface
    {
        switch ($socialNetworks) {
            case SocialNetworks::SimpleBlog:
                return new SimpleBlog("simpleBlogControllerRequest.txt");
            case SocialNetworks::Facebook:
                return new Facebook();
            case SocialNetworks::LinkedIn:
                return new LinkedIn();
            case SocialNetworks::Twitter:
                return new TwitterClient($this->client, new TwitterBearerToken($this->client));
            default:
                return new NullApiProvider();
        }
    }
}
