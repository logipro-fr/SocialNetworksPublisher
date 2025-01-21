<?php

namespace features\Key;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\Generator\Generator;
use PHPUnit\Framework\MockObject\MockObject;
use SocialNetworksPublisher\Domain\Model\Key\Key;
use SocialNetworksPublisher\Domain\Model\Key\KeyId;
use SocialNetworksPublisher\Domain\Model\Key\KeyRepositoryInterface;
use SocialNetworksPublisher\Infrastructure\Api\V1\CreateKeyController;
use SocialNetworksPublisher\Infrastructure\Persistence\Key\KeyRepositoryInMemory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use function PHPUnit\Framework\assertEquals;
use function Safe\json_encode;

/**
 * Defines application features from the specific context.
 */
class ManageKeyContext implements Context
{
    private string $bearerToken;
    private string $refreshToken;
    private string $pageId;
    private KeyRepositoryInterface $keys;
    private CreateKeyController $createKeyController;
    private Response $responseCreateKey;
    private KeyId $keyId;

    public function __construct()
    {
        $this->keys = new KeyRepositoryInMemory();

        /** @var MockObject $entityManager */
        $entityManager = (new Generator())->testDouble(
            EntityManagerInterface::class,
            true,
            true,
            callOriginalConstructor: false
        );
        /** @var EntityManagerInterface $entityManager */
        $this->createKeyController = new CreateKeyController(
            $this->keys,
            $entityManager
        );
    }

    /**
     * @Given I want to create a Twitter API key
     */
    public function iWantToCreateATwitterApiKey(): void
    {
    }

    /**
     * @Given I have the Bearer token :bearer
     */
    public function iHaveTheBearerToken(string $bearer): void
    {
        $this->bearerToken = $bearer;
    }


    /**
     * @Given I have the Refresh token :refresh
     */
    public function iHaveTheRefreshToken(string $refresh): void
    {
        $this->refreshToken = $refresh;
    }

    /**
     * @Given I want to link my API key to my page with the page ID :pageId
     */
    public function iWantToLinkMyApiKeyToMyPageWithThePageId(string $pageId): void
    {
        $this->pageId = $pageId;
    }

    /**
     * @When I create the API key
     */
    public function iCreateTheApiKey(): void
    {
        $request = Request::create(
            "/api/v1/keys/Twitter",
            "POST",
            content: json_encode([
                "bearerToken" => $this->bearerToken,
                "refreshToken" => $this->refreshToken,
                "pageId" => $this->pageId
            ])
        );
        $this->responseCreateKey = $this->createKeyController->execute($request);
    }

    /**
     * @Then the API key is created successfully with the page Id :pageId
     */
    public function theApiKeyIsCreatedSuccessfullyWithThePageId(string $pageId): void
    {
        /** @var string $content */
        $content = $this->responseCreateKey->getContent();
        /** @var \stdClass&object{data:object{keyId:string}} $response */
        $response = json_decode($content);
        /** @var Key $key */
        $key = $this->keys->findById(new KeyId($response->data->keyId));
        $this->keyId = $key->getKeyId();
        Assert::assertTrue($response->success);
        Assert:assertEquals($pageId, $key->getValue());
    }

    /**
     * @Then I have key ID
     */
    public function iHaveKeyId(): void
    {
        Assert::assertStringStartsWith("key_", $this->keyId);
    }
}
