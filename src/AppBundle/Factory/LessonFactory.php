<?php
namespace AppBundle\Factory;

use AppBundle\Command\CreateLesson;
use AppBundle\Entity\Lesson;
use AppBundle\Generator\IdGeneratorInterface;

class LessonFactory
{
    private $idGenerator;

    /**
     * LessonFactory constructor.
     * @param $idGenerator
     */
    public function __construct(IdGeneratorInterface $idGenerator)
    {
        $this->idGenerator = $idGenerator;
    }

    /**
     * @param CreateLesson $command
     * @return Lesson
     */
    public function createFromCommand(CreateLesson $command){
        return new Lesson(
            $this->idGenerator->generate(),
            $command->title,
            $command->description,
            $command->address,
            $command->city,
            null,
            null,
            $command->startDate,
            $command->endDate,
            $command->workshop,
            null
        );
    }
}