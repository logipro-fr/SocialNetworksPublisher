<?php

namespace SocialNetworksPublisher\Domain\Model\Post;

use SocialNetworksPublisher\Domain\Model\Post\Exceptions\BadAuthorIdException;

class Author
{
    public function __construct(private string $id)
    {
        $this->validate();
    }

    public function getId(): string
    {
        return $this->id;
    }

    private function validate(): void
    {
        if (empty($this->id)) {
            throw new BadAuthorIdException(
                "The id parameters cannot be empty",
                BadAuthorIdException::ERROR_CODE
            );
        }
    }
}
