<?php

namespace SocialNetworksPublisher\Domain\Model\Post;

use SocialNetworksPublisher\Domain\Model\Post\Exceptions\BadHashtagFormatException;

class HashTag
{
    public function __construct(private string $hashTag)
    {
        if (!empty($hashTag) && substr_compare($this->hashTag, "#", 0, 1) !== 0) {
            throw new BadHashtagFormatException("Error: Missing or invalid hashtag format.", BadHashtagFormatException::ERROR_CODE);
        }
    }

    public function getHashtag(): string
    {
        return $this->hashTag;
    }

    public function __toString()
    {
        return $this->hashTag;
    }
}
