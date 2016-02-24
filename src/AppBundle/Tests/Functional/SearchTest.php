<?php
namespace AppBundle\Tests\Functional;

use AppBundle\Entity\Workshop;
use AppBundle\Tests\BaseFunctionalTestCase;
use Dende\Calendar\Domain\Calendar;

class SearchTest extends BaseFunctionalTestCase
{
    /**
     * @test
     * @return void
     */
    public function it_searches_workshops_by_title_or_desc()
    {
        $crawler = $this->client->request('GET', sprintf('/search.html'));
        $this->assertEquals(200, $this->getStatusCode());

        $formName ="search_workshop";

        $submitButton = $crawler->selectButton($formName."_submit");
        $form = $submitButton->form();

        /** @var Workshop $workshop */
        $workshop = $this->fixtures["workshop_searchable"];

        $values = [
            "_token" => $form[$formName."[_token]"]->getValue(),
            "search" => "found",
        ];

        $crawler = $this->client->request($form->getMethod(), $form->getUri(), [$formName => $values]);
        $this->assertEquals(200, $this->getStatusCode());

        /** @var Workshop $workshop */
        $this->assertNotNull($workshop);
        $this->assertInstanceOf(Workshop::class, $workshop);
        $this->assertCount(2, $workshop->getLessons());

        $calendar = $workshop->getCalendar();
        $this->assertNotNull($workshop);
        $this->assertInstanceOf(Calendar::class, $calendar);
        $this->assertCount(2, $calendar->events());

        for($a = 0; $a < 2; $a++) {
            $lesson = $workshop->getLessons()[$a];
            $testEvent = $lesson->getEvent();

            $this->assertNotNull($lesson);
            $this->assertNotNull($testEvent);

            $this->assertInstanceOf(Lesson::class, $lesson);
            $this->assertInstanceOf(Calendar\Event::class, $testEvent);

            $this->assertTrue($testEvent == $calendar->events()[$a]);
        }
    }
}