<?php

namespace SocialNetworksPublisher\Domain\Model\Post;

use SocialNetworksPublisher\Domain\Model\Post\Exceptions\BadAuthorIdException;
use SocialNetworksPublisher\Domain\Model\Post\Exceptions\BadSocialNetworksParameterException;

class Author
{
    private const LINKEDIN = "linkedin";
    private const FACEBOOK = "facebook";
    private string $socialNetwork = "undefined";

    public function __construct(string $socialNetwork, private string $id)
    {
        $this->validate($socialNetwork);
        $this->searchSocialNetworks($socialNetwork);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getSocialNetwork(): string
    {
        return $this->socialNetwork;
    }

    private function validate(string $socialNetwork): void
    {
        if (empty($socialNetwork)) {
            throw new BadSocialNetworksParameterException(
                "The social network parameters cannot be empty",
                BadSocialNetworksParameterException::ERROR_CODE
            );
        }
        if (empty($this->id)) {
            throw new BadAuthorIdException(
                "The id parameters cannot be empty",
                BadAuthorIdException::ERROR_CODE
            );
        }
    }

    private function searchSocialNetworks(string $find): void
    {
        if (str_contains(strtolower($find), "linkedin")) {
            $this->socialNetwork = self::LINKEDIN;
        }
        if (str_contains(strtolower($find), "facebook")) {
            $this->socialNetwork = self::FACEBOOK;
        }
    }
}
