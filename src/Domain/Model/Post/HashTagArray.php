<?php

namespace SocialNetworksPublisher\Domain\Model\Post;

class HashTagArray
{
    /** @var HashTag[] */
    private array $hashTags = [];
    public function __construct(?HashTag $hashTag = null)
    {
        if ($hashTag != null) {
            $this->hashTags[] = $hashTag;
        }
    }

    public function add(HashTag $hashTag): void
    {
        $this->hashTags[] = $hashTag;
    }
    /** @return HashTag[] */
    public function getHashTags(): array
    {
        return $this->hashTags;
    }

    public function __toString()
    {
        $result = "";
        foreach ($this->hashTags as $hashTag) {
            $result .= $hashTag . " ";
        }
        return $result;
    }

    public function toArray(): array
    {
        return array_map(function(HashTag $hashTag) {
            return $hashTag->toArray()[0];
        }, $this->hashTags);
    }

    public static function fromArray(array $data): self
    {
        $hashTagArray = new self();
        foreach ($data as $hashTagText) {
            $hashTagArray->add(new HashTag($hashTagText));
        }
        return $hashTagArray;
    }
}
