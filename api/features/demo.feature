# This file contains a user story for demonstration only.
# Learn how to get started with Behat and BDD on Behat's website:
# http://behat.org/en/latest/quick_start.html

Feature:
    In order to prove that the Behat Symfony extension is correctly installed
    As a user
    I want to have a demo scenario

    Background:
      Given I add "Accept" header equal to "application/ld+json"
      And I add "Content-Type" header equal to "application/ld+json"

    @loginAdmin
    Scenario: I can add a PubMed article with a valid ID
      When I send a "POST" request to "/my_resources" with body:
      """
      {}
      """
      Then the response status code should be 404
