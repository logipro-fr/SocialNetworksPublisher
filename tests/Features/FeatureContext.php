<?php

namespace Features;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use DoctrineTestingTools\DoctrineRepositoryTesterTrait;
use SocialNetworksPublisher\Infrastructure\Shared\Symfony\Kernel;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

use function Safe\json_encode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends WebTestCase implements Context
{
    private string $socialNetworksLinkedIn;
    private string $socialNetworksSimpleBlog;
    private string $socialNetworksFacebook;
    private string $authorIdLinkedIn;
    private string $authorIdFacebook;
    private string $authorIdSimpleBlog;
    private string $pageIdLinkedIn;
    private string $pageIdFacebook;
    private string $pageIdSimpleBlog;
    private string $hashTags;
    private string $status;
    private string $postContent;
    
    private KernelBrowser $client;
    
    use DoctrineRepositoryTesterTrait;


    public function __construct()
    {
        $this->initDoctrineTester();
        $this->clearTables(["posts"]);

        $this->client = static::createClient(["debug" => false]);
    }

    /**
     * @Given I intend to publish on LinkedIn
     */
    public function iIntendToPublishOnLinkedin()
    {
        $this->socialNetworksLinkedIn = "linkedIn";
    }

    /**
     * @Given I have the LinkedIn author id :authorId
     */
    public function iHaveTheLinkedinAuthorId($authorId)
    {
        $this->authorIdLinkedIn = $authorId;
    }

    /**
     * @Given I have the LinkedIn page id :pageId
     */
    public function iHaveTheLinkedinPageId($pageId)
    {
        throw new PendingException();
    }

    /**
     * @Given I have written a post:
     */
    public function iHaveWrittenAPost(PyStringNode $postContent)
    {
        $this->postContent = $postContent;
    }

    /**
     * @Given the hashtags are :hashTags
     */
    public function theHashtagsAre($hashTags)
    {
        $this->hashTags = $hashTags;
    }

    /**
     * @Given the status is :status
     */
    public function theStatusIs($status)
    {
        $this->status = $status;
    }

    /**
     * @When I publish the post on LinkedIn
     */
    public function iPublishThePostOnLinkedin()
    {
        throw new PendingException();
    }

    /**
     * @Then my post has a LinkedIn status :arg1
     */
    public function myPostHasALinkedinStatus($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Given I intend to publish on Facebook
     */
    public function iIntendToPublishOnFacebook()
    {
        throw new PendingException();
    }

    /**
     * @Given I have the Facebook author id :authorId
     */
    public function iHaveTheFacebookAuthorId($authorId)
    {
        throw new PendingException();
    }

    /**
     * @Given I have the Facebook page id :pageId
     */
    public function iHaveTheFacebookPageId($pageId)
    {
        throw new PendingException();
    }

    /**
     * @When I publish the post on Facebook
     */
    public function iPublishThePostOnFacebook()
    {
        throw new PendingException();
    }

    /**
     * @Then my post has a Facebook status :arg1
     */
    public function myPostHasAFacebookStatus($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Given I intend to publish on SimpleBlog
     */
    public function iIntendToPublishOnSimpleblog()
    {
        throw new PendingException();
    }

    /**
     * @Given I have the SimpleBlog author id :authorId
     */
    public function iHaveTheSimpleblogAuthorId($authorId)
    {
        $this->authorIdSimpleBlog = $authorId;
    }

    /**
     * @Given I have the SimpleBlog page id :pageId
     */
    public function iHaveTheSimpleblogPageId($pageId)
    {
        $this->pageIdSimpleBlog = $pageId;
    }

    /**
     * @When I publish the post on SimpleBlog
     */
    public function iPublishThePostOnSimpleblog()
    {
        $this->client->request(
            "POST",
            "api/v1/post/publish",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                "socialNetworks" => $this->socialNetworksSimpleBlog,
                "authorId" => $this->authorIdSimpleBlog,
                "pageId" => $this->pageIdSimpleBlog,
                "content" => $this->postContent,
                "hashTags" => $this->hashTags,
            ]),
        );
        $this->assertResponseIsSuccessful();
    }

    /**
     * @Then my post has a SimpleBlog status :arg1
     */
    public function myPostHasASimpleblogStatus($arg1)
    {
        throw new PendingException();
    }
}
