@block @block_links
Feature: The links block allows administrators to define web links for all site users
  In order to view the links block
  As an admin
  I can add links block to the frontpage and view the contents

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email | idnumber |
      | teacher1 | Teacher | 1 | teacher1@example.com | T1 |  
    And I log in as "admin"
    And I navigate to "Links" node in "Site administration>Plugins>Blocks"
    And I follow "Add/Edit Links"
    And I press "Add a new link"
    And I set the following fields to these values:
      | id_linktext | Link to website |
      | id_url | http://example.com   |
      | id_defaultshow | Yes          |
    And I press "Save changes"
    And I am on site homepage
    And I navigate to "Turn editing on" node in "Front page settings"
    And I add the "Links" block
    And I log out

  Scenario: Try to view the logged in user block as a guest
    Given I log in as "guest"
    When I am on site homepage
    Then "Learning Resources" "block" should exist

  Scenario: View the logged in user block by a logged in user
    Given I log in as "teacher1"
    And I am on site homepage
    Then "Learning Resources" "block" should exist
    And I should see "Link to website" in the "Learning Resources" "block"
