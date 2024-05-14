<?php

namespace SocialNetworksPublisher\Domain;

use SocialNetworksPublisher\Domain\Exceptions\EmptyContentException;

class Content
{
    public function __construct(private string $textContent)
    {
        if (empty($this->textContent)) {
            throw new EmptyContentException(EmptyContentException::MESSAGE);
        }
    }

    public function __toString()
    {
        return $this->textContent;
    }
}
