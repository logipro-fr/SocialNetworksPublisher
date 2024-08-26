<?php

namespace SocialNetworksPublisher\Infrastructure\Persistence\Doctrine\Types;

use Phariscope\DoctrineEnumType\EnumType;
use SocialNetworksPublisher\Domain\Model\Page\PostStatus;

class PostStatusType extends EnumType
{
    protected string $name = "post_status";
    protected string $className = PostStatus::class;
}
