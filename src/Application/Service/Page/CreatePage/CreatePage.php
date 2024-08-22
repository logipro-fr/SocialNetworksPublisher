<?php

namespace SocialNetworksPublisher\Application\Service\Page\CreatePage;

use SocialNetworksPublisher\Domain\Model\Page\Page;
use SocialNetworksPublisher\Domain\Model\Page\PageId;
use SocialNetworksPublisher\Domain\Model\Page\PageName;
use SocialNetworksPublisher\Domain\Model\Page\PageRepositoryInterface;
use SocialNetworksPublisher\Domain\Model\Shared\SocialNetworks;
use Symfony\Component\HttpFoundation\Response;

class CreatePage
{
    private CreatePageResponse $response;
    public function __construct(private PageRepositoryInterface $pages)
    {
    }

    public function execute(CreatePageRequest $request): void
    {
        $this->pages->add(new Page(
            $pageId = new PageId(),
            new PageName($request->pageName),
            SocialNetworks::fromString($request->socialNetworks)
        ));

        $this->response = new CreatePageResponse($pageId);
    }

    public function getResponse(): CreatePageResponse
    {
        return $this->response;
    }
}
