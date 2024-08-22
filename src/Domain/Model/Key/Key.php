<?php

namespace SocialNetworksPublisher\Domain\Model\Key;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Safe\DateTimeImmutable;
use SocialNetworksPublisher\Domain\Model\Page\PageId;
use SocialNetworksPublisher\Domain\Model\Shared\SocialNetworks;

class Key
{
    /** @var Collection<int ,PageId> */
    private Collection $pageIds;
    public function __construct(
        private SocialNetworks $socialNetworks,
        private DateTimeImmutable $expirationTime,
        private AbstractKeyData $keyData,
        private KeyId $keyId = new KeyId(),
    ) {
        $this->pageIds = new ArrayCollection();
    }

    public function getKeyId(): KeyId
    {
        return $this->keyId;
    }

    public function getSocialNetwork(): SocialNetworks
    {
        return $this->socialNetworks;
    }

    public function getExpirationDate(): DateTimeImmutable
    {
        return $this->expirationTime;
    }

    public function getKeyData(): AbstractKeyData
    {
        return $this->keyData;
    }
    /**
     * @return Collection<int, PageId>
     */
    public function getPageIds(): Collection
    {
        return $this->pageIds;
    }

    public function setExpirationDateTime(DateTimeImmutable $expirationTime): void
    {
        $this->expirationTime = $expirationTime;
    }

    public function addPageId(PageId $pageId): void
    {
        $this->pageIds->add($pageId);
    }
}
