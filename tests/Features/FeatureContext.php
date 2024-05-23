<?php

namespace Features;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /**
     * @Given I am a LinkedIn author
     */
    public function iAmALinkedinAuthor(): void
    {
        throw new PendingException();
    }

    /**
     * @Given I have written a post for the page :arg1:
     */
    public function iHaveWrittenAPostForThePage(string $arg1, PyStringNode $string): void
    {
        throw new PendingException();
    }

    /**
     * @Given the hashtags are :arg1
     */
    public function theHashtagsAre(string $arg1): void
    {
        throw new PendingException();
    }



    /**
     * @Then my post has a scoial networks status :arg1
     */
    public function myPostHasAScoialNetworksStatus(string $arg1): void
    {
        throw new PendingException();
    }

    /**
     * @Given I am a Facebook author
     */
    public function iAmAFacebookAuthor(): void
    {
        throw new PendingException();
    }

    /**
     * @Given the status is :arg1
     */
    public function theStatusIs(string $arg1): void
    {
        throw new PendingException();
    }

    /**
     * @When I publish the post on LinkedIn
     */
    public function iPublishThePostOnLinkedin(): void
    {
        throw new PendingException();
    }

    /**
     * @When I publish the post on Facebook
     */
    public function iPublishThePostOnFacebook(): void
    {
        throw new PendingException();
    }

     /**
     * @Given I am a SimpleBlog author
     */
    public function iAmASimpleblogAuthor(): void
    {
        throw new PendingException();
    }

    /**
     * @When I publish the post on SimpleBlog
     */
    public function iPublishThePostOnSimpleblog(): void
    {
        throw new PendingException();
    }
}
