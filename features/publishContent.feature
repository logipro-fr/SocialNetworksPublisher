Feature: Publish content on socialNetworks via RESTful API
  As a client of the API
  I want to be able to post, preview, and manage content drafts via API requests
  In order to facilitate professional content manipulation on social networks without direct interaction with the user interface

  Scenario Outline: Successful post via API
    Given I am a <social networks> author
    And I have written a post for the page "Accident Predict":
      """
      Following a prediction made at 10:00, an accident occurred on N02 at 10:35. This underscores the importance of accident prediction in prevention.
      """
    And the hashtags are '#AccidentPrediction, #RoadSafety'
    And the target status is <status>
    When I publish the post
    Then my post has a status <status> on <social networks>
    And I receive the post id

      Example:
        | status    | social networks |
        | draft     | LinkedIn        |
        | published | Facebook        |

  Scenario Outline: Manage Content draft
    Given I am a <social networks> author
    And I have written a draft post for the page "Accident Predict":
      """
      Brouillon d'alerte de trafic.
      """
    And the target status is "DRAFT"
    When I create a draft with the content of this post
    Then the API save this post as a draft
    And I receive the draft id

      Example:
        | social networks |
        | LinkedIn        |
        | Facebook        |

  Scenario Outline: Preview content before publishing
    Given I am a <social networks> author
    And I have written a post 
      "Aperçu de notre dernière analyse de trafic."
    When I request a preview generation
    Then the API creates the preview of the post
    And I receive the preview link  ????

      Example:
          | social networks |
          | LinkedIn        |
          | Facebook        |
