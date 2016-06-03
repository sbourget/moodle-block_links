@block @block_links
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

  Scenario: Add a link using the admin interface
    Given I log in as "admin"
    And I navigate to "Links" node in "Site administration>Plugins>Blocks"
    And I follow "Add/Edit Links"
    And I press "Add a new link"
    And I set the following fields to these values:
      | id_linktext | Link to website |
      | id_url | http://example.com   |
      | id_defaultshow | No           |
    When I press "Save changes"
    Then I should see "example.com" in the "Link to website" "table_row"

  Scenario: Update a link using the admin interface
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
    And I follow "Edit"
    And I set the following fields to these values:
      | id_linktext | Link to website |
      | id_url | http://example2.com   |
      | id_defaultshow | No           |
    Then I press "Save changes"
    And I should see "example2.com" in the "Link to website" "table_row"

  Scenario: Remove a link using the admin interface
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
    Then I follow "Delete"
    And I should not see "example.com"

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
    And I turn editing mode off
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
    And I turn editing mode off
    Then "Learning Resources" "block" should exist
    And I should see "Link to website" in the "Learning Resources" "block"

  Scenario: Add the block to a the course when the block name has been changed
    Given the following config values are set as admin:
      | default_title | My new block name | block_links |
    And I log in as "admin"
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
    And I turn editing mode off
    Then "My new block name" "block" should exist
    And I should see "Link to website" in the "My new block name" "block"