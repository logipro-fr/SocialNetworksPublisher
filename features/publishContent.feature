Feature: Publish content on a socialNetworks 
  As a user of the SocialNetworksPublisher
  I want to be able to post, preview, and manage content drafts on social networks (LinkedIn, Facebook...)
  In order to facilitate professional content manipulation on social networks without direct interaction with the user interface

  Scenario Outline: Post on social networks 
    Given I intend to publish on <social_network>
    And I have the <social_network> author id "1e3brj"
    And I have the <social_network> page id "12af56"
    And I have written a post:
      """
      Following a prediction made at 10:00, an accident occurred on N02 at 10:35. This underscores the importance of accident prediction in prevention.
      """
    And the hashtags are "#AccidentPrediction, #RoadSafety"
    And the status is "ReadyToPublish"
    When I publish the post on <social_network>
    Then my post has a <social_network> status "published"

  Examples:
    | social_network |
    | LinkedIn       |
    | Facebook       |
    | simpleBlog     |

  # Scenario Outline: Manage Content draft
  #   Given I have written a draft post for the page "Accident Predict":
  #     """
  #     Brouillon d'alerte de trafic.
  #     """
  #   And the target status is "DRAFT"
  #   When I create a draft with the content of this post
  #   Then the API save this post as a draft
  #   And I receive the draft id

  # Scenario Outline: Preview content before publishing
  #   Given I am a <social networks> author
  #   And I have written a post 
  #     "Aperçu de notre dernière analyse de trafic."
  #   When I request a preview generation
  #   Then the API creates the preview of the post
  #   And I receive the preview link  ????

  #     Example:
  #         | social networks |
  #         | LinkedIn        |
  #         | Facebook        |
