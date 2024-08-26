Feature: Manage API Key
    In order to publish on a specific social network page
    As a user, I want to be able to create and link an API key.

    Scenario: Create API Key
        Given I want to create a Twitter API key
        And I have the Bearer token "bearer_token"
        And I have the Refresh token "refresh_token"
        When I create the API key
        Then the API key is created successfully
        And I have key ID

    Scenario: Link API Key to Page
        Given I want to link my API key to my page with the page ID "page_id"
        When I link the API key
        Then the API key is linked successfully to the page
