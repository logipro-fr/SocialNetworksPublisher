<?php
namespace Features;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;
use PHPUnit\Framework\Assert;
use SocialNetworksPublisher\Domain\Model\Post\PostId;
use Symfony\Component\HttpKernel\KernelInterface;
use SocialNetworksPublisher\Domain\Model\Post\Status;
/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private string $socialNetwork;
    private string $authorId;
    private string $pageId;
    private string $writtenPost;
    private string $hashtag;
    private string $statusGive;
    private string $response;
    private static KernelInterface $kernel;
   
    /**
     * @BeforeSuite
     */
    public static function prepare(BeforeSuiteScope $scope)
    {
        self::$kernel = new \SocialNetworksPublisher\Infrastructure\Shared\Symfony\Kernel('test', true);
        self::$kernel->boot();
    }

    /**
     * @Given I intend to publish on :socialNetwork
     */
    public function iIntendToPublishOn($socialNetwork)
    {
        $this->socialNetwork = $socialNetwork;
    }

    /**
     * @Given I have the author id :authorId
     */
    public function iHaveTheAuthorId($authorId)
    {
        $this->authorId = $authorId;
    }

    /**
     * @Given I have the page id :pageId
     */
    public function iHaveThePageId($pageId)
    {
        $this->pageId = $pageId;
    }

    /**
     * @Given I have written a post:
     */
    public function iHaveWrittenAPost(PyStringNode $string)
    {
        $this->writtenPost = $string;
    }

    /**
     * @Given the hashtags are :hashtag
     */
    public function theHashtagsAre($hashtag)
    {
        $this->hashtag = $hashtag;
    }

    /**
     * @Given the status is :status
     */
    public function theStatusIs($status)
    {
        $this->statusGive = $status;
    }

    /**
     * @When I publish the post
     */
    public function iPublishThePost()
    {
        $client = self::$kernel->getContainer()->get('test.client');
        $client->request(
            "POST",
            "/api/v1/post/publish",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                "socialNetworks" => $this->socialNetwork,
                "authorId" => $this->authorId,
                "pageId" => $this->pageId,
                "content" => $this->writtenPost,
                "hashtag" => $this->hashtag,
                "status" => $this->statusGive,
            ])
        );
        $this->response = $client->getResponse()->getContent();
        
    }

    /**
     * @Then my post has a status :statusExpected
     */
    public function myPostHasAStatus($statusExpected)
    {
        $container = self::$kernel->getContainer();
        $postRepository = $container->get('post.repository');
        $responseArray = json_decode($this->response, true);
        $postId = $responseArray['data']['postId'];
        $post = $postRepository->findById(new PostId($postId));
        /** @var Status */
        $status = $post->getStatus();
        Assert::assertEquals($statusExpected, $status->getValue());
    }
}
