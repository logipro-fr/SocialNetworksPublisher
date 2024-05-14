<?php

namespace SocialNetworksPublisher\Domain;

use SocialNetworksPublisher\Domain\Exceptions\BadPageIdException;
use SocialNetworksPublisher\Domain\Exceptions\BadSocialNetworksParameterException;

class Page
{
    private const LINKEDIN = "linkedin";
    private const FACEBOOK = "facebook";

    public function __construct(private string $socialNetwork, private string $id)
    {
        $this->isValidPage();
        $this->searchSocialNetworks();
    }

    public function getSocialNetwork(): string
    {
        return $this->socialNetwork;
    }

    public function getId(): string
    {
        return $this->id;
    }

    private function isValidPage() : void {
        if (empty($this->socialNetwork)) {
            throw new BadSocialNetworksParameterException("The social network parameters cannot be empty");
        }
        if (empty($this->id)) {
            throw new BadPageIdException("The id parameters cannot be empty");
        }
    }
    
    private function searchSocialNetworks() :void {
        if (str_contains(strtolower($this->socialNetwork), "linkedin")) {
            $this->socialNetwork = self::LINKEDIN;
        }
        if (str_contains(strtolower($this->socialNetwork), "facebook")) {
            $this->socialNetwork = self::FACEBOOK;
        }
    }
}
