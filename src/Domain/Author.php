<?php

namespace SocialNetworks\Domain;

use SocialNetworks\Domain\Exceptions\BadAuthorIdException;
use SocialNetworks\Domain\Exceptions\BadPageIdException;
use SocialNetworks\Domain\Exceptions\BadSocialNetworksParameterException;

class Author
{
    private const LINKEDIN = "linkedin";
    private const FACEBOOK = "facebook";
    private string $socialNetwork = "undefined";
    
    public function __construct(string $socialNetwork, private string $id)
    {
        $this->isValidAuthor($socialNetwork);
        $this->searchSocialNetworks($socialNetwork);

    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getSocialNetwork(): string {
        return $this->socialNetwork;
    }

    private function isValidAuthor(string $socialNetwork): void
    {
        if (empty($socialNetwork)) {
            throw new BadSocialNetworksParameterException("The social network parameters cannot be empty");
        }
        if (empty($this->id)) {
            throw new BadAuthorIdException("The id parameters cannot be empty");
        }
    }

    private function searchSocialNetworks(string $find) : void {
        if (str_contains(strtolower($find), "linkedin")) {
            $this->socialNetwork = self::LINKEDIN;
        }
        if (str_contains(strtolower($find), "facebook")) {
            $this->socialNetwork = self::FACEBOOK;
        }
    }
}
