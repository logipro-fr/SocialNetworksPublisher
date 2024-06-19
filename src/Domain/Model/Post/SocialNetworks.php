<?php
namespace SocialNetworksPublisher\Domain\Model\Post;

use SocialNetworksPublisher\Domain\Model\Post\Exceptions\BadSocialNetworksParameterException;

enum SocialNetworks: string {
    case Facebook = "Facebook";
    case LinkedIn= "LinkedIn";
    case SimpleBlog = "SimpleBlog";

    public static function fromString(string $value): self
    {
        return match($value) {
            self::Facebook->value => self::Facebook,
            self::LinkedIn->value => self::LinkedIn,
            self::SimpleBlog->value => self::SimpleBlog,
            default => throw new BadSocialNetworksParameterException("Invalid social network", BadSocialNetworksParameterException::ERROR_CODE),
        };
    }
}