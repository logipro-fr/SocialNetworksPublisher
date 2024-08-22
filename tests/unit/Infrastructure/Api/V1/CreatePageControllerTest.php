<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Api\V1;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Shared\Exceptions\SocialNetworksDoesntExist;
use SocialNetworksPublisher\Domain\Model\Page\PageId;
use SocialNetworksPublisher\Domain\Model\Page\PageRepositoryInterface;
use SocialNetworksPublisher\Domain\Model\Shared\SocialNetworks;
use SocialNetworksPublisher\Infrastructure\Api\V1\CreatePageController;
use SocialNetworksPublisher\Infrastructure\Persistence\Page\PageRepositoryInMemory;
use Symfony\Component\HttpFoundation\Request;

use function Safe\json_encode;

class CreatePageControllerTest extends TestCase
{
    use AssertResponseTrait;

    private PageRepositoryInterface $pages;
    private CreatePageController $controller;
    private MockObject $emMock;
    public function setUp(): void
    {
        $this->pages = new PageRepositoryInMemory();
         /** @var MockObject $entityManager */
         $entityManager = $this->createMock(EntityManagerInterface::class);
         $this->emMock = $entityManager;
        /** @var EntityManagerInterface $entityManager */
         $this->controller = new CreatePageController(
             $this->pages,
             $entityManager,
         );
    }

    public function testCreatePage(): void
    {
        $this->emMock->expects($this->once())->method("flush");
        $request = Request::create(
            "/api/v1/pages",
            "POST",
            content: json_encode([
                "pageName" => "page_name",
                "socialNetwork" => "Twitter"
            ])
        );

        $response = $this->controller->execute($request);

        /** @var string $content */
        $content = $response->getContent();
        /** @var \stdClass $responseObject */
        $responseObject = json_decode($content);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertResponseSuccess($response, (object)["pageId" => $responseObject->data->pageId]);

        $createdAccount = $this->pages
        ->findById(new PageId($responseObject->data->pageId));
        $this->assertEquals("page_name", $createdAccount->getName());
        $this->assertEquals(SocialNetworks::Twitter, $createdAccount->getSocialNetwork());
    }

    public function testExceptionRaised(): void
    {

        $request = Request::create(
            "/api/v1/pages",
            "POST",
            content: json_encode([
                "pageName" => "page_name",
                "socialNetwork" => ""
            ])
        );

        $response = $this->controller->execute($request);

        $this->assertResponseFailure(
            $response,
            (new \ReflectionClass(SocialNetworksDoesntExist::class))->getShortName()
        );
    }
}
