<?php

namespace SocialNetworksPublisher\Tests\Infrastructure\Api\V1;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SocialNetworksPublisher\Domain\Model\Key\Exceptions\KeyDataEmptyBearerTokenException;
use SocialNetworksPublisher\Domain\Model\Key\KeyId;
use SocialNetworksPublisher\Domain\Model\Key\TwitterKeyData;
use SocialNetworksPublisher\Domain\Model\Shared\SocialNetworks;
use SocialNetworksPublisher\Infrastructure\Api\V1\CreateKeyController;
use SocialNetworksPublisher\Infrastructure\Persistence\Key\KeyRepositoryInMemory;
use Symfony\Component\HttpFoundation\Request;

use function Safe\json_encode;

class CreateKeyControllerTest extends TestCase
{
    use AssertResponseTrait;

    private KeyRepositoryInMemory $keys;
    private CreateKeyController $controller;
    public function setUp(): void
    {
        $this->keys = new KeyRepositoryInMemory();

         /** @var MockObject $entityManager */
         $entityManager = $this->createMock(EntityManagerInterface::class);
        /** @var EntityManagerInterface $entityManager */
         $this->controller = new CreateKeyController(
             $this->keys,
             $entityManager,
         );
    }

    public function testCreateKeyTwitter(): void
    {
        $request = Request::create(
            "/api/v1/keys/twitter",
            "POST",
            content: json_encode([
                "bearerToken" => "bearer",
                "refreshToken" => "refresh",
                "pageId" => "page_id"
            ])
        );

        $response = $this->controller->execute($request);

        /** @var string $content */
        $content = $response->getContent();
        /** @var \stdClass&object{data:object{keyId:string}} $responseObject */
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
        $this->assertEquals("page_id", $createdKey->getValue());
    }

    public function testExceptionRaised(): void
    {

        $request = Request::create(
            "/api/v1/pages",
            "POST",
            content: json_encode([
                "bearerToken" => "",
                "refreshToken" => "",
                "pageId" => "",
            ])
        );

        $response = $this->controller->execute($request);

        $this->assertResponseFailure(
            $response,
            (new \ReflectionClass(KeyDataEmptyBearerTokenException::class))->getShortName()
        );
    }
}
