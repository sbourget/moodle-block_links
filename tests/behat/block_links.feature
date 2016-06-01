@block @block_badges
Feature: The links block allows administrators to define web links for all site users
  In order to view the links block
  As a teacher
  I can add links block to a course and view the contents

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email | idnumber |
      | teacher1 | Teacher | 1 | teacher1@example.com | T1 |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1 | 0 |
    And the following "course enrolments" exist:
      | user | course | role |
      | teacher1 | C1 | editingteacher |

  Scenario: Add the block to a the course when there aren't any links
    Given I log in as "teacher1"
    And I follow "Course 1"
    And I turn editing mode on
    And I add the "Links" block
    When I turn editing mode off
    Then "Learning Resources" "block" should not exist

  Scenario: Add the block to a the course when all links are hidden
    Given I log in as "admin"
    And I navigate to "Links" node in "Site administration>Plugins>Blocks"
    And I follow "Add/Edit Links"
    And I press "Add a new link"
    And I set the following fields to these values:
      | id_linktext | Link to website |
      | id_url | http://example.com   |
      | id_defaultshow | No           |
    When I press "Save changes"
    And I should see "example.com" in the "Link to website" "table_row"
    And I log out
    And I log in as "teacher1"
    And I follow "Course 1"
    And I turn editing mode on
    And I add the "Links" block
    When I turn editing mode off
    Then "Learning Resources" "block" should not exist

  Scenario: Add the block to a the course when links are visible
    Given I log in as "admin"
    And I navigate to "Links" node in "Site administration>Plugins>Blocks"
    And I follow "Add/Edit Links"
    And I press "Add a new link"
    And I set the following fields to these values:
      | id_linktext | Link to website |
      | id_url | http://example.com   |
      | id_defaultshow | Yes          |
    When I press "Save changes"
    And I should see "example.com" in the "Link to website" "table_row"
    And I log out
    And I log in as "teacher1"
    And I follow "Course 1"
    And I turn editing mode on
    And I add the "Links" block
    When I turn editing mode off
    Then "Learning Resources" "block" should exist
    And I should see "Link to website" in the "Learning Resources" "block"