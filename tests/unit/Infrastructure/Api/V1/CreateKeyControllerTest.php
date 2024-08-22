<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Api\V1;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Key\Exceptions\KeyDataEmptyBearerTokenException;
use SocialNetworksPublisher\Domain\Model\Key\KeyId;
use SocialNetworksPublisher\Domain\Model\Key\KeyRepositoryInterface;
use SocialNetworksPublisher\Domain\Model\Key\TwitterKeyData;
use SocialNetworksPublisher\Domain\Model\Shared\Exceptions\SocialNetworksDoesntExist;
use SocialNetworksPublisher\Domain\Model\Page\PageId;
use SocialNetworksPublisher\Domain\Model\Page\PageRepositoryInterface;
use SocialNetworksPublisher\Domain\Model\Shared\SocialNetworks;
use SocialNetworksPublisher\Infrastructure\Api\V1\CreateKeyController;
use SocialNetworksPublisher\Infrastructure\Api\V1\CreatePageController;
use SocialNetworksPublisher\Infrastructure\Persistence\Key\KeyRepositoryInMemory;
use SocialNetworksPublisher\Infrastructure\Persistence\Page\PageRepositoryInMemory;
use Symfony\Component\HttpFoundation\Request;

use function Safe\json_encode;

class CreateKeyControllerTest extends TestCase
{
    use AssertResponseTrait;

    private KeyRepositoryInMemory $keys;
    private CreateKeyController $controller;
    private MockObject $emMock;
    public function setUp(): void
    {
        $this->keys = new KeyRepositoryInMemory();
         /** @var MockObject $entityManager */
         $entityManager = $this->createMock(EntityManagerInterface::class);
         $this->emMock = $entityManager;
        /** @var EntityManagerInterface $entityManager */
         $this->controller = new CreateKeyController(
             $this->keys,
             $entityManager,
         );
    }

    public function testCreateKeyTwitter(): void
    {
        $this->emMock->expects($this->once())->method("flush");
        $request = Request::create(
            "/api/v1/keys/twitter",
            "POST",
            content: json_encode([
                "bearerToken" => "bearer",
                "refreshToken" => "refresh"
            ])
        );

        $response = $this->controller->execute($request);

        /** @var string $content */
        $content = $response->getContent();
        /** @var \stdClass $responseObject */
        $responseObject = json_decode($content);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertResponseSuccess($response, (object)["keyId" => $responseObject->data->keyId]);

        $createdKey = $this->keys
        ->findById(new KeyId($responseObject->data->keyId));
        $this->assertEquals(SocialNetworks::Twitter, $createdKey->getSocialNetwork());
        /** @var TwitterKeyData */
        $keyData = $createdKey->getKeyData();

        $this->assertEquals("bearer", $keyData->getBearerToken());
        $this->assertEquals("refresh", $keyData->getRefreshToken());
    }

    public function testExceptionRaised(): void
    {

        $request = Request::create(
            "/api/v1/pages",
            "POST",
            content: json_encode([
                "bearerToken" => "",
                "refreshToken" => ""
            ])
        );

        $response = $this->controller->execute($request);

        $this->assertResponseFailure(
            $response,
            (new \ReflectionClass(KeyDataEmptyBearerTokenException::class))->getShortName()
        );
    }
}
