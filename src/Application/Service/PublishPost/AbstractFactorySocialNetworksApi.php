<?php

namespace SocialNetworksPublisher\Application\Service\PublishPost;

use SocialNetworksPublisher\Application\Service\PublishPost\SocialNetworksApiInterface;
use SocialNetworksPublisher\Domain\Model\Shared\SocialNetworks;

abstract class AbstractFactorySocialNetworksApi
{
    abstract public function buildApi(SocialNetworks $socialNetworks): SocialNetworksApiInterface;
}
