<?php

namespace SocialNetworksPublisher\Tests\Integration\Infrastructure\Api\V1;

use Doctrine\ORM\EntityManagerInterface;
use DoctrineTestingTools\DoctrineRepositoryTesterTrait;
use SocialNetworksPublisher\Domain\Model\Page\Page;
use SocialNetworksPublisher\Domain\Model\Page\PageId;
use SocialNetworksPublisher\Domain\Model\Page\PageName;
use SocialNetworksPublisher\Domain\Model\Page\PageRepositoryInterface;
use SocialNetworksPublisher\Domain\Model\Shared\SocialNetworks;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddPostControllerTest extends WebTestCase
{
    use DoctrineRepositoryTesterTrait;

    private KernelBrowser $client;
    private PageRepositoryInterface $pages;

    public function setUp(): void
    {
        $this->initDoctrineTester();
        $this->clearTables(["pages"]);

        $this->client = static::createClient(["debug" => false]);

        /** @var PageRepositoryDoctrine $autoInjectedRepo */
        $autoInjectedRepo = $this->client->getContainer()->get("page.repository");
        $this->pages = $autoInjectedRepo;

        /** @var EntityManagerInterface $em */
        $em = $this->client->getContainer()->get(EntityManagerInterface::class);
        $em->flush();
    }

    public function testAddPage(): void
    {
        $pageIdName = $this->prepareTest();
        $this->client->request(
            "POST",
            "/api/v1/pages/post",
            content: $this->generateContent($pageIdName)
        );

        /** @var string $content */
        $content = $this->client->getResponse()->getContent();
        /** @var \stdClass $response */
        $response = json_decode($content);
        $this->assertTrue($response->success);

        $page = $this->pages->findById(new PageId($pageIdName));
        $this->assertCount(1, $page->getPosts());
    }

    private function generateContent(string $pageIdName): string
    {
        return json_encode([
            "pageId" => $pageIdName,
            "content" => "Twitter",
        ]);
    }

    private function prepareTest(): string
    {
        $page = new Page(
            $id = new PageId(),
            new PageName("page_name"),
            SocialNetworks::Twitter
        );
        $this->pages->add($page);
        return $id;
    }
}
