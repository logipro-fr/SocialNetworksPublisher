<?php

namespace SocialNetworksPublisher\Domain\Model\Post;

use SocialNetworksPublisher\Domain\Model\Post\Exceptions\BadPageIdException;
use SocialNetworksPublisher\Domain\Model\Post\Exceptions\BadSocialNetworksParameterException;

class Page
{
    private const LINKEDIN = "linkedin";
    private const FACEBOOK = "facebook";

    public function __construct(private string $socialNetwork, private string $id)
    {
        $this->validatePage();
        $this->checkSocialNetworks();
    }

    public function getSocialNetwork(): string
    {
        return $this->socialNetwork;
    }

    public function getId(): string
    {
        return $this->id;
    }

    private function validatePage(): void
    {
        if (empty($this->socialNetwork)) {
            throw new BadSocialNetworksParameterException("The social network parameters cannot be empty", BadSocialNetworksParameterException::ERROR_CODE);
        }
        if (empty($this->id)) {
            throw new BadPageIdException("The id parameters cannot be empty", BadPageIdException::ERROR_CODE);
        }
    }

    private function checkSocialNetworks(): void
    {
        if (str_contains(strtolower($this->socialNetwork), "linkedin")) {
            $this->socialNetwork = self::LINKEDIN;
        }
        if (str_contains(strtolower($this->socialNetwork), "facebook")) {
            $this->socialNetwork = self::FACEBOOK;
        }
    }
}
