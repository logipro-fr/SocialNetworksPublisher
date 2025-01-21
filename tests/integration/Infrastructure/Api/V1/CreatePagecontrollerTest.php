<?php

namespace SocialNetworksPublisher\Tests\Integration\Infrastructure\Api\V1;

use Doctrine\ORM\EntityManagerInterface;
use DoctrineTestingTools\DoctrineRepositoryTesterTrait;
use SocialNetworksPublisher\Domain\Model\Page\PageId;
use SocialNetworksPublisher\Domain\Model\Page\PageRepositoryInterface;
use SocialNetworksPublisher\Domain\Model\Shared\SocialNetworks;
use SocialNetworksPublisher\Infrastructure\Persistence\Page\PageRepositoryDoctrine;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use function Safe\json_encode;

class CreatePageControllerTest extends WebTestCase
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

    public function testCreatePage(): void
    {
        $this->client->request(
            "POST",
            "/api/v1/pages",
            content: $this->generateContent()
        );

        /** @var string $content */
        $content = $this->client->getResponse()->getContent();
        /** @var \stdClass&object{data:object{pageId:string}} $response */
        $response = json_decode($content);
        $this->assertTrue($response->success);

        $page = $this->pages->findById(new PageId($response->data->pageId));
        $this->assertEquals("page_name", $page->getName());
        $this->assertEquals(SocialNetworks::Twitter, $page->getSocialNetwork());
    }

    private function generateContent(): string
    {
        return json_encode([
            "pageName" => "page_name",
            "socialNetwork" => "Twitter",
        ]);
    }
}
