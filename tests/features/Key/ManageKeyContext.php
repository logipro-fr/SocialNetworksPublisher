<?php

namespace features\Key;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\Generator\Generator;
use PHPUnit\Framework\MockObject\MockObject;
use Safe\DateTimeImmutable;
use SocialNetworksPublisher\Domain\Model\Key\Key;
use SocialNetworksPublisher\Domain\Model\Key\KeyId;
use SocialNetworksPublisher\Domain\Model\Key\KeyRepositoryInterface;
use SocialNetworksPublisher\Domain\Model\Key\TwitterKeyData;
use SocialNetworksPublisher\Domain\Model\Page\Page;
use SocialNetworksPublisher\Domain\Model\Page\PageId;
use SocialNetworksPublisher\Domain\Model\Page\PageName;
use SocialNetworksPublisher\Domain\Model\Page\PageRepositoryInterface;
use SocialNetworksPublisher\Domain\Model\Shared\SocialNetworks;
use SocialNetworksPublisher\Infrastructure\Api\V1\AddPageController;
use SocialNetworksPublisher\Infrastructure\Api\V1\CreateKeyController;
use SocialNetworksPublisher\Infrastructure\Persistence\Key\KeyRepositoryInMemory;
use SocialNetworksPublisher\Infrastructure\Persistence\Page\PageRepositoryInMemory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
    private AddPageController $addPageController;
    private Response $responseCreateKey;
    private Response $responseAddPage;
    private KeyId $keyId;
    private PageRepositoryInterface $pages;


    public function __construct()
    {
        $this->keys = new KeyRepositoryInMemory();
        $this->pages = new PageRepositoryInMemory();

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

        $this->addPageController = new AddPageController(
            $this->keys,
            $entityManager,
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
     * @When I create the API key
     */
    public function iCreateTheApiKey(): void
    {
        $request = Request::create(
            "/api/v1/keys/Twitter",
            "POST",
            content: json_encode([
                "bearerToken" => $this->bearerToken,
                "refreshToken" => $this->refreshToken
            ])
        );
        $this->responseCreateKey = $this->createKeyController->execute($request);
    }

    /**
     * @Then the API key is created successfully
     */
    public function theApiKeyIsCreatedSuccessfully(): void
    {
        /** @var string $content */
        $content = $this->responseCreateKey->getContent();
        /** @var \stdClass $response */
        $response = json_decode($content);

        $key = $this->keys->findById(new KeyId($response->data->keyId));
        $this->keyId = $key->getKeyId();
        Assert::assertNotNull($key);
        Assert::assertTrue($response->success);
    }

    /**
     * @Then I have key ID
     */
    public function iHaveKeyId(): void
    {
        Assert::assertStringStartsWith("key_", $this->keyId);
    }

    /**
     * @Given I want to link my API key to my page with the page ID :pageId
     */
    public function iWantToLinkMyApiKeyToMyPageWithThePageId(string $pageId): void
    {
        $this->pageId = $pageId;
        $page = new Page(
            new PageId($this->pageId),
            new PageName("test"),
            SocialNetworks::Twitter
        );
        $this->keys->add(new Key(
            new KeyId("test"),
            SocialNetworks::Twitter,
            new DateTimeImmutable(),
            new TwitterKeyData("a", "b")
        ));
        $this->pages->add($page);
    }

    /**
     * @When I link the API key
     */
    public function iLinkTheApiKey(): void
    {
        $request = Request::create(
            "/api/v1/keys/page",
            "POST",
            content: json_encode([
                "keyId" => "test",
                "pageId" => $this->pageId,
            ])
        );
        $this->responseAddPage = $this->addPageController->execute($request);
    }

    /**
     * @Then the API key is linked successfully to the page
     */
    public function theApiKeyIsLinkedSuccessfullyToThePage(): void
    {
        /** @var string $content */
        $content = $this->responseAddPage->getContent();
        /** @var \stdClass $response */
        $response = json_decode($content);
        $key = $this->keys->findById(new KeyId($response->data->keyId));
        Assert::assertEquals($this->pageId, $key->getValue());
    }
}
