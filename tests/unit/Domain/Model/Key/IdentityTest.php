<?php

namespace SocialNetworksPublisher\Tests\Domain\Model\Key;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Key\Identity;
use SocialNetworksPublisher\Domain\Model\Page\PageId;

class IdentityTest extends TestCase
{
    public function testsCreateIdentity(): void
    {
        $pageId = new PageId("page_id");
        $sut = new Identity("page_id");

        $this->assertEquals($pageId, $sut->getPageIdValue());
        $this->assertEquals($pageId->__toString(), $sut);
    }
}
