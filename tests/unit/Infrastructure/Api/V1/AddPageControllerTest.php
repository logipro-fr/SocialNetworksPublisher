<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Api\V1;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Safe\DateTimeImmutable;
use SocialNetworksPublisher\Domain\Model\Key\Exceptions\KeyNotFoundException;
use SocialNetworksPublisher\Domain\Model\Key\Key;
use SocialNetworksPublisher\Domain\Model\Key\KeyId;
use SocialNetworksPublisher\Domain\Model\Key\KeyRepositoryInterface;
use SocialNetworksPublisher\Domain\Model\Key\TwitterKeyData;
use SocialNetworksPublisher\Domain\Model\Page\Page;
use SocialNetworksPublisher\Domain\Model\Page\PageId;
use SocialNetworksPublisher\Domain\Model\Page\PageName;
use SocialNetworksPublisher\Domain\Model\Shared\SocialNetworks;
use SocialNetworksPublisher\Infrastructure\Api\V1\AddPageController;
use SocialNetworksPublisher\Infrastructure\Persistence\Key\KeyRepositoryInMemory;
use SocialNetworksPublisher\Infrastructure\Persistence\Page\PageRepositoryInMemory;
use Symfony\Component\HttpFoundation\Request;

use function Safe\json_encode;

class AddPageControllerTest extends TestCase
{
    use AssertResponseTrait;

    private KeyRepositoryInterface $keys;
    private AddPageController $controller;
    private PageRepositoryInMemory $pages;
    private MockObject $emMock;

    protected function setUp(): void
    {
        $this->keys = new KeyRepositoryInMemory();
        $this->pages = new PageRepositoryInMemory();

        $key = new Key(
            new KeyId("key_id"),
            SocialNetworks::Twitter,
            new DateTimeImmutable(),
            new TwitterKeyData("a", "b")
        );
        $page = new Page(
            new PageId('page_id'),
            new PageName("test"),
            SocialNetworks::Twitter,
        );

        $this->keys->add($key);
        $this->pages->add($page);

        /** @var MockObject $entityManager */
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $this->emMock = $entityManager;

       /** @var EntityManagerInterface $entityManager */
        $this->controller = new AddPageController(
            $this->keys,
            $entityManager,
        );
    }

    public function testAddPage(): void
    {
        $this->emMock->expects($this->once())->method("flush");

        $request = Request::create(
            "/api/v1/keys/addPage",
            "PATCH",
            content: json_encode([
                "keyId" => "key_id",
                "pageId" => "page_id"
                ])
        );

        $response = $this->controller->execute($request);

        /** @var string $content */
        $content = $response->getContent();
        /** @var \stdClass $responseObject */
        $responseObject = json_decode($content);
        /** @var Key */
        $modifiedKey = $this->keys
        ->findById(new KeyId($responseObject->data->keyId));
        $foundPage = $this->pages->findById(new PageId($modifiedKey->getValue()));

        $this->assertResponseSuccess($response, (object)["keyId" => $responseObject->data->keyId]);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals("page_id", $modifiedKey->getValue());
        $this->assertEquals("test", $foundPage->getName());
    }

    public function testExceptionRaised(): void
    {

        $request = Request::create(
            "/api/v1/keys/addPage",
            "PATCH",
            content: json_encode([
                "keyId" => "",
                "pageId" => ""
            ])
        );

        $response = $this->controller->execute($request);

        $this->assertResponseFailure(
            $response,
            (new \ReflectionClass(KeyNotFoundException::class))->getShortName()
        );
    }
}
