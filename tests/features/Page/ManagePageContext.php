<?php

namespace features\Page;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\Generator\Generator;
use PHPUnit\Framework\MockObject\MockObject;
use SocialNetworksPublisher\Domain\Model\Page\Page;
use SocialNetworksPublisher\Domain\Model\Page\PageId;
use SocialNetworksPublisher\Domain\Model\Page\PageName;
use SocialNetworksPublisher\Domain\Model\Page\PageRepositoryInterface;
use SocialNetworksPublisher\Domain\Model\Shared\SocialNetworks;
use SocialNetworksPublisher\Infrastructure\Api\V1\CreatePageController;
use SocialNetworksPublisher\Infrastructure\Persistence\Page\PageRepositoryInMemory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ManagePageContext implements Context
{
    private PageRepositoryInterface $pages;
    private CreatePageController $createPageController;
    private string $socialNetwork;
    private string $pageName;
    private Response $createPageResponse;
    public function __construct()
    {
        $this->pages = new PageRepositoryInMemory();

        /** @var MockObject $entityManager */
        $entityManager = (new Generator())->testDouble(
            EntityManagerInterface::class,
            true,
            true,
            callOriginalConstructor: false
        );
        /** @var EntityManagerInterface $entityManager */
        $this->createPageController = new CreatePageController(
            $this->pages,
            $entityManager
        );
    }

    /**
     * @Given I want to create a page on :socialNetwork
     */
    public function iWantToCreateAPageOn(string $socialNetwork): void
    {
        $this->socialNetwork = $socialNetwork;
    }

    /**
     * @Given I choose the page name :pageName
     */
    public function iChooseThePageName(string $pageName): void
    {
        $this->pageName = $pageName;
    }

    /**
     * @When I create this Page
     */
    public function iCreateThisPage(): void
    {
        $request = Request::create(
            "/api/v1/pages",
            "POST",
            content: json_encode([
                "pageName" => $this->pageName,
                "socialNetwork" => $this->socialNetwork
            ])
        );
        $this->createPageResponse = $this->createPageController->execute($request);
    }

    /**
     * @Then The page is created and I have the pageId
     */
    public function thePageIsCreatedAndIHaveThePageId(): void
    {
        /** @var string $content */
        $content = $this->createPageResponse->getContent();
        /** @var \stdClass $response */
        $response = json_decode($content);

        $page = $this->pages->findById(new PageId($response->data->pageId));
        Assert::assertNotNull($page, "The page was not created.");
        Assert::assertEquals("Page name", $page->getName());
        Assert::assertEquals(SocialNetworks::tryFrom($this->socialNetwork), $page->getSocialNetwork());
    }
}
