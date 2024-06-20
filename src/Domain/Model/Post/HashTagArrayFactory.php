<?php

namespace SocialNetworksPublisher\Domain\Model\Post;

use SocialNetworksPublisher\Domain\Model\Post\Exceptions\EmptyParametersException;

class HashTagArrayFactory
{
    /**
     * @param HashTag[] $hashTags
     */
    public function buildHashTagArrayFromArray(array $hashTags): HashTagArray
    {

        $hashTagArray = new HashTagArray();
        foreach ($hashTags as $hashTag) {
            $hashTagArray->add($hashTag);
        }

        return $hashTagArray;
    }

    public function buildHashTagArrayFromSentence(string $sentence, string $separator): HashTagArray
    {
        if (empty($sentence) || empty($separator)) {
            return new HashTagArray();
        }

        $tab = explode($separator, $sentence);
        /** @var HashTag[] */
        $hashTagTab = array();
        $i = 0;
        foreach ($tab as $hashTag) {
            if (!empty($tab[$i])) {
                $hashTagTab[$i] = new HashTag($tab[$i]);
            }
            $i++;
        }
         return $this->buildHashTagArrayFromArray($hashTagTab);
    }
}
