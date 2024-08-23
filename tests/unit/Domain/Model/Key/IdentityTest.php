<?php

namespace SocialNetworksPublisher\Tests\Domain\Model\Key;

use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Key\Identity;
use SocialNetworksPublisher\Domain\Model\Page\PageId;

class IdentityTest extends TestCase {
    public function testsCreateIdentity(): void {
        $pageId = new PageId();
        $sut = new Identity($pageId);

        $this->assertEquals($pageId, $sut->getValue());
        $this->assertEquals($pageId->__toString(), $sut);
    }

}