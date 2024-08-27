<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Api\V1;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Page\Exceptions\PageNotFoundException;
use SocialNetworksPublisher\Domain\Model\Page\Page;
use SocialNetworksPublisher\Domain\Model\Page\PageId;
use SocialNetworksPublisher\Domain\Model\Page\PageName;
use SocialNetworksPublisher\Domain\Model\Page\PageRepositoryInterface;
use SocialNetworksPublisher\Domain\Model\Shared\SocialNetworks;
use SocialNetworksPublisher\Infrastructure\Api\V1\AddPostController;
use SocialNetworksPublisher\Infrastructure\Persistence\Page\PageRepositoryInMemory;
use Symfony\Component\HttpFoundation\Request;

use function Safe\json_encode;

class AddPostControllerTest extends TestCase
{
    use AssertResponseTrait;

    private PageRepositoryInterface $pages;
    private AddPostController $controller;
    private MockObject $emMock;

    public function setUp(): void
    {
        $this->pages = new PageRepositoryInMemory();

         /** @var MockObject $entityManager */
         $entityManager = $this->createMock(EntityManagerInterface::class);
         $this->emMock = $entityManager;

        /** @var EntityManagerInterface $entityManager */
         $this->controller = new AddPostController(
             $this->pages,
             $entityManager,
         );
    }

    public function testAddPost(): void
    {

        $originalPage = new Page(
            $id = new PageId(),
            new PageName(""),
            SocialNetworks::Twitter,
        );

        $this->pages->add($originalPage);


        $request = Request::create(
            "/api/v1/pages/post",
            "PATCH",
            content: json_encode([
                "pageId" => $id->__toString(),
                "content" => "test_content"
            ])
        );

        $response = $this->controller->execute($request);
        $this->assertEquals(201, $response->getStatusCode());
        $modifiedPage = $this->pages
        ->findById(new PageId($id));
        $this->assertCount(1, $modifiedPage->getPosts());
        $this->assertEquals("test_content", $modifiedPage->getPosts()[0]?->getContent());
    }

    public function testExceptionRaised(): void
    {

        $request = Request::create(
            "/api/v1/pages/post",
            "PATCH",
            content: json_encode([
                "pageId" => "test",
                "content" => "test_content"
            ])
        );

        $response = $this->controller->execute($request);

        $this->assertResponseFailure(
            $response,
            (new \ReflectionClass(PageNotFoundException::class))->getShortName()
        );
    }
}
