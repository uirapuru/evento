<?php
namespace AppBundle\Tests;

use AppBundle\Entity\Workshop;
use Carbon\Carbon;

class RegisterWorkshopTest extends BaseFunctionalTestCase
{
    /**
     * test
     */
    public function it_renders_workshop_creating_form_correctly()
    {
        $crawler = $this->client->request('GET', sprintf('/rejestracja.html'));
        $this->assertEquals(200, $this->getStatusCode());
        $formElement = $crawler->filter("form");
        $this->assertCount(8, $formElement->filter("input"));
        $this->assertCount(1, $formElement->filter("textarea"));
    }

    /**
     * @test
     * @return void
     */
    public function it_creates_new_workshop_correctly()
    {
        $crawler = $this->client->request('GET', sprintf('/rejestracja.html'));
        $this->assertEquals(200, $this->getStatusCode());

        $formName ="register_workshops_form_type";

        $submitButton = $crawler->selectButton($formName."_submit");
        $form = $submitButton->form();

        $dateTimeFormat = "d.m.Y H:i";
        $title = $this->faker->sentence();
        $startDate = $this->faker->dateTimeBetween("5 days", "5 days")->format($dateTimeFormat);
        $endDate = $this->faker->dateTimeBetween($startDate, Carbon::parse($startDate)->addDays(10))->format($dateTimeFormat);

        $values = [
            "_token" => $form[$formName."[_token]"]->getValue(),
            "title" => $title,
            "description" => $this->faker->text(500),
            "startDate" => $startDate,
            "endDate" => $endDate,
            "url" => $this->faker->url(),
            "phone" => $this->faker->phoneNumber(),
            "email" => $this->faker->email(),
            "city" => $this->faker->city(),
            "lessons" => [
                0 => [
                    "title" => $title,
                    "description" => $this->faker->text(200),
                    "startDate" => $this->faker->dateTimeBetween($startDate, $endDate)->format($dateTimeFormat),
                    "endDate" => $this->faker->dateTimeBetween($startDate, $endDate)->format($dateTimeFormat),
                    "address" => $this->faker->address(),
                    "city" => $this->faker->city(),
                ],
                1 => [
                    "title" => $title,
                    "description" => $this->faker->text(200),
                    "startDate" => $this->faker->dateTimeBetween($startDate, $endDate)->format($dateTimeFormat),
                    "endDate" => $this->faker->dateTimeBetween($startDate, $endDate)->format($dateTimeFormat),
                    "address" => $this->faker->address(),
                    "city" => $this->faker->city(),
                ]
            ]
        ];

        $crawler = $this->client->request($form->getMethod(), $form->getUri(), [$formName => $values]);
        $this->assertEquals(200, $this->getStatusCode());

        /** @var Workshop $workshop */
        $workshop = $this->getContainer()->get("app.repository.workshop")->findOneByTitle($title);
        $this->assertNotNull($workshop);
        $this->assertInstanceOf(Workshop::class, $workshop);
        $this->assertCount(2, $workshop->getLessons());
    }

    /**
     * test
     */
    public function it_renders_workshop_updating_form_correctly()
    {
        /** @var Workshop $workshop */
        $workshop = $this->getContainer()->get("app.repository.workshop")->findOneBy([]);

        $crawler = $this->client->request('GET', sprintf('/rejestracja.html'));
        $this->assertEquals(200, $this->getStatusCode());
        $formElement = $crawler->filter("form");
        $this->assertCount(8, $formElement->filter("input"));
        $this->assertCount(1, $formElement->filter("textarea"));
    }

    /**
     * @test
     * @return void
     */
    public function it_updates_new_product_correctly()
    {
        /** @var Workshop $workshop */
        $workshop = $this->getContainer()->get("app.repository.workshop")->findOneBy([]);

        $crawler = $this->client->request('GET', sprintf('/edit/%s', $workshop->getSlug()));
        $this->assertEquals(200, $this->getStatusCode());

        $formName ="register_workshops_form_type";

        $submitButton = $crawler->selectButton("register_workshops_form_type_submit");
        $form = $submitButton->form();

        $title = $this->faker->sentence();

        $form->setValues([
            "register_workshops_form_type[title]" => md5($title)
        ]);

        $this->client->submit($form);
        $this->assertEquals(200, $this->getStatusCode());

        $this->assertEquals(md5($title), $workshop->getTitle());
        $this->assertCount(2, $workshop->getLessons());
    }

}