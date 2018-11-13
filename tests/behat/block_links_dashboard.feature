@block @block_links
Feature: The links block allows administrators to define web links for all site users on the dashboard
  In order to view the links block
  As a user
  I can add links block to my dashboard and view the contents

  Scenario: Add the block to a the dashboard
    Given the following "users" exist:
      | username | firstname | lastname | email | idnumber |
      | teacher1 | Teacher | 1 | teacher1@example.com | T1 |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1 | 0 |
    And the following "course enrolments" exist:
      | user | course | role |
      | teacher1 | C1 | editingteacher |
    And I log in as "admin"
    And I navigate to "Plugins > Blocks > Links" in site administration
    And I follow "Add/Edit Links"
    And I press "Add a new link"
    And I set the following fields to these values:
      | id_linktext | Link to website |
      | id_url | http://example.com   |
      | id_defaultshow | Yes          |
    And I press "Save changes"
    And I log out
    When I log in as "teacher1"
    And I press "Customise this page"
    And I add the "Links" block
    Then "Learning Resources" "block" should exist
    And I should see "Link to website" in the "Learning Resources" "block"
