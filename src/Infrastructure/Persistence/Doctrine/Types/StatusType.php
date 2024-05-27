<?php

namespace SocialNetworksPublisher\Infrastructure\Persistence\Doctrine\Types;

use Phariscope\DoctrineEnumType\EnumType;
use SocialNetworksPublisher\Domain\Model\Post\Status;

class StatusType extends EnumType
{
    protected string $name = "status";
    protected string $className = Status::class;
}
