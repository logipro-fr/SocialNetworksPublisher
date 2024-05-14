<?php

namespace SocialNetworks\Domain;

use PhpParser\Node\Expr\Empty_;
use SocialNetworks\Domain\Exceptions\BadHashtagFormatException;
use SocialNetworks\Domain\Exceptions\EmptyHashtagException;

use function PHPUnit\Framework\isNull;

class HashTag
{
    /** @var string[]  */
    private array $hashTagslist = [];
    /**
     * @param string $hashTags
     */
    public function __construct(string $hashTags = "")
    {
        $this->hashTagslist = explode(", ", $hashTags);
        if (!empty($hashTags) && $this->checkHashTag($this->hashTagslist)) {
            throw new BadHashtagFormatException("Error: Missing or invalid hashtag format.");
        }
    }

    /** @return string[]  */
    public function getHashtag(): array
    {
        return $this->hashTagslist;
    }

    /**
     * @param string[] $list
     */
    private function checkHashTag(array $list): bool
    {
        foreach ($list as $element) {
            if (substr_compare($element, "#", 0, 1) !== 0) {
                return true;
            }
        }
        return false;
    }
}
