<?php

namespace SocialNetworksPublisher\Application\Service\Key\AddPage;

use SocialNetworksPublisher\Domain\Model\Key\Identity;
use SocialNetworksPublisher\Domain\Model\Key\KeyId;
use SocialNetworksPublisher\Domain\Model\Key\KeyRepositoryInterface;
use SocialNetworksPublisher\Domain\Model\Page\PageId;

class AddPage
{
    private AddPageResponse $response;
    public function __construct(
        private KeyRepositoryInterface $keys
    ) {
    }

    public function execute(AddPageRequest $request): void
    {
        $key = $this->keys->findById(new KeyId($request->keyId));
        $key->setIdentity(new PageId($request->pageId));
        $this->response = new AddPageResponse(
            $key->getKeyId()
        );
    }

    public function getResponse(): AddPageResponse
    {
        return $this->response;
    }
}
