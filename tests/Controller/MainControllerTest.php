<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    public function testMainPageLoadsCorrectly(): void
    {
        $client = static::createClient();
        $crawler = $client->request("GET", "/");

        // Check that the page loads successfully
        $this->assertResponseIsSuccessful();

        // Check that we get the expected content
        $this->assertSelectorExists("form");

        // Check that the form has the expected fields
        $this->assertSelectorExists('input[name="form[firstName]"]');
        $this->assertSelectorExists('input[name="form[name]"]');
        $this->assertSelectorExists('textarea[name="form[message]"]');
        $this->assertSelectorExists('button[type="submit"]');
    }

    public function testLoginPageLoadsCorrectly(): void
    {
        $client = static::createClient();
        $crawler = $client->request("GET", "/connexion");

        // Check that the login page loads successfully
        $this->assertResponseIsSuccessful();

        // Check for login form elements
        $this->assertSelectorExists("form");
        $this->assertSelectorExists('input[type="text"]');
        $this->assertSelectorExists('input[type="password"]');
    }

    public function testContactFormHasCorrectStructure(): void
    {
        $client = static::createClient();
        $crawler = $client->request("GET", "/");

        $this->assertResponseIsSuccessful();

        // Check that all required form fields are present
        $form = $crawler->selectButton("Envoyer")->form();

        $this->assertTrue($form->has("form[firstName]"));
        $this->assertTrue($form->has("form[name]"));
        $this->assertTrue($form->has("form[message]"));

        // Verify field labels exist (in French as per the controller)
        $this->assertStringContainsString("Prénom", $client->getResponse()->getContent());
        $this->assertStringContainsString("Nom", $client->getResponse()->getContent());
        $this->assertStringContainsString("Message", $client->getResponse()->getContent());
    }

    public function testMainPageHasCorrectRoute(): void
    {
        $client = static::createClient();
        $client->request("GET", "/");

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame("main_index");
    }
}
