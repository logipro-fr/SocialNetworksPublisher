Feature: Publish content on a socialNetworks 
  As a user of the SocialNetworksPublisher
  I want to be able to post, preview, and manage content drafts on social networks (LinkedIn, Facebook...)
  In order to facilitate professional content manipulation on social networks without direct interaction with the user interface

  Scenario Outline: Post on social networks 
    Given I intend to publish on <socialNetwork>
    And I have the author id "1e3brj"
    And I have the page id "12af56"
    And I have written a post:
      """
      Following a prediction made at 10:00, an accident occurred on N02 at 10:35. 
      This underscores the importance of accident prediction in prevention.
      """
    And the hashtags are "#AccidentPrediction, #RoadSafety"
    And the status is "ReadyToPublish"
    When I publish the post
    Then my post has a status "published"

  Examples:
    |   socialNetwork   |
    |   "LinkedIn"      |
    |    "Facebook"     |
    |   "simpleBlog"    |
    |   "twitter"       |

