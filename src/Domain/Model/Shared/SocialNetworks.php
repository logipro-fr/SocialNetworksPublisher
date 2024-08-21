<?php

namespace SocialNetworksPublisher\Domain\Model\Shared;

use SocialNetworksPublisher\Domain\Model\Page\Exceptions\PageSocialNetworksDoesntExist;
use SocialNetworksPublisher\Domain\Model\Post\Exceptions\BadSocialNetworksParameterException;

enum SocialNetworks: string
{
    case Facebook = "Facebook";
    case LinkedIn = "LinkedIn";
    case SimpleBlog = "SimpleBlog";
    case Twitter = "Twitter";

    public static function fromString(string $value): self
    {
        return match ($value) {
            self::Facebook->value => self::Facebook,
            self::LinkedIn->value => self::LinkedIn,
            self::SimpleBlog->value => self::SimpleBlog,
            self::Twitter->value => self::Twitter,
            default => throw new PageSocialNetworksDoesntExist(
                $value,
            ),
        };
    }
}
