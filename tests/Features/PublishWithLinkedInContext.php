<?php

namespace Features;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;

/**
 * Defines application features from the specific context.
 */
class PublishWithLinkedIn implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given I send a POST request to :arg1 with the body
     */
    public function iSendAPostRequestToWithTheBody(string $arg1, PyStringNode $string): void
    {
        throw new PendingException();
    }

    /**
     * @Then the response status code is :arg1
     */
    public function theResponseStatusCodeIs(int $arg1): void
    {
        throw new PendingException();
    }

    /**
     * @Then the response contains :arg1
     */
    public function theResponseContains(string $arg1): void
    {
        throw new PendingException();
    }
}
