<?php

namespace SocialNetworksPublisher\Domain\Model\Post;

use SocialNetworksPublisher\Domain\Model\Post\Exceptions\BadHashtagFormatException;

class HashTag
{
    public function __construct(private string $hashTag)
    {
        if (substr($hashTag, 0, 1) !== '#') {
            $hashTag = '#' . $hashTag;
        }
        if (preg_match('/^#(?!#)\w+$/u', $hashTag) && strlen($hashTag) > 1) {
            $this->hashTag = $hashTag;
        } else {
            throw new BadHashtagFormatException(
                "Error: Missing or invalid hashtag format.",
                BadHashtagFormatException::ERROR_CODE
            );
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
    /**
     * @return array<string>
     */
    public function toArray(): array
    {
        return [
            $this->hashTag,
        ];
    }
    /**
     * @param array<string> $data
     */
    public static function fromArray(array $data): self
    {
        return new self($data[0]);
    }
}
