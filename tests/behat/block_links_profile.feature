@block @block_links
Feature: The links block allows administrators to define web links for all site users
  In order to view the links block
  As a teacher
  I can add links block to a course and view the contents

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email | idnumber | country | city | institution | department |
      | teacher1 | Teacher | 1 | teacher1@example.com | T1  | US | Goffstown | In1 | dept1 |
      | teacher2 | Teacher | 2 | teacher2@example.com | T2  | AU | Perth     | In2 | dept2 |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1 | 0 |
    And the following "course enrolments" exist:
      | user | course | role |
      | teacher1 | C1 | editingteacher |
      | teacher2 | C1 | editingteacher |

  Scenario: Restrict links by the Institution field
    # Institution value == 1
    Given the following config values are set as admin:
      | profile_field | 1 | block_links |
    And I log in as "admin"
    And I navigate to "Links" node in "Site administration>Plugins>Blocks"
    And I follow "Add/Edit Links"
    And I press "Add a new link"
    And I set the following fields to these values:
      | id_linktext | Link to website |
      | id_url | http://example.com   |
      | id_defaultshow | Yes          |
      | id_department | In1 |
    And I press "Save changes"
    And I log out
    When I log in as "teacher1"
    And I follow "Course 1"
    And I turn editing mode on
    And I add the "Links" block
    And I turn editing mode off
    And "Learning Resources" "block" should exist
    And I should see "Link to website" in the "Learning Resources" "block"
    And I log out
    Then I log in as "teacher2"
    And I follow "Course 1"
    And "Learning Resources" "block" should not exist

  Scenario: Restrict links by the Department field
    # Institution value == 2
    Given the following config values are set as admin:
      | profile_field | 2 | block_links |
    And I log in as "admin"
    And I navigate to "Links" node in "Site administration>Plugins>Blocks"
    And I follow "Add/Edit Links"
    And I press "Add a new link"
    And I set the following fields to these values:
      | id_linktext | Link to website |
      | id_url | http://example.com   |
      | id_defaultshow | Yes          |
      | id_department | dept1 |
    And I press "Save changes"
    And I log out
    When I log in as "teacher1"
    And I follow "Course 1"
    And I turn editing mode on
    And I add the "Links" block
    And I turn editing mode off
    And "Learning Resources" "block" should exist
    And I should see "Link to website" in the "Learning Resources" "block"
    And I log out
    Then I log in as "teacher2"
    And I follow "Course 1"
    And "Learning Resources" "block" should not exist

  Scenario: Restrict links by the City / town field
    # Institution value == 3
    Given the following config values are set as admin:
      | profile_field | 3 | block_links |
    And I log in as "admin"
    And I navigate to "Links" node in "Site administration>Plugins>Blocks"
    And I follow "Add/Edit Links"
    And I press "Add a new link"
    And I set the following fields to these values:
      | id_linktext | Link to website |
      | id_url | http://example.com   |
      | id_defaultshow | Yes          |
      | id_department | Goffstown |
    And I press "Save changes"
    And I log out
    When I log in as "teacher1"
    And I follow "Course 1"
    And I turn editing mode on
    And I add the "Links" block
    And I turn editing mode off
    And "Learning Resources" "block" should exist
    And I should see "Link to website" in the "Learning Resources" "block"
    And I log out
    Then I log in as "teacher2"
    And I follow "Course 1"
    And "Learning Resources" "block" should not exist

  Scenario: Restrict links by the Country
    # Institution value == 4
    Given the following config values are set as admin:
      | profile_field | 4 | block_links |
    And I log in as "admin"
    And I navigate to "Links" node in "Site administration>Plugins>Blocks"
    And I follow "Add/Edit Links"
    And I press "Add a new link"
    And I set the following fields to these values:
      | id_linktext | Link to website |
      | id_url | http://example.com   |
      | id_defaultshow | Yes          |
      | id_department | US |
    And I press "Save changes"
    And I log out
    When I log in as "teacher1"
    And I follow "Course 1"
    And I turn editing mode on
    And I add the "Links" block
    And I turn editing mode off
    And "Learning Resources" "block" should exist
    And I should see "Link to website" in the "Learning Resources" "block"
    And I log out
    Then I log in as "teacher2"
    And I follow "Course 1"
    And "Learning Resources" "block" should not exist