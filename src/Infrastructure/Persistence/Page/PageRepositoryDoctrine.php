<?php

namespace SocialNetworksPublisher\Infrastructure\Persistence\Page;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use SocialNetworksPublisher\Domain\Model\Page\Exceptions\PageNotFoundException;
use SocialNetworksPublisher\Domain\Model\Page\Page;
use SocialNetworksPublisher\Domain\Model\Page\PageId;
use SocialNetworksPublisher\Domain\Model\Page\PageRepositoryInterface;
use SocialNetworksPublisher\Domain\Model\Page\Post;

/**
 * @extends EntityRepository<Page>
 */
class PageRepositoryDoctrine extends EntityRepository implements PageRepositoryInterface
{
    public function __construct(EntityManagerInterface $em)
    {
        $class = $em->getClassMetadata(Page::class);
        parent::__construct($em, $class);
    }
    public function add(Page $page): void
    {
        $this->getEntityManager()->persist($page);
    }

    public function findById(PageId $pageId): Page
    {
        $page = $this->getEntityManager()->find(Page::class, $pageId);
        if ($page == null) {
            throw new PageNotFoundException($pageId);
        }
        return $page;
    }

    public function findAll(): array
    {
        return parent::findAll();
    }
}
