<?php

namespace SocialNetworksPublisher\Domain\Model\Post;

use SocialNetworksPublisher\Domain\Model\Post\Exceptions\EmptyContentException;

class Content
{
    public function __construct(private string $textContent)
    {
        if (empty($this->textContent)) {
            throw new EmptyContentException(EmptyContentException::MESSAGE, EmptyContentException::ERROR_CODE);
        }
    }

    public function __toString()
    {
        return $this->textContent;
    }
}
