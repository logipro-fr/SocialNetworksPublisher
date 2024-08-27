<?php

namespace SocialNetworksPublisher\Tests\Integration\Infrastructure\Api\V1;

use Doctrine\ORM\EntityManagerInterface;
use DoctrineTestingTools\DoctrineRepositoryTesterTrait;
use SocialNetworksPublisher\Domain\Model\Key\KeyId;
use SocialNetworksPublisher\Domain\Model\Key\KeyRepositoryInterface;
use SocialNetworksPublisher\Domain\Model\Key\TwitterKeyData;
use SocialNetworksPublisher\Infrastructure\Persistence\Key\KeyRepositoryDoctrine;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use function Safe\json_encode;

class CreateKeyControllerTest extends WebTestCase
{
    use DoctrineRepositoryTesterTrait;

    private KernelBrowser $client;
    private KeyRepositoryInterface $keys;
    public function setUp(): void
    {
        $this->initDoctrineTester();
        $this->clearTables(["apiKeys"]);

        $this->client = static::createClient(["debug" => false]);

        /** @var KeyRepositoryDoctrine $autoInjectedRepo */
        $autoInjectedRepo = $this->client->getContainer()->get("key.repository");
        $this->keys = $autoInjectedRepo;

        /** @var EntityManagerInterface $em */
        $em = $this->client->getContainer()->get(EntityManagerInterface::class);
        $em->flush();
    }

    public function testCreateTwitterKey(): void
    {
        $this->client->request(
            "POST",
            "/api/v1/keys/twitter",
            content: $this->generateContent()
        );

        /** @var string $content */
        $content = $this->client->getResponse()->getContent();
        /** @var \stdClass $response */
        $response = json_decode($content);
        $this->assertTrue($response->success);

        $key = $this->keys->findById(new KeyId($response->data->keyId));
        /** @var TwitterKeyData $keyData  */
        $keyData = $key->getKeyData();
        $this->assertInstanceOf(TwitterKeyData::class, $keyData);
        $this->assertEquals("bearer_token", $keyData->getBearerToken());
        $this->assertEquals("refresh_token", $keyData->getRefreshToken());
        $this->assertEquals("page_id", $key->getValue());
    }

    private function generateContent(): string
    {
        return json_encode([
            "bearerToken" => "bearer_token",
            "refreshToken" => "refresh_token",
            "pageId" => "page_id"
        ]);
    }
}
