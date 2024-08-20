<?php

namespace SocialNetworksPublisher\Infrastructure\Persistence\Doctrine\Types;

use Phariscope\DoctrineEnumType\EnumType;
use SocialNetworksPublisher\Domain\Model\Shared\SocialNetworks;

class SocialNetworksType extends EnumType
{
    protected string $name = "social_network";
    protected string $className = SocialNetworks::class;
}
