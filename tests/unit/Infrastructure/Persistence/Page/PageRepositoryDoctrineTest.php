<?php
 namespace SocialNetworksPublisher\Tests\Infrastructure\Persistence\Page;

use DoctrineTestingTools\DoctrineRepositoryTesterTrait;
use SocialNetworksPublisher\Infrastructure\Persistence\Page\PageRepositoryDoctrine;

 class PageRepositoryDoctrineTest extends PageRepositoryTestBase {
    use DoctrineRepositoryTesterTrait;

    protected function setUp(): void
    {
        $this->initDoctrineTester();
        $this->clearTables(['pages']);
        $this->pages = new PageRepositoryDoctrine($this->getEntityManager());
    }
 }