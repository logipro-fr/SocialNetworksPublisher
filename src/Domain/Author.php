<?php

namespace SocialNetworks\Domain;

use SocialNetworks\Domain\Exceptions\BadAuthorIdException;
use SocialNetworks\Domain\Exceptions\BadAuthorNameException;
use SocialNetworks\Domain\Exceptions\BadAuthorTypeException;

class Author
{
    public const ORGANIZATION = "urn:li:organization:";
    public const PERSON = "urn:li:person:";
    private string $urn;
    public function __construct(private string $type, private string $id, private string $name)
    {
        $this->isValidAuthor();

        $this->urn = $this->type . $this->id;
    }

    public function getUrn(): string
    {
        return $this->urn;
    }

    public function getName(): string
    {
        return $this->name;
    }

    private function isValidAuthor(): void
    {
        if (empty($this->type)) {
            throw new BadAuthorTypeException("The author type cannot be null");
        }
        if (empty($this->id)) {
            throw new BadAuthorIdException("The author id cannot be null");
        }

        if (empty($this->name)) {
            throw new BadAuthorNameException("The author name cannot be null");
        }
    }
}
