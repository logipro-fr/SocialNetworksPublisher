<?php

namespace SocialNetworksPublisher\Application\Service\PublishPost;

use SocialNetworksPublisher\Application\Service\SocialNetworksApiInterface;
use SocialNetworksPublisher\Infrastructure\Provider\SimpleBlog\SimpleBlog;

abstract class AbstractFactorySocialNetworksApi
{
    abstract public function buildApi(string $socialNetworks): SocialNetworksApiInterface;
}
