<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoutingTest extends WebTestCase
{
    public function testMainPageRouteWorks(): void
    {
        $client = static::createClient();
        $client->request("GET", "/");

        // Check that the main page route responds successfully
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame("main_index");
    }

    public function testLoginPageRouteWorks(): void
    {
        $client = static::createClient();
        $client->request("GET", "/connexion");

        // Check that the login page route responds successfully
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame("main_login");
    }

    public function testLogoutRouteExists(): void
    {
        $client = static::createClient();
        $client->request("POST", "/deconnexion");

        // Logout route should exist but may redirect or throw exception
        // We just want to verify the route is mapped correctly
        $statusCode = $client->getResponse()->getStatusCode();

        // Should not be 404 (route not found)
        $this->assertNotEquals(404, $statusCode);
    }
}
