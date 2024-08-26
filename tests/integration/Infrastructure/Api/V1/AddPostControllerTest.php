<?php

namespace SocialNetworksPublisher\Tests\Integration\Infrastructure\Api\V1;

use Doctrine\ORM\EntityManagerInterface;
use DoctrineTestingTools\DoctrineRepositoryTesterTrait;
use SocialNetworksPublisher\Domain\Model\Page\Page;
use SocialNetworksPublisher\Domain\Model\Page\PageId;
use SocialNetworksPublisher\Domain\Model\Page\PageName;
use SocialNetworksPublisher\Domain\Model\Page\PageRepositoryInterface;
use SocialNetworksPublisher\Domain\Model\Shared\SocialNetworks;
use SocialNetworksPublisher\Infrastructure\Persistence\Page\PageRepositoryDoctrine;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use function Safe\json_encode;

class AddPostControllerTest extends WebTestCase
{
    use DoctrineRepositoryTesterTrait;

    private KernelBrowser $client;
    private PageRepositoryInterface $pages;
    private PageId $id;

    public function setUp(): void
    {
        $this->initDoctrineTester();
        $this->clearTables(["pages_posts","posts","pages"]);

        $this->client = static::createClient(["debug" => false]);

        /** @var PageRepositoryDoctrine $autoInjectedRepo */
        $autoInjectedRepo = $this->client->getContainer()->get("page.repository");
        $this->pages = $autoInjectedRepo;

        /** @var EntityManagerInterface $em */
        $em = $this->client->getContainer()->get(EntityManagerInterface::class);
        $this->id = $this->prepareTest();
        $em->flush();
    }

    public function testAddPage(): void
    {
        $this->client->request(
            "POST",
            "/api/v1/pages/post",
            content: $this->generateContent($this->id)
        );

        /** @var string $content */
        $content = $this->client->getResponse()->getContent();
        /** @var \stdClass $response */
        $response = json_decode($content);
        $this->assertTrue($response->success);
        $page = $this->pages->findById($this->id);
        $this->assertCount(1, $page->getPosts());
    }

    private function generateContent(string $pageIdName): string
    {
        return json_encode([
            "pageId" => $pageIdName,
            "content" => "Twitter",
        ]);
    }

    private function prepareTest(): PageId
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
