Feature: Manage Social Networks Page
  In order to manage pages on various social networks
  As a user
  I want to be able to create a page on a social network

  Scenario Outline: Page Creation
    Given I want to create a page on <socialNetwork>
    And I choose the page name "Page name"
    When I create this Page
    Then The page is created and I have the pageId

    Examples:
      | socialNetwork  |
      | Twitter        |
      | SimpleBlog     |
      | LinkedIn       |
