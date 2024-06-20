<?php

namespace SocialNetworksPublisher\Domain\Model\Post;

use SocialNetworksPublisher\Domain\Model\Post\Exceptions\BadPageIdException;
use SocialNetworksPublisher\Domain\Model\Post\Exceptions\BadSocialNetworksParameterException;

class Page
{
    public function __construct(private string $id)
    {
        $this->validatePage();
    }

    public function getId(): string
    {
        return $this->id;
    }

    private function validatePage(): void
    {
        if (empty($this->id)) {
            throw new BadPageIdException(
                "The id parameters cannot be empty",
                BadPageIdException::ERROR_CODE
            );
        }
    }
}
