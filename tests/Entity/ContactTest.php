<?php

namespace App\Tests\Entity;

use App\Entity\Contact;
use PHPUnit\Framework\TestCase;

class ContactTest extends TestCase
{
    public function testContactEntityCreationWorks(): void
    {
        $contact = new Contact();
        $contact->setFirstName("John");
        $contact->setName("Doe");
        $contact->setMessage("This is a test message");
        $contact->setCreatedAt(new \DateTimeImmutable("now"));

        // Verify that the contact has been created with correct values
        $this->assertEquals("John", $contact->getFirstName());
        $this->assertEquals("Doe", $contact->getName());
        $this->assertEquals("This is a test message", $contact->getMessage());
        $this->assertEquals("new", $contact->getStatus()); // Default status
        $this->assertInstanceOf(\DateTimeImmutable::class, $contact->getCreatedAt());
    }

    public function testContactDefaultStatus(): void
    {
        $contact = new Contact();

        // Verify that new contacts have 'new' status by default
        $this->assertEquals("new", $contact->getStatus());
    }
}
