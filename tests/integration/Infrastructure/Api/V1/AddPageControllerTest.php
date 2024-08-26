<?php

namespace SocialNetworksPublisher\Tests\Integration\Infrastructure\Api\V1;

use Doctrine\ORM\EntityManagerInterface;
use DoctrineTestingTools\DoctrineRepositoryTesterTrait;
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
use SocialNetworksPublisher\Infrastructure\Persistence\Key\KeyRepositoryDoctrine;
use SocialNetworksPublisher\Infrastructure\Persistence\Page\PageRepositoryDoctrine;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use function Safe\json_encode;

class AddPageControllerTest extends WebTestCase
{
    use DoctrineRepositoryTesterTrait;

    private KernelBrowser $client;
    private PageRepositoryInterface $pages;
    private KeyRepositoryInterface $keys;

    public function setUp(): void
    {


        $this->initDoctrineTester();

        $this->clearTables(["pages", "apiKeys"]);
        $this->client = static::createClient(["debug" => false]);

        /** @var PageRepositoryDoctrine $autoInjectedRepo */
        $autoInjectedRepo = $this->client->getContainer()->get("page.repository");
        $this->pages = $autoInjectedRepo;

        /** @var KeyRepositoryDoctrine $autoInjectedRepo */
        $autoInjectedRepo = $this->client->getContainer()->get("key.repository");
        $this->keys = $autoInjectedRepo;

        $key = new Key(
            new KeyId("key_id"),
            SocialNetworks::Twitter,
            new DateTimeImmutable(),
            new TwitterKeyData("a", "b")
        );
        $page = new Page(
            new PageId("page_id"),
            new PageName("test"),
            SocialNetworks::Twitter,
        );

        $this->keys->add($key);
        $this->pages->add($page);

        /** @var EntityManagerInterface $em */
        $em = $this->client->getContainer()->get(EntityManagerInterface::class);
        $em->flush();
    }

    public function testAddPage(): void
    {

        $this->client->request(
            "PATCH",
            "/api/v1/keys/page",
            content: json_encode([
                "keyId" => "key_id",
                "pageId" => "page_id"
            ])
        );

        /** @var string $content */
        $content = $this->client->getResponse()->getContent();
        /** @var \stdClass $response */
        $response = json_decode($content);
        $this->assertTrue($response->success);



        /** @var Key */
        $modifiedKey = $this->keys
        ->findById(new KeyId($response->data->keyId));
        $foundPage = $this->pages->findById(new PageId($modifiedKey->getValue()));
        $this->assertEquals("page_id", $modifiedKey->getValue());
        $this->assertEquals("test", $foundPage->getName());
    }
}
